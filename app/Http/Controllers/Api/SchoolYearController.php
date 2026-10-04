<?php

namespace App\Http\Controllers\Api;

use App\Actions\SchoolYear\DeleteSchoolYearAction;
use App\Actions\SchoolYear\FetchSchoolYearsAction;
use App\Actions\SchoolYear\FindSchoolYearAction;
use App\Actions\SchoolYear\StoreSchoolYearAction;
use App\Actions\SchoolYear\UpdateSchoolYearAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolYear\StoreSchoolYearRequest;
use App\Http\Requests\SchoolYear\UpdateSchoolYearRequest;
use App\Http\Resources\SchoolYearResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SchoolYearController extends Controller
{
    public function index(Request $request, FetchSchoolYearsAction $action): AnonymousResourceCollection
    {
        $schoolYears = $action->execute($request->all());

        return SchoolYearResource::collection($schoolYears);
    }

    public function store(StoreSchoolYearRequest $request, StoreSchoolYearAction $action): JsonResponse
    {
        $schoolYear = $action->execute($request->validated());

        return (new SchoolYearResource($schoolYear))
            ->additional([
                'success' => true,
                'message' => 'School year created successfully',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $id, FindSchoolYearAction $action): JsonResponse|SchoolYearResource
    {
        $schoolYear = $action->execute($id);

        if (!$schoolYear) {
            return response()->json([
                'success' => false,
                'message' => 'School year not found',
            ], 404);
        }

        return new SchoolYearResource($schoolYear);
    }

    public function update(UpdateSchoolYearRequest $request, string $id, FindSchoolYearAction $findAction, UpdateSchoolYearAction $updateAction): JsonResponse|SchoolYearResource
    {
        $schoolYear = $findAction->execute($id);

        if (!$schoolYear) {
            return response()->json([
                'success' => false,
                'message' => 'School year not found',
            ], 404);
        }

        $updated = $updateAction->execute($schoolYear, $request->validated());

        return (new SchoolYearResource($updated))
            ->additional([
                'success' => true,
                'message' => 'School year updated successfully',
            ]);
    }

    public function destroy(string $id, FindSchoolYearAction $findAction, DeleteSchoolYearAction $deleteAction): JsonResponse
    {
        $schoolYear = $findAction->execute($id);

        if (!$schoolYear) {
            return response()->json([
                'success' => false,
                'message' => 'School year not found',
            ], 404);
        }

        $deleteAction->execute($schoolYear);

        return response()->json([
            'success' => true,
            'message' => 'School year deleted successfully',
        ]);
    }
}
