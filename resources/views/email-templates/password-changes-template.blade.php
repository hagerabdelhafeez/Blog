<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed</title>
    <style>
        /* Base styles */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eeeeee;
        }

        .content {
            padding: 20px 0;
        }

        .footer {
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #888888;
            border-top: 1px solid #eeeeee;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3490dc;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
        }

        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
        }

        /* Responsive styles */
        @media screen and (max-width: 600px) {
            .container {
                width: 100% !important;
            }

            .content {
                padding: 10px !important;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Password Changed Successfully</h2>
        </div>

        <div class="content">
            <p>Hello {{ $user->name }},</p>

            <p>Your password has been successfully changed. Here are your updated account details:</p>

            <div class="info-box">
                <div class="info-item">
                    <span class="info-label">Username/Email:</span>{{ $user->username }} or {{ $user->email }}
                </div>
                <div class="info-item">
                    <span class="info-label">New Password:</span> {{ $new_password }}
                </div>
            </div>

            <p>For security reasons, we recommend that you keep this information confidential and do not share it with
                anyone.</p>

            <p>If you did not request this password change, please contact our support team immediately.</p>

            <a href="{{ route('admin.login') }}" class="button">Login to Your Account</a> 
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Blog. All rights reserved.</p>
            <p>This is an automated email, please do not reply.</p>
        </div>
    </div>
</body>

</html>
