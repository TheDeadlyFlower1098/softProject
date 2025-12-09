@extends('layouts.app')

@section('title', 'Patient Additional Information')

@section('content')
  <style>
    .patient-info-box {
      width: 90%;
      margin: 40px auto;
      background: rgb(182, 215, 168); /* light green like your theme */
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0,0,0,0.12);
      color: #000;
    }

    .patient-info-box h2 {
      margin-top: 0;
      font-size: 32px;
      color: rgb(60, 120, 216);
      text-align: center;
    }

    .patient-info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
      font-size: 20px;
    }

    .patient-info-grid div strong {
      font-weight: 700;
    }
  </style>

  <section class="patient-info-box">
    <h2>Patient Information</h2>

    @if(isset($patient))
      <div class="patient-info-grid">
        <div><strong>Patient ID:</strong> {{ $patient->id }}</div>
        <div><strong>Name:</strong> {{ $patient->name ?? ($patient->patient_name ?? 'N/A') }}</div>
        <div><strong>Group:</strong> {{ $patient->group ?? 'N/A' }}</div>
        <div><strong>Admission Date:</strong> {{ $patient->admission_date ?? 'N/A' }}</div>
      </div>
    @else
      <p style="text-align:center; font-size:18px; margin-top:10px;">
        No patient selected. Please choose a patient from the Patients page.
      </p>
    @endif
  </section>
@endsection
