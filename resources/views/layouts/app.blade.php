<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Dashboard')</title>

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
      background:rgba(74, 113, 150, 1);
      border:2px solid rgb(182, 215, 168);
      margin-top:20px;
    }

    .nav a:hover{background:var(--glass)}
    .nav a.active{
      background:linear-gradient(90deg, rgba(110,231,183,0.12), rgba(110,231,183,0.06));
    }

    .logo-img{
      width:70px;
      height:70px;
      transition:transform 0.3s, filter 0.3s;
    }

    @media (max-width:900px){
      .sidebar{display:none}
      .app{padding:14px}
    }
  </style>
</head>

<body>
  <div class="app">

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
      <div class="brand">
        <div class="logo">
          <img src="{{ asset('images/sun.png') }}" alt="Logo" class="logo-img">
        </div>

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

    {{-- Main Content --}}
    <main class="main-content" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:flex-start; color:#fff;">
      @yield('content')
    </main>

  </div>

  <script>
    document.querySelectorAll('.nav a').forEach(a => a.addEventListener('click', () => {
      document.querySelectorAll('.nav a').forEach(x => x.classList.remove('active'));
      a.classList.add('active');
    }));
  </script>
</body>
</html>
