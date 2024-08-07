<?php

namespace Modules\Client\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Client\app\Models\Ticket;

class TicketController extends Controller
{
    public array $data = [];

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


    public function showByUser($taxid){
        try {
            $data = Ticket::where('tickets.user_taxid', $taxid)
            ->join('raffles', 'raffles.id','=','tickets.raffles_id')
            ->where('raffles.is_complete',false)
            ->get();
            return response_success($data);
        } catch (\Throwable $th) {
           return response_error($th->getMessage());
        }
    }
}
