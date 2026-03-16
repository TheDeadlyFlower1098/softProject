@extends('layouts.app')

@section('title', 'Roster Dashboard')

@section('content')

@php
    $user = auth()->user();
    $role = $user && $user->role ? strtolower($user->role->name) : null;
@endphp

<style>
  :root{
    --blue-100:#d9e9fb;
    --blue-300:#9ec1e6;
    --green-200:#b9d7a9;
  }

  *{box-sizing:border-box;}

  /* Outer frame */
  .layout{
    width:1100px;
    max-width:96vw;
    margin:16px auto;
    background:var(--blue-100);
    border:3px solid #c7d3e2;
    border-radius:6px;
    padding:18px;
    display:flex;
    gap:26px;
    align-items:flex-start;
  }

  /* Left column */
  .left{
    flex:1 1 auto;
    min-width:640px;
  }

  .title{
    font-size:42px;
    font-weight:700;
    margin:6px 0 10px 6px;
    color:#000;
  }

  /* Date row */
  .date-row{
    display:flex;
    gap:10px;
    align-items:center;
    margin:2px 0 10px 6px;
  }

  .date-label{
    padding:6px 10px;
    border:3px solid #6c8bb5;
    border-radius:4px;
    background:#8fb2de;
    color:#0f2438;
    font-weight:700;
  }

  .date-input{
    height:32px;
    width:180px;
    border:2px solid #6c8bb5;
    border-radius:4px;
    background:#cfe1f6;
    padding:4px 8px;
  }

  /* Green panel */
  .card{
    background:var(--green-200);
    border:3px solid #739966;
    border-radius:6px;
    padding:16px;
    min-height:200px;
    display:flex;
    flex-direction:column;
    gap:16px;
  }

  /* Table container – same style idea as Patients table wrapper */
  .canvas{
    background:var(--blue-100);
    border:3px solid #7f93ac;
    border-radius:6px;
    padding:10px;
    overflow-x:auto;
  }

  /* Roster table styled to match Patients table */
  table.roster-table{
    width:100%;
    border-collapse:collapse;
    font-size:15px;
    background:#ffffff;
  }

  table.roster-table thead th{
    background:#cfe1f6;
    border-bottom:2px solid #7f93ac;
    padding:10px;
    text-align:left;
    font-weight:600;
    white-space:nowrap;
    color:#000;
  }

  table.roster-table tbody td{
    border-bottom:1px solid #d0d7e2;
    padding:8px 10px;
    vertical-align:middle;
    color:#000;
  }

  table.roster-table tbody tr:nth-child(even){
    background:#f5f7fb;
  }

  table.roster-table tbody tr:nth-child(odd){
    background:#ffffff;
  }

  .new-roster-btn{
    display:inline-block;
    padding:6px 16px;
    margin-left:auto;
    border:2px solid #3d6b2a;
    border-radius:4px;
    background:#6aa84f;
    font-weight:600;
    font-size:14px;
    text-decoration:none;
    color:#000;
    cursor:pointer;
  }

  .new-roster-btn:hover{
    filter:brightness(1.05);
  }

  .date-row-spacer{
    flex:1;
  }

  /* Right rail */
  .rail{
    flex:0 0 340px;
    align-self:stretch;
    background:var(--blue-300);
    border:3px solid #7ea4c9;
    border-radius:6px;
    padding:18px;
    display:flex;
    align-items:flex-start;
  }

  .rail p{
    margin:0;
    font-weight:600;
    font-size:20px;
    text-align:center;
    color:#000;
  }
</style>

<div class="layout">
  <div class="left">

    <h1 class="title">Roster</h1>

    <form method="GET"
          action="{{ route('roster.dashboard') }}"
          class="date-row">
      <span class="date-label">Date:</span>

      <input
        class="date-input"
        type="date"
        name="date"
        value="{{ $selectedDate }}"
        onchange="this.form.submit()"
      >

      <div class="date-row-spacer"></div>

      {{-- Show "New Roster" button only for Admin & Supervisor --}}
      @if(in_array($role, ['admin', 'supervisor']))
        <a
          href="{{ route('roster.new', ['date' => $selectedDate]) }}"
          class="new-roster-btn"
        >
          New Roster
        </a>
      @endif
    </form>

    {{-- Roster table --}}
    <section class="card">
      {{-- 🔥 removed the empty <div class="bar"></div> --}}
      <div class="canvas">
        <table class="roster-table">
          <thead>
            <tr>
              <th>Role</th>
              <th>Employee</th>
              <th>Group</th>
            </tr>
          </thead>
          <tbody>
            {{-- Supervisor --}}
            @php $sup = $roster->supervisor_id ? ($employeesById[$roster->supervisor_id] ?? null) : null; @endphp
            <tr>
              <td>Supervisor</td>
              <td>{{ $sup->name ?? '—' }}</td>
              <td>—</td>
            </tr>

            {{-- Doctor --}}
            @php $doc = $roster->doctor_id ? ($employeesById[$roster->doctor_id] ?? null) : null; @endphp
            <tr>
              <td>Doctor</td>
              <td>{{ $doc->name ?? '—' }}</td>
              <td>—</td>
            </tr>

            {{-- Caregivers / Groups --}}
            @php
              $rows = [
                ['role' => 'Caregiver', 'group' => 'Group 1', 'id' => $roster->caregiver_1],
                ['role' => 'Caregiver', 'group' => 'Group 2', 'id' => $roster->caregiver_2],
                ['role' => 'Caregiver', 'group' => 'Group 3', 'id' => $roster->caregiver_3],
                ['role' => 'Caregiver', 'group' => 'Group 4', 'id' => $roster->caregiver_4],
              ];
            @endphp

            @foreach($rows as $row)
              @php $emp = $row['id'] ? ($employeesById[$row['id']] ?? null) : null; @endphp
              <tr>
                <td>{{ $row['role'] }}</td>
                <td>{{ $emp->name ?? '—' }}</td>
                <td>{{ $row['group'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>

  </div>

  <aside class="rail">
    @if(!$roster)
      <p>
        No roster has been created for
        {{ \Illuminate\Support\Carbon::parse($selectedDate)->format('M d, Y') }}.
      </p>
    @else
      @if($currentEmployee)
        @if($currentAssignment)
          <p>
            Hello {{ $currentEmployee->name }}!<br><br>
            You are scheduled as
            <strong>{{ $currentAssignment['type'] }}</strong>
            @if($currentAssignment['group'])
              in <strong>{{ $currentAssignment['group'] }}</strong>
            @endif
            on {{ \Illuminate\Support\Carbon::parse($selectedDate)->format('M d, Y') }}.
          </p>
        @else
          <p>
            Hello {{ $currentEmployee->name }}!<br><br>
            You are <strong>not scheduled</strong> on
            {{ \Illuminate\Support\Carbon::parse($selectedDate)->format('M d, Y') }}.
          </p>
        @endif
      @else
        <p>
          You don't have an employee record linked yet, so we can't
          determine your schedule for
          {{ \Illuminate\Support\Carbon::parse($selectedDate)->format('M d, Y') }}.
        </p>
      @endif
    @endif
  </aside>

</div>

@endsection
