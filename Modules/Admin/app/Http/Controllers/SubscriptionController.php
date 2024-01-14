<?php

namespace Modules\Admin\app\Http\Controllers;

use App\Core\JWToken;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Admin\app\Http\Requests\SubscriptionRequest;
use Modules\Admin\app\Models\Subscription;
use Modules\Admin\app\Services\SubscriptionService;
use Modules\Admin\app\Services\UserService;

class SubscriptionController extends Controller
{
    public array $data = [];
    public function __construct(
        private SubscriptionService $subscriptionService,
        private UserService $userService
        ) 
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        //
        $data = Subscription::all();
        return response_success($data);
    }

    public function getActives(): JsonResponse
    {
        $data = Subscription::where('is_active',1)->get();

        return response_success($data);
    } 

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubscriptionRequest $request): JsonResponse
    {
        // dd($request);
        try{
            $data = $this->subscriptionService->save();
            return response_create($data);

        }catch(ValidationException $e){
            return response_error($e->getMessage(),200,['messages' => $e->validator->errors()]);
        }

    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            //code...
            $data = Subscription::findOrFail($id);
    
            return response_success($data);
        } catch (\Throwable $e) {
            //throw $th;
            return response_error($e->getMessage(),200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        //
        $data = $this->subscriptionService->update($id);
        return response()->json($this->data);
    }

    public function updateSubUser(Request $request, $id): JsonResponse
    {
        
        try{
            $extra = ['is_new' => false];
            $data = $this->userService->update($id,$extra);
            return response_update(['user' => $data, 'token' => JWToken::create($data->toArray())]);
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
