<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset Instructions</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; padding: 20px; text-align: center;">
        <img src="{{ asset('telenta.png') }}" alt="Telenta" style="width: 60px; margin-bottom: 20px;">

        <h2>Password Reset Instructions</h2>
        <p>Hello {{ $name }},</p>
        <p>Click the button below to reset your password for your account.</p>

        <a href="{{ $resetUrl }}" style="display: inline-block; padding: 12px 24px; background: #007bff; color: #fff; text-decoration: none; font-weight: bold; border-radius: 6px; margin: 20px 0;">
            RESET PASSWORD
        </a>

        <p>If the button doesn’t work, copy and paste this link into your browser:</p>
        <p><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>

        <p style="margin-top: 30px;">Thank you,<br>Telenta</p>
    </div>
</body>
</html>
