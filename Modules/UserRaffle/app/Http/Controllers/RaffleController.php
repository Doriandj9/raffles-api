<?php

namespace Modules\UserRaffle\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\FileHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function listForItems($taxid){

        try {

            $raffles = Raffle::where('user_taxid',$taxid)->get();

            return response_success($raffles);
            
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }
}
