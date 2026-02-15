# تعليمات إعداد السيرفر - مشكلة تسجيل الحسابات

## المشكلة
عند تسجيل حساب جديد، يتم إنشاء الحساب في قاعدة البيانات لكن التطبيق يعرض خطأ "Server Error" ولا ينتقل إلى صفحة OTP.

## السبب
كان الإشعار `EmailVerificationOtp` يستخدم Queue (`ShouldQueue`)، مما يعني أن البريد الإلكتروني يُضاف إلى قائمة الانتظار بدلاً من الإرسال مباشرة. إذا لم يكن Queue Worker يعمل على السيرفر، لن يُرسل البريد وسيفشل التسجيل.

## الحلول المطبقة

### 1. إزالة Queue من EmailVerificationOtp ✅
تم تعديل ملف `app/Notifications/EmailVerificationOtp.php` لإرسال البريد مباشرة بدون queue.

### 2. تحسين معالجة الأخطاء ✅
تم تحسين `RegisteredUserController` لتسجيل تفاصيل الأخطاء بشكل أفضل في الـ logs.

### 3. تحسين طباعة الأخطاء في التطبيق ✅
تم تعديل `laravel_service.dart` في التطبيق لطباعة تفاصيل الخطأ كاملة.

## خطوات النشر على السيرفر

### 1. رفع الملفات المعدلة
```bash
# رفع الملفات التالية إلى السيرفر:
- app/Notifications/EmailVerificationOtp.php
- app/Http/Controllers/Auth/RegisteredUserController.php
```

### 2. مسح الـ Cache
```bash
cd /path/to/masaar-laravel-backend
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 3. التحقق من إعدادات البريد الإلكتروني
تأكد من أن ملف `.env` على السيرفر يحتوي على:
```env
MAIL_MAILER=smtp
MAIL_HOST=msar.app
MAIL_PORT=465
MAIL_USERNAME=support@msar.app
MAIL_PASSWORD=LntK8rq55
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=support@msar.app
MAIL_FROM_NAME="Msar"
```

### 4. اختبار إرسال البريد
```bash
php artisan tinker
# ثم اكتب:
Mail::raw('Test email', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

## (اختياري) تشغيل Queue Worker

إذا أردت استخدام Queue في المستقبل، يمكنك:

### 1. تشغيل Queue Worker يدوياً
```bash
php artisan queue:work --tries=3
```

### 2. إعداد Supervisor (للإنتاج)
إنشاء ملف `/etc/supervisor/conf.d/laravel-worker.conf`:
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/masaar-laravel-backend/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/masaar-laravel-backend/storage/logs/worker.log
stopwaitsecs=3600
```

ثم:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

## التحقق من الحل

1. جرب تسجيل حساب جديد من التطبيق
2. تحقق من الـ logs في `storage/logs/laravel.log`
3. تحقق من وصول البريد الإلكتروني
4. تأكد من الانتقال إلى صفحة OTP

## ملاحظات مهمة

- ✅ الحل الحالي يرسل البريد مباشرة (synchronous) - قد يكون أبطأ قليلاً لكنه أكثر موثوقية
- ⚠️ إذا كان السيرفر يستقبل عدد كبير من التسجيلات، يُفضل استخدام Queue مع Supervisor
- 📧 تأكد من أن بيانات SMTP صحيحة وأن السيرفر يسمح بالاتصال بمنفذ 465
