@extends('layouts.app')

@section('title', 'Registration Approval')

@section('content')
<style>
    .approval-wrapper {
        width: 100%;
        max-width: 1100px;
        background: #c7dbf0; 
        border-radius: 12px;
        padding: 18px 22px 24px;
        box-shadow: 0 0 8px rgba(0,0,0,0.15);
    }

    .approval-title {
        margin: 0 0 10px 0;
        font-size: 32px;
        font-weight: 700;
        color: #4f81bd;
        text-align: left;
    }

    .approval-inner {
        background: #b7d7a8; 
        border-radius: 8px;
        padding: 18px 20px 10px;
        min-height: 380px;
        position: relative;
    }

    .approval-search {
        margin-bottom: 10px;
        text-align: left;
    }

    .approval-search input {
        width: 260px;
        padding: 6px 8px;
        border-radius: 4px;
        border: 1px solid #8a8a8a;
        font-size: 13px;
        color: #000;
    }

    .approval-search button {
        padding: 6px 12px;
        margin-left: 6px;
        border-radius: 4px;
        border: none;
        background: #6fa8dc;
        color: #fff;
        font-size: 13px;
        cursor: pointer;
        font-weight: 600;
    }

    .approval-search button:hover {
        background: #4f81bd;
    }

    .flash-box {
        margin-bottom: 10px;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
    }
    .flash-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    /* table area */
    .approval-table-wrapper {
        max-height: 700px;
        overflow-y: auto;
        border-radius: 4px;
        background: rgba(255,255,255,0.3);
    }

    .approval-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        color: #000;
    }

    .approval-table th,
    .approval-table td {
        padding: 8px 10px;
        text-align: left;
        border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    .approval-table th {
        font-weight: 600;
        color: #4f81bd;
        background: #b7d7a8;
    }

    .col-pending-header {
        text-align: center;
    }
    .col-pending-header div {
        font-size: 13px;
    }
    .col-pending-header span {
        display: inline-block;
        width: 40px;
        text-align: center;
        font-size: 11px;
    }

    .pending-cell {
        text-align: center;
    }
    .pending-actions {
        display: inline-flex;
        gap: 10px;
    }

    .pending-btn {
        width: 28px;
        height: 28px;
        border-radius: 3px;
        border: 1px solid #000;
        background: #d9e3f0; 
        cursor: pointer;
        padding: 0;
    }
    .pending-btn:hover {
        background: #c2d4f0;
    }

    .empty-row {
        text-align: center;
        padding: 20px;
        color: #000;
    }
</style>

<div class="approval-wrapper">

    {{-- Title --}}
    <h1 class="approval-title">approval</h1>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flash-box flash-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Green inner box --}}
    <div class="approval-inner">

        {{-- Search bar (top-left inside green area) --}}
        <form action="{{ route('registration.approval') }}" method="GET" class="approval-search">
            <input type="text"
                   name="search"
                   placeholder="Search by name, email, role, ID..."
                   value="{{ $search ?? '' }}">
            <button type="submit">Search</button>
        </form>

        {{-- Scrollable table --}}
        <div class="approval-table-wrapper">
            <table class="approval-table">
                <thead>
                    <tr>
                        <th style="width:40%;">name</th>
                        <th style="width:25%;">role</th>
                        <th style="width:35%;" class="col-pending-header">
                            <div>pending</div>
                            <span>yes</span>
                            <span>no</span>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td>{{ $req->first_name }} {{ $req->last_name }}</td>
                            <td>{{ $req->role }}</td>
                            <td class="pending-cell">
                                <div class="pending-actions">

                                    {{-- YES = approve --}}
                                    <form action="{{ route('registration.approve', $req->id) }}"
                                          method="POST">
                                        @csrf
                                        <button type="button"
                                                class="pending-btn"
                                                title="Approve"
                                                onclick="confirmApprove(this.form)">
                                        </button>
                                    </form>

                                    {{-- NO = deny --}}
                                    <form action="{{ route('registration.deny', $req->id) }}"
                                          method="POST">
                                        @csrf
                                        <button type="button"
                                                class="pending-btn"
                                                title="Deny"
                                                onclick="confirmDeny(this.form)">
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-row">
                                No pending registration requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    function confirmApprove(form) {
        if (confirm("Are you sure you want to APPROVE this registration?")) {
            form.submit();
        }
    }

    function confirmDeny(form) {
        if (confirm("Are you sure you want to DENY and DELETE this registration?")) {
            form.submit();
        }
    }
</script>
@endsection
