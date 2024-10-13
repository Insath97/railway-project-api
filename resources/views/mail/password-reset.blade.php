<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Password Reset - Railway Stock Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f7f7f7;
        }

        .content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            color: #ffffff;
            background-color: #007bff;
            border-radius: 5px;
            text-decoration: none;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="content">
            <h2>Password Reset Request</h2>
            <p>Hello,</p>
            <p>We received a request to reset your password for your DS Office account. You can reset your password by
                clicking the link below:</p>
            <a href="{{ $reset_url }}" target="_blank" class="button text-white">Reset your password</a>
            <p>If you did not request a password reset, please ignore this email or contact support if you have
                concerns.</p>
            <p>Best regards,<br>Railway Department Office</p>
        </div>
    </div>
</body>

</html>
