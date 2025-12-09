@extends('layouts.app')

@section('title', 'Doctor Appointments')

@section('content')
<style>
  main {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .container {
    width: 100%;
    max-width: 700px;
    padding: 20px;
    background-color: #6791c3ff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(66, 104, 122, 1);
  }

  h1, h2 { text-align: center; color: #e6eef8; }

  form {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
  }

  input, select, textarea {
    padding: 10px;
    border-radius: 8px;
    border: none;
    font-size: 16px;
    background: rgba(255,255,255,0.1);
    color: #fff;
  }

  button {
    padding: 12px;
    background-color: #4ade80;
    color: #0f1724;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
  }
  button:hover { background-color: #22c55e; }

  .appointments { margin-top: 20px; }
  .appointment {
    padding: 15px;
    background: rgba(255,255,255,0.06);
    border-left: 4px solid #6ee7b7;
    margin-bottom: 12px;
    border-radius: 10px;
    transition: transform 0.2s;
    position: relative;
  }
  .appointment:hover { transform: translateY(-2px); }

  .appointment h3 { margin: 0 0 5px 0; color: #fff; }
  .appointment p { margin: 2px 0; color: #cbd5f5; }

  .filters {
    display: grid;
    grid-template-columns: 1fr;
    gap: 8px;
    margin-bottom: 20px;
  }

  .filters label {
    font-size: 13px;
    color: #e6eef8;
    display: block;
  }

  .filters input {
    width: 100%;
  }

  @media (min-width: 600px) {
    .filters {
      grid-template-columns: repeat(3, 1fr);
    }
  }
</style>

<main>
  <div class="container">
    {{-- Filters --}}
    <h1>Appointments</h1>

    <div class="filters">
      <div>
        <label for="filterName">Search by Patient / Doctor</label>
        <input
          type="text"
          id="filterName"
          placeholder="e.g. John, Dr. Smith"
        >
      </div>

      <div>
        <label for="filterDate">Search by Date</label>
        <input
          type="text"
          id="filterDate"
          placeholder="e.g. 2025-12-09"
        >
      </div>

      <div>
        <label for="filterNotes">Search by Reason / Status</label>
        <input
          type="text"
          id="filterNotes"
          placeholder="e.g. check-up, completed"
        >
      </div>
    </div>

    {{-- Upcoming appointments --}}
    <div class="appointments" id="upcomingAppointments">
      <h1>Upcoming Appointments</h1>

      @forelse ($upcomingAppointments as $appointment)
        @php
          $patientName = $appointment->patient && $appointment->patient->user
              ? $appointment->patient->user->first_name . ' ' . $appointment->patient->user->last_name
              : 'No Patient';

          $doctorName = $appointment->doctor->name ?? 'Doctor';
          $status     = $appointment->status ?? 'scheduled';
          $notes      = $appointment->notes ?? '';
        @endphp

        <div class="appointment"
             data-patient="{{ strtolower($patientName) }}"
             data-doctor="{{ strtolower($doctorName) }}"
             data-date="{{ strtolower($appointment->date) }}"
             data-status="{{ strtolower($status) }}"
             data-notes="{{ strtolower($notes) }}">
          <h3>{{ $patientName }}</h3>
          <p><strong>Date:</strong> {{ $appointment->date }}</p>
          <p><strong>Status:</strong> {{ $status }}</p>
          @if($notes)
            <p><strong>Reason:</strong> {{ $notes }}</p>
          @endif

          <a href="{{ route('appointment.details', $appointment->id) }}"
             style="position:absolute; top:15px; right:15px; padding:6px 12px; background:#6ee7b7; color:#022; border-radius:6px; text-decoration:none; font-weight:600;">
             View Info
          </a>
        </div>
      @empty
        <p>No upcoming appointments.</p>
      @endforelse
    </div>

    {{-- Past appointments --}}
    <div class="appointments" id="pastAppointments">
      <h1>Past Appointments</h1>

      @php $pastAppointments = $pastAppointments ?? collect(); @endphp

      @forelse($pastAppointments as $appt)
        @php
          $docName   = $appt->doctor->name ?? 'No Doctor';
          $status    = $appt->status ?? '—';
          $notes     = $appt->notes ?? '';
          $patientNm = $appt->patient && $appt->patient->user
              ? $appt->patient->user->first_name . ' ' . $appt->patient->user->last_name
              : 'No Patient';
        @endphp

        <div class="appointment"
             data-patient="{{ strtolower($patientNm) }}"
             data-doctor="{{ strtolower($docName) }}"
             data-date="{{ strtolower($appt->date) }}"
             data-status="{{ strtolower($status) }}"
             data-notes="{{ strtolower($notes) }}">
          <h3>{{ $docName }}</h3>
          <p><strong>Date:</strong> {{ $appt->date }}</p>
          <p><strong>Status:</strong> {{ $status }}</p>

          @if($notes)
            <p><strong>Notes:</strong> {{ $notes }}</p>
          @endif
        </div>
      @empty
        <div class="appointment">
          <p><em>No past appointments found.</em></p>
        </div>
      @endforelse
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const filterName  = document.getElementById("filterName");
      const filterDate  = document.getElementById("filterDate");
      const filterNotes = document.getElementById("filterNotes");

      const upcomingAppointments = document.getElementById("upcomingAppointments");
      const pastAppointments     = document.getElementById("pastAppointments");

      if (!upcomingAppointments || !pastAppointments) return;

      const allAppointmentCards = [
        ...upcomingAppointments.getElementsByClassName("appointment"),
        ...pastAppointments.getElementsByClassName("appointment"),
      ];

      function applyFilters() {
        const nameFilter  = (filterName.value  || "").toLowerCase();
        const dateFilter  = (filterDate.value  || "").toLowerCase();
        const notesFilter = (filterNotes.value || "").toLowerCase();

        allAppointmentCards.forEach(card => {
          const patient = (card.dataset.patient || "").toLowerCase();
          const doctor  = (card.dataset.doctor  || "").toLowerCase();
          const date    = (card.dataset.date    || "").toLowerCase();
          const status  = (card.dataset.status  || "").toLowerCase();
          const notes   = (card.dataset.notes   || "").toLowerCase();

          // Name filter matches either patient OR doctor
          const matchesName  = !nameFilter  || patient.includes(nameFilter) || doctor.includes(nameFilter);
          const matchesDate  = !dateFilter  || date.includes(dateFilter);
          const matchesNotes = !notesFilter || notes.includes(notesFilter) || status.includes(notesFilter);

          if (matchesName && matchesDate && matchesNotes) {
            card.style.display = "block";
          } else {
            card.style.display = "none";
          }
        });
      }

      [filterName, filterDate, filterNotes].forEach(input => {
        input.addEventListener("input", applyFilters);
      });
    });
  </script>
</main>
@endsection
