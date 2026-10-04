<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\CampusController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ExamScheduleController;
use App\Http\Controllers\Api\GeoController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\SchoolYearController;
use App\Http\Controllers\Api\VenueController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// Geo Location Routes
Route::prefix('geo')->group(function () {
    Route::get('/regions', [GeoController::class, 'regions']);
    Route::get('/provinces', [GeoController::class, 'provinces']);
    Route::get('/municipalities', [GeoController::class, 'municipalities']);
    Route::get('/barangays', [GeoController::class, 'barangays']);
});

// Public / Resource Routes
Route::apiResource('campuses', CampusController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('applications', ApplicationController::class)->only(['index', 'store', 'show']);
Route::patch('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);
Route::post('/applications/{id}/assign-schedule', [ApplicationController::class, 'assignSchedule']);
Route::get('/applications/{id}/pdf-permit', [\App\Http\Controllers\PermitController::class, 'showPermit']);

// Exam Scheduling Resource Routes
Route::apiResource('school-years', SchoolYearController::class);
Route::apiResource('venues', VenueController::class);
Route::apiResource('rooms', RoomController::class);
Route::apiResource('batches', BatchController::class);
Route::apiResource('exam-schedules', ExamScheduleController::class);

