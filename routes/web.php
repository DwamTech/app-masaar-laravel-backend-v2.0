<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| هذه الروابط وظيفتها فقط عرض ملفات Blade الخاصة بالداش بورد.
| الحماية والتحقق من الصلاحيات ستتم بالكامل عبر JavaScript والـ API
| من خلال "حارس الحماية" الذي نضعه في بداية كل صفحة.
|
*/

// --- صفحة تسجيل الدخول (Route عام) ---
// هذه الصفحة هي الوحيدة التي لا تحتاج إلى "حارس حماية" في الـ JavaScript.
Route::get('/login', function () {
    // تأكد من أن ملفك موجود في resources/views/auth/login-custom.blade.php
    return view('auth.login-custom');
})->name('login');


// --- صفحات الداش بورد (Routes محمية عبر JavaScript) ---

// الصفحة الرئيسية للداش بورد
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// صفحة الموافقة على الإعلانات
Route::get('/ads-approval', function () {
    return view('ads-approval');
})->name('ads-approval');

// صفحة إدارة الحسابات
Route::get('/accounts', function () {
    return view('accounts');
})->name('accounts');

// صفحة الطلبات (Requests)
Route::get('/requests', function () {
    return view('requests');
})->name('requests');

// صفحة التصاريح الأمنية
Route::get('/securityPermits', function () {
    return view('securityPermits');
})->name('securityPermits');

// صفحة التحكم بالتطبيق
Route::get('/appController', function () {
    return view('appController');
})->name('appController');

// صفحة إعدادات التطبيق (متغيرات النظام)
Route::get('/AppSettings', function () {
    return view('AppSettings'); // تأكد من تطابق اسم الملف
})->name('AppSettings');

// صفحة المحادثات
Route::get('/chat', function () {
    return view('chat');
})->name('chat');

// صفحة إعدادات فلاتر البحث
Route::get('/search-filter-settings', function () {
    return view('search-filter-settings');
})->name('search-filter-settings');

// صفحة بنرات الأقسام
Route::get('/section-banners', function () {
    return view('section-banners');
})->name('section-banners');

// صفحة إدارة الإعلانات
Route::get('/ads-management', function () {
    return view('ads-management');
})->name('ads-management');

// صفحة أفضل المعلنين
Route::get('/best-advertisers', function () {
    return view('best-advertisers');
})->name('best-advertisers');

// صفحة إرسال الإشعارات
Route::get('/send-notification', function () {
    return view('send-notification');
})->name('send-notification');

// صفحة إدارة المستخدمين
Route::get('/users-management', function () {
    // إنشاء مجموعة مستخدمين وهمية للعرض
    $users = collect([
        (object) [
            'id' => 1,
            'name' => 'أحمد محمد',
            'email' => 'ahmed@example.com',
            'phone' => '+971501234567',
            'role' => 'user',
            'status' => 'active',
            'user_type' => 'advertiser',
            'ads_count' => 5,
            'registration_method' => 'تطبيق الجوال',
            'registration_method_en' => 'Mobile App',
            'created_at' => now()->subDays(10),
            'email_verified_at' => now()->subDays(9),
        ],
        (object) [
            'id' => 2,
            'name' => 'فاطمة علي',
            'email' => 'fatima@example.com',
            'phone' => '+971507654321',
            'role' => 'user',
            'status' => 'active',
            'user_type' => 'normal_user',
            'ads_count' => 0,
            'registration_method' => 'الموقع الإلكتروني',
            'registration_method_en' => 'Website',
            'created_at' => now()->subDays(5),
            'email_verified_at' => now()->subDays(4),
        ],
        (object) [
            'id' => 3,
            'name' => 'محمد خالد',
            'email' => 'mohammed@example.com',
            'phone' => '+971509876543',
            'role' => 'admin',
            'status' => 'blocked',
            'user_type' => 'advertiser',
            'ads_count' => 12,
            'registration_method' => 'جوجل',
            'registration_method_en' => 'Google',
            'created_at' => now()->subDays(15),
            'email_verified_at' => null,
        ]
    ]);
    
    $filter = request('filter', 'all');
    
    return view('users-management', compact('users', 'filter'));
})->name('users-management');

// صفحة تفاصيل المستخدم
Route::get('/user-details/{id}', function ($id) {
    // بيانات وهمية لتفاصيل المستخدم
    $user = (object) [
        'id' => $id,
        'name' => 'أحمد محمد',
        'email' => 'ahmed@example.com',
        'phone' => '+971501234567',
        'role' => 'user',
        'status' => 'active',
        'user_type' => 'advertiser',
        'ads_count' => 5,
        'registration_method' => 'تطبيق الجوال',
        'registration_method_en' => 'Mobile App',
        'created_at' => now()->subDays(10),
        'email_verified_at' => now()->subDays(9),
    ];
    
    return view('user-details', compact('user'));
})->name('user-details');

// --- صفحات إدارة الأقسام ---

// صفحة إدارة بيع السيارات
Route::get('/sections/car-sale', function () {
    return view('sections.car-sale');
})->name('sections.car-sale');

// صفحة إدارة خدمات السيارات
Route::get('/sections/car-services', function () {
    return view('sections.car-services');
})->name('sections.car-services');

// صفحة إدارة تأجير السيارات
Route::get('/sections/car-rent', function () {
    return view('sections.car-rent');
})->name('sections.car-rent');

// صفحة إدارة المطاعم
Route::get('/sections/restaurant', function () {
    return view('sections.restaurant');
})->name('sections.restaurant');

// صفحة إدارة الوظائف
Route::get('/sections/jobs', function () {
    return view('sections.jobs');
})->name('sections.jobs');

// صفحة إدارة الخدمات الأخرى
Route::get('/sections/other-services', function () {
    return view('sections.other-services');
})->name('sections.other-services');

// صفحة إدارة العقارات
Route::get('/sections/real-estate', function () {
    return view('sections.real-estate');
})->name('sections.real-estate');

// صفحة إدارة الإلكترونيات
Route::get('/sections/electronics', function () {
    return view('sections.electronics');
})->name('sections.electronics');


// --- التوجيه الافتراضي ---
// إذا حاول أي شخص الوصول إلى المسار الرئيسي /، سيتم توجيهه إلى صفحة تسجيل الدخول.
Route::get('/', function () {
    return view('home');
});
// English landing page
Route::get('/en', function () {
    return view('home-en');
})->name('home-en');

// صفحات قانونية عامة
Route::view('/privacy-policy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');

// --- لا يوجد Route لتسجيل الخروج هنا ---
// عملية تسجيل الخروج ستتم عبر JavaScript عن طريق حذف التوكن من localStorage وتوجيه المستخدم لصفحة الدخول.