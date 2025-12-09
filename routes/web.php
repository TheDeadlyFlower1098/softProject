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
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RolesController;

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
| Public Routes (no auth)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dataviewer', [DataViewerController::class, 'index'])
    ->name('dataviewer');


/*
|--------------------------------------------------------------------------
| Guest Routes (only when NOT logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    // Signup page (reuses welcome)
    Route::get('/signup', function () {
        return view('welcome');
    })->name('signup');

    // Handle login / signup
    Route::post('/login_attempt', [LoginAuthController::class, 'attempt'])
        ->name('login_attempt');

    Route::post('/signup', [RegistrationRequestController::class, 'store'])
        ->name('signup.store');

    // Example public-only page
    Route::get('/family-member', function () {
        return view('family_member');
    })->name('family.member');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes (any logged-in user)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Generic dashboards / views
    Route::get('/dashboard', fn () => view('patient_dashboard'))
        ->name('dashboard');

    Route::get('/home', fn () => view('home'))
        ->name('home');

    // Patient dashboard
    Route::get('/patient_dashboard', [PatientDashboardController::class, 'index'])
        ->name('patient.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Patients and additional information (Admin/Supervisor/Doctor/Caregiver)
    |--------------------------------------------------------------------------
    */

    // Patients list (points to patientsList.blade.php)
    Route::get('/patients', [PatientController::class, 'index'])
        ->middleware(['auth', 'role:Admin,Supervisor,Doctor,Caregiver'])
        ->name('patients');

    // Additional patient information page (Admin/Supervisor/Doctor/Caregiver)
    // (you already have this, just keep it)
    Route::middleware(['auth', 'role:Admin,Supervisor,Doctor,Caregiver'])
        ->group(function () {
            Route::get('/patients/{patient}/additional', [PatientController::class, 'additional'])
                ->name('patients.additional');
        });
        
    /*
    |--------------------------------------------------------------------------
    | Employees (Admin & Supervisor)
    |--------------------------------------------------------------------------
    */
    Route::get('/employees', [EmployeeController::class, 'index'])
        ->middleware('role:Admin,Supervisor')
        ->name('employees');

    Route::get('/employees/filter', [EmployeeController::class, 'filtered'])
        ->middleware('role:Admin,Supervisor');

    Route::put('/employees/{id}', [EmployeeController::class, 'update'])
        ->middleware('role:Admin,Supervisor');

    /*
    |--------------------------------------------------------------------------
    | Doctor appointments (created by Admin & Supervisor)
    |--------------------------------------------------------------------------
    */
    Route::get('/doctor-appointments', function () {
        return view('doctor_appointments');
    })->middleware('role:Admin,Supervisor')
      ->name('doctor.appointments');

    /*
    |--------------------------------------------------------------------------
    | Medicine check routes
    |--------------------------------------------------------------------------
    */
    Route::post(
        '/patient_dashboard/medicine-check',
        [MedicineCheckController::class, 'saveSingle']
    )->name('medicinecheck.saveSingle');

    // Caregiver dashboard + save
    Route::get('/caregiver', [MedicineCheckController::class, 'dashboard'])
        ->name('caregiver.dashboard');

    Route::post('/caregiver/save-today',
        [MedicineCheckController::class, 'saveMultiple'])
        ->name('caregiver.saveToday');

    // Roster routes (controller itself can further restrict create/store)
    Route::get('/roster', [RosterController::class, 'dashboard'])
        ->name('roster.dashboard');

    Route::get('/roster/new', [RosterController::class, 'create'])
        ->name('roster.new');

    Route::post('/roster', [RosterController::class, 'store'])
        ->name('roster.store');

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
| Doctor Routes (Doctor role only)
|--------------------------------------------------------------------------
*/

// Doctor home route
Route::get('/doctorHome', [DoctorHomeController::class, 'index'])
    ->middleware(['auth', 'role:Doctor'])
    ->name('doctorHome');


/*
|--------------------------------------------------------------------------
| Doctor Appointment Creation (Admin + Supervisor)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {

    // Appointment creation form
    Route::get(
        '/doctor-appointments',
        [DoctorHomeController::class, 'createAppointment']
    )->name('doctor.appointments');

    // Store appointment
    Route::post(
        '/doctor-appointments',
        [DoctorHomeController::class, 'storeAppointment']
    )->name('doctor.appointments.store');

    // AJAX patient lookup
    Route::get(
        '/api/patients/{patient}',
        [DoctorHomeController::class, 'lookupPatient']
    )->name('patients.lookup');
});


/*
|--------------------------------------------------------------------------
| Family Dashboard (Family role only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Family'])->group(function () {
    Route::get('/family-dashboard', [FamilyDashboardController::class, 'index'])
        ->name('family.dashboard');
});


/*
|--------------------------------------------------------------------------
| Admin-only Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->group(function () {

    // Payments
    Route::get('/payments', [PaymentController::class, 'adminIndex'])
        ->name('payments');

    Route::post('/payments/{patient}/pay',
        [PaymentController::class, 'recordPayment'])
        ->name('payments.pay');

    // Roles management
    Route::get('/roles', [RolesController::class, 'index'])
        ->name('roles.index');

    Route::post('/roles', [RolesController::class, 'store'])
        ->name('roles.store');

    Route::patch(
        '/roles/users/{user}',
        [RolesController::class, 'updateUserRole']
    )->name('roles.users.update');
});


/*
|--------------------------------------------------------------------------
| Admin / Supervisor Routes (Registration Approval, etc.)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {

    // Admin registrations list (you can keep this or drop it; route name is fine)
    Route::get('/admin/registrations',
        [RegistrationApprovalController::class, 'index'])
        ->name('admin.registrations');

    // ✅ This is now THE registration approval page
    Route::get('/admin/registration-approval',
        [RegistrationApprovalController::class, 'index'])
        ->name('registration.approval');

    Route::post('/admin/registration-approval/{id}/approve',
        [RegistrationApprovalController::class, 'approve'])
        ->name('registration.approve');

    Route::post('/admin/registration-approval/{id}/deny',
        [RegistrationApprovalController::class, 'deny'])
        ->name('registration.deny');
});


/*
|--------------------------------------------------------------------------
| Auth scaffolding routes (Laravel default)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
