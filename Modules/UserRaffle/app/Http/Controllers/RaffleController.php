<?php

namespace Modules\UserRaffle\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use App\Models\User;
use App\Traits\FileHandler;
use Error;
use ErrorException;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Client\app\Models\Ticket;
use Modules\UserRaffle\app\Http\Requests\RaffleRequest;
use Modules\UserRaffle\app\Http\Services\RaffleServices;
use Modules\UserRaffle\app\Models\Raffle;

class RaffleController extends Controller
{
    use FileHandler;
    public array $data = [];

    public function __construct(
        private RaffleServices $raffleServices
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
            $raffles = $this->raffleServices->customSaveTickets($data);
            $user = User::find(auth()->user()->id);
            $user->raffles = ($user->raffles + 1);
            $user->save();

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
            return response_success($data);
        } catch (\Throwable $e) {
            return response_error($e->getMessage(),200);
        }
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
            ->paginate(1);

            return response()->json($raffles);
            
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }

    public function showTicketsByRaffle($id) {
        try {

            $tickets = Ticket::where('raffles_id',$id)
            ->orderBy('order', 'asc')
            ->get();
            return response_success($tickets);
            
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
            $data = $this->raffleServices->update($id,$dataDB,false);
            DB::commit();
            return response_success($data);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response_error($e->getMessage());
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
