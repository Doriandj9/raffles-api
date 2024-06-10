<?php

namespace Modules\UserRaffle\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CardTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public array $data = [];

    public function __construct( private CardTransactionService $cardTransactionService )
    {}

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
           $statusCode = $request->json('statusCode');
           $idTransaction = $request->json('clientTransactionId');

           $data = [
            'status_code' => $statusCode,
            'transaction_id' => $idTransaction,
            'payload' => json_encode($request->json()->all())
           ];

           $transaction = $this->cardTransactionService->save($data,false);

           if($statusCode !== 3){
            throw new \ErrorException('La transacción no se pudo completar por parte de payphone.');
           }
           
           DB::commit();
           return response_success($transaction);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response_error($th->getMessage(),200);
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
}
