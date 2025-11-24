<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Approval</title>

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background-color: #c8e0b5;
        }
        header {
            background-color: #f4eed2;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            flex:1;
        }
        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-bar input {
            width: 300px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #aaa;
            font-size: 16px;
        }
        .search-bar button {
            padding: 10px 16px;
            border: none;
            background-color: #6fa8dc;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        .search-bar button:hover {
            opacity: 0.9;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #6fa8dc;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .actions button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .approve {
            background-color: #4caf50;
            color: white;
        }
        .deny {
            background-color: #e06666;
            color: white;
        }
        .footer {
            margin-top: 40px;
            background-color: #f4eed2;
            padding: 15px;
            text-align: center;
            font-size: 14px;
        }
        .success-box {
            background:#d4edda;
            padding:12px;
            border-radius:5px;
            margin-bottom:15px;
            color:#155724;
            border:1px solid #c3e6cb;
        }
        .pagination {
            text-align: center;
            margin-top: 15px;
        }
        .pagination a {
            padding: 8px;
            background: #6fa8dc;
            color: white;
            margin: 3px;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>

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
</head>

<body>

<header>
    Registration Approval
</header>

<div class="container">

    <h2>Pending User Registrations</h2>

    @if(session('success'))
        <div class="success-box">
            {{ session('success') }}
        </div>
    @endif

    <!-- SEARCH BAR -->
    <div class="search-bar">
        <form action="{{ route('registration.approval') }}" method="GET">
            <input type="text" name="search" placeholder="Search by name, email, role, date, ID..."
                   value="{{ $search ?? '' }}">
            <button type="submit">Search</button>
        </form>
    </div>

    <table>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Date Registered</th>
            <th>Actions</th>
        </tr>

        @forelse ($requests as $req)
            <tr>
                <td>{{ $req->id }}</td>
                <td>{{ $req->first_name }} {{ $req->last_name }}</td>
                <td>{{ $req->email }}</td>
                <td>{{ $req->role }}</td>
                <td>{{ $req->created_at->format('Y-m-d') }}</td>

                <td class="actions">
                    <!-- APPROVE -->
                    <form action="{{ route('registration.approve', $req->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="button" class="approve" onclick="confirmApprove(this.form)">
                            Approve
                        </button>
                    </form>

                    <!-- DENY -->
                    <form action="{{ route('registration.deny', $req->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="button" class="deny" onclick="confirmDeny(this.form)">
                            Deny
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center;">No pending registration requests.</td>
            </tr>
        @endforelse
    </table>

    <div class="pagination">
        {{ $requests->links() }}
    </div>

</div>

<div class="footer">
    Footer: about, copyrights, etc
</div>

</body>
</html>
