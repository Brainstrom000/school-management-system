<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\TeacherApiController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\MarkApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes here are prefixed with /api automatically.
| Auth routes are public; everything else requires a valid Sanctum token
| (Authorization: Bearer <token>) obtained via POST /api/login.
|
*/

// Public
Route::post('/login', [AuthController::class, 'login']);

// Protected (requires Sanctum token)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (\Illuminate\Http\Request $request) {
        return response()->json($request->user());
    });

    Route::apiResource('students', StudentApiController::class)->names('api.students');
    Route::apiResource('teachers', TeacherApiController::class)->names('api.teachers');
    Route::apiResource('attendances', AttendanceApiController::class)->names('api.attendances');
    Route::apiResource('marks', MarkApiController::class)->names('api.marks');

});
