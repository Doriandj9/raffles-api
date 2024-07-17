<?php

namespace Modules\Admin\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Admin\app\Models\Winners;
use Modules\Admin\app\Services\WinnersService;
use Modules\UserRaffle\app\Models\Raffle;

class WinnersController extends Controller
{
    public array $data = [];

    public function __construct(private WinnersService $winnersService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $data = $this->winnersService
            ->where('is_active', true)
            ->where('status', Winners::STATUS_ACTIVE)
            ->paginate(10);

            return response()->json($data);
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
        //code...
       } catch (\Throwable $th) {
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

    public function rafflesCompleted () {
        try {
            $raffles = Raffle::where('is_complete',true)
            ->paginate(10);

            return response()->json($raffles);
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
}
