<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Home</title>

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
      width: 1000px;
      min-height: 560px;
      background: #d6e6f7; /* light blue */
      padding: 25px 30px;
      margin-top: 20px;
    }

    .title {
      font-size: 48px;
      margin-bottom: 20px;
    }

    .layout {
      display: flex;
      gap: 25px;
      align-items: stretch;
    }

    /* LEFT MAIN SCHEDULE AREA */
    .left-area {
      flex: 3 1 0;
      background: #bfddb1; /* light green */
      border: 1px solid #8aa172;
      padding: 25px 25px 35px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    /* date / caregiver row */
    .top-inputs {
      display: flex;
      gap: 20px;
    }

    .input-group {
      display: flex;
      align-items: center;
      gap: 8px;
      flex: 1 1 0;
    }

    .input-label {
      font-size: 16px;
    }

    .top-input {
      flex: 1 1 0;
      height: 32px;
      border: 1px solid #6f7fa2;
      background: #c9d8ec;
    }

    /* column header bar */
    .column-header {
      display: flex;
      width: 100%;
      height: 60px;
      border: 1px solid #6f7fa2;
      background: #c9d8ec;
    }

    .column-header div {
      flex: 1 1 0;
      border-right: 1px solid #6f7fa2;
      text-align: center;
      padding-top: 19px;
    }

    .column-header div:last-child {
      border-right: none;
    }

    /* grid body with vertical lines and small checkboxes */
    .grid-body {
      flex: 1 1 auto;
      display: flex;
      border-left: 1px solid #6f7fa2;
      border-right: 1px solid #6f7fa2;
      padding-top: 40px;
    }

    .grid-column {
        flex: 1 1 0;
        border-right: 1px solid #6f7fa2;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .grid-column:last-child {
        border-right: none;
    }

    /* box that visually matches the mockup */
    .grid-box {
      width: 45px;
      height: 25px;
      border: 1px solid #6f7fa2;
      background: #c9d8ec;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* actual checkbox inside the box */
    .grid-checkbox {
      width: 18px;
      height: 18px;
      cursor: pointer;
      transform: translateY(-5px);
    }

    /* RIGHT PATIENT INFO PANEL */
    .patient-panel {
      flex: 1 1 0;
      background: #a7bddd;
      border: 1px solid #6f7fa2;
      padding: 25px 25px 35px;
      display: flex;
      flex-direction: column;
      gap: 25px;
    }

    .patient-title {
      font-size: 32px;
      font-weight: bold;
      color: #ffeccc;
      line-height: 1.1;
    }

    .patient-title-wrapper {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    /* simple sun icon */
    .sun {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      border: 3px solid #4f6ea2;
      position: relative;
    }

    .sun::before,
    .sun::after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      width: 70px;
      height: 3px;
      background: #4f6ea2;
      transform: translate(-50%, -50%);
    }

    .sun::after {
      transform: translate(-50%, -50%) rotate(90deg);
    }

    .patient-input {
      width: 100%;
      height: 38px;
      border: 1px solid #4f6ea2;
      background: #dbe6f5;
      font-size: 16px;
      padding-left: 10px;
    }

  </style>
</head>

<body>
  <div class="page">
    <h1 class="title">Patient Dashboard</h1>

    <div class="layout">
      <!-- LEFT MAIN AREA -->
      <div class="left-area">
        <!-- date & caregiver -->
        <div class="top-inputs">
          <div class="input-group">
            <span class="input-label">Date:</span>
            <input class="top-input" type="text">
          </div>
          <div class="input-group">
            <span class="input-label">caregiver:</span>
            <input class="top-input" type="text">
          </div>
        </div>

        <!-- column headers -->
        <div class="column-header">
          <div>Breakfast</div>
          <div>Lunch</div>
          <div>Dinner</div>
          <div>Medicine 1</div>
          <div>Medicine 2</div>
          <div>Medicine 3</div>
        </div>

        <!-- grid body -->
        <div class="grid-body">
          <div class="grid-column">
            <input type="checkbox" class="grid-checkbox">
          </div>

          <div class="grid-column">
            <input type="checkbox" class="grid-checkbox">
          </div>

          <div class="grid-column">
            <input type="checkbox" class="grid-checkbox">
          </div>

          <div class="grid-column">
            <input type="checkbox" class="grid-checkbox">
          </div>

          <div class="grid-column">
            <input type="checkbox" class="grid-checkbox">
          </div>

          <div class="grid-column">
            <input type="checkbox" class="grid-checkbox">
          </div>
        </div>
      </div>

      <!-- RIGHT PATIENT INFO PANEL -->
      <div class="patient-panel">
        <div class="patient-title-wrapper">
          <div class="patient-title">
            Patient<br>info
          </div>
          <div class="sun"></div>
        </div>

        <input class="patient-input" placeholder="Patient Name" />
        <input class="patient-input" placeholder="Patient ID" />
      </div>
    </div>
  </div>
</body>
</html>
