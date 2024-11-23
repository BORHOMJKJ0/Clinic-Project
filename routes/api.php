<?php

use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('api')->group(function () {
    Route::apiResource('patients', PatientController::class);
    Route::apiResource('doctors', DoctorController::class);
    Route::prefix('patients')->controller(PatientController::class)->group(function () {
        Route::get('/order/{column}/{direction}', 'OrderBy');
    });
    Route::prefix('doctors')->controller(DoctorController::class)->group(function () {
        Route::get('/order/{column}/{direction}', 'OrderBy');
    });
});
Route::prefix('users')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/forget', [UserController::class, 'forgetPassword']);
    Route::post('/resetPassword', [UserController::class, 'resetPassword']);

    Route::group(['middleware' => ['check_user_token:user-api']], function () {
        Route::post('/verify', [UserController::class, 'emailVerify']);
        Route::post('/resendVerificationCode', [UserController::class, 'resendVerificationCode']);
        Route::group(['middleware' => ['check_verification']], function () {
            Route::post('/logout', [UserController::class, 'logout']);
            Route::get('/getProfile', [UserController::class, 'getProfile']);
            Route::post('/updateProfile', [UserController::class, 'updateProfile']);
            Route::delete('/deleteAccount', [UserController::class, 'deleteAccount']);
        });
    });
});
