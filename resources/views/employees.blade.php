@extends('layouts.app')

@section('title', 'Employee')

@section('content')

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
*{box-sizing:border-box;}
body{
  margin:0;
  font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
  background:white;
}

/* ============================================================
   PAGE LAYOUT
   ============================================================ */
.layout{
  width:1400px;
  max-width:98vw;
  margin:16px auto;
  background:var(--blue-100);
  border:3px solid #c7d3e2;
  border-radius:6px;
  padding:18px;
  display:flex;
  gap:26px;
  align-items:flex-start;
}

/* Main left area */
.left{
  flex:1 1 auto;
  min-width:800px;
}

/* Titles */
.title{
  font-size:42px;
  font-weight:700;
  margin:6px 0 15px 6px;
}
.subtitle{
  font-size:20px;
  font-weight:600;
  margin:4px 0 12px 6px;
}

/* Inputs & Buttons */
.field-group{
  display:flex;
  flex-direction:column;
  gap:4px;
}
.field-input{
  height:32px;
  width:180px;
  border:2px solid #6c8bb5;
  border-radius:4px;
  background:#cfe1f6;
  padding:4px 8px;
  font-size:15px;
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
.btn:hover{
  filter:brightness(1.05);
}

/* ============================================================
   GREEN CARD - MAIN TABLE AREA
   ============================================================ */
.card{
  margin:10px 6px 0;
  background:var(--green-200);
  border:3px solid #739966;
  border-radius:6px;
  padding:24px;
  min-height:70vh;
  width:100%;
  display:flex;
  flex-direction:column;
  gap:16px;
  overflow:hidden;
}

/* ============================================================
   FILTER BAR
   ============================================================ */
.filter-bar{
  display:flex;
  gap:14px;
  flex-wrap:wrap;
  justify-content:center;
  margin-bottom:4px;
}
.filter-bar .field-input{
  width:160px;
}

/* ============================================================
   TABLE GRID SYSTEM
   ============================================================ */
.grid-row{
  display:grid;
  grid-template-columns:
      80px   /* ID */
      3fr    /* Name */
      1fr    /* Role */
      140px  /* Salary */
      140px; /* Action */
  gap:18px;
  width:100%;
  align-items:center;
}

/* Header cells */
.header-cell{
  background:#cfe1f6;
  border:3px solid #7f93ac;
  border-radius:4px;
  padding:10px;
  font-weight:600;
  text-align:center;
}

/* Table rows */
.table-row{
  width:100%;
}
.table-cell{
  background:white;
  border:2px solid #7f93ac;
  border-radius:4px;
  padding:10px;
  text-align:center;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}

/* Scrollable employee list */
#employeeTable{
  max-height:60vh;
  overflow-y:auto;
  padding-right:6px;
}

/* ============================================================
   RIGHT SIDEBAR (Admin Dashboard)
   ============================================================ */
.rail{
  flex:0 0 240px;
  background:var(--blue-300);
  border:3px solid #7ea4c9;
  border-radius:6px;
  padding:18px;
  display:flex;
  justify-content:center;
  align-items:flex-start;
}
.rail p{
  font-size:26px;
  font-weight:700;
  margin:0;
}

/* ============================================================
   RESPONSIVE BEHAVIOR
   ============================================================ */
@media (max-width:1100px){
  .layout{
    flex-direction:column;
    width:100%;
  }
  .left{
    min-width:100%;
  }
}

@media (max-width:820px){
  .grid-row{
    grid-template-columns:
        60px
        2fr
        1fr
        100px
        100px;
    gap:12px;
  }
}

@media (max-width:520px){
  /* Convert to stacked cards */
  .grid-row{
    display:flex;
    flex-direction:column;
    gap:10px;
  }
  .header-cell{
    display:none;
  }
  .table-row{
    background:white;
    border:2px solid #7f93ac;
    border-radius:6px;
    padding:12px;
    display:flex;
    flex-direction:column;
    gap:6px;
  }
  .table-cell{
    text-align:left;
    white-space:normal;
  }
}
</style>
</head>

