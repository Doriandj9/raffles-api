<?php

namespace Modules\Seller\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Client\app\Models\Receipt;
use Modules\Client\app\Models\Ticket;
use Modules\Client\app\Services\ReceiptService;
use Modules\Seller\app\Http\Services\SalesService;
use Modules\Seller\app\Models\Commissions;
use Modules\Seller\app\Models\Sales;
use Modules\UserRaffle\app\Models\Raffle;

class PaymentTicketsController extends Controller
{
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
               $template = 'emails.register';
               $code = base64_encode($user->id);
                   sendEmail($request->email,'Autentificacion de registro',$template,[
                       'user' => $user,
                       'url' => "security/register/confirm/$code"
                   ]);
            }
            if(!$this->validateUser($user)){
                throw new \ErrorException(Messages::DATA_USER_INCORRECT);
            }
            $raffle = Raffle::where('id', $request->raffles_id)->first();

            if($user->taxid === $raffle->user_taxid){
                throw new \ErrorException(Messages::USER_OWNER_RAFFLE);
            }
            

            $dataReceipt  = [
                'user_id' => $user->id,
                'organizer_raffles_taxid' => $raffle->user_taxid,
                'description' => $request->tickets,
                'total' => $request->total,
                'subtotal' => $request->subtotal ?? ($request->amount * $request->single_price),
                'amount' => $request->amount,
                'single_price' => $request->single_price,
                'voucher' => '',
                'is_active' => false,
                'transaction' => true, 
                'status' => Receipt::STATUS_CONFIRM
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

                throw new \ErrorException($message);
            }

            Ticket::whereIn('id',$tickets)
            ->update(['user_taxid' => $user->taxid,'is_buy' => true]);
            // ingresamos las comisiones
            $response = $this->validateCode($request,auth()->user());
            if(is_array($response)){
                return response_error($response['message_code']);
            }
            $dataTickets = Ticket::whereIn('id',$tickets)->get();
            $template = 'emails.payment-tickets';

            sendEmail($user->email,'Compra de boletos HAYU24', $template,[
                'tickets' => $dataTickets,
                'raffle' => $raffle,
                'user' => $user,
                'receipt' => $receipt,
                'seller' => true
            ]);

            $message = $newUser ? Messages::NEW_USER_PAYMENT_TICKET : Messages::USER_PAYMENT_TICKET;
            DB::commit();
            return response_create($receipt,[],$message);
        } catch (\Throwable $th) {
            DB::rollBack();
            $message = $th instanceof UniqueConstraintViolationException ? 
            'Error un usuario con el mismo número de cédula ya existe en la plataforma y el correo electrinico es diferente.'
            : $th->getMessage() . $th->getFile() . $th->getLine();
            return response_error($message);
        }
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

    public function salesByUser($taxid){
        try {
            $commissions = Commissions::where('user_taxid',$taxid)
            ->get();
            $commissions = $commissions->map(function(Commissions $commission, int $index){
                return $commission->id;
            });
            $sales = Sales::whereIn('commissions_id',$commissions->toArray())
            ->where('is_sales_code', false)
            ->with(['ticket'])
            ->get();

            return response_success($sales);

        } catch (\Throwable $th) {
            return response_error($th->getMessage());
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
    
    private function validateCode(Request $request,$user){

        if(!$request->has('commission_id')){
            return [
                'invalid_code' => true,
                'message_code' => 'No puede realizar una venta si es afiliado.'
            ]; 
        }

        $commission = Commissions::where('id', $request->commission_id)
        ->with(['raffle'])
        ->first();
        if(!$commission){
            return [
                'invalid_code' => true,
                'message_code' => 'Su afiliación es incorrecta.'
            ];
        }

        if( $commission->status !== Commissions::STATUS_ACTIVE ){
            return [
                'invalid_code' => true,
                'message_code' => 'La afiliación ingresada no se encuentra activa.'
            ];
        
        }
        if( $commission->seller_pos == false  ){
            return [
                'invalid_code' => true,
                'message_code' => 'No tiene permisos para realizar ventas físicas en esta comisión.'
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
                'is_sales_code' => false,
                'value' => $value,
                'is_complete' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => $date,
                'updated_at' => $date
            ];
           array_push($dataTotal,$data);
        }

        Sales::insert($dataTotal);       
        
    }
}
