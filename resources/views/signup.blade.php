<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard — Side Nav</title>
  <style>
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
      background:linear-gradient(180deg,#061023 0%, #081426 100%);
      color:#e6eef8;
    }

    .app{
      display:flex;
      min-height:100vh;
      padding:24px;
      gap:24px;
      background: rgb(111, 168, 220);
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
      background:linear-gradient(135deg,var(--accent),#4dd6b1);
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
      color:inherit;
      text-decoration:none;
      border-radius:8px;
      font-size:14px;
    }
    .nav a:hover{background:var(--glass)}
    .nav a.active{
      background:linear-gradient(90deg, rgba(110,231,183,0.12), rgba(110,231,183,0.06));
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
        <div class="logo">DB</div>
        <div>
          <h1>Control Panel</h1>
          <p>Acme Analytics</p>
        </div>
      </div>

      <nav class="nav">
        <a href="#">Overview</a>
        <a href="#">Reports</a>
        <a href="#">Projects</a>
        <a href="#">Settings</a>
      </nav>
    </aside>
  </div>

  <script>
    document.querySelectorAll('.nav a').forEach(a=>a.addEventListener('click', ()=>{
      document.querySelectorAll('.nav a').forEach(x=>x.classList.remove('active'));
      a.classList.add('active');
    }));
  </script>
</body>
</html>
