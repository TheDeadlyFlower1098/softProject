<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Employee</title>
<style>
  :root{
    --blue-100:#d9e9fb;
    --blue-300:#9ec1e6;
    --green-200:#b9d7a9;
    --green-500:#7cab5f;
  }
  *{box-sizing:border-box}
  body{
    margin:0;
    font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
    background:white;
  }

  /* outer frame */
  .layout{
    width:1100px; max-width:96vw;
    margin:16px auto;
    background:var(--blue-100);
    border:3px solid #c7d3e2; border-radius:6px;
    padding:18px;
    display:flex; gap:26px; align-items:flex-start;
  }

  /* left side ====================================================== */
  .left{flex:1 1 auto; min-width:640px}

  .title{
    font-size:42px; font-weight:700;
    margin:6px 0 15px 6px;
  }
  
  .subtitle{
    font-size:20px; font-weight:600;
    margin:4px 0 12px 6px;
  }

  /* row with Emp ID / New Salary / buttons */
  .edit-row{
    display:flex;
    gap:14px;
    align-items:flex-end;
    margin:0 0 16px 6px;
  }

  .field-group{
    display:flex;
    flex-direction:column;
    gap:4px;
  }
  .field-label{
    padding:6px 10px;
    border:3px solid #6c8bb5;
    border-radius:4px;
    background:#8fb2de;
    font-weight:600;
  }
  .field-input{
    height:32px;
    width:160px;
    border:2px solid #6c8bb5;
    border-radius:4px;
    background:#cfe1f6;
    padding:4px 8px;
  }

  .btn{
    background-color:var(--green-500);
    border:2.5px solid #578640;
    border-radius:4px;
    padding:6px 14px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
  }
  .btn + .btn{ margin-left:4px; }
  .btn:hover{ filter:brightness(1.05); }

  /* big green content card ======================================== */
  .card{
    margin:10px 6px 0;
    background:var(--green-200);
    border:3px solid #739966;
    border-radius:6px;
    padding:18px;
    min-height:420px;
    display:flex;
    flex-direction:column;
    gap:12px;
  }

  .header-row{
    display:flex;
    gap:24px;
    justify-content: center;
  }
  .header-cell{
    min-width:80px;
    text-align:center;
    padding:6px 10px;
    background:#cfe1f6;
    border:3px solid #7f93ac;
    border-radius:4px;
    font-weight:600;
  }

  .content-area{
    flex:1;
    margin-top:8px;
    background:transparent; /* keep inside green, you can add rows later */
  }

  /* right rail ===================================================== */
  .rail{
    flex:0 0 340px;
    align-self:stretch;
    background:var(--blue-300);
    border:3px solid #7ea4c9;
    border-radius:6px;
    padding:18px;
    display:flex;
    align-items:flex-start;
    justify-content: center;
  }
  .rail p{
    margin:0;
    font-weight:700;
    font-size:26px;
  }
</style>
</head>
<body>
  <div class="layout">
    <!-- LEFT MAIN CONTENT -->
    <section class="left">
      <h1 class="title">Employee</h1>
      <div class="subtitle">Edit Salary:</div>

      <!-- Emp ID / New Salary / update / cancel on ONE LINE -->
      <div class="edit-row">
        <div class="field-group">
          <span class="field-label">Emp ID:</span>
          <input type="text" class="field-input">
        </div>

        <div class="field-group">
          <span class="field-label">New Salary:</span>
          <input type="text" class="field-input">
        </div>

        <button class="btn">update</button>
        <button class="btn">cancel</button>
      </div>

      <!-- BIG GREEN BOX -->
      <div class="card">
        <div class="header-row">
          <div class="header-cell">ID</div>
          <div class="header-cell">Name</div>
          <div class="header-cell">Role</div>
          <div class="header-cell">Salary</div>
        </div>

        <!-- placeholder for table/rows later -->
        <div class="content-area"></div>
      </div>
    </section>

    <!-- RIGHT ADMIN DASHBOARD -->
    <aside class="rail">
      <p>Admin Dashboard</p>
    </aside>
  </div>
</body>
</html>
