@extends('layouts.app')

@section('title', 'Family Member')

@section('content')

<style>
  * {
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
  }

  /* Outer container INSIDE layouts.app main content */
  .family-page {
    width: 900px;
    min-height: 550px;
    background: #d6e6f7;           /* light blue */
    padding: 30px 40px;
    margin-top: 20px;
    margin-bottom: 40px;
    color: #000;                   /* override white from layouts.app */
  }

  /* Title */
  .family-page .title {
    font-size: 48px;
    margin-bottom: 40px;
    color: #000;
  }

  /* Top section layout */
  .family-page .top-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
  }

  /* Left column with the two rows of inputs */
  .family-page .fields {
    display: flex;
    flex-direction: column;
    gap: 25px;
  }

  /* One label + input row */
  .family-page .field-row {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  /* Green label boxes */
  .family-page .field-label {
    background: #9bbf61;
    padding: 8px 14px;
    min-width: 120px;
    text-align: center;
    border: 1px solid #6e8642;
    color: #000;
    font-weight: 600;
  }

  /* Blue inputs */
  .family-page .field-input {
    width: 180px;
    height: 30px;
    border: 1px solid #6f7fa2;
    background: #b7c9ea;
    color: #000;
    padding: 0 6px;
  }

  /* Buttons on the right */
  .family-page .buttons {
    display: flex;
    flex-direction: row;
    gap: 20px;
    margin-top: 10px;
  }

  .family-page .btn {
    background: #9bbf61;
    border: 1px solid #6e8642;
    padding: 8px 35px;
    font-size: 16px;
    cursor: pointer;
    color: #000;
    font-weight: 600;
  }

  .family-page .btn:hover {
    filter: brightness(1.05);
  }

  /* Big patient-details box */
  .family-page .patient-box {
    margin-top: 10px;
    background: #bfddb1;           /* light green */
    border: 1px solid #8aa172;
    min-height: 320px;
    padding: 30px;
    display: flex;
    align-items: flex-start;
    color: #000;
  }

  .family-page .patient-label {
    font-size: 20px;
    margin-bottom: 10px;
    font-weight: 700;
  }

  .family-page p {
    margin: 4px 0;
  }

  .family-page strong {
    font-weight: 700;
    color: #000;
  }
</style>

<div class="family-page">
  <h1 class="title">Family member</h1>

  <!-- Top area: inputs + buttons -->
  <div class="top-row">

    <!-- Left: input fields -->
    <div class="fields">
      <div class="field-row">
        <label class="field-label" for="familyCode">Family code</label>
        <input class="field-input" id="familyCode" type="text"
               value="{{ $familyLink->family_code ?? '' }}" readonly />
      </div>

      <div class="field-row">
        <label class="field-label" for="patientId">Patient ID</label>
        <input class="field-input" id="patientId" type="text"
               value="{{ $patient->patient_identifier }}" readonly />
      </div>
    </div>

    <!-- Right: buttons -->
    <div class="buttons">
      <button class="btn" onclick="window.location.reload()">ok</button>
      <button class="btn" onclick="history.back()">cancel</button>
    </div>

  </div>

  <!-- Bottom big box: PATIENT DATA GOES HERE -->
  <div class="patient-box">
    <div>
      <h2 class="patient-label">Patient Details</h2>

      <p><strong>Name:</strong> {{ $patient->patient_name }}</p>
      <p><strong>Patient ID:</strong> {{ $patient->patient_identifier }}</p>
      <p><strong>Admission Date:</strong> {{ $patient->admission_date }}</p>
      <p><strong>Group:</strong> {{ optional($patient->group)->name ?? 'N/A' }}</p>

      <br>

      <h3>Your Relationship</h3>
      <p><strong>Relation:</strong> {{ $familyLink->relation ?? 'N/A' }}</p>
      <p><strong>Family Code:</strong> {{ $familyLink->family_code ?? 'N/A' }}</p>
    </div>
  </div>
</div>

@endsection
