@extends('layouts.app')

@section('title', 'New Roster')

@section('content')
<style>
    :root{
        --blue-100:#d9e9fb;
        --blue-300:#9ec1e6;
        --blue-600:#2563eb;
        --green-200:#b9d7a9;
        --green-400:#8fbd75;
        --ink:#111827;
    }

    /* don't touch body – layouts.app handles it */
    .page-title{
        text-align:left;
        margin:10px 0 18px;
        font-size:34px;
        font-weight:700;
        color:#1f2933;
    }

    .new-roster-layout{
        width:92%;
        margin:0 auto 40px;
        padding:24px;
        border:2px solid #b9c6d8;
        border-radius:8px;
        background:var(--blue-100);
        display:grid;
        grid-template-columns:1fr 320px;
        gap:28px;
    }

    /* LEFT SIDE (form area) */
    .new-roster-main{
        display:flex;
        flex-direction:column;
    }

    .header-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:10px;
    }

    .view-rosters-btn{
        padding:8px 20px;
        border-radius:6px;
        border:2px solid #3d6b2a;
        background:#6aa84f;
        color:#000;
        text-decoration:none;
        font-weight:600;
        box-shadow:0 1px 2px rgba(0,0,0,0.15);
    }
    .view-rosters-btn:hover{
        filter:brightness(1.05);
    }

    .status-alert{
        margin-bottom:12px;
        padding:8px 12px;
        border-radius:6px;
        background:#d1f2d7;
        border:1px solid #8bc48f;
        color:#14532d;
        font-size:14px;
    }

    .roster-card{
        background:var(--green-200);
        border-radius:8px;
        border:2px solid #7ea065;
        padding:22px 24px 24px;
    }

    .form-grid{
        display:grid;
        grid-template-columns:repeat(2, minmax(0,1fr));
        column-gap:40px;
        row-gap:18px;
        margin-bottom:26px;
    }

    .field-block{
        display:flex;
        flex-direction:column;
    }

    .field-label{
        font-weight:600;
        margin-bottom:4px;
        color:#1f2933;
    }

    .field-input,
    .field-select{
        height:40px;
        border-radius:4px;
        border:2px solid #7a9bbd;
        background:#b7d0e5;
        padding:6px 10px;
        font-size:14px;
    }

    .field-select.large{
        background:#d2dfeb;
    }

    .error-text{
        margin-top:3px;
        font-size:12px;
        color:#b00020;
    }

    .actions-row{
        display:flex;
        justify-content:center;
        gap:30px;
        margin-top:8px;
    }

    .btn-main{
        min-width:120px;
        padding:8px 22px;
        border-radius:6px;
        border:2px solid #3d6b2a;
        background:#739966;
        color:#000;
        font-size:16px;
        font-weight:600;
        cursor:pointer;
        text-align:center;
        text-decoration:none;
    }
    .btn-main:hover{
        filter:brightness(1.05);
    }

    /* RIGHT RAIL */
    .new-roster-rail{
        background:var(--blue-300);
        border-radius:8px;
        border:2px solid #91a8c5;
        padding:20px;
        min-height:520px;
    }

    .rail-title{
        text-align:center;
        margin-top:0;
        margin-bottom:12px;
        font-size:20px;
        font-weight:700;
        color:#1f2933;
    }
    .rail-text{
        font-size:14px;
        color:#111827;
        line-height:1.6;
    }
</style>

<div class="new-roster-layout">

    {{-- LEFT: main content --}}
    <div class="new-roster-main">

        <div class="header-row">
            <h1 class="page-title">New roster</h1>

            {{-- top “View rosters” button --}}
            <a href="{{ route('roster.dashboard', ['date' => $date]) }}"
               class="view-rosters-btn">
                View rosters
            </a>
        </div>

        @if (session('status'))
            <div class="status-alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="roster-card">
            <form method="POST" action="{{ route('roster.store') }}">
                @csrf

                <div class="form-grid">
                    {{-- LEFT COLUMN --}}
                    <div class="field-block">
                        <label class="field-label" for="date">Date</label>
                        <input
                            id="date"
                            class="field-input"
                            type="date"
                            name="date"
                            value="{{ old('date', $date) }}"
                        >
                        @error('date')
                          <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-block">
                        <label class="field-label" for="supervisor_id">Supervisor</label>
                        <select id="supervisor_id" name="supervisor_id" class="field-select">
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

                    <div class="field-block">
                        <label class="field-label" for="doctor_id">Doctor</label>
                        <select id="doctor_id" name="doctor_id" class="field-select">
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

                    {{-- RIGHT COLUMN: groups --}}
                    @php
                        $groupFields = [
                          ['label' => 'Group 1 caregiver', 'name' => 'caregiver_1'],
                          ['label' => 'Group 2 caregiver', 'name' => 'caregiver_2'],
                          ['label' => 'Group 3 caregiver', 'name' => 'caregiver_3'],
                          ['label' => 'Group 4 caregiver', 'name' => 'caregiver_4'],
                        ];
                    @endphp

                    @foreach($groupFields as $gf)
                        @php
                            $field = $gf['name'];
                            $existingValue = optional($existing)->{$field};
                        @endphp
                        <div class="field-block">
                            <label class="field-label" for="{{ $field }}">{{ $gf['label'] }}</label>
                            <select id="{{ $field }}" name="{{ $field }}" class="field-select large">
                                <option value="">-- Select caregiver --</option>
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

                <div class="actions-row">
                    <button type="submit" class="btn-main">
                        {{ $existing ? 'Update' : 'Create' }}
                    </button>

                    <a href="{{ route('roster.dashboard', ['date' => $date]) }}"
                       class="btn-main">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- RIGHT RAIL --}}
    <aside class="new-roster-rail">
        <h3 class="rail-title">Roster overview</h3>
        <p class="rail-text">
            Use this page to assign the supervisor, doctor, and caregivers
            for each patient group on a specific date. When you save, the
            roster will appear on the main roster dashboard for everyone.
        </p>
    </aside>

</div>
@endsection
