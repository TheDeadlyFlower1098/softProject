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

Route::get('/appointments/{id}/details',
    [DoctorHomeController::class, 'appointmentDetails'])
    ->name('appointment.details');
// view a single appointment's details
Route::get(
    '/appointments/{id}/details',
    [DoctorHomeController::class, 'appointmentDetails']
)->name('appointment.details');

// Doctor home route (doctor only)
Route::get('/doctorHome', [DoctorHomeController::class, 'index'])
    ->middleware(['auth', 'role:Doctor'])
    ->name('doctorHome');


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

Route::get('/dataviewer', [\App\Http\Controllers\DataViewerController::class, 'index']);

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
// Data viewer
Route::get('/dataviewer', [DataViewerController::class, 'index']);


/*
|--------------------------------------------------------------------------
| Guest routes (not logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/registration-approval', function() {
        return view('registration_approval');
    });

    Route::get('/registration-approval', [RegistrationApprovalController::class, 'index'])
        ->name('registration.approval');

    // approve a specific request
    Route::post('/registration-approval/{id}/approve', [RegistrationApprovalController::class, 'approve'])
        ->name('registration.approve');

    // deny a specific request
    Route::post('/registration-approval/{id}/deny', [RegistrationApprovalController::class, 'deny'])
        ->name('registration.deny');

    // Login page
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    // Signup page (uses welcome with signup form)
    Route::get('/signup', function () {
        return view('welcome');
    })->name('signup');

    // Handle login / signup
    Route::post('/login_attempt', [LoginAuthController::class, 'attempt'])
        ->name('login_attempt');

    Route::post('/signup',
        [RegistrationRequestController::class, 'store'])
        ->name('signup.store');

    // (optional) public-only page example
    Route::get('/family-member', function () {
        return view('family_member');
    })->name('family.member');
});


/*
|--------------------------------------------------------------------------
    

    Route::middleware(['auth', 'role:Admin'])->group(function () {
        // List all patients + payments
        Route::get('/payments', [PaymentController::class, 'adminIndex'])
            ->name('payments');

        // Record a payment for a specific patient
        Route::post('/payments/{patient}/pay', [PaymentController::class, 'recordPayment'])
            ->name('payments.pay');
    });



    /*
    |--------------------------------------------------------------------------
    | Roster routes
    |--------------------------------------------------------------------------
    */

    // Everyone logged in can view the roster dashboard
    Route::get('/roster', [RosterController::class, 'dashboard'])
        ->name('roster.dashboard');

    // New roster form (controller will check Admin / Supervisor role)
    Route::get('/roster/new', [RosterController::class, 'create'])
        ->name('roster.new');

    // Save roster (create / update)
    Route::post('/roster', [RosterController::class, 'store'])
        ->name('roster.store');

    /*
    |--------------------------------------------------------------------------
    | Roles page (Admin only)
    |--------------------------------------------------------------------------
    */
    Route::get('/roles', function () {
        return view('roles');
    })->middleware('role:Admin')
      ->name('roles.index');

    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RolesController::class, 'store'])->name('roles.store');
    Route::patch('/roles/users/{user}', [RolesController::class, 'updateUserRole'])
        ->name('roles.users.update');

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});


/*
|--------------------------------------------------------------------------
| Admin / Supervisor routes for Registration Approval
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {
    Route::get('/admin/registrations', [RegistrationApprovalController::class, 'index'])
        ->name('admin.registrations');
});

/*
|--------------------------------------------------------------------------
| Auth scaffolding routes (Laravel default)
|--------------------------------------------------------------------------
*/
Route::middleware('role:Admin,Supervisor')->group(function () {
    Route::get('/admin/registrations',
        [RegistrationApprovalController::class, 'index'])
        ->name('admin.registrations');
});


require __DIR__ . '/auth.php';
