<?php

namespace App\Http\Controllers\Api;

use App\Actions\Course\DeleteCourseAction;
use App\Actions\Course\FetchCoursesAction;
use App\Actions\Course\FindCourseAction;
use App\Actions\Course\StoreCourseAction;
use App\Actions\Course\UpdateCourseAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\GetCoursesRequest;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(GetCoursesRequest $request, FetchCoursesAction $action): AnonymousResourceCollection
    {
        $courses = $action->execute($request->validated());

        return CourseResource::collection($courses);
    }

    /**
     * Store a newly created course.
     */
    public function store(StoreCourseRequest $request, StoreCourseAction $action): JsonResponse
    {
        $course = $action->execute($request->validated());

        return (new CourseResource($course))
            ->additional([
                'success' => true,
                'message' => 'Course created successfully',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified course.
     */
    public function show(string $id, FindCourseAction $action): JsonResponse|CourseResource
    {
        $course = $action->execute($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);
        }

        return new CourseResource($course);
    }

    /**
     * Update the specified course.
     */
    public function update(UpdateCourseRequest $request, string $id, FindCourseAction $findAction, UpdateCourseAction $updateAction): JsonResponse|CourseResource
    {
        $course = $findAction->execute($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);
        }

        $updatedCourse = $updateAction->execute($course, $request->validated());

        return (new CourseResource($updatedCourse))
            ->additional([
                'success' => true,
                'message' => 'Course updated successfully',
            ]);
    }

    /**
     * Remove the specified course.
     */
    public function destroy(string $id, FindCourseAction $findAction, DeleteCourseAction $deleteAction): JsonResponse
    {
        $course = $findAction->execute($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);
        }

        $deleteAction->execute($course);

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully',
        ]);
    }
}
