<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>رمز التحقق</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: right;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #f4f4f4;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
        .content {
            padding: 30px 20px;
            text-align: center;
        }
        .otp-box {
            background: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .otp-code {
            font-size: 32px;
            letter-spacing: 5px;
            color: #007bff;
            font-weight: bold;
            direction: ltr;
            text-align: center;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 14px;
            border-top: 2px solid #f4f4f4;
        }
        .warning {
            color: #dc3545;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" class="logo">
            <h1>رمز التحقق</h1>
        </div>

        <div class="content">
            <p>مرحباً!</p>
            <p>لقد طلبت رمز التحقق الخاص بـ {{ $type }}</p>

            <div class="otp-box">
                <p>رمز التحقق الخاص بك هو:</p>
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <p>سينتهي هذا الرمز خلال <strong>10 دقائق</strong></p>

            <div class="warning">
                <p>⚠️ لا تشارك هذا الرمز مع أي شخص</p>
                <p>لن يطلب فريقنا هذا الرمز أبداً</p>
            </div>
        </div>

        <div class="footer">
            <p>هذه رسالة آلية، يرجى عدم الرد عليها</p>
            <p>جميع الحقوق محفوظة &copy; {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
