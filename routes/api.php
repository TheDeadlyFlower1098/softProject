<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\MedicineCheckController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;

// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('patients', PatientController::class);

    // REMOVE employees from here!
    // Route::apiResource('employees', EmployeeController::class);

    Route::apiResource('rosters', RosterController::class);
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('prescriptions', PrescriptionController::class);
    Route::apiResource('medicine-checks', MedicineCheckController::class)->only(['index','store','show','update']);
    
    Route::apiResource('payments', PaymentController::class)->only(['index','store']);
    Route::get('payments/calc/{patient}', [PaymentController::class,'calculateForPatient']);

    Route::get('reports/missed-activities', [ReportController::class,'missedActivities']);
});
