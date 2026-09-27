<?php

namespace App\Http\Controllers\Api;

use App\Actions\Batch\DeleteBatchAction;
use App\Actions\Batch\FetchBatchesAction;
use App\Actions\Batch\FindBatchAction;
use App\Actions\Batch\StoreBatchAction;
use App\Actions\Batch\UpdateBatchAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Batch\StoreBatchRequest;
use App\Http\Requests\Batch\UpdateBatchRequest;
use App\Http\Resources\BatchResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BatchController extends Controller
{
    public function index(Request $request, FetchBatchesAction $action): AnonymousResourceCollection
    {
        $batches = $action->execute($request->all());

        return BatchResource::collection($batches);
    }

    public function store(StoreBatchRequest $request, StoreBatchAction $action): JsonResponse
    {
        $batch = $action->execute($request->validated());

        return (new BatchResource($batch))
            ->additional([
                'success' => true,
                'message' => 'Exam batch created successfully',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $id, FindBatchAction $action): JsonResponse|BatchResource
    {
        $batch = $action->execute($id);

        if (!$batch) {
            return response()->json([
                'success' => false,
                'message' => 'Exam batch not found',
            ], 404);
        }

        return new BatchResource($batch);
    }

    public function update(UpdateBatchRequest $request, string $id, FindBatchAction $findAction, UpdateBatchAction $updateAction): JsonResponse|BatchResource
    {
        $batch = $findAction->execute($id);

        if (!$batch) {
            return response()->json([
                'success' => false,
                'message' => 'Exam batch not found',
            ], 404);
        }

        $updated = $updateAction->execute($batch, $request->validated());

        return (new BatchResource($updated))
            ->additional([
                'success' => true,
                'message' => 'Exam batch updated successfully',
            ]);
    }

    public function destroy(string $id, FindBatchAction $findAction, DeleteBatchAction $deleteAction): JsonResponse
    {
        $batch = $findAction->execute($id);

        if (!$batch) {
            return response()->json([
                'success' => false,
                'message' => 'Exam batch not found',
            ], 404);
        }

        $deleteAction->execute($batch);

        return response()->json([
            'success' => true,
            'message' => 'Exam batch deleted successfully',
        ]);
    }
}
