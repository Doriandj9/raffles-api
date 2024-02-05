<?php

namespace Modules\Client\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use App\Models\User;
use App\Services\AuthService;
use App\Traits\FileHandler;
use ErrorException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Client\app\Models\Ticket;
use Modules\Client\app\Services\ReceiptService;
use Modules\UserRaffle\app\Models\Raffle;

class PaymentTicketController extends Controller
{
    use FileHandler;
    public array $data = [];

    public function __construct(
        private AuthService $authService,
        private ReceiptService $receiptService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        //

        return response()->json($this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'email' => 'required|email',
            ]);
            $user = User::where('email',$request->email)->first();
            $newUser = false;
            if(!$user){
               $dataUser = $request->only(['email','taxid','first_name','last_name','phone']); 
               $dataUser['is_client'] = true;
               $user = $this->authService->save($dataUser,false);
               $newUser = true;
               $template = 'emails.register';
               $code = base64_encode($user->id);
                   sendEmail($request->email,'Autentificacion de registro',$template,[
                       'user' => $user,
                       'url' => "security/register/confirm/$code"
                   ]);
            }
            
            if(!$this->validateUser($user)){
                throw new ErrorException(Messages::DATA_USER_INCORRECT);
            }
            $raffle = Raffle::where('id', $request->raffles_id)->first();

            if($user->taxid === $raffle->user_taxid){
                throw new ErrorException(Messages::USER_OWNER_RAFFLE);
            }

            if(!$request->hasFile('voucher')){
                throw new ErrorException(Messages::NOT_VOUCHER_PRESENT);
            }

            $type = 'receipts';
            $uri = "users/$user->taxid/$type";
            $pathVoucher = $this->storeFile($request->file('voucher'),$uri);

            $dataReceipt  = [
                'user_id' => $user->id,
                'organizer_raffles_taxid' => $raffle->user_taxid,
                'description' => $request->tickets,
                'total' => $request->total,
                'subtotal' => $request->subtotal ?? ($request->amount * $request->single_price),
                'amount' => $request->amount,
                'single_price' => $request->single_price,
                'voucher' => $pathVoucher,
            ];

            $request->user_id = $user->id;
            $receipt = $this->receiptService->save($dataReceipt, false);
            $tickets = json_decode($receipt->description);
            Ticket::whereIn('id',$tickets)
            ->update(['user_taxid' => $user->taxid]);

            $message = $newUser ? Messages::NEW_USER_PAYMENT_TICKET : Messages::USER_PAYMENT_TICKET;
            DB::commit();
            return response_create($receipt,[],$message);

        } catch (\Throwable $th) {
            DB::rollBack();
            $message = $th instanceof UniqueConstraintViolationException ? 'Error un usuario con el mismo número de cédula ya existe en la plataforma'
            : $th->getMessage();
            return response_error($message);
        }
    }

    private function validateUser(User $user): bool
    {
        
        if(request()->taxid && request()->taxid !== $user->taxid ||
         request()->phone && request()->phone !== $user->phone){
            return false;
        }

        return true;

    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        //

        return response()->json($this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        //

        return response()->json($this->data);
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
