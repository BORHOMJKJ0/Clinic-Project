<?php

use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Patient\PatientController;
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
Route::apiResource('patients', PatientController::class);
Route::apiResource('doctors', DoctorController::class);
Route::prefix('patients')->controller(PatientController::class)->group(function () {
    Route::get('/order/{column}/{direction}', 'OrderBy');
});
Route::prefix('doctors')->controller(DoctorController::class)->group(function () {
    Route::get('/order/{column}/{direction}', 'OrderBy');
});
