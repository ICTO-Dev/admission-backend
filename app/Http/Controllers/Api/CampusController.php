<?php

namespace App\Http\Controllers\Api;

use App\Actions\Campus\DeleteCampusAction;
use App\Actions\Campus\FetchCampusesAction;
use App\Actions\Campus\FindCampusAction;
use App\Actions\Campus\StoreCampusAction;
use App\Actions\Campus\UpdateCampusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Campus\StoreCampusRequest;
use App\Http\Requests\Campus\UpdateCampusRequest;
use App\Http\Resources\CampusResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CampusController extends Controller
{
    /**
     * Display a listing of campuses.
     */
    public function index(Request $request, FetchCampusesAction $action): AnonymousResourceCollection
    {
        $campuses = $action->execute($request->boolean('with_courses'));

        return CampusResource::collection($campuses);
    }

    /**
     * Store a newly created campus.
     */
    public function store(StoreCampusRequest $request, StoreCampusAction $action): JsonResponse
    {
        $campus = $action->execute($request->validated());

        return (new CampusResource($campus))
            ->additional([
                'success' => true,
                'message' => 'Campus created successfully',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified campus.
     */
    public function show(string $id, FindCampusAction $action): JsonResponse|CampusResource
    {
        $campus = $action->execute($id);

        if (!$campus) {
            return response()->json([
                'success' => false,
                'message' => 'Campus not found',
            ], 404);
        }

        return new CampusResource($campus);
    }

    /**
     * Update the specified campus.
     */
    public function update(UpdateCampusRequest $request, string $id, FindCampusAction $findAction, UpdateCampusAction $updateAction): JsonResponse|CampusResource
    {
        $campus = $findAction->execute($id);

        if (!$campus) {
            return response()->json([
                'success' => false,
                'message' => 'Campus not found',
            ], 404);
        }

        $updatedCampus = $updateAction->execute($campus, $request->validated());

        return (new CampusResource($updatedCampus))
            ->additional([
                'success' => true,
                'message' => 'Campus updated successfully',
            ]);
    }

    /**
     * Remove the specified campus.
     */
    public function destroy(string $id, FindCampusAction $findAction, DeleteCampusAction $deleteAction): JsonResponse
    {
        $campus = $findAction->execute($id);

        if (!$campus) {
            return response()->json([
                'success' => false,
                'message' => 'Campus not found',
            ], 404);
        }

        $deleteAction->execute($campus);

        return response()->json([
            'success' => true,
            'message' => 'Campus deleted successfully',
        ]);
    }
}
