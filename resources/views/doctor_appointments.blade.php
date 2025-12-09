@extends('layouts.app')

@section('title', 'Doctor Appointment')

@section('content')
<style>
  * { box-sizing: border-box; }

  body {
   background-color: #6791c3ff; 
    font-family: Arial, Helvetica, sans-serif;
  }

  .page {
    width: 950px;
    min-height: 520px;
    margin: 30px auto;
    padding: 30px 40px;
    background: #a1cafbff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }

  .title {
    font-size: 40px;
    font-weight: 700;
    text-align: center;
    margin-bottom: 30px;
  }

  .row {
    display: flex;
    align-items: center;
    margin-bottom: 18px;
    gap: 16px;
  }

  .label-box {
    width: 150px;
    background: #4378d6;
    color: #fff;
    padding: 10px 12px;
    border-radius: 4px;
    text-align: center;
    font-weight: 600;
  }

  .input-box {
    flex: 0 0 220px;
  }

  .input-box input,
  .input-box select {
    width: 100%;
    padding: 8px 10px;
    border-radius: 4px;
    border: 2px solid #4378d6;
    font-size: 15px;
  }

  .input-box input[readonly] {
    background: #f3f4f6;
  }

  .right-text {
    margin-left: auto;
    flex: 0 0 280px;
  }



  .buttons {
    margin-top: 26px;
    display: flex;
    justify-content: center;
    gap: 30px;
  }

  .btn {
    min-width: 120px;
    padding: 10px 18px;
    border-radius: 4px;
    border: none;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
  }

  .btn-ok {
    background: #4b9d4b;
    color: #fff;
  }

  .btn-ok:hover {
    background: #3e8640;
  }

  .btn-cancel {
    background: #888;
    color: #fff;
  }

  .btn-cancel:hover {
    background: #6d6d6d;
  }

  .error-text {
    color: #b91c1c;
    font-size: 13px;
    margin-top: 4px;
  }
</style>

<div class="page">
  <h1 class="title">Doctor&rsquo;s Appointment</h1>

  @if (session('success'))
    <p style="color:#15803d; text-align:center; margin-bottom:18px;">
      {{ session('success') }}
    </p>
  @endif

  {{-- Validation errors --}}
  @if ($errors->any())
    <ul style="color:#b91c1c; margin-bottom:18px;">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form method="POST" action="{{ route('doctor.appointments.store') }}">
    @csrf

    {{-- Patient ID + Patient Name --}}
    <div class="row">
      <div class="label-box">Patient ID</div>
      <div class="input-box">
        <input
          type="number"
          name="patient_id"
          id="patient_id"
          value="{{ old('patient_id') }}"
          required
        >
      </div>

      <div class="label-box" style="margin-left:auto;">Patient Name</div>
      <div class="input-box">
        <input
          type="text"
          id="patient_name"
          value="{{ $patientName ?? '' }}"
          readonly
        >
      </div>
    </div>

    {{-- Date --}}
    <div class="row">
      <div class="label-box">Date</div>
      <div class="input-box">
        <input
          type="date"
          name="date"
          id="date"
          value="{{ $selectedDate }}"
          required
        >
      </div>
      <div class="right-text">
        <small>
          Changing the date will reload this page and filter the
          doctor dropdown to the doctor on duty in the roster.
        </small>
      </div>
    </div>

    {{-- Doctor --}}
    <div class="row">
      <div class="label-box">Doctor</div>
      <div class="input-box">
        <select name="doctor_id" id="doctor_id" required>
          <option value="">-- Select Doctor --</option>
          @foreach($doctors as $doctor)
            <option value="{{ $doctor->id }}"
              {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
              {{ $doctor->name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="buttons">
      <button type="submit" class="btn btn-ok">Ok</button>
      <button type="button" onclick="window.history.back()" class="btn btn-cancel">
        Cancel
      </button>
    </div>
  </form>
</div>

{{-- Simple JS: load patient name and refilter doctors by date --}}
<script>
  // Fetch patient name when ID loses focus
  document.getElementById('patient_id').addEventListener('blur', function () {
    const id = this.value.trim();
    const nameBox = document.getElementById('patient_name');

    if (!id) {
      nameBox.value = '';
      return;
    }

    fetch("{{ url('/api/patients') }}/" + encodeURIComponent(id))
      .then(res => res.ok ? res.json() : null)
      .then(data => {
        nameBox.value = data && data.name ? data.name : '';
      })
      .catch(() => { nameBox.value = ''; });
  });

  // When date changes, reload page with ?date=...
  document.getElementById('date').addEventListener('change', function () {
    const value = this.value;
    if (!value) return;

    const url = new URL(window.location.href);
    url.searchParams.set('date', value);
    window.location.href = url.toString();
  });
</script>
@endsection
