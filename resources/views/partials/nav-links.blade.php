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
    <a href="{{ route('home') }}" class="{{ nav_active('home') }}">
        Home
    </a>

    {{-- Patient home: patients only --}}
    @if($role === 'patient')
        <a href="{{ route('dashboard') }}" class="{{ nav_active('dashboard') }}">
            Patient Home
        </a>
    @endif

    {{-- Family member home: family only --}}
    @if($role === 'family')
        <a href="{{ route('family.home') }}" class="{{ nav_active('family.home') }}">
            Family Home
        </a>
    @endif

    {{-- Caregiver home: caregivers only --}}
    @if($role === 'caregiver')
        <a href="{{ route('caregiver.home') }}" class="{{ nav_active('caregiver.home') }}">
            Caregiver Home
        </a>
    @endif

    {{-- Doctor home: doctors only --}}
    @if($role === 'doctor')
        <a href="{{ route('doctorHome') }}" class="{{ nav_active('doctorHome') }}">
            Doctor Home
        </a>
    @endif

    {{-- Patients list: Admin, Supervisor, Doctor, Caregiver --}}
    @if(in_array($role, ['admin','supervisor','doctor','caregiver']))
        <a href="{{ route('patients') }}" class="{{ nav_active('patients') }}">
            Patients
        </a>
    @endif

    {{-- Employees page: Admin & Supervisor --}}
    @if(in_array($role, ['admin','supervisor']))
        <a href="{{ route('employees') }}" class="{{ nav_active('employees') }}">
            Employees
        </a>
    @endif

    {{-- Registration Approval: Admin & Supervisor --}}
    @if(in_array($role, ['admin','supervisor']))
        <a href="{{ route('registration.approval') }}" class="{{ nav_active('registration.approval') }}">
            Registration Approval
        </a>
    @endif

    {{-- Doctor Appointments (creation / management): Admin & Supervisor --}}
    @if(in_array($role, ['admin','supervisor']))
        <a href="{{ route('doctor.appointments') }}" class="{{ nav_active('doctor.appointments') }}">
            Doctor Appointments
        </a>
    @endif

    {{-- Roster (view only): everyone logged in --}}
    <a href="{{ route('roster.dashboard') }}" class="{{ nav_active('roster.dashboard') }}">
        Roster
    </a>

    {{-- New Roster: Admin & Supervisor --}}
    @if(in_array($role, ['admin','supervisor']))
        <a href="{{ route('roster.new') }}" class="{{ nav_active('roster.new') }}">
            New Roster
        </a>
    @endif

    {{-- Admin Report: Admin & Supervisor --}}
    @if(in_array($role, ['admin','supervisor']))
        <a href="{{ route('admin.report') }}" class="{{ nav_active('admin.report') }}">
            Admin Report
        </a>
    @endif

    {{-- Payments: Admin only --}}
    @if($role === 'admin')
        <a href="{{ route('payments') }}" class="{{ nav_active('payments') }}">
            Payments
        </a>
    @endif

    {{-- Roles management: Admin only (if you made that page) --}}
    {{-- @if($role === 'admin')
        <a href="{{ route('roles.index') }}" class="{{ nav_active('roles.*') }}">
            Roles
        </a>
    @endif --}}

    {{-- Logout (everyone) --}}
    <form action="{{ route('logout') }}" method="POST" style="margin-top:10px;">
        @csrf
        <button type="submit"
                style="width:100%; padding:8px; border-radius:6px; border:0; cursor:pointer; background:#e06666; color:#fff;">
            Logout
        </button>
    </form>

@else
    {{-- =============== TOP NAV (home page) =============== --}}
    {{-- These are wrapped in <ul class="nav-links"> in your home.blade --}}

    <li><a href="{{ route('home') }}" class="{{ nav_active('home') }}">Home</a></li>

    {{-- Patient home --}}
    @if($role === 'patient')
        <li><a href="{{ route('dashboard') }}" class="{{ nav_active('dashboard') }}">Patient Home</a></li>
    @endif

    {{-- Family home --}}
    @if($role === 'family')
        <li><a href="{{ route('family.home') }}" class="{{ nav_active('family.home') }}">Family Home</a></li>
    @endif

    {{-- Caregiver home --}}
    @if($role === 'caregiver')
        <li><a href="{{ route('caregiver.home') }}" class="{{ nav_active('caregiver.home') }}">Caregiver Home</a></li>
    @endif

    {{-- Doctor home --}}
    @if($role === 'doctor')
        <li><a href="{{ route('doctorHome') }}" class="{{ nav_active('doctorHome') }}">Doctor Home</a></li>
    @endif

    {{-- Patients list --}}
    @if(in_array($role, ['admin','supervisor','doctor','caregiver']))
        <li><a href="{{ route('patients') }}" class="{{ nav_active('patients') }}">Patients</a></li>
    @endif

    {{-- Employees --}}
    @if(in_array($role, ['admin','supervisor']))
        <li><a href="{{ route('employees') }}" class="{{ nav_active('employees') }}">Employees</a></li>
    @endif

    {{-- Registration Approval --}}
    @if(in_array($role, ['admin','supervisor']))
        <li><a href="{{ route('registration.approval') }}" class="{{ nav_active('registration.approval') }}">Registration Approval</a></li>
    @endif

    {{-- Doctor Appointments --}}
    @if(in_array($role, ['admin','supervisor']))
        <li><a href="{{ route('doctor.appointments') }}" class="{{ nav_active('doctor.appointments') }}">Doctor Appointments</a></li>
    @endif

    {{-- Roster (view) --}}
    <li><a href="{{ route('roster.dashboard') }}" class="{{ nav_active('roster.dashboard') }}">Roster</a></li>

    {{-- New Roster --}}
    @if(in_array($role, ['admin','supervisor']))
        <li><a href="{{ route('roster.new') }}" class="{{ nav_active('roster.new') }}">New Roster</a></li>
    @endif

    {{-- Admin Report --}}
    @if(in_array($role, ['admin','supervisor']))
        <li><a href="{{ route('admin.report') }}" class="{{ nav_active('admin.report') }}">Admin Report</a></li>
    @endif

    {{-- Payments --}}
    @if($role === 'admin')
        <li><a href="{{ route('payments') }}" class="{{ nav_active('payments') }}">Payments</a></li>
    @endif

    {{-- Roles --}}
    @if($role === 'admin')
        <li><a href="{{ route('roles.index') }}" class="{{ nav_active('roles.*') }}">Roles</a></li>
    @endif

    {{-- Logout --}}
    <li>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </li>
@endif
