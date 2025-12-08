<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Caregiver Dashboard</title>
  <style>
    :root {
      --bg: #0f1724;
      --muted: #9aa7bd;
      --accent: #6ee7b7;
    }

    body {
      margin: 0;
      font-family: Inter, ui-sans-serif, system-ui;
      background: rgba(84, 128, 170, 1);
      color: #e6eef8;
    }

    .app {
      display: flex;
      min-height: 100vh;
      padding: 24px;
      gap: 24px;
    }

    .sidebar {
      width: 260px;
      background: rgb(111, 168, 220);
      border-radius: 12px;
      padding: 18px;
      display: flex;
      flex-direction: column;
      gap: 18px;
    }

    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      color: #fff;
    }

    .caregiver-box {
      width: 90%;
      margin: 20px auto;
      background: rgb(182, 215, 168);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0,0,0,0.12);
    }

    .caregiver-box h2 {
      margin-top: 0;
      text-align: center;
      color: rgb(60, 120, 216);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      font-size: 16px;
      text-align: center;
    }

    th, td {
      padding: 10px;
      border: 1px solid rgba(0,0,0,0.2);
    }

    select {
      padding: 4px;
      border-radius: 6px;
      border: none;
    }

    button {
      margin-top: 15px;
      padding: 10px 20px;
      background: #6ee7b7;
      color: #022;
      border-radius: 8px;
      font-weight: 600;
      border: none;
      cursor: pointer;
    }

    @media (max-width: 900px) {
      .sidebar { display: none; }
      .app { padding: 14px; }
    }
  </style>
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand">
        <div class="logo">
          <img src="images/sun.png" alt="Logo" style="width:70px; height:70px;">
        </div>
        <div>
          <h1>Caregiver</h1>
        </div>
      </div>
      <nav class="nav">
        <a href="#">Dashboard</a>
        <a href="#">Patients</a>
        <a href="#">Reports</a>
      </nav>
    </aside>

    <main class="main-content">
      <div class="caregiver-box">
        <h2>Patient Daily Medication & Meals</h2>
        <form action="#" method="POST">
          <table>
            <thead>
              <tr>
                <th>Medication / Meal</th>
                <th>Morning</th>
                <th>Afternoon</th>
                <th>Night</th>
              </tr>
            </thead>
            <tbody>
              <!-- Medications -->
              <tr>
                <td>Medication A</td>
                <td>
                  <select name="medA_morning">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </td>
                <td>
                  <select name="medA_afternoon">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </td>
                <td>
                  <select name="medA_night">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </td>
              </tr>

              <tr>
                <td>Medication B</td>
                <td>
                  <select name="medB_morning">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </td>
                <td>
                  <select name="medB_afternoon">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </td>
                <td>
                  <select name="medB_night">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </td>
              </tr>

              <!-- Meals -->
              <tr>
                <td>Breakfast</td>
                <td>
                  <select name="breakfast_morning">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </td>
                <td>-</td>
                <td>-</td>
              </tr>

              <tr>
                <td>Lunch</td>
                <td>-</td>
                <td>
                  <select name="lunch_afternoon">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </td>
                <td>-</td>
              </tr>

              <tr>
                <td>Dinner</td>
                <td>-</td>
                <td>-</td>
                <td>
                  <select name="dinner_night">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>

          <button type="submit">Save Daily Report</button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
