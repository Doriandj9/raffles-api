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
use Modules\Seller\app\Http\Services\SalesService;
use Modules\Seller\app\Models\Commissions;
use Modules\Seller\app\Models\Sales;
use Modules\UserRaffle\app\Models\Raffle;

class PaymentTicketController extends Controller
{
    use FileHandler;
    public array $data = [];

    public function __construct(
        private AuthService $authService,
        private ReceiptService $receiptService,
        private SalesService $salesService
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
            }
            $pathVoucher = 'N/A';
            if(!$this->validateUser($user)){
                throw new ErrorException(Messages::DATA_USER_INCORRECT);
            }
            $raffle = Raffle::where('id', $request->raffles_id)->first();

            if($user->taxid === $raffle->user_taxid){
                throw new ErrorException(Messages::USER_OWNER_RAFFLE);
            }

            if(!$request->has('credit_transaction') && !$request->hasFile('voucher')){
                throw new ErrorException(Messages::NOT_VOUCHER_PRESENT);
            }
            
            if($request->no_code == 'false'){
              $response = $this->validateCode($request,$user);
              if(is_array($response)){
                return response_success([],$response);
              }

            }
            $type = 'receipts';
            $uri = "users/$user->taxid/$type";
            if($request->hasFile('voucher')){
                $pathVoucher = $this->storeFile($request->file('voucher'),$uri);
            }

            if($request->has('credit_transaction')){
                $this->transactionCredit($raffle);
            }

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
            $ticketsPayments = Ticket::whereIn('id',$tickets)
            ->whereNotNull('user_taxid')->get();
            if($ticketsPayments->count() > 0){
                $ticks = '';
                $message = 'Sentimos los inconvenientes los siguientes boletos(';
                foreach($ticketsPayments as $ticket){
                    $ticks .= $ticket->order . ','; 
                }

                $ticks = rtrim($ticks,',');

                $message .= $ticks . ') ya se encuentran reservados, por favor selecione otros boletos.';

                throw new ErrorException($message);
            }
            $updateTickets = ['user_taxid' => $user->taxid];

            if($request->has('credit_transaction')){
                $updateTickets['is_buy']  = true;
            }

            Ticket::whereIn('id',$tickets)
            ->update($updateTickets);
            $dataTickets = Ticket::whereIn('id',$tickets)->get();

            if($request->has('credit_transaction')){
                $template = 'emails.payment-tickets';
                sendEmail($user->email,'Compra de boletos HAYU24', $template,[
                    'tickets' => $dataTickets,
                    'raffle' => $raffle,
                    'user' => $user,
                    'receipt' => $receipt,
                    'seller' => true
                ]);
            } else {
                $template = 'emails.payment-tickets';
                sendEmail($user->email,'Compra de boletos HAYU24', $template,[
                    'tickets' => $dataTickets,
                    'raffle' => $raffle,
                    'user' => $user,
                    'receipt' => $receipt,
                ]);
            }
            
            if($newUser){
                $template = 'emails.register';
                $code = base64_encode($user->id);
                   sendEmail($request->email,'Autentificacion de registro',$template,[
                       'user' => $user,
                       'url' => "security/register/confirm/$code"
                   ]);
            }

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

    private function transactionCredit($raffle) {
        $income = floatval($raffle->income);
        $total =  floatval(request()->get('total'));
        $value =  round(($income  +  $total),2);
        $raffle->income = $value;
        $raffle->save();
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


    private function validateCode(Request $request,$user){

        if(!$request->has('seller_code')){
            return;    
        }

        $commission = Commissions::where('code', $request->seller_code)
        ->with(['raffle'])
        ->first();
        if(!$commission){
            return [
                'invalid_code' => true,
                'message_code' => 'El código ingresado no existe.'
            ];
        }

        if( $commission->status !== Commissions::STATUS_ACTIVE ){
            return [
                'invalid_code' => true,
                'message_code' => 'El código ingresado no se encuentra activo.'
            ];
        
        }
        $tickets = json_decode($request->tickets);
        $raffle = $commission->raffle;
        $porcent = floatval($raffle->commission_sellers);
        $price =  floatval($raffle->price);
        $value = round($price * $porcent,2);
        $dataTotal = [];
        $date = now();
        foreach($tickets as $ticket){
            $data = [
                'commissions_id' => $commission->id,
                'tickets_id' => $ticket,
                'is_sales_code' => true,
                'value' => $value,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => $date,
                'updated_at' => $date
            ];
            if($request->has('credit_transaction')){
                $data['is_complete'] = true;
            }
           array_push($dataTotal,$data);
        }

        Sales::insert($dataTotal);       
        
    }
    
}
