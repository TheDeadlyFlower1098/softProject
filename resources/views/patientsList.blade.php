<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Patients</title>
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
  .title{font-size:42px; font-weight:700; margin:6px 0 14px 6px}

  /* Green content card */
  .card{
    background:var(--green-200);
    border:3px solid #739966; border-radius:6px;
    padding:18px; min-height:560px;
    display:flex; flex-direction:column; gap:18px;
  }

  /* Search bar */
  .search{
    background:var(--blue-100);
    border:3px solid #7f93ac; border-radius:6px;
    height:54px;
  }

  /* Columns area */
  .columns{
    flex:1;
    display:flex; gap:18px; align-items:stretch;
  }
  .col{
    background:var(--blue-100);
    border:3px solid #7f93ac; border-radius:6px;
    flex:1 1 0;
    min-width:90px;    
  }

  /* Right rail */
  .rail{
    flex:0 0 360px;
    align-self:stretch;
    background:var(--blue-300);
    border:3px solid #7ea4c9; border-radius:6px;
    padding:18px;
    display:flex; align-items:flex-start;
    font-size: 20px;
  }
  .rail p{margin:0; line-height:1.4}
</style>
</head>
<body>
  <div class="layout">
    <div class="left">
      <h1 class="title">Patients</h1>

      <section class="card">
        <div class="search"></div>

        <div class="columns">
          <div class="col"></div>
          <div class="col"></div>
          <div class="col"></div>
          <div class="col"></div>
          <div class="col"></div>
          <div class="col"></div>
        </div>
      </section>
    </div>

    <aside class="rail">
      <p>Patients, admin, supervisor,<br>doctors, caregiver dashboard</p>
    </aside>
  </div>
</body>
</html>
