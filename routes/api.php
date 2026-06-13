<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\ExerciseRutineController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\BodyMeasurementController;
use App\Http\Controllers\Api\AttendanceController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'show']);

    Route::post('/change-password', [\App\Http\Controllers\PasswordController::class, 'changePassword']);

    // Trainers Routes
    Route::apiResource('trainer', TrainerController::class);
    Route::get('/trainer/{id}/clients', [TrainerController::class, 'showClients']);

    // Drivers Routes
    Route::apiResource('driver', DriverController::class);
    Route::get('/driver/{id}/clients', [DriverController::class, 'showClients']);

    //Exercises Rurines Routes
    Route::apiResource('exercise_rutine', ExerciseRutineController::class);
    Route::get('/exercise_rutine/{id}/clients', [ExerciseRutineController::class, 'showClients']);

    //Clients Routes
    Route::apiResource('client', ClientController::class);
    Route::get('/client/{id}/payments', [ClientController::class, 'showPayments']);
    Route::get('/client/{id}/body_measurements', [ClientController::class, 'showBodyMeasurements']);
    Route::get('/client/{id}/attendances', [ClientController::class, 'showAttendances']);

    //Payments Routes
    Route::apiResource('payment', PaymentController::class);

    //Body Measurements Routes
    Route::apiResource('body_measurement', BodyMeasurementController::class);

    //Attendances Routes
    Route::apiResource('attendance', AttendanceController::class);
});
