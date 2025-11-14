<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Roster</title>
<style>
  :root{
    --blue-100:#d9e9fb;
    --blue-300:#9ec1e6;
    --green-200:#b9d7a9;
  }
  *{box-sizing:border-box}
  body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;}

  /* Outer frame */
  .layout{
    width:1100px; max-width:96vw;
    margin:16px auto;
    background:var(--blue-100);
    border:3px solid #c7d3e2; border-radius:6px;
    padding:18px;
    display:flex; gap:26px; align-items:flex-start;
  }

  /* Left column */
  .left{flex:1 1 auto; min-width:640px}
  .title{font-size:42px; font-weight:700; margin:6px 0 10px 6px}

  /* Date row */
  .date-row{
    display:flex; gap:10px; align-items:center;
    margin:2px 0 10px 6px;
  }
  .date-label{
    padding:6px 10px;
    border:3px solid #6c8bb5; border-radius:4px;
    background:#8fb2de; color:#0f2438; font-weight:700;
  }
  .date-input{
    height:32px; width:180px;
    border:2px solid #6c8bb5; border-radius:4px;
    background:#cfe1f6; padding:4px 8px;
  }

  /* Green panel */
  .card{
    background:var(--green-200);
    border:3px solid #739966; border-radius:6px;
    padding:16px; min-height:560px;
    display:flex; flex-direction:column; gap:16px;
  }
  .bar, .canvas{
    background:#cfe1f6;
    border:3px solid #7f93ac; border-radius:4px;
  }
  .bar{height:70px}
  .canvas{flex:1}

  /* Right rail */
  .rail{
    flex:0 0 340px;
    align-self:stretch;
    background:var(--blue-300);
    border:3px solid #7ea4c9; border-radius:6px;
    padding:18px; display:flex; align-items:flex-start;
  }
  .rail p{margin:0; font-weight:600; font-size: 20px; text-align: center;}
</style>
</head>
<body>
  <div class="layout">
    <div class="left">
      <h1 class="title">Roster</h1>

      <div class="date-row">
        <span class="date-label">Date:</span>
        <input class="date-input" type="date">
      </div>

      <section class="card">
        <div class="bar"></div>
        <div class="canvas"></div>
      </section>
    </div>

    <aside class="rail">
      <p>Everyone's dashboard</p>
    </aside>
  </div>
</body>
</html>
