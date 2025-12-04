<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Report</title>

  <style>
    :root{
      --blue-100:#d9e9fb;
      --blue-300:#9ec1e6;
      --blue-600:#2563eb;
      --green-200:#b9d7a9;
      --green-400:#8fbd75;
      --red-400:#e06f6f;
      --orange-400:#f0ad4e;
      --ink:#111827;
    }

    body{margin:0;font-family:Arial;background:#fff;color:var(--ink)}
    h1{text-align:center;margin:20px 0}

    .layout{
      width:92%;margin:0 auto 40px;padding:24px;
      border:2px solid #b9c6d8;border-radius:8px;
      background:var(--blue-100);
      display:grid;grid-template-columns:1fr 320px;gap:28px;
    }

    .right-rail{
      background:var(--blue-300);
      border-radius:8px;
      padding:20px;
      min-height:520px;
    }

    .summary-box{
      background:white;border-radius:8px;padding:14px;
      border:1px solid #b5c5d4;
      line-height:1.6;
    }

    .pill{background:var(--blue-600);color:white;padding:8px 22px;border-radius:6px;font-weight:bold;}
    .date-row{display:flex;gap:16px;margin-bottom:20px;align-items:center;}
    input[type="date"], input[type="text"]{
      padding:8px;border:1px solid #8fa3bf;border-radius:6px;background:#eef4ff;font-size:16px;
    }

    .search-row{margin-bottom:20px;}

    .activity-card{
      background:var(--green-200);padding:18px;border-radius:6px;position:relative;
    }

    .tag{
      background:var(--green-400);
      padding:8px 14px;border-radius:6px;font-weight:bold;
      position:absolute;top:-14px;left:16px;border:1px solid #6ea65b;
    }

    .missed-list{
      margin-top:40px;
      background:white;border-radius:8px;padding:16px;border:1px solid #b3c2d1;
      max-height:520px;overflow-y:auto;
    }

    .missed-item{
      padding:12px;border-bottom:1px solid #ddd;
    }
    .missed-item:last-child{border-bottom:none;}

    .missed-name{font-weight:bold;font-size:17px;margin-bottom:6px;}

    .status-none{color:var(--red-400);font-weight:bold;}
    .status-partial{color:var(--orange-400);font-weight:bold;}
    .status-complete { color: #4caf50; font-weight:bold; }
    .date-label{color:#666;font-size:14px;margin-bottom:6px;}

    .badge{
      display:inline-block;padding:4px 10px;border-radius:6px;font-weight:bold;margin-right:6px;margin-bottom:4px;font-size:14px;
    }

    .badge-taken{background:#8fbd75;color:white;}
    .badge-missed{background:#e06f6f;color:white;}
    .badge-unknown{background:#ccc;color:#333;}
  </style>

  <script>
function loadMissedActivities() {
    let date = document.getElementById("report_date").value;
    let search = document.getElementById("filter_input").value;
    let showAll = document.getElementById("show_all_toggle").checked;

    const url = `/admin-report/data?date=${date}&filter=${search}&all=${showAll}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            // Update Summary
            document.getElementById("summary_total").innerText = data.summary.total_patients;
            document.getElementById("summary_full").innerText = data.summary.fully_missed;
            document.getElementById("summary_partial").innerText = data.summary.partial_missed;
            document.getElementById("summary_completed").innerText = data.summary.fully_completed ?? 0;

            // Update Missed List
            let list = document.getElementById("missed_list");
            list.innerHTML = "";

            if (!data.report || data.report.length === 0) {
                list.innerHTML = "<div class='missed-item'>No records found 🎉</div>";
                return;
            }

            data.report.forEach(m => {
                let statusClass = m.status === "complete" ? "status-complete" :
                                  m.status === "none" ? "status-none" :
                                  "status-partial";

                let statusText = m.status === "complete" ? "All checks done ✅" :
                                 m.status === "none" ? "All checks missing" :
                                 "Some checks missing";

                // Medications badges
                let medBadges = `
                    <div>
                        <span class="badge ${m.morning === 'taken' ? 'badge-taken' : m.morning === 'missed' ? 'badge-missed' : 'badge-unknown'}">Morning: ${m.morning ?? 'unknown'}</span>
                        <span class="badge ${m.afternoon === 'taken' ? 'badge-taken' : m.afternoon === 'missed' ? 'badge-missed' : 'badge-unknown'}">Afternoon: ${m.afternoon ?? 'unknown'}</span>
                        <span class="badge ${m.night === 'taken' ? 'badge-taken' : m.night === 'missed' ? 'badge-missed' : 'badge-unknown'}">Night: ${m.night ?? 'unknown'}</span>
                    </div>`;

                // Meals badges
                let mealBadges = `
                    <div style="margin-top:6px;">
                        <span class="badge ${m.breakfast === 'taken' ? 'badge-taken' : m.breakfast === 'missed' ? 'badge-missed' : 'badge-unknown'}">Breakfast: ${m.breakfast ?? 'unknown'}</span>
                        <span class="badge ${m.lunch === 'taken' ? 'badge-taken' : m.lunch === 'missed' ? 'badge-missed' : 'badge-unknown'}">Lunch: ${m.lunch ?? 'unknown'}</span>
                        <span class="badge ${m.dinner === 'taken' ? 'badge-taken' : m.dinner === 'missed' ? 'badge-missed' : 'badge-unknown'}">Dinner: ${m.dinner ?? 'unknown'}</span>
                    </div>`;

                list.innerHTML += `
                    <div class="missed-item">
                        <div class="missed-name">${m.patient}</div>
                        <div><strong>Caretaker:</strong> ${m.caretaker ?? 'Unknown'}</div>
                        <div class="${statusClass}">${statusText}</div>
                        <div class="date-label">Date: ${m.date ?? 'No record'}</div>
                        ${medBadges}
                        ${mealBadges}
                    </div>`;
            });
        })
        .catch(err => {
            console.error("Error loading report:", err);
        });
}

// Load on page load
window.onload = function() {
    loadMissedActivities();
};
</script>


</head>

<body>
<h1>Admin & Supervisor Report</h1>

<div class="layout">

  <!-- MAIN CONTENT -->
  <main>

    <!-- Date Picker -->
    <div class="date-row">
      <div class="pill">Date</div>
      <input type="date" 
       id="report_date" 
       value="{{ $latestDate ?? '' }}" 
       onchange="loadMissedActivities()">

      <!-- Show all dates toggle -->
      <label>
        <input type="checkbox" id="show_all_toggle" onchange="loadMissedActivities()"> Show all dates
      </label>
    </div>

    <!-- Search bar -->
    <div class="search-row">
      <input type="text" id="filter_input" placeholder="Search by name, ID, status, or date..."
             onkeyup="loadMissedActivities()" style="width:300px;">
    </div>

    <!-- Activity Card -->
    <section class="activity-card">
      <div class="tag">Missed activity</div>

        <!-- Big light-blue bar -->
        <div class="big-bar"></div>

        <!-- Row of six checkboxes -->
        <div class="checkbox-row">
          <input type="checkbox" class="cb" id="doctor-appointment-cb" name="doctor_appointment">
          <input type="checkbox" class="cb" id="morning-meds-cb"    name="morning_meds">
          <input type="checkbox" class="cb" id="afternoon-meds-cb"  name="afternoon_meds">
          <input type="checkbox" class="cb" id="night-meds-cb"      name="night_meds">
          <input type="checkbox" class="cb" id="breakfast-cb"       name="breakfast">
          <input type="checkbox" class="cb" id="lunch-cb"           name="lunch">
        </div>

        <div class="spacer"></div>
      </section>
    </main>

  <!-- RIGHT SIDEBAR -->
  <aside class="right-rail">
    <h3 style="text-align:center;margin-top:0;">Summary</h3>
    <div class="summary-box">
      <strong>Total Missed:</strong> <span id="summary_total">0</span><br>
      <strong>Fully Missed:</strong> <span id="summary_full">0</span><br>
      <strong>Partially Missed:</strong> <span id="summary_partial">0</span><br>
      <strong>Fully Completed:</strong> <span id="summary_completed">0</span><br>

    </div>
  </aside>

</div>

<script>
    window.onload = () => loadMissedActivities();
</script>

</body>
</html>

