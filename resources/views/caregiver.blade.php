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

  .sidebar {
    width: 260px;
    background: rgb(111, 168, 220);
    border-radius: 12px;
    padding: 18px;
    display: flex;
    flex-direction: column;
    gap: 18px;
  }

  .brand { display:flex; align-items:center; gap:12px; }
  .logo-img { width:50px; height:50px; border-radius:10px; }

  .nav { display:flex; flex-direction:column; gap:6px; margin-top:20px; }
  .nav a {
    padding:10px;
    color: rgb(182, 215, 168);
    text-decoration:none;
    border-radius:8px;
    background: rgba(74,113,150,1);
    border: 2px solid rgb(182,215,168);
  }

  .main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #fff;
  }

  .caregiver-box {
    width: 90%;
    margin: 20px auto;
    background: rgb(182, 215, 168);
    padding: 20px;
    border-radius: 10px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    color: black;
  }

  th, td {
    padding: 10px;
    border: 1px solid rgba(0,0,0,0.2);
  }

  th { background-color: rgb(182,215,168); }

  button {
    margin-top: 15px;
    padding: 10px 20px;
    background: #6ee7b7;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
  }
  </style>
</head>

<body>
  <div class="app">

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="brand">
        <img src="images/sun.png" alt="Logo" class="logo-img">
        <h1>Caregiver</h1>
      </div>

      <nav class="nav">
        <a href="#">Dashboard</a>
        <a href="#">Patients</a>
        <a href="#">Reports</a>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
      <h2>Patients</h2>

      <!-- ✅ Correct Form -->
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
                  {{ $patient->user->first_name }} {{ $patient->user->last_name }}
                  <input type="hidden" name="patients[{{ $patient->id }}][patient_id]" value="{{ $patient->id }}">
                </td>

                <!-- Medicine -->
                <td><input type="checkbox" name="patients[{{ $patient->id }}][morning]" value="1"></td>
                <td><input type="checkbox" name="patients[{{ $patient->id }}][afternoon]" value="1"></td>
                <td><input type="checkbox" name="patients[{{ $patient->id }}][night]" value="1"></td>

                <!-- Meals -->
                <td><input type="checkbox" name="patients[{{ $patient->id }}][breakfast]" value="1"></td>
                <td><input type="checkbox" name="patients[{{ $patient->id }}][lunch]" value="1"></td>
                <td><input type="checkbox" name="patients[{{ $patient->id }}][dinner]" value="1"></td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <button type="submit" class="ok-button">OK</button>
        <button type="reset" class="cancel-button">Cancel</button>

      </form>

    </main>
  </div>
</body>
</html>
