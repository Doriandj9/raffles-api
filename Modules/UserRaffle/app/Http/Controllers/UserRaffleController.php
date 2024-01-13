<?php

namespace Modules\UserRaffle\app\Http\Controllers;

use App\Core\JWToken;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Admin\app\Services\UserService;

class UserRaffleController extends Controller
{
    public array $data = [];

    public function __construct(private UserService $userService)
    {
        $this->userService = $userService;
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
        //
        try{
            $data = $this->userService->update($id);
            return response_update(['user' => $data, 'token' => JWToken::create($data)],
            ['plan' => 'Te has actualizado correctamente al nuevo plan.']);
            return response_update($data,);
        }catch(\Throwable $e){
            return response_error($e->getMessage(),200);
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
