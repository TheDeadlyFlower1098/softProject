<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Roles</title>

  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      background: #ffffff;
    }

    .page {
      width: 950px;
      min-height: 550px;
      background: #d6e6f7; /* light blue */
      padding: 30px 35px;
      margin-top: 20px;
    }

    .title {
      font-size: 48px;
      margin-bottom: 25px;
    }

    .layout {
      display: flex;
      gap: 30px;
      align-items: stretch;
    }

    /* Left big green panel */
    .left-panel {
      flex: 1 1 0;
      background: #bfddb1; /* light green */
      border: 1px solid #8aa172;
      padding: 40px 50px;
      display: flex;
      flex-direction: column;
      gap: 40px;
    }

    /* Right tall blue admin box */
    .right-panel {
      width: 200px;
      background: #a7bddd;
      border: 1px solid #6f7fa2;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding-top: 40px;
      font-size: 22px;
      text-align: center;
      line-height: 1.4;
    }

    /* Header row for TABLE HEADINGS */
    .header-row {
      display: flex;
      justify-content: flex-start;
      gap: 20px;
      width: 100%;
    }

    .header-box {
      flex: 1 1 0;
      height: 80px;
      background: #c9d8ec;
      border: 1px solid #6f7fa2;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 22px;
      font-weight: bold;
    }

    /* Form rows */
    .form-row {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .label-box {
      background: #9bbf61;
      border: 1px solid #6e8642;
      padding: 10px 18px;
      min-width: 130px;
      text-align: center;
      font-size: 16px;
      height: 40px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .input-box {
      width: 180px;
      height: 40px;
      border: 1px solid #6e8642;
      background: #d6e6b7;
    }

  </style>
</head>

<body>
  <div class="page">
    <h1 class="title">Roles</h1>

    <div class="layout">
      
      <!-- LEFT MAIN PANEL -->
      <div class="left-panel">

        <!-- Top table-like header -->
        <div class="header-row">
          <div class="header-box">ROLE</div>
          <div class="header-box">ACCESS LEVEL</div>
        </div>

        <!-- New role input rows -->
        <div class="form-row">
          <div class="label-box">NEW ROLE</div>
          <input class="input-box" type="text">
        </div>

        <div class="form-row">
          <div class="label-box">ACCESS LEVEL</div>
          <input class="input-box" type="text">
        </div>

      </div>

      <!-- RIGHT ADMIN-ONLY PANEL -->
      <div class="right-panel">
        ADMIN<br>ONLY
      </div>

    </div>
  </div>
</body>
</html>
