{{-- patientAdditional.blade.php --}}
@extends('layouts.app')

@section('title', 'Additional Patient Information')

@section('content')
<style>
  .patient-info-box {
      width: 92%;
      margin: 20px auto 40px;
      background: #d9e9fb;
      border-radius: 12px;
      border: 2px solid #b9c6d8;
      padding: 24px 26px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.12);
      color: #0d1b2a;
  }

  .patient-info-box h2 {
      margin-top: 0;
      margin-bottom: 18px;
      font-size: 24px;
      font-weight: 800;
      color: #0d1b2a;
      text-align: left;
  }

  .patient-info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 12px 18px;
      font-size: 16px;
  }

  .patient-info-grid div strong {
      font-weight: 700;
  }

  .patient-info-empty {
      font-size: 16px;
      color: #444;
  }
</style>

<section class="patient-info-box">
    @php
        // Make it robust: fall back to the logged-in user's patient if the controller
        // forgot to pass a $patient variable.
        $p = isset($patient) ? $patient : (auth()->user()->patient ?? null);
    @endphp

    @if($p)
        <h2>Patient Information</h2>

        <div class="patient-info-grid">
            <div><strong>Patient ID:</strong> {{ $p->id }}</div>
            <div><strong>Name:</strong> {{ $p->patient_name ?? $p->name }}</div>

            {{-- adjust "group" field name to whatever your patients table uses --}}
            <div><strong>Group:</strong> {{ $p->group ?? $p->group_id ?? 'N/A' }}</div>

            <div>
                <strong>Admission Date:</strong>
                {{ optional($p->admission_date)->format('Y-m-d') ?? $p->admission_date ?? 'N/A' }}
            </div>
        </div>
    @else
        <h2>Patient Information</h2>
        <p class="patient-info-empty">
            No patient record was provided to this page. Please navigate here via the Patients list
            or ask your instructor to wire the route to pass a Patient model.
        </p>
    @endif
</section>
@endsection
