<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Doctor Appointments</title>
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

    <main>
      <div class="container">
        <div class="appointments" id="upcomingAppointments">
            <h1>upcomingAppointments</h1>
    @forelse ($upcomingAppointments as $appointment)
    <div class="appointment" style="position:relative; padding:15px; background: rgba(255,255,255,0.05); border-radius:10px; margin-bottom:12px;">
   <h3>
        {{ $appointment->patient && $appointment->patient->user 
            ? $appointment->patient->user->first_name . ' ' . $appointment->patient->user->last_name 
            : 'No Patient' }}
    </h3>
        <p><strong>Date:</strong> {{ $appointment->date }}</p>
        <p><strong>Reason:</strong> {{ $appointment->notes }}</p>

        <a href="{{ route('appointment.details', $appointment->id) }}"
           style="position:absolute; top:15px; right:15px; padding:6px 12px; background:#6ee7b7; color:#022; border-radius:6px; text-decoration:none; font-weight:600;">
           View Info
        </a>
    </div>
@empty
    <p>No upcoming appointments.</p>
@endforelse
</div>
        <div id="PastAppointments">
        <h1>Past Appointments</h1>

            <div id="PastAppointments">
                @php $pastAppointments = $pastAppointments ?? collect(); @endphp

                @forelse($pastAppointments as $appt)
                    <div class="appointment">
                        <h3>{{ $appt->doctor->name ?? 'No Doctor' }}</h3>
                        <p><strong>Date:</strong> {{ $appt->date }}</p>
                        <p><strong>Status:</strong> {{ $appt->status ?? '—' }}</p>

                        @if(!empty($appt->notes))
                            <p><strong>Notes:</strong> {{ $appt->notes }}</p>
                        @endif
                    </div>
                @empty
                    <div class="appointment">
                        <p><em>No past appointments found.</em></p>
                    </div>
                @endforelse
</div>

   
      </div>

      <script>

        // Search
        searchInput.addEventListener('input', function() {
          const filter = searchInput.value.toLowerCase();
          const appointments = upcomingAppointments.getElementsByClassName('appointment');
          Array.from(appointments).forEach(appointment => {
            const name = appointment.querySelector('h3').textContent.toLowerCase();
            appointment.style.display = name.includes(filter) ? '' : 'none';
          });
        });
      </script>
    </main>
  </div>
</body>
</html>
