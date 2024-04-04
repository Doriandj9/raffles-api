<?php

namespace App\Http\Controllers;

use App\Core\JWToken;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(private AuthService $service)
    {
        $this->service = $service;
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if($user && $user->is_pending){
            if($user->is_raffles){
                throw ValidationException::withMessages([
                    'raffles' => ['Lamentamos informarte que no puedes acceder al sistema hasta que se confirme tu autenticidad.'],
                ]);    
            }
            throw ValidationException::withMessages([
                'pending' => ['Lamentamos informarte que no puedes acceder al sistema hasta que completes el proceso de confirmación en tu correo electrónico.'],
            ]);
        }
        if($user && !$user->is_active){
            throw ValidationException::withMessages([
                'inactive' => [
                    'Lamentamos informarle que se encuentra bloqueado de la plataforma.'
                ]
            ]);
        }
        try {
            //code...
            if(!Auth::attempt($request->only(['email','password']))){
                throw ValidationException::withMessages([
                    'email' => ['Las credenciales ingresadas son incorrectas'],
                ]);
            }
        }catch (\Throwable $e) {
            $message = $e instanceof ValidationException ? $e->getMessage() : 'No cuenta con una contraseña. Por favor, revise su correo electrónico para verificar su cuenta y establecer una nueva contraseña.';
           return response_error($message
           ,200, ['message_throwable' => $e->getMessage()]);
        }
        $accesToken = $user->createToken('auth_token')->plainTextToken;
        $data = [
            'user' => $user,
            'accessToken' => $accesToken,
            'token' => JWToken::create($user->toArray())
        ];
        return response_success($data);

    }
    public function register(RegisterRequest $request){
        try {
            DB::beginTransaction();
            $user = User::where('taxid',$request->taxid)->first();
            $additional = [
                'is_raffles' => false
            ];
            $extra = [];
            if($request->is_raffles){
                if(!$request->hasFile('photo')){
                    throw ValidationException::withMessages([
                        'photo' => 'No a ingresado la foto de verificación.'
                    ]);
                }
                $additional['is_raffles'] = $user && !$user->is_pending 
                && $request->is_seller == 'true' ?  false : true;

                $extra['verify_photo'] = $this->service->savePhoto($request->file('photo'), $request->taxid);
            }

            if(empty($additional['verify_photo']) && $request->is_seller){
                if(!$request->hasFile('photo')){
                    throw ValidationException::withMessages([
                        'photo' => 'No a ingresado la foto de verificación.'
                    ]);
                }
                $extra['verify_photo'] = $this->service->savePhoto($request->file('photo'), $request->taxid);
            }
            try {
                if($request->change){
                    $dataUpdate = $request->only(['is_raffles','is_seller','is_pending']);
                    $dataUpdate['verify_photo'] = $request->hasFile('photo') ? $extra['verify_photo'] : null;
                    $data = $this->service->update($user->id,$dataUpdate,false);
                    DB::commit();
                    return response_success($data);
                }else{
                    $data = $this->service->save($extra);
                }
                $template = 'emails.register';
                $code = base64_encode($user ? $user->id : $data->id);
                sendEmail($request->email,'Autentificacion de registro',$template,[
                    'user' => $user ? $user : $data,
                    'url' => "security/register/confirm/$code"
                ]);
                DB::commit();
                return response_create($data, $additional);
            } catch (UniqueConstraintViolationException $e) {
                DB::rollBack();
                return response_error('Error ya existe un registro con ese número de cédula o correo electronico.',
                200,['messageSQL' => $e->getMessage()]);
            }
            
        } catch (ValidationException $e) {
            DB::rollBack();
            return response_error($e->getMessage(),200,['messages' => $e->validator->errors()]);
        }
        
    }

    public function confirRegister(Request $request, $id){
        try {
            DB::beginTransaction();
        $request->validate([
            'password' => 'required',
        ]);
        $extra = [];
        $user = User::find($id);
        if($user){
            $extra['is_pending'] = false;
        }
        if($user && $user->is_raffles && $user->is_new  && $user->is_pending){
            $extra['is_pending'] = true;
        }
        $data = $this->service->update($id, $extra);
        DB::commit();
        return response_success($data);
    } catch (\Throwable $th) {
       DB::rollBack();
       return response_error($e->getMessage(),400,['messages' => $e->validator->errors()]);
    }
    }

    public function refresh(){
        try {
            $user = auth()->user();
            if(!$user){
                throw ValidationException::withMessages([
                    'user' => 'No autenticate'
                ]);
            }
            $user = $user->refresh();
            $data = [
                'user' => $user,
                'token' => JWToken::create($user->toArray())
            ];
            
            return response_success($data);

        } catch (ValidationException $th) {
            return response_error($e->getMessage(),200,['messages' => $e->validator->errors()]);
        }
    }

    public function restorePasswords(Request $request){
        try {
            $user = User::where('email',$request->email)->first();
            if(!$user){
                return response_error('No dispones de una cuenta dentro de la plataforma.',200);
            }

            $template = 'emails.restore-password';
            $user->password = null;
            $user->save();
            $code = base64_encode($user->id);
                sendEmail($request->email,'Restauración de clave de acceso',$template,[
                    'user' => $user,
                    'url' => "security/register/confirm/$code"
                ]);
            return response_create($user,[],"
                Estimado usuario, le informamos que se ha enviado un enlace a su correo electrónico para que pueda restablecer su contraseña. Por favor, revise su bandeja de entrada y proceda a restablecer su nueva contraseña.
            ");
        } catch (\Throwable $e) {
            return response_error($e->getMessage(),200);

        }
    }
    
    public function logout(){
        auth()->user()->tokens()->delete();

        return response_success(['message' => 'success logout']);

    }
}
