@extends('layouts.app')

@section('title', 'Patient List')

@section('content')
<style>
  :root{
    --blue-100:#d9e9fb;
    --blue-300:#9ec1e6;
    --green-200:#b9d7a9;
  }
  *{box-sizing:border-box;}

  /* Outer frame */
  .layout{
    width:100%;
    max-width:1200px; 
    margin:20px auto;
    background:var(--blue-100);
    border:3px solid #c7d3e2;
    border-radius:6px;
    padding:18px;
  }

  /* Left column becomes the full width */
  .left{
    width:100%;
  }

  .title{
    font-size:42px;
    font-weight:700;
    margin:6px 0 14px 6px;
  }

  /* Green content card */
  .card{
    background:var(--green-200);
    border:3px solid #739966;
    border-radius:6px;
    padding:18px;
    min-height:200px;
    display:flex;
    flex-direction:column;
    gap:18px;
  }

  /* Search bar */
.search{
  background:var(--blue-100);
  border:3px solid #7f93ac;
  border-radius:6px;
  height:54px;
  display:flex;
  align-items:center;
  padding:0 12px;
  font-size:14px;
  color:#445;
  gap:10px;
}

.search input[type="text"]{
  flex:1;
  height:34px;
  border:1px solid #7f93ac;
  border-radius:4px;
  padding:0 8px;
  font-size:14px;
}

.search button{
  padding:6px 14px;
  border-radius:4px;
  border:2px solid #2b6cb0;
  background:#2b6cb0;
  color:#f1f2f4;
  font-size:13px;
  font-weight:600;
  cursor:pointer;
  transition:background-color .2s, transform .2s;
}

.search button:hover{
  background:#6fa6e0;
  transform:translateY(-1px);
}

  /* Table wrapper */
  .table-wrapper{
    margin-top:10px;
    background:var(--blue-100);
    border:3px solid #7f93ac;
    border-radius:6px;
    padding:10px;
  }

  table.patients-table{
    width:100%;
    border-collapse:collapse;
    font-size:15px;
    background:white;
  }

  table.patients-table thead th{
    background:#cfe1f6;
    border-bottom:2px solid #7f93ac;
    padding:10px;
    text-align:left;
    font-weight:600;
    white-space:nowrap;
    color:#000; /* visible header text */
  }

  table.patients-table tbody td{
    border-bottom:1px solid #d0d7e2;
    padding:8px 10px;
    vertical-align:middle;
    color:#000; /* make data text black */
  }

  table.patients-table tbody tr:nth-child(even){
    background:#f5f7fb;
  }

  .patient-actions{
    white-space:nowrap;
  }

  .btn-small{
    display:inline-block;
    padding:6px 10px;
    border-radius:4px;
    border:2px solid #2b6cb0;
    background:#2b6cb0;
    color:#f1f2f4;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    cursor:pointer;
    transition:background-color .2s, transform .2s;
  }

  .btn-small:hover{
    background:#6fa6e0;
    transform:translateY(-1px);
  }
</style>

@php
    $user = auth()->user();
    $role = $user && $user->role ? strtolower($user->role->name) : null;
@endphp

<div class="layout">
  <div class="left">
    <h1 class="title">Patients</h1>

    <section class="card">
      <form method="GET" action="{{ route('patients') }}" class="search">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by patient name, ID, emergency contact, or phone..."
        >
        <button type="submit">Filter</button>
      </form>


      {{-- Patients table --}}
      <div class="table-wrapper">
        <table class="patients-table">
          <thead>
            <tr>
              <th>Patient ID</th>
              <th>Name</th>
              <th>Age</th>
              <th>Emergency Phone</th>
              <th>Emergency Contact Name</th>
              <th>Admission Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @isset($patients)
              @forelse($patients as $patient)
                @php
                  $age = $patient->user && $patient->user->dob
                      ? \Illuminate\Support\Carbon::parse($patient->user->dob)->age
                      : null;
                @endphp
                <tr>
                  <td>{{ $patient->patient_identifier }}</td>
                  <td>{{ $patient->patient_name }}</td>
                  <td>{{ $age ?? 'N/A' }}</td>
                  <td>{{ $patient->emergency_contact_phone ?? 'N/A' }}</td>
                  <td>{{ $patient->emergency_contact_name ?? 'N/A' }}</td>
                  <td>
                    @if(!empty($patient->admission_date))
                      {{ \Illuminate\Support\Carbon::parse($patient->admission_date)->toDateString() }}
                    @else
                      N/A
                    @endif
                  </td>
                  <td class="patient-actions">
                    @if(in_array($role, ['admin','supervisor']))
                      <a href="{{ route('patients.additional', ['patient' => $patient->id]) }}"
                         class="btn-small">
                        Additional Info
                      </a>
                    @else
                      —
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7">No patients found.</td>
                </tr>
              @endforelse
            @else
              <tr>
                <td colspan="7">
                  No patient data provided to this view.
                  Ask your instructor to ensure the controller passes a $patients collection.
                </td>
              </tr>
            @endisset
          </tbody>
        </table>
      </div>
    </section>
  </div>
</div>
@endsection
