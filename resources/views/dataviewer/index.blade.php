<!DOCTYPE html>
<html>
<head>
    <title>Database Viewer</title>
    <meta http-equiv="refresh" content="5">


    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            padding: 20px;
        }
        .table-section {
            margin-bottom: 40px;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 8px #cccccc;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 8px 10px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background: #4287f5;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .empty {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>📊 Database Table Viewer</h1>
<p>Showing up to 50 records per table.</p>

@foreach ($data as $tableName => $rows)
    <div class="table-section">
        <h2>Table: {{ $tableName }} ({{ count($rows) }} rows)</h2>

        @if (count($rows) === 0)
            <p class="empty">This table is empty.</p>
        @else
            <table>
                <thead>
                    <tr>
                        @foreach (array_keys((array)$rows[0]) as $col)
                            <th>{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            @foreach ((array)$row as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endforeach

</body>
</html>
