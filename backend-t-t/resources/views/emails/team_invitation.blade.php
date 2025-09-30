<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>You're Invited to Join {{ $teamName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            margin-top: 20px;
            font-size: 16px;
            color: #ffffff !important;
            background-color: #2563eb;
            border-radius: 6px;
            text-decoration: none;
        }

        .footer {
            font-size: 12px;
            color: #6b7280;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>You’re invited to join <strong>{{ $teamName }}</strong></h2>

        <p>Hello,</p>
        <p>
            You have been invited to join the team <strong>{{ $teamName }}</strong> on our platform.
            Click the button below to accept the invitation and become a member.
        </p>

        <a href="{{ $inviteUrl }}" class="btn">Accept Invitation</a>

        <p>If you did not expect this invitation, you can safely ignore this email.</p>

        <div class="footer">
            &copy; {{ date('Y') }} Your App. All rights reserved.
        </div>
    </div>
</body>

</html>