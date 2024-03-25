<?php

namespace Modules\Seller\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Seller\app\Http\Services\CommissionService;
use Modules\Seller\app\Models\Commissions;

class CommissionsController extends Controller
{
    public array $data = [];

    public function __construct(
        private CommissionService $commissionService
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
        //

        return response()->json($this->data);
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
        try {
            $data = $this->commissionService->update($id);

            return response_update($data);

        } catch (\Throwable $th) {
            return response_error($th->getMessage());
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

    public function byUser($taxid){
        try {
            //code...
            $commision = Commissions::where('user_taxid',$taxid)
            ->with(['raffle'])
            ->get();
            return response_success($commision);
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }

    }
}
