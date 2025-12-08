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

    /* LEFT MAIN SCHEDULE AREA */
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
    }

    .input-group {
      display: flex;
      align-items: center;
      gap: 8px;
      flex: 1 1 0;
    }

    .input-label {
      font-size: 16px;
    }

    .top-input {
      flex: 1 1 0;
      height: 32px;
      border: 1px solid #6f7fa2;
      background: #c9d8ec;
    }

    /* column header bar */
    .column-header {
      display: flex;
      width: 100%;
      height: 60px;
      border: 1px solid #6f7fa2;
      background: #c9d8ec;
    }

    .column-header div {
      flex: 1 1 0;
      border-right: 1px solid #6f7fa2;
      text-align: center;
      padding-top: 19px;
    }

    .column-header div:last-child {
      border-right: none;
    }

    /* grid body with vertical lines and small checkboxes */
    .grid-body {
      flex: 1 1 auto;
      display: flex;
      border-left: 1px solid #6f7fa2;
      border-right: 1px solid #6f7fa2;
      padding-top: 40px;
    }

    .grid-column {
      flex: 1 1 0;
      border-right: 1px solid #6f7fa2;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    .grid-column:last-child {
      border-right: none;
    }

    .grid-checkbox {
      width: 18px;
      height: 18px;
      cursor: pointer;
      transform: translateY(-5px);
    }

    .patient-panel {
      flex: 1 1 0;
      background: #a7bddd;
      border: 1px solid #6f7fa2;
      padding: 25px 25px 35px;
      display: flex;
      flex-direction: column;
      gap: 25px;
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
    }
  </style>
</head>

<body>
  <div class="page">
    <h1 class="title">Patient Dashboard</h1>

    <div class="layout">
      <!-- LEFT MAIN AREA -->
      <div class="left-area">
        <!-- date & caregiver -->
        <div class="top-inputs">
          <div class="input-group">
            <span class="input-label">Date:</span>
            <input class="top-input" type="text" value="{{ now()->toDateString() }}" readonly>
          </div>
          <div class="input-group">
            <span class="input-label">caregiver:</span>
            <input class="top-input" type="text" value="{{ auth()->user()->name }}" readonly>
          </div>
        </div>

        <!-- column headers -->
        <div class="column-header">
          <div>Breakfast</div>
          <div>Lunch</div>
          <div>Dinner</div>
          <div>Medicine 1</div>
          <div>Medicine 2</div>
          <div>Medicine 3</div>
        </div>

        <!-- grid body + form -->
        <form action="{{ route('medicinecheck.saveToday') }}" method="POST">
          @csrf
          <div class="grid-body">
            {{-- Breakfast -> morning --}}
            <div class="grid-column">
              <input
                type="checkbox"
                name="morning"
                value="1"
                class="grid-checkbox"
                {{ optional($todayMedicineCheck)->morning ? 'checked' : '' }}>
            </div>

            {{-- Lunch -> afternoon --}}
            <div class="grid-column">
              <input
                type="checkbox"
                name="afternoon"
                value="1"
                class="grid-checkbox"
                {{ optional($todayMedicineCheck)->afternoon ? 'checked' : '' }}>
            </div>

            {{-- Dinner -> night --}}
            <div class="grid-column">
              <input
                type="checkbox"
                name="night"
                value="1"
                class="grid-checkbox"
                {{ optional($todayMedicineCheck)->night ? 'checked' : '' }}>
            </div>

            {{-- Medicine 1/2/3: visual only for now --}}
            <div class="grid-column">
              <input type="checkbox" class="grid-checkbox" disabled>
            </div>

            <div class="grid-column">
              <input type="checkbox" class="grid-checkbox" disabled>
            </div>

            <div class="grid-column">
              <input type="checkbox" class="grid-checkbox" disabled>
            </div>
          </div>

          <div style="margin-top:15px; text-align:right;">
            <button type="submit"
                    style="padding:6px 16px; border:1px solid #4f6ea2; background:#c9d8ec; cursor:pointer;">
              Save
            </button>
          </div>
        </form>
      </div>

      <!-- RIGHT PATIENT INFO PANEL -->
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
          <p>No medicine checklist recorded yet for today.</p>
        @else
          <ul class="med-status-list">
            <li>Morning: {{ $todayMedicineCheck->morning ? 'Taken' : 'Not taken' }}</li>
            <li>Afternoon: {{ $todayMedicineCheck->afternoon ? 'Taken' : 'Not taken' }}</li>
            <li>Night: {{ $todayMedicineCheck->night ? 'Taken' : 'Not taken' }}</li>
          </ul>
          <p style="font-size: 0.8rem; opacity: 0.8;">
            Date: {{ $todayMedicineCheck->date->format('Y-m-d') }}
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

            <table class="med-table" style="width:100%; margin-top:8px; font-size:0.9rem;">
                <thead>
                    <tr>
                        <th style="text-align:left;">Medicine</th>
                        <th style="text-align:left;">Dosage</th>
                        <th style="text-align:left;">Frequency</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($latestPrescription->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->dosage }}</td>
                        <td>{{ $item->frequency }}</td>
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
