<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Caregiver Dashboard</title>
  <style>
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

    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      color: #fff;
    }

    .caregiver-box {
      width: 90%;
      margin: 20px auto;
      background: rgb(182, 215, 168);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0,0,0,0.12);
    }

    .caregiver-box h2 {
      margin-top: 0;
      text-align: center;
      color: rgb(60, 120, 216);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      font-size: 16px;
      text-align: center;
      background-color:rgba(255, 255, 255, 1);
    }

    th, td {
      padding: 10px;
      border: 1px solid rgba(0,0,0,0.2);
      color: black;
    }

    th {
      background-color:  rgb(182,215,168);
    }

    button {
      margin-top: 15px;
      padding: 10px 20px;
      background: #6ee7b7;
      color: #022;
      border-radius: 8px;
      font-weight: 600;
      border: none;
      cursor: pointer;
    }

    @media (max-width: 900px) {
      .sidebar { display: none; }
      .app { padding: 14px; }
    }
  </style>
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand">
        <div class="logo">
          <img src="{{ asset('images/sun.png') }}" alt="Logo" style="width:70px; height:70px;">
        </div>
        <div>
          <h1>Caregiver</h1>
        </div>
      </div>
      <nav class="nav">
        <a href="#">Dashboard</a>
        <a href="#" class="active">Patients</a>
        <a href="#">Reports</a>
      </nav>
    </aside>

    <main class="main-content">
      <h2>Patients</h2>

      {{-- Show the date we’re looking at, if provided --}}
      @isset($selectedDate)
        <p>
          Date:
          {{ \Illuminate\Support\Carbon::parse($selectedDate)->format('M d, Y') }}
        </p>
      @endisset

      {{-- Helpful messages when not assigned / or no patients --}}
      @if(is_null($assignedGroup))
        <p><em>You are not assigned to a group in the roster for this date.</em></p>
      @elseif($patients->isEmpty())
        <p><em>No patients are assigned to your group for this date.</em></p>
      @endif

      {{-- Optional: old debug line, you can remove if you want --}}
      @if($patients->isEmpty())
          <p><em>No patients found in the database.</em></p>
      @endif

      <form action="{{ route('caregiver.saveToday') }}" method="POST">
        @csrf

        <table>
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
              <tr>
                <td>
                  {{ optional($patient->user)->first_name }} {{ optional($patient->user)->last_name }}
                  <input type="hidden"
                         name="patients[{{ $patient->id }}][patient_id]"
                         value="{{ $patient->id }}">
                </td>

                {{-- Medicine --}}
                <td><input type="checkbox" name="patients[{{ $patient->id }}][morning]"   value="1"></td>
                <td><input type="checkbox" name="patients[{{ $patient->id }}][afternoon]" value="1"></td>
                <td><input type="checkbox" name="patients[{{ $patient->id }}][night]"     value="1"></td>

                {{-- Meals (wire these up when DB columns exist) --}}
                <td><input type="checkbox" name="patients[{{ $patient->id }}][breakfast]" value="1"></td>
                <td><input type="checkbox" name="patients[{{ $patient->id }}][lunch]"     value="1"></td>
                <td><input type="checkbox" name="patients[{{ $patient->id }}][dinner]"    value="1"></td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <button type="submit" class="ok-button">Save Daily Report</button>
        <button type="reset"  class="cancel-button">Cancel</button>
      </form>
    </main>
  </div>
</body>
</html>
