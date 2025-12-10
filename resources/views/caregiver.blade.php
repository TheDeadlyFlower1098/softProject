@extends('layouts.app')

@section('title', 'Caregiver Dashboard')

@section('content')

@php
  $existingChecks = $existingChecks ?? collect();
@endphp

<style>
  :root {
    --card-bg: rgba(255,255,255,0.05);
  }

  .caregiver-page {
    width: 100%;
    max-width: 1100px;
    margin: 20px auto;
  }

  .caregiver-title {
    font-size: 42px;
    font-weight: 700;
    margin: 6px 0 10px 6px;
    color: #fff;
  }

  .caregiver-box {
    width: 100%;
    margin: 0 auto 20px;
    background: rgb(182, 215, 168);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 12px rgba(0,0,0,0.12);
    color: #022;
  }

  .caregiver-box h2 {
    margin-top: 0;
    text-align: left;
    color: rgb(60, 120, 216);
  }

  .caregiver-box p {
    margin: 4px 0;
  }

  .caregiver-row-locked {
    background: #ccc;
    opacity: 0.7;
  }

  .caregiver-row-locked td {
    color: #555;
  }


  .caregiver-table-wrapper {
    background: #d9e9fb;
    border: 3px solid #7f93ac;
    border-radius: 6px;
    padding: 10px;
    margin-top: 10px;
  }

  table.caregiver-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 5px;
    font-size: 15px;
    text-align: center;
    background-color: #ffffff;
  }

  table.caregiver-table th,
  table.caregiver-table td {
    padding: 10px;
    border: 1px solid rgba(0,0,0,0.2);
    color: #000;
  }

  table.caregiver-table thead th {
    background-color: rgb(182,215,168);
    font-weight: 600;
  }

  .caregiver-actions {
    margin-top: 15px;
    display: flex;
    gap: 10px;
  }

  .caregiver-actions button {
    padding: 10px 20px;
    background: #6ee7b7;
    color: #022;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
  }

  .caregiver-actions button[type="reset"] {
    background: #e06666;
    color: #fff;
  }

  .caregiver-actions button:hover {
    filter: brightness(1.05);
  }
</style>

<div class="caregiver-page">
  <h1 class="caregiver-title">Caregiver Dashboard</h1>

  <div class="caregiver-box">
    <h2>Patients</h2>

    {{-- Show the date we’re looking at, if provided --}}
    @isset($selectedDate)
      <p>
        <strong>Date:</strong>
        {{ \Illuminate\Support\Carbon::parse($selectedDate)->format('M d, Y') }}
      </p>
    @endisset

    {{-- Helpful messages when not assigned / or no patients --}}
    @if(is_null($assignedGroup))
      <p><em>You are not assigned to a group in the roster for this date.</em></p>
    @elseif($patients->isEmpty())
      <p><em>No patients are assigned to your group for this date.</em></p>
    @endif

    @if($patients->isEmpty())
      <p><em>No patients found in the database.</em></p>
    @endif

    <form action="{{ route('caregiver.saveToday') }}" method="POST">
      @csrf

      <div class="caregiver-table-wrapper">
        <table class="caregiver-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Morning Medicine</th>
              <th>Afternoon Medicine</th>
              <th>Night Medicine</th>
              <th>Breakfast</th>
              <th>Lunch</th>
              <th>Dinner</th>
            </tr>
          </thead>

          <tbody>
            @foreach ($patients as $patient)
              @php
                $check = $existingChecks->get($patient->id);
                $isLocked = !is_null($check);
              @endphp

              <tr class="{{ $isLocked ? 'caregiver-row-locked' : '' }}">
                <td>
                  {{ optional($patient->user)->first_name }}
                  {{ optional($patient->user)->last_name }}

                  @if($isLocked)
                    <br>
                    <small><em>Report already submitted for this patient today.</em></small>
                  @else
                    <input type="hidden"
                          name="patients[{{ $patient->id }}][patient_id]"
                          value="{{ $patient->id }}">
                  @endif
                </td>

                {{-- Morning --}}
                <td>
                  @if($isLocked)
                    {{ $check->morning === 'taken' ? '✔' : '✖' }}
                  @else
                    <input type="checkbox" name="patients[{{ $patient->id }}][morning]" value="1">
                  @endif
                </td>

                {{-- Afternoon --}}
                <td>
                  @if($isLocked)
                    {{ $check->afternoon === 'taken' ? '✔' : '✖' }}
                  @else
                    <input type="checkbox" name="patients[{{ $patient->id }}][afternoon]" value="1">
                  @endif
                </td>

                {{-- Night --}}
                <td>
                  @if($isLocked)
                    {{ $check->night === 'taken' ? '✔' : '✖' }}
                  @else
                    <input type="checkbox" name="patients[{{ $patient->id }}][night]" value="1">
                  @endif
                </td>

                {{-- Breakfast --}}
                <td>
                  @if($isLocked)
                    {{ $check->breakfast === 'taken' ? '✔' : '✖' }}
                  @else
                    <input type="checkbox" name="patients[{{ $patient->id }}][breakfast]" value="1">
                  @endif
                </td>

                {{-- Lunch --}}
                <td>
                  @if($isLocked)
                    {{ $check->lunch === 'taken' ? '✔' : '✖' }}
                  @else
                    <input type="checkbox" name="patients[{{ $patient->id }}][lunch]" value="1">
                  @endif
                </td>

                {{-- Dinner --}}
                <td>
                  @if($isLocked)
                    {{ $check->dinner === 'taken' ? '✔' : '✖' }}
                  @else
                    <input type="checkbox" name="patients[{{ $patient->id }}][dinner]" value="1">
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>

        </table>
      </div>

      <div class="caregiver-actions">
        <button type="submit">Save Daily Report</button>
        <button type="reset">Cancel</button>
      </div>
    </form>
  </div>
</div>
@endsection
