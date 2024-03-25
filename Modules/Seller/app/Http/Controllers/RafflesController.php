<?php

namespace Modules\Seller\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\Raffles;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Seller\app\Http\Services\CommissionService;
use Modules\Seller\app\Models\Commissions;
use Modules\UserRaffle\app\Models\Raffle;

class RafflesController extends Controller
{
    public function __construct(
        private CommissionService $commissionService
    )
    {
    }
    public array $data = [];

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();
            $commissions = Commissions::where('user_taxid',$user->taxid)->get();
            $commissions = $commissions->map(function(Commissions $commission,int $key){
                return $commission->raffles_id;
            }); 


            $raffles = Raffle::where('is_complete',false)
            ->orderBy('created_at','desc')
            ->get();

            $raffles = $raffles->filter(function(Raffle $raffle) use ($commissions) {
                return !in_array(intval($raffle->id),$commissions->toArray());
            });
            $data = [...$raffles->toArray()];
            

            
            return response_success($data);
        } catch (\Throwable $th) {
           return response_error($th->getMessage() . $th->getLine());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        //
        try {
            DB::beginTransaction();
            $this->validations();
            $commission = $this->commissionService->storeCommission($request);
            $userSeller = auth()->user();
            $raffle= Raffle::find($request->raffles_id);
            $userRaffle = $raffle->user;
            // Correo electronico al vendedor
            $templateSeller = 'emails.reserve-solicitude';
            $templateRaffle = 'emails.solicitude-seller';

            sendEmail($userSeller->email,'Solicitud de afiliacion', $templateSeller,[
                'user' => $userSeller,
                'raffle' => $raffle,
                'commission' => $commission
            ]);

            sendEmail($userRaffle->email,'Solicitud de afiliacion',$templateRaffle,[
                'user' => $userRaffle,
                'raffle' => $raffle,
                'commission' => $commission,
                'seller' => $userSeller
            ]);

            DB::commit();
            return response_create($commission);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response_error($th->getMessage());
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

    private function validations() {
        $user = auth()->user();
        $commission = Commissions::where('raffles_id',request()->raffles_id)
        ->where('user_taxid',$user->id)
        ->get();
        if($commission->count() > 0) {
            throw new ErrorException('No puede solicitar una afiliación a una ya existente, por favor revise sus afiliaciones.');
        }
    }
}
