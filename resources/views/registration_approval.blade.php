@extends('layouts.app')

@section('title', 'Registration Approval')

@section('content')
    <div style="width:100%; max-width:1200px; padding:20px;">

        <h1 style="margin-top:0; font-size:28px; font-weight:700; text-align:center; color:#000;">
            Registration Approval
        </h1>

        @if(session('success'))
            <div class="success-box" style="color:#155724; background:#d4edda; border:1px solid #c3e6cb; padding:12px; border-radius:5px; margin-bottom:15px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- SEARCH BAR -->
        <div class="search-bar" style="margin-bottom:20px; text-align:center;">
            <form action="{{ route('registration.approval') }}" method="GET">
                <input type="text" name="search" placeholder="Search by name, email, role, date, ID..."
                       value="{{ $search ?? '' }}" style="width:300px; padding:10px; border-radius:6px; border:1px solid #aaa; font-size:16px; color:#000;">
                <button type="submit" style="padding:10px 16px; border:none; background-color:#6fa8dc; color:white; border-radius:6px; cursor:pointer; font-weight:bold;">
                    Search
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; background-color:#fff; border-radius:10px; overflow:hidden; color:#000;">
                <tr>
                    <th style="padding:14px; background-color:#6fa8dc; color:white; text-align:left;">User ID</th>
                    <th style="padding:14px; background-color:#6fa8dc; color:white; text-align:left;">Name</th>
                    <th style="padding:14px; background-color:#6fa8dc; color:white; text-align:left;">Email</th>
                    <th style="padding:14px; background-color:#6fa8dc; color:white; text-align:left;">Role</th>
                    <th style="padding:14px; background-color:#6fa8dc; color:white; text-align:left;">Date Registered</th>
                    <th style="padding:14px; background-color:#6fa8dc; color:white; text-align:left;">Actions</th>
                </tr>

                @forelse ($requests as $req)
                    <tr style="border-bottom:1px solid #ddd; color:#000;">
                        <td>{{ $req->id }}</td>
                        <td>{{ $req->first_name }} {{ $req->last_name }}</td>
                        <td>{{ $req->email }}</td>
                        <td>{{ $req->role }}</td>
                        <td>{{ $req->created_at->format('Y-m-d') }}</td>
                        <td class="actions">
                            <form action="{{ route('registration.approve', $req->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="button" class="approve" onclick="confirmApprove(this.form)"
                                        style="padding:8px 16px; border:none; border-radius:5px; cursor:pointer; font-weight:bold; background-color:#4caf50; color:white;">
                                    Approve
                                </button>
                            </form>

                            <form action="{{ route('registration.deny', $req->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="button" class="deny" onclick="confirmDeny(this.form)"
                                        style="padding:8px 16px; border:none; border-radius:5px; cursor:pointer; font-weight:bold; background-color:#e06666; color:white;">
                                    Deny
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:#000;">No pending registration requests.</td>
                    </tr>
                @endforelse
            </table>
        </div>

        <div class="pagination" style="text-align:center; margin-top:15px;">
            {{ $requests->links() }}
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
