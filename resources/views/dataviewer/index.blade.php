<!DOCTYPE html>
<html>
<head>
    <title>Database Viewer</title>
    {{-- auto-refresh every 5 seconds --}}
    <meta http-equiv="refresh" content="5">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            padding: 20px;
        }
        h1 {
            margin-bottom: 10px;
        }
        .summary {
            background: #ffffff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 0 8px #cccccc;
            margin-bottom: 25px;
        }
        .summary ul {
            margin: 0;
            padding-left: 20px;
        }
        .summary li {
            margin: 4px 0;
        }
        .summary a {
            text-decoration: none;
            color: #4287f5;
        }
        .summary a:hover {
            text-decoration: underline;
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
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 8px 10px;
            text-align: left;
            font-size: 13px;
            word-wrap: break-word;
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
        .bool-yes {
            color: green;
            font-weight: bold;
        }
        .bool-no {
            color: #c0392b;
            font-weight: bold;
        }
        .json-cell {
            font-family: "Courier New", monospace;
            white-space: pre-wrap;
            font-size: 12px;
        }
    </style>
</head>
<body>

<h1>📊 Database Table Viewer</h1>
<p>This page auto-refreshes every 5 seconds. All rows passed from the controller are shown below.</p>

{{-- quick index of all tables --}}
<div class="summary">
    <strong>Tables in this view:</strong>
    <ul>
        @foreach ($data as $tableName => $rows)
            <li>
                <a href="#table-{{ $tableName }}">
                    {{ $tableName }}
                </a>
                ({{ count($rows) }} rows)
            </li>
        @endforeach
    </ul>
</div>

@foreach ($data as $tableName => $rows)
    <div class="table-section" id="table-{{ $tableName }}">
        <h2>Table: {{ $tableName }} ({{ count($rows) }} rows)</h2>

        @if (count($rows) === 0)
            <p class="empty">This table is empty.</p>
        @else
            <table>
                <thead>
                    <tr>
                        @foreach (array_keys((array) $rows[0]) as $col)
                            <th>{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            @foreach ((array) $row as $value)
                                @php
                                    // Try to detect JSON
                                    $decoded = null;
                                    $isJson = false;

                                    if (is_string($value)) {
                                        $decoded = json_decode($value, true);
                                        $isJson = json_last_error() === JSON_ERROR_NONE && is_array($decoded);
                                    }
                                @endphp

                                @if (is_bool($value) || $value === 0 || $value === 1)
                                    <td>
                                        @if ($value)
                                            <span class="bool-yes">✔ Yes</span>
                                        @else
                                            <span class="bool-no">✖ No</span>
                                        @endif
                                    </td>
                                @elseif ($isJson)
                                    <td class="json-cell">
{!! json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
                                    </td>
                                @else
                                    <td>{{ $value }}</td>
                                @endif
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
