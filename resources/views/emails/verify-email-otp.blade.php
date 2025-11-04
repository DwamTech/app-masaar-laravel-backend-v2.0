<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق لتأكيد البريد الإلكتروني</title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; background:#f6f6f6; color:#222; margin:0; padding:20px; direction: rtl; text-align: right; }
        .container { max-width:600px; margin:0 auto; background:#ffffff; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden; }
        .header { padding:20px 24px; background:#0f6efd; color:#fff; text-align: right; }
        .brand { font-size:18px; font-weight:bold; }
        .content { padding:24px; line-height:1.9; text-align: right; }
        .greeting { font-size:18px; font-weight:bold; margin-bottom:10px; }
        .otp-box { margin:16px 0; padding:18px; text-align:center; border:2px dashed #0f6efd; border-radius:8px; background:#f0f7ff; }
        .otp-code { font-size:28px; letter-spacing:4px; font-weight:bold; direction:ltr; }
        .footer { padding:16px 24px; font-size:12px; color:#666; background:#fafafa; text-align: right; }
    </style>
    
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">اهلا وسهلا  في msar</div>
        </div>
        <div class="content">
            <div class="greeting">مرحباً {{ $userName }}!</div>
            <p>
                شكراً لك على التسجيل في تطبيقنا. لتأكيد بريدك الإلكتروني، يرجى استخدام رمز التحقق التالي:
            </p>

            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <p>
                هذا الرمز صالح لمدة 10 دقائق فقط.
                إذا لم تقم بإنشاء حساب، يرجى تجاهل هذا البريد الإلكتروني.
            </p>
            <p>
                لا تشارك هذا الرمز مع أي شخص آخر لحماية حسابك.
            </p>
        </div>
        <div class="footer">
            © {{ date('Y') }} Masar. جميع الحقوق محفوظة.
        </div>
    </div>
</body>
</html>