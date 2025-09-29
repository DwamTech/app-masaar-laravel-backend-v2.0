<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// إعداد قاعدة البيانات
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'masaarr',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    echo "🔧 إصلاح جدول العقارات...\n";
    
    // إضافة قيم افتراضية للحقول المطلوبة
    DB::statement("ALTER TABLE properties MODIFY COLUMN old_type VARCHAR(255) DEFAULT 'apartment'");
    DB::statement("ALTER TABLE properties MODIFY COLUMN old_price DECIMAL(15,2) DEFAULT 0");
    DB::statement("ALTER TABLE properties MODIFY COLUMN old_image_url VARCHAR(255) DEFAULT ''");
    DB::statement("ALTER TABLE properties MODIFY COLUMN old_view VARCHAR(255) DEFAULT ''");
    DB::statement("ALTER TABLE properties MODIFY COLUMN old_area DECIMAL(10,2) DEFAULT 0");
    
    echo "✅ تم إصلاح جدول العقارات بنجاح!\n";
    
} catch (Exception $e) {
    echo "❌ خطأ في إصلاح الجدول: " . $e->getMessage() . "\n";
}