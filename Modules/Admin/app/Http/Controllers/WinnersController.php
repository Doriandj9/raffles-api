<?php

namespace Modules\Admin\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\FileHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Admin\app\Models\Winners;
use Modules\Admin\app\Services\WinnersService;
use Modules\UserRaffle\app\Http\Services\RaffleServices;
use Modules\UserRaffle\app\Models\Raffle;

class WinnersController extends Controller
{
    use FileHandler;
    public array $data = [];

    public function __construct(private WinnersService $winnersService, private RaffleServices $raffleServices)
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
            ->where('state', Winners::STATUS_ACTIVE)
            ->get();

            return \response_success($data);
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
        $payload = json_decode($request->get('payload'));
        foreach($payload as $key => $item) {
            if($request->hasFile($item->img_reference)) {
                $ci = $item->user->taxid;
                $type = 'winner';
                $uri = "users/$ci/$type";
                $path = $this->storeFile($request->file($item->img_reference),$uri);
                $payload[$key]->path = $path;
            }   
        }
        $payload = json_encode($payload);
        $data = [
            'payload' => $payload,
            'raffles_id' => $request->get('raffles_id'),
            'state' => Winners::STATUS_ACTIVE
        ];
        if($request->has('id')){
            $response = $this->winnersService->update($request->get('id'),$data,false);
            return response_create($response);
        }

        $response = $this->winnersService->save($data,false);
        return response_create($response);
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
        try {
            $data = $this->winnersService->update($id);
            return response_success($data);
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }

    public function rafflesCompleted () {
        try {
            $raffles = Raffle::where('is_complete',true)
            ->where('is_active', true)
            ->with(['winner'])
            ->paginate(10);

            return response()->json($raffles);
           } catch (\Throwable $th) {
            return response_error($th->getMessage());
           }
    }

    public function updateRaffleComplete(Request $request, $id): JsonResponse
    {
        try {
            $data = $this->raffleServices->update($id);
            return response_success($data);
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
