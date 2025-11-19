<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>New Roster</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      height: 100vh;
      display: flex;              /* main page split: left / right */
      background-color: #cfdfee;  /* outer light blue */
      padding: 10px;
      justify-content: center;
      align-items: center;
    }

    /* LEFT MAIN AREA */
    .left-panel {
      flex: none;
      margin-right: 50px;
      display: flex;
      flex-direction: column;
    }

    .title {
      font-size: 36px;
      font-weight: bold;
      margin: 15px 0 10px 25px;
      line-height: 1.1;
    }

    .form-wrapper {
      flex: 1;
      border: 3px solid #8ba579;
      background-color: #b9d7a9;  /* green */
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 30px 40px 40px;
      max-width: 1000px;
    }

    /* "View rosters" button area */
    .view-rosters-wrapper {
      width: 100%;
      display: flex;
      justify-content: center;
      margin-bottom: 30px;
    }

    .btn-view {
      background-color: #6aa84f;
      border: 2px solid #3d6b2a;
      padding: 8px 25px;
      cursor: pointer;
      font-size: 20px;
    }

    .btn-view:hover {
      filter: brightness(1.05);
    }

    /* FIELDS AREA */
    .fields {
      display: flex;
      gap: 80px;           /* space between left & right column */
      margin-bottom: 40px;
    }

    .field-column {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }

    .field-input {
      width: 300px;        /* left column width */
      height: 50px;
      border: 2px solid #7a9bbd;
      background-color: #b7d0e5;
    }

    .field-input.large {
      width: 300px;        /* right column width */
      background-color: #d2dfeb;
    }

    /* BOTTOM BUTTONS */
    .bottom-buttons {
      margin-top: auto;
      display: flex;
      justify-content: center;
      gap: 40px;
      width: 100%;
    }

    .btn-main {
      min-width: 90px;
      padding: 8px 20px;
      background-color: #739966;
      border: 2px solid #3d6b2a;
      cursor: pointer;
      font-size: 20px;
    }

    .btn-main:hover {
      filter: brightness(1.05);
    }

    /* RIGHT SIDEBAR */
    .right-panel {
      flex: none;
      width: 400px;
      border: 3px solid #91a8c5;
      background-color: #9fbadf;  /* darker blue block */
    }

    /* optional: keep right panel full height minus body padding */
    .right-panel {
      min-height: calc(100vh - 20px);
    }
  </style>
</head>
<body>
  <!-- LEFT SIDE -->
  <div class="left-panel">
    <div class="title">
      <p style="text-align: center;">New Roster</p>
    </div>

    <div class="form-wrapper">
      <div class="view-rosters-wrapper">
        <button class="btn-view">View rosters</button>
      </div>

      <div class="fields">
        <div class="field-column">
          <input class="field-input" type="text">
          <input class="field-input" type="text">
          <input class="field-input" type="text">
          <input class="field-input" type="text">
          <input class="field-input" type="text">
          <input class="field-input" type="text">
          <input class="field-input" type="text">
        </div>

        <div class="field-column">
          <input class="field-input large" type="text">
          <input class="field-input large" type="text">
          <input class="field-input large" type="text">
          <input class="field-input large" type="text">
          <input class="field-input large" type="text">
          <input class="field-input large" type="text">
          <input class="field-input large" type="text">
        </div>
      </div>

      <div class="bottom-buttons">
        <button class="btn-main">create</button>
        <button class="btn-main">Cancel</button>
      </div>
    </div>
  </div>

  <!-- RIGHT SIDE BAR -->
  <div class="right-panel"></div>
</body>
</html>
