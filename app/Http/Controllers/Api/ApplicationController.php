<?php

namespace App\Http\Controllers\Api;

use App\Actions\Application\FetchApplicationsAction;
use App\Actions\Application\FindApplicationAction;
use App\Actions\Application\StoreApplicationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\StoreApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Services\ApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications.
     */
    public function index(Request $request, FetchApplicationsAction $action, ApplicationService $service): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'status', 'campus_id', 'school_year', 'school_year_id']);
        $perPage = $request->integer('per_page', 10);

        $applications = $action->execute($filters, $perPage);

        $campusId = !empty($filters['campus_id']) ? (int) $filters['campus_id'] : null;
        $schoolYear = $filters['school_year_id'] ?? $filters['school_year'] ?? null;
        $counts = $service->getStatusCounts($campusId, $schoolYear);

        return ApplicationResource::collection($applications)->additional([
            'success' => true,
            'counts' => $counts,
        ]);
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

    /**
     * Update application review status (Approve, Reject, Pending).
     */
    public function updateStatus(Request $request, string $id, ApplicationService $service): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'rejection_reason' => 'nullable|string',
            'rejectionReason' => 'nullable|string',
        ]);

        $reason = $validated['rejection_reason'] ?? $validated['rejectionReason'] ?? null;

        try {
            $application = $service->updateStatus($id, $validated['status'], $reason);

            return (new ApplicationResource($application))
                ->additional([
                    'success' => true,
                    'message' => "Application status updated to {$application->status}",
                ])
                ->response();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update application status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Assign exam schedule slot to applicant.
     */
    public function assignSchedule(Request $request, string $id, ApplicationService $service): JsonResponse
    {
        $validated = $request->validate([
            'exam_schedule_id' => 'nullable|integer',
            'slotId' => 'nullable|integer',
            'slot_id' => 'nullable|integer',
            'course' => 'nullable|string',
        ]);

        $slotId = $validated['exam_schedule_id'] ?? $validated['slotId'] ?? $validated['slot_id'] ?? null;
        if (!$slotId) {
            return response()->json([
                'success' => false,
                'message' => 'Exam schedule slot ID is required.',
            ], 422);
        }

        try {
            $application = $service->assignExamSchedule($id, $slotId, $validated['course'] ?? null);

            return (new ApplicationResource($application))
                ->additional([
                    'success' => true,
                    'message' => 'Examination slot allocated successfully',
                ])
                ->response();
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign schedule: ' . $e->getMessage(),
            ], 500);
        }
    }
}
