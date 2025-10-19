<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// البحث عن المدير
$admin = App\Models\User::where('email', 'admin@masar.app')->first();

if (!$admin) {
    echo "Admin not found!" . PHP_EOL;
    exit;
}

echo "Testing Notifications API for Admin: " . $admin->name . " (ID: " . $admin->id . ")" . PHP_EOL;

// تسجيل دخول المدير
$admin->login();

// محاكاة طلب API
$request = new Illuminate\Http\Request();

// إنشاء instance من NotificationController
$controller = new App\Http\Controllers\NotificationController();

try {
    // استدعاء method index
    $response = $controller->index($request);
    
    // طباعة النتيجة
    echo "API Response Status: " . $response->getStatusCode() . PHP_EOL;
    echo "API Response Content: " . $response->getContent() . PHP_EOL;
    
    // تحليل JSON
    $data = json_decode($response->getContent(), true);
    
    if (isset($data['notifications'])) {
        echo "Notifications count from API: " . count($data['notifications']) . PHP_EOL;
        
        if (count($data['notifications']) > 0) {
            echo "\n--- First 3 Notifications from API ---\n";
            foreach (array_slice($data['notifications'], 0, 3) as $notification) {
                echo "ID: " . $notification['id'] . PHP_EOL;
                echo "Title: " . $notification['title'] . PHP_EOL;
                echo "Message: " . substr($notification['message'], 0, 50) . "..." . PHP_EOL;
                echo "---" . PHP_EOL;
            }
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo "Trace: " . $e->getTraceAsString() . PHP_EOL;
}