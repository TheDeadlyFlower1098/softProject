<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Doctors appointment</title>
<style>
  :root{
    --blue-100:#d9e9fb;
    --blue-300:#9ec1e6;
    --green-200:#b9d7a9;
    --green-500:#7cab5f;
    --ink:#121826;
  }
  *{box-sizing:border-box}
  body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:var(--ink);}

  /* Outer frame */
  .layout{
    width: 980px;
    max-width: 96vw;
    margin: 18px auto;
    background: var(--blue-100);
    border: 3px solid #c7d3e2;
    border-radius: 6px;
    padding: 18px;
    display:flex;
    gap: 26px;                 /* main + right rail */
    align-items: flex-start;   /* keep title and rail aligned to top */
  }

  /* Left column (title + form card) */
  .left{
    flex: 1 1 auto;  
    min-width: 520px;
  }

  .title{
    font-size: 40px;
    font-weight: 700;
    margin: 10px 0 14px 6px;   /* slight indent like mock */
  }

  /* Green form card */
  .card{
    background: var(--green-200);
    border: 3px solid #739966;
    border-radius: 6px;
    padding: 20px 24px 26px;
    min-height: 530px;         /* tall like the sketch */
    display:flex;
    flex-direction: column;
    gap: 16px;
  }

  /* Labels + inputs */
  .row{
    display:flex;
    gap: 26px;
    flex-wrap: wrap;
  }
  .field{
    flex: 1 1 calc(50% - 13px);  /* two side-by-side on first row */
    min-width: 230px;
  }
  .field.full{                   /* Date & Doctor full width */
    flex-basis: 100%;
  }
  label{
    display:block;
    font-weight:700;
    margin-bottom:6px;
    text-align:left;
  }
  input[type="text"], input[type="date"]{
    width:100%;
    height:44px;
    padding:8px 10px;
    border:2px solid #7f93ac;
    border-radius:6px;
    background:#d7e7f6;
    font-size:16px;
  }

  /* Buttons row */
  .actions{
    display:flex;
    gap: 32px;
    margin-top: 18px;
  }
  .btn{
    height:44px;
    padding:0 28px;
    border:2px solid #517c3b;
    background:#7fb46a;
    border-radius:6px;
    font-weight:700;
    cursor:pointer;
  }
  .btn:active{ transform: translateY(1px); }

  /* Right rail */
  .rail{
    flex: 0 0 320px;            
    align-self: stretch;    
    background: var(--blue-300);
    border: 3px solid #7ea4c9;
    border-radius: 6px;
    padding: 20px;
    display:flex;
    align-items:flex-start;
  }
  .rail h2{
    margin: 6px 0 0;
    color:#57a433;
    font-size: 28px;
    line-height: 1.05;
  }
</style>
</head>
<body>
  <div class="layout">
    <div class="left">
      <h1 class="title">Doctors appointment</h1>

      <section class="card">
        <div class="row">
          <div class="field">
            <label for="pid">Patient ID</label>
            <input type="text" id="pid">
          </div>
          <div class="field">
            <label for="pname">Patient Name</label>
            <input type="text" id="pname">
          </div>

          <div class="field full">
            <label for="date">Date</label>
            <input type="text" id="date">
            <!-- Or: <input type="date" id="date"> -->
          </div>

          <div class="field full">
            <label for="doctor">Doctor</label>
            <input type="text" id="doctor">
          </div>
        </div>

        <div class="actions">
          <button class="btn">schedule</button>
          <button class="btn">cancel</button>
        </div>
      </section>
    </div>

    <aside class="rail">
      <h2>Admin<br>dashboard</h2>
    </aside>
  </div>
</body>
</html>
