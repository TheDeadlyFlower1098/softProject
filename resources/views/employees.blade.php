@extends('layouts.app')

@section('title', 'Employee')

@section('content')
<style>
    :root{
        --blue-100:#d9e9fb;
        --blue-300:#9ec1e6;
        --blue-600:#2563eb;
        --green-200:#b9d7a9;
        --green-500:#7cab5f;
        --ink:#111827;
    }

    *{ box-sizing:border-box; }

    /* OUTER LAYOUT – matches other screens */
    .layout{
        width:92%;
        max-width:1400px;
        margin:16px auto 32px;
        background:var(--blue-100);
        border:2px solid #c7d3e2;
        border-radius:8px;
        padding:20px;
        display:flex;
        gap:26px;
        align-items:flex-start;
    }

    .left{
        flex:1 1 auto;
        min-width:0;
    }

    .title{
        font-size:34px;
        font-weight:700;
        margin:0 0 14px 4px;
        color:var(--ink);
    }

    /* GREEN CARD (table area) */
    .card{
        margin:6px 4px 0;
        background:var(--green-200);
        border:2px solid #739966;
        border-radius:8px;
        padding:20px 22px;
        min-height:70vh;
        display:flex;
        flex-direction:column;
        gap:14px;
        overflow:hidden;
    }

    /* FILTER BAR */
    .filter-bar{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
        justify-content:center;
        margin-bottom:6px;
    }

    .field-input{
        height:32px;
        width:170px;
        border:2px solid #6c8bb5;
        border-radius:4px;
        background:#cfe1f6;
        padding:4px 8px;
        font-size:14px;
    }
    .filter-bar .field-input{ width:150px; }

    .btn{
        background-color:var(--green-500);
        border:2px solid #578640;
        border-radius:4px;
        padding:6px 14px;
        font-size:15px;
        font-weight:600;
        cursor:pointer;
        color:#000;
    }
    .btn:hover{
        filter:brightness(1.05);
    }

    /* TABLE GRID */
    .grid-row{
        display:grid;
        grid-template-columns:
            80px   /* ID */
            3fr    /* Name */
            1fr    /* Role */
            140px  /* Salary */
            140px; /* Action */
        gap:16px;
        width:100%;
        align-items:center;
    }

    .header-cell{
        background:#b7cce6;
        border:2px solid #7f93ac;
        border-radius:4px;
        padding:8px 10px;
        font-weight:600;
        text-align:center;
        color:#0f172a;
    }

    .table-row{ width:100%; }

    .table-cell{
        background:#fff;
        border:2px solid #7f93ac;
        border-radius:4px;
        padding:8px 10px;
        text-align:center;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
        font-size:14px;
        color:#111827;
    }

    #employeeTable{
        max-height:60vh;
        overflow-y:auto;
        padding-right:6px;
    }

    /* RIGHT SIDEBAR */
    .rail{
        flex:0 0 260px;
        background:var(--blue-300);
        border:2px solid #7ea4c9;
        border-radius:8px;
        padding:18px;
        display:flex;
        justify-content:center;
        align-items:flex-start;
    }

    .rail p{
        font-size:22px;
        font-weight:700;
        color:#0f172a;
        margin:0;
    }

    /* RESPONSIVE */
    @media (max-width:1100px){
        .layout{
            flex-direction:column;
            width:95%;
        }
    }

    @media (max-width:820px){
        .grid-row{
            grid-template-columns:
                60px
                2fr
                1fr
                110px
                110px;
            gap:10px;
        }
    }

    @media (max-width:520px){
        .layout{ padding:16px; }
        .grid-row{
            display:flex;
            flex-direction:column;
            gap:10px;
        }
        .header-cell{ display:none; }
        .table-row{
            background:#fff;
            border:2px solid #7f93ac;
            border-radius:6px;
            padding:10px;
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
        <p>Admin dashboard</p>
    </aside>

</div>

<script>
const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

// Escape text for safety (unchanged)
function escapeHtml(text){
  if (text === null || text === undefined) return '';
  return String(text).replace(/[&<>"'`=\/]/g, s => ({
    '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;',
    '`':'&#x60;','=':'&#x3D;','/':'&#x2F;'
  }[s]));
}

// Render employee rows (unchanged)
function renderEmployees(list){
  const container = document.getElementById("employeeTable");
  if (!list.length){
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
  fetch('/employees', {
      headers: { 'Accept': 'application/json' }
  })
  .then(response => {
      if (!response.ok) {
          throw new Error('Network error ' + response.status);
      }
      return response.json();
  })
  .then(payload => {
      const list = Array.isArray(payload.data) ? payload.data : [];
      renderEmployees(list);
  })
  .catch(err => {
      console.error('Error loading employees:', err);
      const container = document.getElementById("employeeTable");
      if (container) {
        container.innerHTML =
          "<div style='padding:14px;'>Error loading employees.</div>";
      }
  });
}

document.addEventListener('DOMContentLoaded', loadEmployees);

// FILTERING
document.getElementById("filterBtn").onclick = function(){
  const q = new URLSearchParams();
  if(filterId.value) q.append("id", filterId.value);
  if(filterName.value) q.append("name", filterName.value);
  if(filterRole.value) q.append("role", filterRole.value);
  if(filterMinSalary.value) q.append("min_salary", filterMinSalary.value);
  if(filterMaxSalary.value) q.append("max_salary", filterMaxSalary.value);

  fetch(`/employees/filter?${q.toString()}`)
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
    const raw = prompt("Enter new salary:", currentSalary ?? "");
    if (raw === null) return; // user hit cancel

    const trimmed = raw.trim();
    if (trimmed === "") {
        alert("Salary cannot be empty. Enter a number like 45000.");
        return;
    }

    const salary = Number(trimmed);
    if (Number.isNaN(salary)) {
        alert("Please enter a numeric salary only (e.g. 45000).");
        return;
    }

    fetch(`/employees/${id}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({ salary })
    })

    .then(async r => {
        if (!r.ok) {
            let msg = "Error updating salary.";

            // Try to extract validation errors from Laravel (422)
            try {
                const data = await r.json();
                if (data && data.errors && data.errors.salary) {
                    msg = data.errors.salary.join("\n");
                }
            } catch (e) {}

            throw new Error(msg);
        }
        return r.json();
    })
    .then(() => {
        alert("Salary updated");
        loadEmployees();
    })
    .catch(err => alert(err.message));
}
@else
function editSalary(){
    alert("Only admins can modify salaries.");
}
@endif
</script>
@endsection
