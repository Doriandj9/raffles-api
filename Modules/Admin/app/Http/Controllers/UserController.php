<?php

namespace Modules\Admin\app\Http\Controllers;

use App\Core\JWToken;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Admin\app\Services\UserService;

class UserController extends Controller
{
    public array $data = [];

    public function __construct(
        private UserService $userService,
        private FileService $fileService
        )
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        //
        
        return response()->json($this->data);
    }

    public function userRaffles(): JsonResponse
    {
        //
        $data = $this->userService
        ->where('is_raffles',true)
        ->where('is_active', true)
        ->paginate(2);
        
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        //

        return response()->json($this->data);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            //code...
            $data = User::findOrFail($id);
    
            return response_success($data);
        } catch (\Throwable $e) {
            //throw $th;
            return response_error($e->getMessage(),200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try{
            $data = $this->userService->update($id);
            return response_update(['user' => $data, 'token' => JWToken::create($data)]);
        }catch(\Throwable $e){
            return response_error($e->getMessage(),200);
        }

    }

    public function updateAuth(Request $request, $id): JsonResponse
    {
        try{
            $data = $this->userService->update($id);
            $template = 'emails.authregister';
            if(boolval($request->is_pending === "true" ? 1 : 0  )){
                $template = 'emails.authregisterfaild';
                sendEmail($data->email,'Fallo la autenticacion del registro',$template,[
                    'user' => $data,
                    'observation' => $request->observation
                ]);
                return response_update($data);
            }
            sendEmail($data->email,'Autenticacion completada',$template,['user' => $data]);
            return response_update($data);
        }catch(\Throwable $e){
            return response_error($e->getMessage(),200);
        }
    }

    public function createFilePaymentPlan(Request $request): JsonResponse
    {
        $extra = [];
        try{
            if(!$request->hasFile('file')){
                throw ValidationException::withMessages([
                    'file' => 'No a ingresado el comprobante de pago.'
                ]);
            }
            $extra['path'] = $this->fileService->rafflesPayment($request->file('file'), $request->fileable_id);
            $extra['type'] = 'raffles_payment_plan';
            $data = $this->fileService->save($extra);
            return response_create('OK');
        }catch(\Throwable $e){
            return response_error($e->getMessage(),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        //

        return response()->json($this->data);
    }
}
