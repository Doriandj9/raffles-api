<?php

namespace Modules\Client\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Client\app\Models\Rating;
use Modules\Client\app\Services\RatingService;

class RatingController extends Controller
{
    public array $data = [];

    public function __construct(private RatingService $ratingService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $data = Rating::all();
            return response_success($data);
        } catch (\Throwable $th) {
            return response_error($th->getMessage());
        }
    }

    public function indexDisabled(): JsonResponse
    {
        try {
            $data = Rating::where('is_active', true)
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
            $data = $this->ratingService->save();
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
            $data = Rating::find($id);
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
            $data = $this->ratingService->update($id);
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
