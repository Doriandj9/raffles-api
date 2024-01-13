<?php

namespace App\Http\Controllers;

use App\Core\JWToken;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                $additional['is_raffles'] = true;
                $extra['verify_photo'] = $this->service->savePhoto($request->file('photo'), $request->taxid);
            }
            try {
                $data = $this->service->save($extra);
                $template = 'emails.register';
                $user = User::where('taxid',$request->taxid)->first();
                $code = base64_encode($user->id);
                sendEmail($request->email,'Autentificacion de registro',$template,[
                    'user' => $user,
                    'url' => "security/register/confirm/$code"
                ]);
                return response_create($data, $additional);
            } catch (UniqueConstraintViolationException $e) {
                return response_error('Error ya existe un registro con ese número de cédula o correo electronico.',
                200,['messageSQL' => $e->getMessage()]);
            }
            
        } catch (ValidationException $e) {
            return response_error($e->getMessage(),200,['messages' => $e->validator->errors()]);
        }
        
    }

    public function confirRegister(Request $request, $id){
        $request->validate([
            'password' => 'required',
        ]);
        $extra = [];
        $user = User::find($id);
        if($user){
            $extra['is_pending'] = false;
        }
        if($user && $user->is_raffles){
            $extra['is_pending'] = true;
        }
        $data = $this->service->update($id, $extra);
        return response_success($data);
    }
    
    public function logout(){
        auth()->user()->tokens()->delete();

        return response_success(['message' => 'success logout']);

    }
}
