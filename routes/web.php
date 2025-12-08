<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegistrationApprovalController;
use App\Http\Controllers\RegistrationRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\MedicineCheckController;
use App\Http\Controllers\FamilyDashboardController;
use App\Http\Controllers\DoctorHomeController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\DataViewerController;
use App\Http\Controllers\DoctorController;

/*
|--------------------------------------------------------------------------
| Prescription / Appointment Routes
|--------------------------------------------------------------------------
*/

Route::post(
    '/appointments/{appointment}/prescriptions',
    [PrescriptionController::class, 'store']
)->name('appointments.prescriptions.store');

Route::get(
    '/appointments/{id}/details',
    [DoctorHomeController::class, 'appointmentDetails']
)->name('appointment.details');


/*
|--------------------------------------------------------------------------
| Medicine Check Routes (Requires Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Patient dashboard
    Route::get('/patient_dashboard', [PatientDashboardController::class, 'index'])
        ->name('patient.dashboard');

    // Patient saves single record
    Route::post('/patient_dashboard/medicine-check',
        [MedicineCheckController::class, 'saveSingle'])
        ->name('medicinecheck.saveSingle');

    // Caregiver dashboard
    Route::get('/caregiver',
        [MedicineCheckController::class, 'dashboard'])
        ->name('caregiver.dashboard');

    // Caregiver saves multiple patients
    Route::post('/caregiver/save-today',
        [MedicineCheckController::class, 'saveMultiple'])
        ->name('caregiver.saveToday');
});


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dataviewer', [DataViewerController::class, 'index']);

Route::get('/admin/registration-approval',
    [RegistrationRequestController::class, 'index'])
    ->name('registration.approval');

Route::post('/admin/registration-approval/{id}/approve',
    [RegistrationRequestController::class, 'approve'])
    ->name('registration.approve');

Route::post('/admin/registration-approval/{id}/deny',
    [RegistrationRequestController::class, 'deny'])
    ->name('registration.deny');

Route::get('/doctorHome',
    [DoctorHomeController::class, 'index'])
    ->name('doctorHome');


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', fn() => view('login'))
        ->name('login');

    Route::get('/signup', fn() => view('welcome'))
        ->name('signup');

    Route::post('/login_attempt',
        [LoginAuthController::class, 'attempt'])
        ->name('login_attempt');

    Route::post('/signup',
        [RegistrationRequestController::class, 'store'])
        ->name('signup.store');

    Route::get('/family-member', fn() => view('family_member'))
        ->name('family.member');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', fn() => view('patient_dashboard'))
        ->name('dashboard');

    Route::get('/home', fn() => view('home'))
        ->name('home');

    Route::get('/employees', fn() => view('employees'))
        ->name('employees');

    Route::get('/doctor-appointments', fn() => view('doctor_appointments'))
        ->name('doctor.appointments');

    Route::get('/new-roster', fn() => view('new_roster'))
        ->name('new.roster');

    Route::get('/supervisor-roster', fn() => view('supervisor_roster'))
        ->name('supervisor.roster');

    // Family dashboard (role restricted)
    Route::middleware(['role:Family'])->group(function () {
        Route::get('/family-dashboard',
            [FamilyDashboardController::class, 'index'])
            ->name('family.dashboard');
    });

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});


/*
|--------------------------------------------------------------------------
| Admin / Supervisor Routes
|--------------------------------------------------------------------------
*/
Route::middleware('role:Admin,Supervisor')->group(function () {
    Route::get('/admin/registrations',
        [RegistrationApprovalController::class, 'index'])
        ->name('admin.registrations');
});


/*
|--------------------------------------------------------------------------
| Laravel Breeze / Fortify / Auth Scaffolding
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
