<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\OtpAuthController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Auth\LoginController;

// صفحة تسجيل الدخول
Route::get('/login', function () {
    return view('login');
})->name('login');

// Web login to establish session for admin access
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Logout route used by admin header
Route::post('/logout', function (Request $request) {
    Auth::guard()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');
Route::get('/', function () {
    return view('landing');
})->name('landing');
Route::get('/terms', function () {
    return view('terms');
})->name('terms');
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');
// صفحة الداشبورد
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
// صفحة ادارة الحسابات (محمي للأدمن فقط)
Route::get('/accounts', function () {
    return view('accounts');
})->name('accounts')->middleware(['auth', 'is_admin']);
Route::get('/notifications', function () {
    return view('notifications');
})->name('notifications');
// صفحة إرسال الإشعار المستقلة
Route::get('/notifications/send', function () {
    return view('notifications.send');
})->name('notifications.send');
// 
Route::get('/requests', function () {
    return view('requests');
})->name('requests');

Route::get('/securityPermits', function () {
    return view('securityPermits');
})->name('securityPermits');

Route::get('/appController', function () {
    return view('appController');
})->name('appController');
Route::get('/AppSettings', function () {
    return view('AppSettings');
})->name('AppSettings');
// ... (المسارات الأخرى)

// صفحة المحادثات
Route::get('/chat', function () {
    return view('chat'); // اسم ملف ה-Blade سيكون chat.blade.php
})->name('chat');

// OTP Web Routes
Route::get('/verify-email', function () {
    return view('auth.verify-email-otp');
})->name('otp.verify-email-form')->middleware('guest');

Route::get('/reset-password', function () {
    return view('auth.reset-password-otp');
})->name('otp.reset-password-form')->middleware('guest');

// OTP Form Handlers
Route::post('/otp/verify-email', [OtpAuthController::class, 'verifyEmailOtp'])->name('otp.verify-email')->middleware('guest');
Route::post('/otp/resend-email-verification', [OtpAuthController::class, 'resendEmailVerificationOtp'])->name('otp.resend-email-verification')->middleware('guest');
Route::post('/otp/send-password-reset', [OtpAuthController::class, 'sendPasswordResetOtp'])->name('otp.send-password-reset')->middleware('guest');
Route::post('/otp/verify-password-reset', [OtpAuthController::class, 'verifyPasswordResetOtp'])->name('otp.verify-password-reset')->middleware('guest');
Route::post('/otp/reset-password', [OtpAuthController::class, 'resetPassword'])->name('otp.reset-password')->middleware('guest');

// Google OAuth Routes
Route::get('auth/google/redirect', [SocialLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('auth/google/callback', [SocialLoginController::class, 'handleGoogleCallback'])->name('google.callback');

// Admin Property Management Pages (public views; API remains protected)
Route::get('/admin/properties', function () {
    return view('admin.properties.index');
})->name('admin.properties.index');

Route::get('/admin/properties/create', function () {
    return view('admin.properties.create');
})->name('admin.properties.create');

Route::get('/admin/properties/{id}/edit', function ($id) {
    return view('admin.properties.edit', ['propertyId' => $id]);
})->name('admin.properties.edit');

// Convenience redirect: /properties -> admin properties (for admins)
Route::get('/properties', function () {
    return redirect()->route('admin.properties.index');
});
