@php
    $user   = auth()->user();
    // normalize to lowercase and handle missing relationship safely
    $role   = $user && $user->role ? strtolower($user->role->name) : null;
    $layout = $layout ?? 'sidebar';  // 'sidebar' or 'top'
@endphp

@php
    if (! function_exists('nav_active')) {
        function nav_active($pattern) {
            return request()->routeIs($pattern) ? 'active' : '';
        }
    }
@endphp

@if($layout === 'sidebar')
    {{-- =============== SIDEBAR (dashboard layout) =============== --}}

    {{-- Everyone logged in gets Home --}}
    @if(Route::has('home'))
        <a href="{{ route('home') }}" class="{{ nav_active('home') }}">
            Home
        </a>
    @endif

    {{-- Patient home: patients only --}}
    @if($role === 'patient' && Route::has('dashboard'))
        <a href="{{ route('dashboard') }}" class="{{ nav_active('dashboard') }}">
            Patient Home
        </a>
    @endif

    {{-- Family member home: family only (use family.dashboard) --}}
    @if($role === 'family' && Route::has('family.dashboard'))
        <a href="{{ route('family.dashboard') }}" class="{{ nav_active('family.dashboard') }}">
            Family Home
        </a>
    @endif

    {{-- Caregiver home: caregivers only (use caregiver.dashboard) --}}
    @if($role === 'caregiver' && Route::has('caregiver.dashboard'))
        <a href="{{ route('caregiver.dashboard') }}" class="{{ nav_active('caregiver.dashboard') }}">
            Caregiver Home
        </a>
    @endif

    {{-- Doctor home: doctors only --}}
    @if($role === 'doctor' && Route::has('doctorHome'))
        <a href="{{ route('doctorHome') }}" class="{{ nav_active('doctorHome') }}">
            Doctor Home
        </a>
    @endif

    {{-- Patients list: Admin, Supervisor, Doctor, Caregiver --}}
    @if(in_array($role, ['admin','supervisor','doctor','caregiver']) && Route::has('patients'))
        <a href="{{ route('patients') }}" class="{{ nav_active('patients') }}">
            Patients
        </a>
    @endif

    {{-- Employees page: Admin & Supervisor --}}
    @if(in_array($role, ['admin','supervisor']) && Route::has('employees'))
        <a href="{{ route('employees') }}" class="{{ nav_active('employees') }}">
            Employees
        </a>
    @endif

    {{-- Registration Approval: Admin & Supervisor --}}
    @if(in_array($role, ['admin','supervisor']) && Route::has('registration.approval'))
        <a href="{{ route('registration.approval') }}" class="{{ nav_active('registration.approval') }}">
            Registration Approval
        </a>
    @endif

    {{-- Doctor Appointments (creation / management): Admin & Supervisor --}}
    @if(in_array($role, ['admin','supervisor']) && Route::has('doctor.appointments'))
        <a href="{{ route('doctor.appointments') }}" class="{{ nav_active('doctor.appointments') }}">
            Doctor Appointments
        </a>
    @endif

    {{-- Roster (view only): everyone logged in --}}
    @if(Route::has('roster.dashboard'))
        <a href="{{ route('roster.dashboard') }}" class="{{ nav_active('roster.dashboard') }}">
            Roster
        </a>
    @endif

    {{-- New Roster: Admin & Supervisor --}}
    @if(in_array($role, ['admin','supervisor']) && Route::has('roster.new'))
        <a href="{{ route('roster.new') }}" class="{{ nav_active('roster.new') }}">
            New Roster
        </a>
    @endif

    {{-- Admin Report: Admin & Supervisor (only if route exists) --}}
    @if(in_array($role, ['admin','supervisor']) && Route::has('admin.report'))
        <a href="{{ route('admin.report') }}" class="{{ nav_active('admin.report') }}">
            Admin Report
        </a>
    @endif

    {{-- Payments: Admin only --}}
    @if($role === 'admin' && Route::has('payments'))
        <a href="{{ route('payments') }}" class="{{ nav_active('payments') }}">
            Payments
        </a>
    @endif

    {{-- (Optional) Roles management: Admin only --}}
    @if($role === 'admin' && Route::has('roles.index'))
        <a href="{{ route('roles.index') }}" class="{{ nav_active('roles.index') }}">
            Roles
        </a>
    @endif

    {{-- Logout (everyone) --}}
    @if(Route::has('logout'))
        <form action="{{ route('logout') }}" method="POST" style="margin-top:10px;">
            @csrf
            <button type="submit"
                    style="width:100%; padding:8px; border-radius:6px; border:0; cursor:pointer; background:#e06666; color:#fff;">
                Logout
            </button>
        </form>
    @endif

