@extends('layouts.app')

@section('title', 'Doctor Appointment')

@section('content')
<style>

    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      color: #fff;
    }

    .patient-info-box {
      width: 90%;
      margin: 40px auto;
      background: rgb(182, 215, 168);
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0,0,0,0.12);
    }

    .patient-info-box h2 {
      margin-top: 0;
      font-size: 28px;
      color: rgb(60, 120, 216);
      text-align: center;
    }

    .patient-info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
      font-size: 18px;
      margin-top: 20px;
    }

    input, textarea {
      width: 100%;
      padding: 8px;
      border-radius: 6px;
      border: none;
      margin-top: 5px;
      font-size: 16px;
    }

    button, .back-button {
      padding: 10px 20px;
      background: #6cacd1ff;
      color: #022;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      border: none;
      cursor: pointer;
      margin-top: 10px;
    }

    hr { border-color: rgba(0,0,0,0.1); margin: 20px 0; }

    @media (max-width: 900px) {
      .sidebar { display: none; }
      .app { padding: 14px; }
    }
  :root {
    --bg: #0f1724;
    --muted: #9aa7bd;
    --accent: #6ee7b7;
    --card-bg: rgba(255,255,255,0.05);
    --button-bg: #4ade80;
    --button-hover: #22c55e;
  }
  * { box-sizing: border-box; }
  body {
    margin: 0;
    font-family: Inter, Arial, sans-serif;
    background: #5466aa;
    color: #e6eef8;
  }

  .app {
    display: flex;
    min-height: 100vh;
    gap: 24px;
    padding: 24px;
  }

  /* Sidebar */
  .sidebar {
    width: 260px;
    background: rgb(111, 168, 220);
    border-radius: 12px;
    padding: 18px;
    backdrop-filter: blur(6px);
    display: flex;
    flex-direction: column;
    gap: 18px;
    flex-shrink: 0;
  }
  .brand { display:flex; align-items:center; gap:12px; }
  .logo-img { width:50px; height:50px; border-radius:10px; }
  .nav { display:flex; flex-direction:column; gap:6px; margin-top:20px; }
  .nav a {
    padding:10px;
    display:flex;
    align-items:center;
    color: rgb(182, 215, 168);
    text-decoration:none;
    border-radius:8px;
    font-size:14px;
    background: rgba(74,113,150,1);
    border: 2px solid rgb(182,215,168);
    transition: 0.2s;
  }
  .nav a:hover { background: rgba(255,255,255,0.1); }
  .nav a.active { background: linear-gradient(90deg, rgba(110,231,183,0.12), rgba(110,231,183,0.06)); }

  /* Main content */
  main {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .container {
    width: 100%;
    max-width: 700px;
    padding: 20px;
    background-color: #6791c3ff; 
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(66, 104, 122, 1);
  }

  h1, h2 { text-align: center; color: #e6eef8; }

  form {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
  }

  input, select, textarea {
    padding: 10px;
    border-radius: 8px;
    border: none;
    font-size: 16px;
    background: var(--card-bg);
    color: #fff;
  }

  button {
    padding: 12px;
    background-color: var(--button-bg);
    color: #0f1724;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
  }
  button:hover { background-color: var(--button-hover); }

  .appointments { margin-top: 20px; }
  .appointment {
    padding: 15px;
    background: var(--card-bg);
    border-left: 4px solid var(--accent);
    margin-bottom: 12px;
    border-radius: 10px;
    transition: transform 0.2s;
  }
  .appointment:hover { transform: translateY(-2px); }

  .appointment h3 { margin: 0 0 5px 0; color: #fff; }
  .appointment p { margin: 2px 0; color: var(--muted); }
</style>
</head>
<body>
  <div class="app">
    <aside class="sidebar" id="sidebar">
      <div class="brand">
        <div class="logo">
          <img src="{{ asset('images/sun.png') }}" alt="Logo" class="logo-img">
        </div>
        <h1>Dashboard</h1>
      </div>
      <nav class="nav">
        <a href="#" class="active">Appointments</a>
        <a href="#">Page 2</a>
        <a href="#">Page 3</a>
        <a href="#">Page 4</a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="main-content">
      
      <!-- Patient & Appointment Info -->
      <div class="patient-info-box">
        <h2>Appointment Details</h2>
        <div class="patient-info-grid">
          <div><strong>Patient Name:</strong></div><div>{{ $appointment->patient && $appointment->patient->user 
            ? $appointment->patient->user->first_name . ' ' . $appointment->patient->user->last_name 
            : 'No Patient' }}</div>
          <div><strong>Patient ID:</strong></div><div>{{ $appointment->patient->id }}</div>
          <!-- <div><strong>Group:</strong></div><div>{{ $appointment->patient->group }}</div> -->
          <div><strong>Admission Date:</strong></div><div>{{ $appointment->patient->admission_date }}</div>
          <div><strong>Appointment Date:</strong></div><div>{{ $appointment->date }}</div>
          <div><strong>Reason / Comments:</strong></div><div>{{ $appointment->notes }}</div>
        </div>
      </div>

      <div class="patient-info-box">
  <h2>Add Prescription</h2>

  {{-- Validation errors --}}
  @if ($errors->any())
      <div style="background:#fee2e2;color:#991b1b;padding:10px;border-radius:8px;margin-bottom:10px;">
          <strong>There were some problems with your input:</strong>
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  <form method="POST" action="{{ route('appointments.prescriptions.store', $appointment->id) }}">
      @csrf

      {{-- main prescription text --}}
      <label for="content"><strong>Prescription</strong></label>
      <textarea id="content" name="content" rows="4" required>{{ old('content') }}</textarea>

      {{-- optional notes for staff/patient --}}
      <label for="notes" style="margin-top:10px;"><strong>Additional Notes</strong></label>
      <textarea id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>

      {{-- you can keep these hidden, or fill them in controller --}}
      <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
      <input type="hidden" name="patient_id" value="{{ $appointment->patient->id }}">

      <button type="submit" style="margin-top:15px;">Save Prescription</button>
  </form>
  <div class="patient-info-box">
  <h2>Previous Prescriptions</h2>

  @if($prescriptions->isEmpty())
      <p>No previous prescriptions from you for this patient.</p>
  @else
      @foreach($prescriptions as $p)
          <div style="margin-bottom:12px;padding:10px;background:#d5ecc0;border-radius:8px;">
              <div><strong>Date:</strong> {{ $p->created_at->format('M d, Y H:i') }}</div>
              <div style="margin-top:6px;"><strong>Prescription:</strong></div>
              <div>{{ $p->content }}</div>

              @if($p->notes)
                  <div style="margin-top:6px;"><strong>Notes:</strong></div>
                  <div>{{ $p->notes }}</div>
              @endif
          </div>
      @endforeach
  @endif
</div>

</div>


     <a href="{{ route('doctorHome') }}" class="back-button">Back to Doctor Home</a>


    </main>
  </div>

@endsection