<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        /* Base styles */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #3490dc;
            padding: 30px 20px;
            text-align: center;
        }

        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .email-body {
            padding: 30px 20px;
        }

        .email-footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666666;
        }

        /* Button styles */
        .btn {
            display: inline-block;
            background-color: #3490dc;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background-color: #2779bd;
        }

        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                border-radius: 0;
            }

            .email-header {
                padding: 20px 15px;
            }

            .email-header h1 {
                font-size: 20px;
            }

            .email-body {
                padding: 20px 15px;
            }

            .btn {
                display: block;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Password Reset Request</h1>
        </div>

        <div class="email-body">
            <p>Hello {{ $user->name ?? 'there' }},</p>

            <p>We received a request to reset your password. If you didn't make this request, you can safely ignore this
                email.</p>

            <p>To reset your password, click the button below:</p>

            <div style="text-align: center;">
                <a href="{{ $link }}" target="_blank" class="btn">Reset Password</a>
            </div>

            <p>If the button doesn't work, you can also copy and paste the following link into your browser:</p>

            <p style="word-break: break-all; font-size: 14px; color: #666666;">
                {{ $link }}
            </p>

            <p>This password reset link will expire in '15 minutes'.</p>

            <p>Best regards,<br>The Blog Team</p>
        </div>

        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Blog. All rights reserved.</p>
            <p>If you have any questions, please contact our support team.</p>
        </div>
    </div>
</body>

</html>