<body>
<div class="layout">

  <section class="left">

    <h1 class="title">Employee</h1>

    @php
      $isAdmin = auth()->check() &&
           strtolower(auth()->user()->role->name) === 'admin';

      $isSupervisor = auth()->check() &&
                strtolower(auth()->user()->role->name) === 'supervisor';

    @endphp

    <div class="card">

      <!-- FILTER BAR -->
      <div class="filter-bar">
        <input id="filterId" placeholder="ID" class="field-input">
        <input id="filterName" placeholder="Name" class="field-input">
        <input id="filterRole" placeholder="Role" class="field-input">
        <input id="filterMinSalary" placeholder="Min Salary" class="field-input">
        <input id="filterMaxSalary" placeholder="Max Salary" class="field-input">

        <button id="filterBtn" class="btn">Filter</button>
        <button id="resetBtn" class="btn">Reset</button>
      </div>

      <!-- TABLE HEADER -->
      <div class="grid-row">
        <div class="header-cell">ID</div>
        <div class="header-cell">Name</div>
        <div class="header-cell">Role</div>
        <div class="header-cell">Salary</div>
        <div class="header-cell">Action</div>
      </div>

      <!-- TABLE BODY -->
      <div id="employeeTable"></div>

    </div>
  </section>

  <aside class="rail">
    <p>Admin Dashboard</p>
  </aside>

</div>

<script>
// Escape text for safety
function escapeHtml(text){
  if(text === null || text === undefined) return '';
  return String(text).replace(/[&<>"'`=\/]/g, s => ({
    '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','`':'&#x60;','=':'&#x3D;','/':'&#x2F;'
  }[s]));
}

// Render employee rows
function renderEmployees(list){
  const container = document.getElementById("employeeTable");
  if(!list.length){
    container.innerHTML = "<div style='padding:14px;'>No employees found.</div>";
    return;
  }

  let html = "";
  list.forEach(emp => {
    html += `
      <div class="table-row grid-row">
        <div class="table-cell">${emp.id}</div>
        <div class="table-cell">${escapeHtml(emp.name)}</div>
        <div class="table-cell">${escapeHtml(emp.role)}</div>
        <div class="table-cell">$${emp.salary ?? '—'}</div>
        @if($isAdmin)
        <div class="table-cell">
          <button class="btn" onclick="editSalary(${emp.id}, '${emp.salary ?? ''}')">Edit</button>
        </div>
        @else
        <div class="table-cell">—</div>
        @endif
      </div>
    `;
  });

  container.innerHTML = html;
}

// Load employees
function loadEmployees(){
  fetch('/api/employees')
    .then(r=>r.json())
    .then(r=>renderEmployees(r.data ?? []));
}
loadEmployees();

// FILTERING
document.getElementById("filterBtn").onclick = function(){
  const q = new URLSearchParams();
  if(filterId.value) q.append("id", filterId.value);
  if(filterName.value) q.append("name", filterName.value);
  if(filterRole.value) q.append("role", filterRole.value);
  if(filterMinSalary.value) q.append("min_salary", filterMinSalary.value);
  if(filterMaxSalary.value) q.append("max_salary", filterMaxSalary.value);

  fetch(`/api/employees/filter?${q.toString()}`)
    .then(r=>r.json())
    .then(data => {
      if(!Array.isArray(data)){
        alert("Unexpected server response.");
        console.log(data);
        return;
      }
      renderEmployees(data);
    })
    .catch(()=>alert("Filter failed"));
};

document.getElementById("resetBtn").onclick = () => {
  filterId.value = "";
  filterName.value = "";
  filterRole.value = "";
  filterMinSalary.value = "";
  filterMaxSalary.value = "";
  loadEmployees();
};

// INLINE SALARY UPDATE
@if($isAdmin)
function editSalary(id, currentSalary){
    const salary = prompt("Enter new salary:", currentSalary);
    if(salary === null) return;

    fetch(`/api/employees/${id}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ salary })
    })
    .then(r => {
        if(!r.ok) throw new Error();
        return r.json();
    })
    .then(() => {
        alert("Salary updated");
        loadEmployees();
    })
    .catch(() => alert("Error updating salary"));
}
@else
function editSalary(){
    alert("Only admins can modify salaries.");
}
@endif

</script>

</body>
</html>
