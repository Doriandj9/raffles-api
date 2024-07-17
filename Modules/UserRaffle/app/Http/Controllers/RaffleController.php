<?php

namespace Modules\UserRaffle\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\CompleteRaffle;
use App\Jobs\NewRaffle;
use App\Jobs\Raffles;
use App\Models\Messages;
use App\Models\User;
use App\Traits\FileHandler;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Client\app\Models\Receipt;
use Modules\Client\app\Models\Ticket;
use Modules\UserRaffle\app\Filters\ReceiptFilter;
use Modules\UserRaffle\app\Http\Requests\RaffleRequest;
use Modules\UserRaffle\app\Http\Services\RaffleServices;
use Modules\UserRaffle\app\Models\Raffle;

class RaffleController extends Controller
{
    use FileHandler;
    public array $data = [];

    public function __construct(
        private RaffleServices $raffleServices,
        private ReceiptFilter $filters
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
    public function store(RaffleRequest $request): JsonResponse
    {
        
        try {
            DB::beginTransaction();
            $this->validationsRaffes();
            $type = 'logos_raffles';
            $ci = auth()->user()->taxid;
            $uri = "users/$ci/$type";
            $dataDB = [
                'name' => $request->name,
                'draw_date' => $request->draw_date,
                'description' => $request->description,
                'summary' => $request->summary,
                'price' => $request->price,
                'commission_sellers' => $request->commission_sellers,
                'number_tickets' => $request->number_tickets,
                'subscriptions_id' => $request->subscription_id,
                'user_taxid' => $ci
            ];
            if($request->hasFile('logo_raffles')){
                $path = $this->storeFile($request->file('logo_raffles'),$uri);
                $dataDB['logo_raffles'] = $path;
            }

            $awards = json_decode($request->awards);
            $type = 'awards';
            $uri = "users/$ci/$type";
            foreach($awards as $key => $award){
                if($request->hasFile($award->imgId)){
                    $path = $this->storeFile($request->file($award->imgId),$uri);
                    $awards[$key]->path = $path;
                }
            }
            $dataDB['awards'] = json_encode($awards);
            $data = $this->raffleServices->save($dataDB,false);
            $tickets = $this->raffleServices->customSaveTickets($data);
            $user = User::find(auth()->user()->id);
            $user->raffles = ($user->raffles + 1);
            $user->save();
            NewRaffle::dispatch($data->id);
            DB::commit();
            return response_success($data);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response_error($e->getMessage());
        }

    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        //
        try {
            $data = Raffle::findOrFail($id);
            if($data->is_complete){
                throw new \Exception('Esta rifa no esta disponible');
            }

            return response_success($data);
        } catch (\Throwable $e) {
            return response_error($e->getMessage(),400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->raffleServices->update($id);
            if($request->in_sorter == 'true' ? true : false){
                Raffles::dispatch($id,[],'emails.update-raffles-link','Notificacion, ingreso al en vivo del sorteo de la rifa');
            }
            DB::commit();
            return response_success($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response_error($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = User::find(auth()->user()->id);
            $raffle = Raffle::findOrFail($id);
            $tickets = Ticket::where('raffles_id',$raffle->id)->get();
            $tickets = $tickets->filter(function(Ticket $ticket, int $index){
                return $ticket->user_taxid;
            });
            if(count($tickets) > 0){
                throw new ErrorException(Messages::NOT_PERMITE_DELETE_RAFFLE);
            }
            $ticketsIds = [];
            foreach($tickets as $ticket){
                array_push($ticketsIds,$ticket->id);
            }
            Ticket::whereIn('id',$ticketsIds)->delete();
            $raffle->delete();
            $userRaffles = ($user->raffles - 1);
            $user->raffles = $userRaffles;
            $user->save();
            DB::commit();
            return response_success('OK');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response_error($th->getMessage());
            
        }
    }

    public function listForItems($taxid){

        try {
            $raffles = Raffle::where('user_taxid',$taxid)
            ->where('is_complete',false)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
            return response()->json($raffles);
            
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }


    public function indexRaffles(){
        try {

            $raffles = Raffle::where('is_complete',false)
            ->orderBy('created_at','desc')
            ->paginate(8);

            return response()->json($raffles);
            
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }

    public function lastRaffles(){
        try {

            $raffles = Raffle::where('is_complete',false)
            ->orderBy('draw_date','asc')
            ->limit(3)
            ->get();

            return response_success($raffles);
            
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }

    public function showTicketsByRaffle($id) {
        try {

            $tickets = Ticket::where('raffles_id',$id)
            ->orderBy('order', 'asc')
            ->paginate(200);
            return response()->json($tickets);
            
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }


    public function updateRaffle(Request $request, $id){
        try {
            DB::beginTransaction();
            $this->validationsRaffesUpdate();
            $type = 'logos_raffles';
            $ci = auth()->user()->taxid;
            $uri = "users/$ci/$type";
            $raffle = Raffle::find($id);
            $updated = $this->raffleServices->updateCustomTickets($raffle,intval($request->more_tickets));

            $dataDB = [
                'name' => $request->name,
                'draw_date' => $request->draw_date,
                'description' => $request->description,
                'summary' => $request->summary,
                'price' => $request->price,
                'commission_sellers' => $request->commission_sellers,
                'number_tickets' => $updated ? (intval(request()->number_tickets) + intval(request()->more_tickets)) : $request->number_tickets,
            ];

            if($request->hasFile('logo_raffles')){
                $path = $this->storeFile($request->file('logo_raffles'),$uri);
                $dataDB['logo_raffles'] = $path;
            }

            $awards = json_decode($request->awards);
            $type = 'awards';
            $uri = "users/$ci/$type";
            foreach($awards as $key => $award){
                if($request->hasFile($award->imgId)){
                    $path = $this->storeFile($request->file($award->imgId),$uri);
                    $awards[$key]->path = $path;
                }
            }

            $dataDB['awards'] = json_encode($awards);
            $dataChange = [
                'Fecha del sorteo' => [$raffle->draw_date, $request->draw_date]
            ];
            $data = $this->raffleServices->update($id,$dataDB,false);

            //lanzamos envios de correos electronicos masivos
            $date1 = Carbon::parse($dataChange['Fecha del sorteo'][0]);
            $date2 = Carbon::parse($dataChange['Fecha del sorteo'][1]);

            if($date1->format('Y-m-d H:i') !== $date2->format('Y-m-d H:i')){
                Raffles::dispatch($id,$dataChange,'emails.update-raffles','Cambio de fecha para el sorteo de la rifa');
            }
            DB::commit();
            return response_success($data);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response_error($e->getMessage());
        }
    }

    public function showReceiptsByUser($taxid){
        try {
            $data = Receipt::where('organizer_raffles_taxid', $taxid)
            ->filters($this->filters)
            ->where('transaction', true)
            ->paginate(10);

            return response()->json($data);
        } catch (\Throwable $th) {
           return response_error($th->getMessage());
        }
    }

    public function reSendEmail($receipt_id){
        try {
            $receipt = Receipt::find($receipt_id);
            $user = User::find($receipt->user_id);
            $tickets = json_decode($receipt->description);
            $dataTickets = Ticket::whereIn('id',$tickets)->get();
            if($receipt->status === Receipt::STATUS_CONFIRM){
                $template = 'emails.payment-tickets-aprove';
                sendEmail($user->email,'Verificacion de boletos HAYU24', $template,[
                    'tickets' => $dataTickets,
                    'user' => $user,
                ]);

                return response_success([]);
            }

            $template = 'emails.payment-tickets-faild';
            sendEmail($user->email,'Fallo la veracidad del comprobante de pago',$template,[
                    'user' => $user,
                    'tickets' => $dataTickets,
                    'observation' => 'Comprobante con errores.'
                ]);
            return response_success([]);
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }

    public function complete(Request $request, $id){
        try {
            DB::beginTransaction();
            $data = $this->raffleServices->update($id); 
            CompleteRaffle::dispatch($id);
            DB::commit();
            return response_success($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response_error($th->getMessage() . ' ' . $th->getMessage());
        }
    }


    private function validationsRaffes(): void
    {
        $user = auth()->user();
        $orgRaffles = User::find($user->id);

        if($orgRaffles->bankAccounts->count() === 0){
            throw new ErrorException(Messages::NOT_ACCOUNT_PRESENT);
        }

        if($user->remaining_days_suscription <= 0 ){
            throw new ErrorException(Messages::NOT_DAYS_SUBSCRIPTION);
        }

        if($orgRaffles->raffles >= $user->subscription->number_raffles && $orgRaffles->raffles !== 0 ){
            throw new ErrorException(Messages::NOT_PERMITE_MORE_RAFFLES);
        }

    }

    private function validationsRaffesUpdate(): void
    {
        $user = auth()->user();
        $subscription = $user->subscription;

        if((intval(request()->number_tickets) + intval(request()->more_tickets)) > $subscription->maximum_tickets){
            throw new ErrorException(Messages::NOT_TICKET_FOR_CHANGE_PLAN);
        }

    }


}
