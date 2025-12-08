@extends('layouts.app')

@section('title', 'Admin Report')

@section('content')
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

  /* General Title */
  h1{
    text-align:center;
    margin:20px 0 30px;
    font-size:32px;
    font-weight:800;
    color:#0d1b2a;
  }

  /* Outer Layout */
  .layout{
    width:92%;
    margin:0 auto 40px;
    padding:28px;
    border:2px solid #b9c6d8;
    border-radius:12px;
    background:var(--blue-100);
    display:grid;
    grid-template-columns:1fr 330px;
    gap:32px;
    box-shadow:0 4px 14px rgba(0,0,0,0.12);
  }

  /* Right Sidebar */
  .right-rail{
    background:var(--blue-300);
    border-radius:12px;
    padding:22px 20px;
    min-height:520px;
    box-shadow:0 4px 10px rgba(0,0,0,0.15);
  }

  /* Summary box makeover */
  .summary-box{
    background:white;
    border-radius:12px;
    padding:20px 18px;
    border:1px solid #b5c5d4;
    line-height:1.8;
    color:#000 !important;
    box-shadow:0 3px 8px rgba(0,0,0,0.08);
    font-size:16px;
  }

  .summary-box strong{
    color:#0d1b2a !important;
    font-weight:700;
  }

  .summary-box span{
    font-weight:600;
    color:#000 !important;
  }

  /* Pills & inputs */
  .pill{
    background:var(--blue-600);
    color:white;
    padding:8px 22px;
    border-radius:8px;
    font-weight:700;
    letter-spacing:0.3px;
  }

  .date-row{
    display:flex;
    gap:18px;
    margin-bottom:24px;
    align-items:center;
  }

  input[type="date"],
  input[type="text"]{
    padding:10px 12px;
    border:1px solid #8fa3bf;
    border-radius:8px;
    background:#eef4ff;
    font-size:16px;
    transition:0.2s;
  }

  input[type="date"]:focus,
  input[type="text"]:focus{
    outline:none;
    border-color:var(--blue-600);
    background:white;
  }

  /* Search spacing */
  .search-row{
    margin-bottom:22px;
  }

  /* MAIN CARD (Missed Activity Box) */
  .activity-card{
    background:var(--green-200);
    padding:22px 20px;
    border-radius:12px;
    position:relative;
    box-shadow:0 3px 8px rgba(0,0,0,0.12);
  }

  .tag{
    background:var(--green-400);
    padding:8px 18px;
    border-radius:8px;
    font-weight:700;
    font-size:15px;
    color:white;
    position:absolute;
    top:-16px;
    left:18px;
    border:1px solid #6ea65b;
    box-shadow:0 2px 6px rgba(0,0,0,0.15);
  }

  /* Scrolling list */
  .missed-list{
    margin-top:45px;
    background:white;
    border-radius:12px;
    padding:18px;
    border:1px solid #b3c2d1;
    max-height:520px;
    overflow-y:auto;
    box-shadow:0 3px 8px rgba(0,0,0,0.06);
  }

  /* List items */
  .missed-item{
    padding:14px;
    border-bottom:1px solid #d6d6d6;
  }

  .missed-item:last-child{
    border-bottom:none;
  }

  .missed-name{
    font-weight:700;
    font-size:18px;
    margin-bottom:6px;
    color:#000 !important;
  }

  .caretaker-label,
  .caretaker-text{
    color:#000 !important;
    font-size:15px;
  }

  /* Status styling */
  .status-none{
    color:var(--red-400);
    font-weight:700;
  }
  .status-partial{
    color:var(--orange-400);
    font-weight:700;
  }
  .status-complete{
    color:#2e7d32;
    font-weight:700;
  }

  .date-label{
    color:#555;
    font-size:14px;
    margin-bottom:6px;
  }

  /* Badges */
  .badge{
    display:inline-block;
    padding:5px 12px;
    border-radius:8px;
    font-weight:700;
    font-size:13px;
    margin-right:6px;
    margin-bottom:6px;
    box-shadow:0 2px 4px rgba(0,0,0,0.1);
  }

  .badge-taken{
    background:#8fbd75;
    color:white;
  }

  .badge-missed{
    background:#e06f6f;
    color:white;
  }

  .badge-unknown{
    background:#ccc;
    color:#333;
  }

</style>

<h1>Admin & Supervisor Report</h1>

<div class="layout">

  <!-- MAIN CONTENT -->
  <main>

    <!-- Date Picker -->
    <div class="date-row">
      <div class="pill">Date</div>
      <input type="date" 
        id="report_date" 
        value="{{ $latestDate ? \Illuminate\Support\Carbon::parse($latestDate)->toDateString() : '' }}" 
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

      <div class="missed-list" id="missed_list">
        <div class="missed-item">Select a date to load missed activities.</div>
      </div>
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
                        <div><strong class="caretaker-label">Caretaker:</strong> <span class="caretaker-text">${m.caretaker ?? 'Unknown'}</span></div>
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
document.addEventListener('DOMContentLoaded', loadMissedActivities);
</script>
@endsection
