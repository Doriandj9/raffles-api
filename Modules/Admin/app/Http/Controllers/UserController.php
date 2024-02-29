<?php

namespace Modules\Admin\app\Http\Controllers;

use App\Core\JWToken;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FileService;
use App\Traits\FileHandler;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Admin\app\Models\AuthorizationRaffle;
use Modules\Admin\app\Services\AuthRafflesService;
use Modules\Admin\app\Services\UserService;

class UserController extends Controller
{
    use FileHandler;
    public array $data = [];

    public function __construct(
        private UserService $userService,
        private FileService $fileService,
        private AuthRafflesService $authRafflesService
        )
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try{
            $data = $this->userService
            ->where('is_admin', '!=', true)
            ->paginate(10);
            return response()->json($data);
        }catch(\Throwable $e){
            return response_error($e->getMessage(),200);
        }
    }

    public function userRaffles(): JsonResponse
    {
        //
        $data = $this->userService
        ->where('is_raffles',true)
        ->where('is_active', true)
        ->orderBy('created_at','DESC')
        ->paginate(10);
        
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
            DB::beginTransaction();
            $data = $this->userService->update($id);
            if($request->resetPassword){
                $template = 'emails.reset-password';
                sendEmail($data->email,'Restauracion de clave de acceso',$template,[
                    'user' => $data,
                    'password' => trim($request->password)
                ]);
            }
            if($request->blockUser){
                $template = 'emails.block-user';
                $block = $request->is_active == 'true' ? 'Desbloqueo' : 'Bloqueado';
                sendEmail($data->email,"$block de la plataforma",$template,[
                    'user' => $data,
                    'active' => $request->is_active == 'true' ? false : true
                ]);
            }
            
            DB::commit();
            return response_update(['user' => $data, 'token' => JWToken::create($data->toArray())]);
        }catch(\Throwable $e){
            DB::rollBack();
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
            DB::beginTransaction();
            if(!$request->hasFile('file')){
                throw ValidationException::withMessages([
                    'file' => 'No a ingresado el comprobante de pago.'
                ]);
            }
            $extra['path'] = $this->fileService->rafflesPayment($request->file('file'), $request->fileable_id);
            $extra['type'] = 'raffles_payment_plan';
            $data = $this->fileService->save($extra);
            $dataAuthRaffles = [
                'user_id' => $request->fileable_id,
                'file_id' => $data->id
            ];
            $data2 = $this->authRafflesService->save($dataAuthRaffles,false);
            $startDate = now();
            $endDate = now()->addMonth();
            $startDayMonth = now()->startOfMonth();
            $diffInDays = $startDate->diffInDays($endDate);

            if($startDate->day !== $endDate->day){
                $dateEnd = $startDayMonth->addMonth(); 
                $endDayMonth = $dateEnd->endOfMonth();
                $endDate = $endDayMonth;
            }
            $dataUser= [
                'organize_riffs' => false,
                'start_date_supcription' => now(),
                'end_date_suscription' => $endDate,
                'remaining_days_suscription' => $diffInDays,
                'subscription_id' => $request->subscription_id
            ];
            $user = $this->userService->update($request->fileable_id,$dataUser,false); 
            $plan = User::find($user->id);
            $template = 'emails.plans';
            sendEmail($user->email,'Actualizacion de plan suscripcion de rifas',$template,[
                'user' => $user,
                'sub' => $plan->subscription,
                'startDate' => $startDate->format('Y/m/d'),
                'endDate' => $endDate->format('Y/m/d'),
            ]);
            DB::commit();
            return response_create('OK');
        }catch(\Throwable $e){
            DB::rollBack();
            return response_error($e->getMessage(),200);
        }
    }

    public function authRafflesPending(): JsonResponse
    {
        try {
            $data = AuthorizationRaffle::where('is_active',1)
            ->orderBy('created_at','DESC')
            ->get();
           return response_success($data);
        } catch (\Throwable $e) {
           return response_error($e->getMessage(),202);
        }
    }

    public function authRafflePending($id): JsonResponse
    {
        try {
            $data = AuthorizationRaffle::findOrFail($id);
            return response_success($data);
        } catch (\Throwable $e) {
            return response_error($e->getMessage(),202);
        }
    }

    public function authRafflesPendingUpdate(Request $request, $id): JsonResponse
    {
        try{
            $extra1 = ['is_active' => false];
            $extra2 = ['organize_riffs' => $request->organize_riffs];
            $data1 = $this->authRafflesService->update($id, $extra1, false);
            $data2 = $this->userService->update($request->user_id,$extra2,false); 
            if(boolval($request->organize_riffs !== "true" ? 1 : 0  )){
                $data2 = $this->userService->update($request->user_id,['is_new' => true]);
                $template = 'emails.voucherfail';
                sendEmail($data2->email,'Fallo la autenticacion del comprobante de pago',$template,[
                    'user' => $data2,
                    'observation' => $request->observation
                ]);
                return response_update($data2);
            }
            $template = 'emails.vouchersuccess';
            sendEmail($data2->email,'Autenticacion correcta del comprobante de pago',$template,['user' => $data2]);
            return response_update($data1);
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

    public function storeAvatar(Request $request, $id) {
        try{
           if(!$request->hasFile('avatar')){
                throw new ErrorException('Error no ha enviado una imagen.');
           }
           $user = auth()->user();
           $ci = $user->taxid;
           $type = 'avatars';
           $uri = "users/$ci/$type";
           $path = $this->storeFile($request->file('avatar'),$uri);
           $data = ['avatar' => $path];
           $this->userService->update($id,$data,false);
           return response_update($data);
        }catch(\Throwable $e){
            return response_error($e->getMessage(),200);
        }
    }
}
