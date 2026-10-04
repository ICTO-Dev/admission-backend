<?php

namespace App\Http\Controllers\Api;

use App\Actions\ExamSchedule\DeleteExamScheduleAction;
use App\Actions\ExamSchedule\FetchExamSchedulesAction;
use App\Actions\ExamSchedule\FindExamScheduleAction;
use App\Actions\ExamSchedule\StoreExamScheduleAction;
use App\Actions\ExamSchedule\UpdateExamScheduleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExamSchedule\StoreExamScheduleRequest;
use App\Http\Requests\ExamSchedule\UpdateExamScheduleRequest;
use App\Http\Resources\ExamScheduleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExamScheduleController extends Controller
{
    public function index(Request $request, FetchExamSchedulesAction $action): AnonymousResourceCollection
    {
        $schedules = $action->execute($request->all());

        return ExamScheduleResource::collection($schedules);
    }

    public function store(StoreExamScheduleRequest $request, StoreExamScheduleAction $action): JsonResponse
    {
        $schedule = $action->execute($request->validated());

        return (new ExamScheduleResource($schedule))
            ->additional([
                'success' => true,
                'message' => 'Exam schedule created successfully',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $id, FindExamScheduleAction $action): JsonResponse|ExamScheduleResource
    {
        $schedule = $action->execute($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Exam schedule not found',
            ], 404);
        }

        return new ExamScheduleResource($schedule);
    }

    public function update(UpdateExamScheduleRequest $request, string $id, FindExamScheduleAction $findAction, UpdateExamScheduleAction $updateAction): JsonResponse|ExamScheduleResource
    {
        $schedule = $findAction->execute($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Exam schedule not found',
            ], 404);
        }

        try {
            $updated = $updateAction->execute($schedule, $request->validated());
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return (new ExamScheduleResource($updated))
            ->additional([
                'success' => true,
                'message' => 'Exam schedule updated successfully',
            ]);
    }

    public function destroy(string $id, FindExamScheduleAction $findAction, DeleteExamScheduleAction $deleteAction): JsonResponse
    {
        $schedule = $findAction->execute($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Exam schedule not found',
            ], 404);
        }

        try {
            $deleteAction->execute($schedule);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Exam schedule deleted successfully',
        ]);
    }
}
