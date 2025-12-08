@extends('layouts.app')

@section('title', 'Payments')

@section('content')<!DOCTYPE html>


<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Payment</title>

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
      color: black;
    }

    .page {
      width: 950px;
      min-height: 550px;
      background: #d6e6f7; /* light blue */
      padding: 30px 35px;
      margin-top: 20px;
    }

    .title {
      font-size: 48px;
      margin-bottom: 25px;
      color: black;
    }

    .content-row {
      display: flex;
      gap: 25px;
      align-items: stretch;
    }

    .left-panel {
      flex: 1 1 0;
      background: #bfddb1; /* light green */
      border: 1px solid #8aa172;
      padding: 40px 60px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    .right-panel {
      width: 220px;
      background: #a7bddd; /* darker blue */
      border: 1px solid #6f7fa2;
      padding: 20px;
      color: #000;
      font-size: 14px;
    }

    .patient-form {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 30px;
    }

    .patient-row {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .patient-label {
      background: #7da4e3;
      padding: 8px 18px;
      min-width: 110px;
      border: 1px solid #5c729c;
      text-align: center;
    }

    .patient-input {
      width: 200px;
      height: 30px;
      border: 1px solid #808f63;
      background: #d6e6b7;
    }

    .button-row {
      align-self: center;
      display: flex;
      gap: 35px;
      margin-top: 10px;
    }

    .btn {
      background: #9bbf61;
      border: 1px solid #6e8642;
      padding: 6px 28px;
      font-size: 16px;
      cursor: pointer;
    }

    .update-row {
      align-self: center;
      margin-top: 10px;
    }

    .summary-title {
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 10px;
    }

    .summary-total {
      font-size: 22px;
      margin-bottom: 12px;
    }

    .summary-list {
      list-style: none;
      padding-left: 0;
    }

    .summary-list li {
      margin-bottom: 6px;
    }

    .error {
      color: darkred;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="page">
    <h1 class="title">Payment</h1>

    <div class="content-row">
      <!-- Left green area -->
      <div class="left-panel">
        <form class="patient-form" action="{{ route('payments.calculate') }}" method="POST">
          @csrf

          @php
            $hasAutoPatient = isset($patient) && $patient;
          @endphp

          @unless($hasAutoPatient)
            @error('patient_id')
              <div class="error">{{ $message }}</div>
            @enderror
          @endunless

          <div class="patient-row">
            @if($hasAutoPatient)
              {{-- Family or Patient user: auto-resolved patient --}}
              <span class="patient-label">Patient</span>
              <input
                class="patient-input"
                type="text"
                value="{{ $patient->patient_name }} (ID: {{ $patient->id }})"
                readonly
              />
            @else
              {{-- Admin / other roles: manual ID entry --}}
              <span class="patient-label">Patient ID</span>
              <input
                class="patient-input"
                type="text"
                name="patient_id"
                value="{{ old('patient_id') }}"
                required
              />
            @endif
          </div>

          <div class="button-row">
            <button type="submit" class="btn">ok</button>
            <button type="reset" class="btn">cancel</button>
          </div>

          <div class="update-row">
            <button type="submit" class="btn">update</button>
          </div>
        </form>
      </div>

            <!-- Right tall blue area (summary) -->
      <div class="right-panel">
        @if(session('success'))
          <div class="flash-box flash-success" style="margin-bottom:10px;">
            {{ session('success') }}
          </div>
        @endif

        @isset($summary)
          <div class="summary-title">Total Due</div>
          <div class="summary-total">
            ${{ number_format($summary['remaining'], 2) }}
          </div>

          <ul class="summary-list">
            <li>
              <strong>Patient:</strong>
              {{ $summary['patient']->id }} - {{ $summary['patient']->patient_name ?? '' }}
            </li>
            <li><strong>Original charges:</strong> ${{ number_format($summary['total'], 2) }}</li>
            <li><strong>Paid so far:</strong> ${{ number_format($summary['totalPaid'], 2) }}</li>
            <li><strong>Remaining:</strong> ${{ number_format($summary['remaining'], 2) }}</li>
            <li><strong>Days:</strong> {{ $summary['days'] }} x $10 = ${{ $summary['dailyCharge'] }}</li>
            <li><strong>Appointments:</strong> {{ $summary['appointmentsCount'] }} x $50 = ${{ $summary['appointmentCharge'] }}</li>
            <li><strong>Medicine doses:</strong> {{ $summary['doseCount'] }} x $5 = ${{ $summary['medicineCharge'] }}</li>
            <li><strong>Payment type:</strong> Cash only (no taxes)</li>
          </ul>

          <hr style="margin:12px 0;">

          {{-- Payment form --}}
          <form action="{{ route('payments.pay') }}" method="POST">
            @csrf
            {{-- For Admin: keep patient_id; for Family/Patient it will be overridden in controller --}}
            <input type="hidden" name="patient_id" value="{{ $summary['patient']->id }}">

            <label for="payment_amount"><strong>Make a payment:</strong></label><br>
            <input
              type="number"
              step="0.01"
              min="0.01"
              name="payment_amount"
              id="payment_amount"
              class="patient-input"
              style="width: 120px; margin: 6px 0;"
              required
            />

            @error('payment_amount')
              <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn" style="margin-top: 4px;">Pay</button>
          </form>
        @else
          @php
            $hasAutoPatient = isset($patient) && $patient;
          @endphp

          @if($hasAutoPatient)
            <p>
              Press <strong>ok</strong> to calculate the total due for
              <strong>{{ $patient->patient_name }}</strong>.
            </p>
          @else
            <p>
              Enter a Patient ID on the left and press <strong>ok</strong> to calculate the total due.
            </p>
          @endif
        @endisset
      </div>
    </div>
  </div>
</body>
</html>
