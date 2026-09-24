<?php

namespace App\Http\Controllers\Api;

use App\Actions\Application\FetchApplicationsAction;
use App\Actions\Application\FindApplicationAction;
use App\Actions\Application\StoreApplicationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\StoreApplicationRequest;
use App\Http\Resources\ApplicationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications.
     */
    public function index(Request $request, FetchApplicationsAction $action): AnonymousResourceCollection
    {
        $applications = $action->execute(
            $request->only(['search', 'status']),
            $request->integer('per_page', 15)
        );

        return ApplicationResource::collection($applications);
    }

    /**
     * Store a newly created application.
     */
    public function store(StoreApplicationRequest $request, StoreApplicationAction $action): JsonResponse
    {
        $application = $action->execute($request->validated());

        return (new ApplicationResource($application))
            ->additional([
                'success' => true,
                'message' => 'Application submitted successfully',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified application by Application Number or ID.
     */
    public function show(string $applicationNo, FindApplicationAction $action): JsonResponse|ApplicationResource
    {
        $application = $action->execute($applicationNo);

        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'Application record not found',
            ], 404);
        }

        return (new ApplicationResource($application))
            ->additional([
                'success' => true,
            ]);
    }
}
