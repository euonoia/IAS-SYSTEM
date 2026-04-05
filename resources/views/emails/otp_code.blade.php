<!DOCTYPE html>
<html>
<head>
    <style>
        .code { font-size: 32px; font-weight: bold; color: #2563eb; letter-spacing: 5px; }
        .footer { font-size: 12px; color: #64748b; margin-top: 20px; }
    </style>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Rxcel Clinic Security</h2>
    <p>Hello {{ $name }},</p>
    <p>Your one-time passcode for secure login is:</p>
    <h1 style="color: #2563eb; letter-spacing: 5px;">{{ $otpCode }}</h1>
    <p>This code is valid for 10 minutes. If you did not request this, please ignore this email.</p>
    <hr>
    <p style="font-size: 0.8em; color: #777;">&copy; 2026 Rxcel Medical Systems</p>
</body>
</html>