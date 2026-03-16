@extends('layouts.app')

@section('title', 'Patient Home')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Home</title>

  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      background: #ffffff;
    }

    .page {
      width: 1000px;
      min-height: 560px;
      background: #d6e6f7;
      padding: 25px 30px;
      margin-top: 20px;
      color: #000 !important;
    }

    .title {
      font-size: 48px;
      margin-bottom: 20px;
    }

    .layout {
      display: flex;
      gap: 25px;
      align-items: stretch;
    }

    /* LEFT MAIN AREA */
    .left-area {
      flex: 3 1 0;
      background: #bfddb1; /* light green */
      border: 1px solid #8aa172;
      padding: 25px 25px 35px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    /* date / caregiver row */
    .top-inputs {
      display: flex;
      gap: 20px;
      margin-bottom: 10px;
    }

    .input-group {
      display: flex;
      align-items: center;
      gap: 8px;
      flex: 1 1 0;
    }

    .input-label {
      font-size: 16px;
      font-weight: 600;
    }

    .top-input {
      flex: 1 1 0;
      height: 32px;
      border: 1px solid #6f7fa2;
      background: #c9d8ec;
      padding: 0 8px;
      color: #000;
    }

    .caregiver-text {
      flex: 1 1 0;
      min-height: 32px;
      border: 1px solid #6f7fa2;
      background: #c9d8ec;
      display: flex;
      align-items: center;
      padding: 0 8px;
      color: #000;
    }

    /* Activity card / table area (inspired by admin report) */
    .activity-card{
      background:#b9d7a9;
      padding:22px 20px;
      border-radius:12px;
      position:relative;
      box-shadow:0 3px 8px rgba(0,0,0,0.12);
      margin-top:10px;
    }

    .tag{
      background:#8fbd75;
      padding:8px 18px;
      border-radius:8px;
      font-weight:700;
      font-size:15px;
      color:white;
      position:absolute;
      top:-16px;
      left:18px;
      border:1px solid #6ea65b;
      box-shadow:0 2px 6px rgba(0,0,0,0.15);
    }

    .activity-inner{
      margin-top:32px;
      background:white;
      border-radius:10px;
      padding:16px;
      border:1px solid #b3c2d1;
      box-shadow:0 3px 8px rgba(0,0,0,0.06);
      color:#000;
    }

    .activity-title{
      font-weight:700;
      margin-bottom:8px;
      font-size:18px;
    }

    .activity-date{
      font-size:14px;
      color:#444;
      margin-bottom:12px;
    }

    table.activity-table{
      width:100%;
      border-collapse:collapse;
      font-size:15px;
      text-align:center;
    }

    table.activity-table th,
    table.activity-table td{
      padding:8px 6px;
      border:1px solid #c5d0e0;
      color:#000;
    }

    table.activity-table th{
      background:#cfe1f6;
      font-weight:700;
    }

    .badge{
      display:inline-block;
      padding:4px 10px;
      border-radius:8px;
      font-weight:700;
      font-size:13px;
      box-shadow:0 2px 4px rgba(0,0,0,0.1);
    }

    .badge-taken{
      background:#8fbd75;
      color:#fff;
    }

    .badge-missed{
      background:#e06f6f;
      color:#fff;
    }

    .badge-unknown{
      background:#ccc;
      color:#333;
    }

    .status-text{
      margin-top:10px;
      font-weight:600;
    }

    .status-complete{
      color:#2e7d32;
    }

    .status-none{
      color:#e06f6f;
    }

    .status-partial{
      color:#f0ad4e;
    }

    .patient-panel {
      flex: 1 1 0;
      background: #a7bddd;
      border: 1px solid #6f7fa2;
      padding: 25px 25px 35px;
      display: flex;
      flex-direction: column;
      gap: 25px;
      color:#000;
    }

    .patient-title {
      font-size: 32px;
      font-weight: bold;
      color: #ffeccc;
      line-height: 1.1;
    }

    .patient-title-wrapper {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .sun {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      border: 3px solid #4f6ea2;
      position: relative;
    }

    .sun::before,
    .sun::after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      width: 70px;
      height: 3px;
      background: #4f6ea2;
      transform: translate(-50%, -50%);
    }

    .sun::after {
      transform: translate(-50%, -50%) rotate(90deg);
    }

    .patient-input {
      width: 100%;
      height: 38px;
      border: 1px solid #4f6ea2;
      background: #dbe6f5;
      font-size: 16px;
      padding-left: 10px;
      color:#000;
    }
  </style>
</head>

<body>
  <div class="page">
    <h1 class="title">Patient Dashboard</h1>

    <div class="layout">
      <!-- LEFT MAIN AREA -->
      <div class="left-area">

        {{-- Date filter + caregiver name --}}
        <form method="GET"
              action="{{ route('dashboard') }}"
              class="top-inputs">
          <div class="input-group">
            <span class="input-label">Date:</span>
            <input
              class="top-input"
              type="date"
              name="date"
              value="{{ $selectedDate }}"
              onchange="this.form.submit()"
            >
          </div>

          <div class="input-group">
            <span class="input-label">Caregiver:</span>
            <div class="caregiver-text">
              {{ $caregiverName ?? 'Not assigned for this date' }}
            </div>
          </div>
        </form>

        {{-- MEDICINE / MEAL TABLE --}}
        <section class="activity-card">
          <div class="tag">Medicine & Meals</div>

          <div class="activity-inner">
            @if(!$todayMedicineCheck)
              <div class="activity-title">
                No record found for this date.
              </div>
              <p>
                There is no medicine / meal checklist recorded for
                {{ \Illuminate\Support\Carbon::parse($selectedDate)->format('M d, Y') }}.
              </p>
            @else
              <div class="activity-title">
                Checklist for {{ $patient->patient_name }}
              </div>
              <div class="activity-date">
                Date: {{ \Illuminate\Support\Carbon::parse($todayMedicineCheck->date)->format('M d, Y') }}
              </div>

              @php
                $slots = [
                  'morning'   => 'Morning Medicine',
                  'afternoon' => 'Afternoon Medicine',
                  'night'     => 'Night Medicine',
                  'breakfast' => 'Breakfast',
                  'lunch'     => 'Lunch',
                  'dinner'    => 'Dinner',
                ];

                $takenCount = 0;
                $missedCount = 0;
                $totalSlots = 0;
              @endphp

              <table class="activity-table">
                <thead>
                  <tr>
                    <th>Check</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($slots as $key => $label)
                    @php
                      $value = optional($todayMedicineCheck)->{$key};
                      $totalSlots++;

                      if ($value === 'taken') $takenCount++;
                      if ($value === 'missed') $missedCount++;

                      $class = $value === 'taken'
                        ? 'badge-taken'
                        : ($value === 'missed'
                            ? 'badge-missed'
                            : 'badge-unknown');

                      $text = $value ?? 'unknown';
                    @endphp
                    <tr>
                      <td>{{ $label }}</td>
                      <td>
                        <span class="badge {{ $class }}">
                          {{ ucfirst($text) }}
                        </span>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>

              @php
                $statusClass = 'status-partial';
                $statusText  = 'Some checks completed';

                if ($takenCount === 0 && $missedCount === 0) {
                    $statusClass = 'status-partial';
                    $statusText  = 'No clear data recorded';
                } elseif ($takenCount === $totalSlots) {
                    $statusClass = 'status-complete';
                    $statusText  = 'All checks completed ✅';
                } elseif ($missedCount === $totalSlots) {
                    $statusClass = 'status-none';
                    $statusText  = 'All checks missed';
                }
              @endphp

              <p class="status-text {{ $statusClass }}">
                {{ $statusText }}
              </p>
            @endif
          </div>
        </section>
      </div>

      <!-- RIGHT PATIENT INFO PANEL (unchanged) -->
      <div class="patient-panel">
        <div class="patient-title-wrapper">
          <div class="patient-title">
            Patient<br>info
          </div>
          <div class="sun"></div>
        </div>

        {{-- PATIENT NAME --}}
        <label>Patient Name</label>
        <input class="patient-input"
               value="{{ $patient->patient_name }}"
               readonly />

        {{-- PATIENT IDENTIFIER / ID --}}
        <label>Patient ID</label>
        <input class="patient-input"
               value="{{ $patient->patient_identifier }}"
               readonly />

        {{-- TODAY'S APPOINTMENT --}}
        <h3>Today's Appointment</h3>

        @if($todayAppointments->isEmpty())
          <p>No appointment scheduled for today.</p>
        @else
          @foreach($todayAppointments as $appt)
            <p>
              {{ $appt->date->format('g:i A') }}
              with Dr. {{ $appt->doctor->name ?? 'Unknown' }}
              @if(!empty($appt->status))
                ({{ ucfirst($appt->status) }})
              @endif
            </p>
          @endforeach
        @endif

        {{-- TODAY'S MEDICINE CHECK --}}
        <h3>Medicine (today)</h3>

        @if(!$todayMedicineCheck)
          <p>No medicine checklist recorded yet for this date.</p>
        @else
          <ul class="med-status-list">
            <li>Morning:   {{ $todayMedicineCheck->morning   ?? 'unknown' }}</li>
            <li>Afternoon: {{ $todayMedicineCheck->afternoon ?? 'unknown' }}</li>
            <li>Night:     {{ $todayMedicineCheck->night     ?? 'unknown' }}</li>
          </ul>
          <p style="font-size: 0.8rem; opacity: 0.8; margin-top:6px;">
            Date: {{ \Illuminate\Support\Carbon::parse($todayMedicineCheck->date)->format('Y-m-d') }}
          </p>
        @endif

        {{-- CURRENT PRESCRIPTIONS --}}
        <h3>Current Prescriptions</h3>

        @if(!$latestPrescription || $latestPrescription->items->isEmpty())
            <p>No prescriptions recorded.</p>
        @else
            <p style="font-size:0.9rem; opacity:0.85;">
                Prescribed by Dr. {{ $latestPrescription->doctor->name ?? 'Unknown' }}
                on {{ $latestPrescription->created_at->format('Y-m-d') }}
            </p>

            <table class="med-table" style="width:100%; margin-top:8px; font-size:0.9rem; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left; border:1px solid #4f6ea2; padding:4px;">Medicine</th>
                        <th style="text-align:left; border:1px solid #4f6ea2; padding:4px;">Dosage</th>
                        <th style="text-align:left; border:1px solid #4f6ea2; padding:4px;">Frequency</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($latestPrescription->items as $item)
                    <tr>
                        <td style="border:1px solid #4f6ea2; padding:4px;">{{ $item->name }}</td>
                        <td style="border:1px solid #4f6ea2; padding:4px;">{{ $item->dosage }}</td>
                        <td style="border:1px solid #4f6ea2; padding:4px;">{{ $item->frequency }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
      </div>
    </div>
  </div>
</body>
</html>
@endsection
