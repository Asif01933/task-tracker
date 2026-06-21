<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Task Report Summary</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9fafb; margin:0; padding:0;">
    <div
        style="max-width:700px; margin:20px auto; background:#ffffff; padding:20px; border-radius:10px; box-shadow:0 0 5px rgba(0,0,0,0.1);">

        <h2 style="text-text-align:center; color:#1a73e8;">Task Report Summary</h2>
        <p style="text-text-align:center;">Reporting Period: <strong>{{ $startDate }}</strong> to
            <strong>{{ $endDate }}</strong>
        </p>

        <!-- Category-wise Report -->
        <h3 style="color:#1a73e8; border-bottom:1px solid #ddd; padding-bottom:5px;">Category-wise Report</h3>

        @foreach($categoryReports as $category => $tasks)
            <h4 style="margin-top:15px; color:#333;">{{ $category }}</h4>
            <table width="100%" cellpadding="6" cellspacing="0" border="1"
                style="border-collapse:collapse; border-color:#ddd; margin-top:5px;">
                <thead style="background:#f3f4f6;">
                    <tr>
                        <th text-align="left">Task</th>
                        <th text-align="left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task['task'] }}</td>
                            <td>{{ $task['status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

        <!-- Date-wise Report -->
        <h3 style="color:#1a73e8; border-bottom:1px solid #ddd; margin-top:25px; padding-bottom:5px;">Date-wise Report
        </h3>

        @foreach($dateReports as $date => $tasks)
            <h4 style="margin-top:15px; color:#333;">{{ $date }}</h4>
            <table width="100%" cellpadding="6" cellspacing="0" border="1"
                style="border-collapse:collapse; border-color:#ddd; margin-top:5px;">
                <thead style="background:#f3f4f6;">
                    <tr>
                        <th text-align="left">Task</th>
                        <th text-align="left">Description</th>
                        <th text-align="left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task['task'] }}</td>
                            <td>{{ $task['problem_description'] }}</td>
                            <td>{{ $task['status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

        <p style="text-text-align:center; margin-top:30px; color:#555;">
            — Task Tracker App
        </p>
    </div>
</body>

</html>