@else
    {{-- =============== TOP NAV (home page) =============== --}}
    {{-- These are wrapped in <ul class="nav-links"> in your home.blade --}}

    @if(Route::has('home'))
        <li><a href="{{ route('home') }}" class="{{ nav_active('home') }}">Home</a></li>
    @endif

    {{-- Patient home --}}
    @if($role === 'patient' && Route::has('dashboard'))
        <li><a href="{{ route('dashboard') }}" class="{{ nav_active('dashboard') }}">Patient Home</a></li>
    @endif

    {{-- Family home --}}
    @if($role === 'family' && Route::has('family.dashboard'))
        <li><a href="{{ route('family.dashboard') }}" class="{{ nav_active('family.dashboard') }}">Family Home</a></li>
    @endif

    {{-- Caregiver home --}}
    @if($role === 'caregiver' && Route::has('caregiver.dashboard'))
        <li><a href="{{ route('caregiver.dashboard') }}" class="{{ nav_active('caregiver.dashboard') }}">Caregiver Home</a></li>
    @endif

    {{-- Doctor home --}}
    @if($role === 'doctor' && Route::has('doctorHome'))
        <li><a href="{{ route('doctorHome') }}" class="{{ nav_active('doctorHome') }}">Doctor Home</a></li>
    @endif

    {{-- Patients list --}}
    @if(in_array($role, ['admin','supervisor','doctor','caregiver']) && Route::has('patients'))
        <li><a href="{{ route('patients') }}" class="{{ nav_active('patients') }}">Patients</a></li>
    @endif

    {{-- Employees --}}
    @if(in_array($role, ['admin','supervisor']) && Route::has('employees'))
        <li><a href="{{ route('employees') }}" class="{{ nav_active('employees') }}">Employees</a></li>
    @endif

    {{-- Registration Approval --}}
    @if(in_array($role, ['admin','supervisor']) && Route::has('registration.approval'))
        <li><a href="{{ route('registration.approval') }}" class="{{ nav_active('registration.approval') }}">Registration Approval</a></li>
    @endif

    {{-- Doctor Appointments --}}
    @if(in_array($role, ['admin','supervisor']) && Route::has('doctor.appointments'))
        <li><a href="{{ route('doctor.appointments') }}" class="{{ nav_active('doctor.appointments') }}">Doctor Appointments</a></li>
    @endif

    {{-- Roster (view) --}}
    @if(Route::has('roster.dashboard'))
        <li><a href="{{ route('roster.dashboard') }}" class="{{ nav_active('roster.dashboard') }}">Roster</a></li>
    @endif

    {{-- New Roster --}}
    @if(in_array($role, ['admin','supervisor']) && Route::has('roster.new'))
        <li><a href="{{ route('roster.new') }}" class="{{ nav_active('roster.new') }}">New Roster</a></li>
    @endif

    {{-- Admin Report --}}
    @if(in_array($role, ['admin','supervisor']) && Route::has('admin.report'))
        <li><a href="{{ route('admin.report') }}" class="{{ nav_active('admin.report') }}">Admin Report</a></li>
    @endif

    {{-- Payments --}}
    @if($role === 'admin' && Route::has('payments'))
        <li><a href="{{ route('payments') }}" class="{{ nav_active('payments') }}">Payments</a></li>
    @endif

    {{-- Roles --}}
    @if($role === 'admin' && Route::has('roles.index'))
        <li><a href="{{ route('roles.index') }}" class="{{ nav_active('roles.index') }}">Roles</a></li>
    @endif

    {{-- Logout --}}
    @if(Route::has('logout'))
        <li>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </li>
    @endif
@endif
