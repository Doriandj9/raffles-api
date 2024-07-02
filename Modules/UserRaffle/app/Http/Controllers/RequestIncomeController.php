<?php

namespace Modules\UserRaffle\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\UserRaffle\app\Http\Services\RequestIncomeServices;
use Modules\UserRaffle\app\Models\Raffle;
use Modules\UserRaffle\app\Models\RequestIncome;

class RequestIncomeController extends Controller
{
    public array $data = [];

    public function __construct(private RequestIncomeServices $requestIncomeServices)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $requests = RequestIncome::where('is_active',true)
            ->where('is_active', true)
            ->paginate(10);
            return response()->json($requests);
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $this->validationStore();
            $user = auth()->user();
            $additional = ['user_id' => $user->id ];
            $data = $this->requestIncomeServices->save($additional);
            return response_create($data);
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $data = RequestIncome::findOrFail($id);
            return response_success($data);
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->requestIncomeServices->update($id);
            if($request->status === 'AC'){
                $raffle =  Raffle::find($data->raffle_id);
                $income = floatval($raffle->income);
                $value = round(($income - floatval($data->amount)),2);
                $raffle->income = $value;
                $raffle->save(); 
            }
            DB::commit();
            return response_update($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response_error($th->getMessage());
        }
    }

    public function getForUser($id){
        try {
            $data = RequestIncome::where('user_id', $id)
            ->where('is_active', true)
            ->paginate(10);
            return response()->json($data);
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        
        try {
            $requestIncome = RequestIncome::findOrFail($id);
            if($requestIncome->status  === RequestIncome::STATUS_INPROGRESS){
                throw new ErrorException(Messages::NOT_DELETE_INCOME_INPROGRESS);
            }
            $this->requestIncomeServices->update($id,['is_active' => false],false);
            return response_delete('OK');
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }

    private function validationStore(){
        
        $raffle = Raffle::findOrFail(request()->get('raffle_id'));
        $incomeTotal = round(floatval($raffle->income),2);
        $amontIncome = round(floatval(request()->get('amount')));

        if($amontIncome > $incomeTotal){
            throw new ErrorException(Messages::NOT_INCOME_AMOUTN);
        }

        $requestIncomesCount = RequestIncome::where('raffle_id',request()->get('raffle_id'))
        ->where('user_id', auth()->id())
        ->where('status',RequestIncome::STATUS_DRAFT)
        ->count();

        if($requestIncomesCount > 0){
            throw new ErrorException(Messages::NOT_DRAFT_INCOME_COUNT);
        }


    }
}
