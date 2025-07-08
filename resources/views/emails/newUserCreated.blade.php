<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Credentials</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        h2 {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to {{ config('app.name') }}, {{ $user->name }}</h2>

        <p>We are pleased to inform you that your account has been successfully created on our platform. Please find your login credentials below:</p>

        <ul>
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Temporary Password:</strong> {{ $password }}</li>
        </ul>

        <p>For security reasons, we strongly recommend changing your password after your first login.</p>

        <p>If you have any questions or require assistance, feel free to reach out to our support team.</p>

        <p>Best regards,<br>
        {{ config('app.name') }} Team</p>
    </div>
</body>
</html>
