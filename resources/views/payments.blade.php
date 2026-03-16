@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<!DOCTYPE html>
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
      padding: 20px;
      overflow-x: auto;
    }

    .right-panel {
      width: 220px;
      background: #a7bddd; /* darker blue */
      border: 1px solid #6f7fa2;
      padding: 20px;
      color: #000;
      font-size: 14px;
    }

    .payments-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
      color: #000;
    }

    .payments-table th,
    .payments-table td {
      border: 1px solid rgba(0,0,0,0.15);
      padding: 6px 8px;
      text-align: left;
    }

    .payments-table th {
      background: #7da4e3;
      color: #fff;
      font-weight: 600;
    }

    .payments-table td.numeric {
      text-align: right;
    }

    .btn {
      background: #9bbf61;
      border: 1px solid #6e8642;
      padding: 4px 10px;
      font-size: 13px;
      cursor: pointer;
    }

    .amount-input {
      width: 80px;
      height: 24px;
      border: 1px solid #808f63;
      background: #d6e6b7;
      font-size: 13px;
      padding: 2px 4px;
    }

    .flash-success {
      background: #d4edda;
      border: 1px solid #c3e6cb;
      color: #155724;
      padding: 8px 10px;
      border-radius: 4px;
      margin-bottom: 10px;
      font-size: 13px;
    }

    .error {
      color: darkred;
      font-size: 12px;
      margin-top: 4px;
    }
  </style>
</head>
<body>
  <div class="page">
    <h1 class="title">Payment</h1>

    <div class="content-row">
      <!-- Left: table of all patients -->
      <div class="left-panel">
        @if(session('success'))
          <div class="flash-success">
            {{ session('success') }}
          </div>
        @endif

        <table class="payments-table">
          <thead>
            <tr>
              <th style="color: black;">Patient</th>
              <th style="color: black;">Days</th>
              <th style="color: black;">Appointments</th>
              <th style="color: black;">Doses</th>
              <th style="color: black;">Original<br>charges</th>
              <th style="color: black;">Paid<br>so far</th>
              <th style="color: black;">Remaining</th>
              <th style="color: black;">Record payment</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rows as $row)
              @php
                $patient   = $row['patient'];
                $remaining = $row['remaining'];
              @endphp
              <tr>
                <td>
                  {{ $patient->patient_name }}
                  <br>
                  <small>ID: {{ $patient->id }}</small>
                </td>
                <td class="numeric">{{ $row['days'] }}</td>
                <td class="numeric">{{ $row['appointmentsCount'] }}</td>
                <td class="numeric">{{ $row['doseCount'] }}</td>
                <td class="numeric">${{ number_format($row['total'], 2) }}</td>
                <td class="numeric">${{ number_format($row['totalPaid'], 2) }}</td>
                <td class="numeric">${{ number_format($row['remaining'], 2) }}</td>
                <td>
                  <form action="{{ route('payments.pay', $patient->id) }}" method="POST">
                    @csrf
                    <input
                      type="number"
                      step="0.01"
                      min="0.01"
                      name="amount"
                      class="amount-input"
                      placeholder="0.00"
                      @if($remaining <= 0) disabled @endif
                    >
                    <button type="submit" class="btn" @if($remaining <= 0) disabled @endif>
                      Pay
                    </button>

                    @error('amount')
                      {{-- This will show the last validation error; fine for a school project --}}
                      <div class="error">{{ $message }}</div>
                    @enderror
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8">No patients found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Right: info panel -->
      <div class="right-panel">
        <div class="summary-title" style="font-weight:bold; margin-bottom:8px;">
          Instructions
        </div>
        <p style="margin-bottom:8px;">
          This page is for <strong>Admin</strong> only.
        </p>
        <p style="margin-bottom:8px;">
          1. Patients pay in person (cash only).<br>
          2. Find the patient in the table.<br>
          3. Enter the amount they paid in the
             <em>Record payment</em> column and press <strong>Pay</strong>.
        </p>
        <p style="margin-bottom:8px;">
          The <strong>Original charges</strong> come from
          the patient&rsquo;s stay, appointments, and medicine doses.
        </p>
        <p>
          <strong>Remaining</strong> = Original charges minus all recorded
          payments.
        </p>
      </div>
    </div>
  </div>
</body>
</html>
@endsection
