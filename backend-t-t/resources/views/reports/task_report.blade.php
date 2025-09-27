<!DOCTYPE html>
<html>

<head>
    <title>Task Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>

<body>
    <h2>Task Report - {{ $team->name }}</h2>
    <p>Generated at: {{ now() }}</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Task</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task->created_at }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ ucfirst($task->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>