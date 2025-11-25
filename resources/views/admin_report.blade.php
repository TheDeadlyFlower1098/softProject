<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Report</title>
  <style>
    :root{
      --blue-100:#d9e9fb;
      --blue-300:#9ec1e6;
      --blue-600:#2563eb;
      --green-200:#b9d7a9;
      --green-400:#8fbd75;
      --ink:#111827;
      --line:#9aa4b2;
    }
    *{box-sizing:border-box}
    body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:var(--ink);background:#fff}
    h1{margin:24px 0;text-align:center}

    /* Outer panel with right rail */
    .layout{
      width: 92%;
      margin: 0 auto 40px;
      border: 2px solid #b9c6d8;
      border-radius: 8px;
      padding: 24px;
      background: var(--blue-100);
      display: grid;
      grid-template-columns: 1fr 320px;   /* main + sidebar */
      gap: 28px;
    }
    .right-rail{
      background: var(--blue-300);
      border-radius: 8px;
      min-height: 520px;
    }

    /* Date row */
    .date-row{
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 40px;
    }
    .pill{
      background: var(--blue-600);
      color:#fff;
      border-radius: 6px;
      padding: 8px 22px;
      border:1px solid #0f3fb1;
      font-weight:600;
    }
    #date_input{
      height: 36px;
      padding: 6px 10px;
      border:1px solid #8fa3bf;
      border-radius: 4px;
      width: 220px;
      background: #eef4ff;
    }

    /* Missed activity card */
    .activity-card{
      position: relative;
      background: var(--green-200);
      border-radius: 6px;
      padding: 18px;
      min-height: 360px;
    }
    .tag{
      position:absolute;
      top:-14px; left:16px;
      background: var(--green-400);
      padding: 8px 14px;
      border-radius: 6px;
      font-weight:700;
      border:1px solid #6ea65b;
    }
    .big-bar{
      height: 50px;
      background:#d7e7f6;
      border:1px solid #7f93ac;
      border-radius:6px;
      margin: 18px 10px 28px;
    }

    /* Checkbox row */
    .checkbox-row{
      display:flex;
      gap:28px;
      padding:0 10px;
    }

    /* Custom checkbox style (works with DB .checked) */
    .cb{
      appearance:none; -webkit-appearance:none;
      width: 28px; height: 28px;
      border: 2px solid #3b82f6;
      border-radius: 6px;
      position:relative;
      background:#eef4ff;
      cursor:pointer;
    }
    .cb:checked{background:#3b82f6; border-color:#2563eb}
    .cb:checked::after{
      content:""; position:absolute; left:9px; top:4px;
      width:7px; height:14px; border:solid #fff; border-width:0 3px 3px 0; transform:rotate(45deg);
    }

    /* Spacing below the row to mimic big green area */
    .activity-card .spacer{height: 220px;}

    :root{
      --bg:#0f1724;
      --muted:#9aa7bd;
      --accent:#6ee7b7;
      --glass: rgba(255,255,255,0.03);
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family:Inter, ui-sans-serif, system-ui;
      background:rgba(84, 128, 170, 1);
      color:#e6eef8;
    }

    .app{
      display:flex;
      min-height:100vh;
      padding:24px;
      gap:24px;
    }

    .sidebar{
      width:260px;
      background: rgb(111, 168, 220);
      border-radius:12px;
      padding:18px;
      backdrop-filter:blur(6px);
      display:flex;
      flex-direction:column;
      gap:18px;
      flex-shrink:0;
    }

    .brand{display:flex;align-items:center;gap:12px}
    .logo{
      width:44px;height:44px;
      border-radius:10px;
      display:flex;align-items:center;justify-content:center;
      font-weight:700;color:#022;
    }
    .brand h1{margin:0;font-size:16px}
    .brand p{margin:0;font-size:12px;color:var(--muted)}

    .nav{display:flex;flex-direction:column;gap:6px}
    .nav a{
    padding:10px;
    display:flex;
    align-items:center;
    gap:12px;
    color:rgb(182, 215, 168);
    text-decoration:none;
    border-radius:8px;
    font-size:14px;
    background:  rgba(74, 113, 150, 1);
    border: 2px solid  rgb(182, 215, 168);
    margin-top: 20px;
    }
    .nav a:hover{background:var(--glass)}
    .nav a.active{
      background:linear-gradient(90deg, rgba(110,231,183,0.12), rgba(110,231,183,0.06));
    }
    .logo-img {
    width: 70px;
    height: 70px;
    margin: 0 px;
    transition: transform 0.3s, filter 0.3s;
}

    @media (max-width:900px){
      .sidebar{display:none}
      .app{padding:14px}
    }
  </style>
</head>
<body>
  <div class="app">
    <aside class="sidebar" id="sidebar">
      <div class="brand">
        <div class="logo"><img src="{{ asset('images/sun.png') }}" alt="Logo" class="logo-img"></div>
        <div>
          <h1>Dash Board</h1>
        </div>
      </div>

      <nav class="nav">
        <a href="#">page 1</a>
        <a href="#">page 2</a>
        <a href="#">page 3</a>
        <a href="#">page 4</a>
      </nav>
    </aside>
   <main class="main-content" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:flex-start; color:#fff;">
<h1 style="margin-top:0; font-size:28px; font-weight:700;">Caregiver, Doctor, Patients, supervisor</h1>

     <main>
      <div class="date-row">
        <div class="pill">Date</div>
        <input type="text" placeholder="MM/DD/YYYY" id="date_input">
        <div style="flex:1"></div>
      </div>

      <section class="activity-card">
        <div class="tag">Missed activity</div>

        <!-- Big light-blue bar -->
        <div class="big-bar"></div>

        <!-- Row of six checkboxes -->
        <div class="checkbox-row">
          <input type="checkbox" class="cb" id="doctor-appointment-cb" name="doctor_appointment">
          <input type="checkbox" class="cb" id="morning-meds-cb"    name="morning_meds">
          <input type="checkbox" class="cb" id="afternoon-meds-cb"  name="afternoon_meds">
          <input type="checkbox" class="cb" id="night-meds-cb"      name="night_meds">
          <input type="checkbox" class="cb" id="breakfast-cb"       name="breakfast">
          <input type="checkbox" class="cb" id="lunch-cb"           name="lunch">
        </div>

        <div class="spacer"></div>
      </section>
    </main>
<section style="width:90%; height:70vh; background: rgba(182, 215, 168, 0.68);; padding:20px; border-radius:12px; font-size:22px; font-weight:600; display:flex; flex-direction:column; gap:16px; justify-content:center; align-items:center;"">

</section>
</main>
</div>

  </div>

  <script>
    document.querySelectorAll('.nav a').forEach(a=>a.addEventListener('click', ()=>{
      document.querySelectorAll('.nav a').forEach(x=>x.classList.remove('active'));
      a.classList.add('active');
    }));
  </script>
</body>
</html>
