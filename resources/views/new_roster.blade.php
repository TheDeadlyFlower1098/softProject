@extends('layouts.app')

@section('title', 'New Roster')

@section('content')
<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
  }

  .roster-page {
    min-height: calc(100vh - 80px);
    display: flex;
    justify-content: center;
    align-items: stretch;
    background-color: #cfdfee;
    padding: 10px;
  }

  /* LEFT MAIN AREA */
  .left-panel {
    margin-right: 50px;
    display: flex;
    flex-direction: column;
  }

  .title {
    font-size: 36px;
    font-weight: bold;
    margin: 15px 0 10px 25px;
    line-height: 1.1;
    text-align: center;
  }

  .form-wrapper {
    border: 3px solid #8ba579;
    background-color: #b9d7a9;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 30px 40px 40px;
    max-width: 1000px;
    min-width: 700px;
  }

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
    font-size: 18px;
    text-decoration: none;
    color: #000;
  }

  .btn-view:hover {
    filter: brightness(1.05);
  }

  .fields {
    display: flex;
    gap: 80px;
    margin-bottom: 40px;
    width: 100%;
    justify-content: center;
  }

  .field-column {
    display: flex;
    flex-direction: column;
    gap: 18px;
  }

  .field-label {
    font-weight: 600;
    margin-bottom: 4px;
  }

  .field-input,
  .field-select {
    width: 300px;
    height: 50px;
    border: 2px solid #7a9bbd;
    background-color: #b7d0e5;
    padding: 6px 10px;
  }

  .field-input.large,
  .field-select.large {
    background-color: #d2dfeb;
  }

  .error-text {
    color: #b00020;
    font-size: 13px;
    margin-top: 3px;
  }

  .bottom-buttons {
    margin-top: auto;
    display: flex;
    justify-content: center;
    gap: 40px;
    width: 100%;
  }

  .btn-main {
    min-width: 110px;
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
    width: 400px;
    border: 3px solid #91a8c5;
    background-color: #9fbadf;
    min-height: calc(100vh - 20px);
  }
</style>

<div class="roster-page">
  <!-- LEFT SIDE -->
  <div class="left-panel">
    <div class="title">
      New Roster
    </div>

  @if (session('status'))
    <div class="alert alert-success" style="margin: 0 0 10px 6px;">
        {{ session('status') }}
    </div>
  @endif


    <form class="form-wrapper" method="POST" action="{{ route('roster.store') }}">
      @csrf

      {{-- top "View rosters" button --}}
      <div class="view-rosters-wrapper">
        <a href="{{ route('roster.dashboard', ['date' => $date]) }}" class="btn-view">
          View Rosters
        </a>
      </div>

      {{-- fields --}}
      <div class="fields">
        {{-- LEFT COLUMN --}}
        <div class="field-column">
          {{-- Date --}}
          <div>
            <div class="field-label">Date</div>
            <input
              class="field-input"
              type="date"
              name="date"
              value="{{ old('date', $date) }}"
            >
            @error('date')
              <div class="error-text">{{ $message }}</div>
            @enderror
          </div>

          {{-- Supervisor --}}
          <div>
            <div class="field-label">Supervisor</div>
            <select name="supervisor_id" class="field-select">
              <option value="">-- Select Supervisor --</option>
              @foreach($supervisors as $sup)
                <option value="{{ $sup->id }}"
                  @selected(old('supervisor_id', optional($existing)->supervisor_id) == $sup->id)>
                  {{ $sup->name }}
                </option>
              @endforeach
            </select>
            @error('supervisor_id')
              <div class="error-text">{{ $message }}</div>
            @enderror
          </div>

          {{-- Doctor --}}
          <div>
            <div class="field-label">Doctor</div>
            <select name="doctor_id" class="field-select">
              <option value="">-- Select Doctor --</option>
              @foreach($doctors as $doc)
                <option value="{{ $doc->id }}"
                  @selected(old('doctor_id', optional($existing)->doctor_id) == $doc->id)>
                  {{ $doc->name }}
                </option>
              @endforeach
            </select>
            @error('doctor_id')
              <div class="error-text">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- RIGHT COLUMN: groups 1–4 --}}
        <div class="field-column">
          @php
            $groupFields = [
              ['label' => 'Group 1 Caregiver', 'name' => 'caregiver_1'],
              ['label' => 'Group 2 Caregiver', 'name' => 'caregiver_2'],
              ['label' => 'Group 3 Caregiver', 'name' => 'caregiver_3'],
              ['label' => 'Group 4 Caregiver', 'name' => 'caregiver_4'],
            ];
          @endphp

          @foreach($groupFields as $gf)
            @php
              $field = $gf['name'];
              $existingValue = optional($existing)->{$field};
            @endphp
            <div>
              <div class="field-label">{{ $gf['label'] }}</div>
              <select name="{{ $field }}" class="field-select large">
                <option value="">-- Select Caregiver --</option>
                @foreach($caregivers as $cg)
                  <option value="{{ $cg->id }}"
                    @selected(old($field, $existingValue) == $cg->id)>
                    {{ $cg->name }}
                  </option>
                @endforeach
              </select>
              @error($field)
                <div class="error-text">{{ $message }}</div>
              @enderror
            </div>
          @endforeach
        </div>
      </div>

      {{-- bottom buttons --}}
      <div class="bottom-buttons">
        <button type="submit" class="btn-main">
          {{ $existing ? 'Update' : 'Create' }}
        </button>

        <a href="{{ route('roster.dashboard', ['date' => $date]) }}" class="btn-main" style="text-align:center; line-height:34px;">
          Cancel
        </a>
      </div>
    </form>
  </div>

  <!-- RIGHT SIDE BAR -->
  <div class="right-panel"></div>
</div>
@endsection
