<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// البحث عن المدير
$admin = App\Models\User::where('email', 'admin@masar.app')->first();

if ($admin) {
    echo "Admin found - ID: " . $admin->id . " - Name: " . $admin->name . PHP_EOL;
    echo "User Type: " . $admin->user_type . PHP_EOL;
    
    // جلب الإشعارات
    $notifications = $admin->notifications;
    echo "Notifications count: " . $notifications->count() . PHP_EOL;
    
    if ($notifications->count() > 0) {
        echo "\n--- Notifications Details ---\n";
        foreach ($notifications as $n) {
            echo "ID: " . $n->id . PHP_EOL;
            echo "Title: " . $n->title . PHP_EOL;
            echo "Message: " . substr($n->message, 0, 50) . "..." . PHP_EOL;
            echo "Type: " . $n->type . PHP_EOL;
            echo "Read: " . ($n->is_read ? 'Yes' : 'No') . PHP_EOL;
            echo "Created: " . $n->created_at . PHP_EOL;
            echo "---" . PHP_EOL;
        }
    } else {
        echo "No notifications found for admin." . PHP_EOL;
    }
    
    // التحقق من جدول الإشعارات مباشرة
    echo "\n--- Direct Database Check ---\n";
    $directNotifications = DB::table('notifications')->where('user_id', $admin->id)->get();
    echo "Direct DB notifications count: " . $directNotifications->count() . PHP_EOL;
    
    if ($directNotifications->count() > 0) {
        echo "\n--- Direct DB Notifications ---\n";
        foreach ($directNotifications as $n) {
            echo "ID: " . $n->id . " - Title: " . $n->title . " - User ID: " . $n->user_id . PHP_EOL;
        }
    }
    
    // التحقق من جميع الإشعارات في الجدول
    echo "\n--- All Notifications in DB ---\n";
    $allNotifications = DB::table('notifications')->get();
    echo "Total notifications in DB: " . $allNotifications->count() . PHP_EOL;
    
    if ($allNotifications->count() > 0) {
        foreach ($allNotifications as $n) {
            echo "ID: " . $n->id . " - User ID: " . $n->user_id . " - Title: " . $n->title . PHP_EOL;
        }
    }
    
} else {
    echo "Admin not found with email: admin@masar.app" . PHP_EOL;
    
    // البحث عن جميع المدراء
    $admins = App\Models\User::where('user_type', 'admin')->get();
    echo "Total admins found: " . $admins->count() . PHP_EOL;
    
    foreach ($admins as $admin) {
        echo "Admin: " . $admin->name . " - Email: " . $admin->email . PHP_EOL;
    }
}