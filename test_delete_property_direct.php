<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Property;
use Laravel\Sanctum\PersonalAccessToken;

try {
    // البحث عن المستخدم التجريبي
    $user = User::where('email', 'test@example.com')->first();
    
    if (!$user) {
        echo "❌ المستخدم التجريبي غير موجود!\n";
        exit(1);
    }
    
    echo "🔑 المستخدم: {$user->name}\n\n";
    
    // عرض جميع العقارات قبل الحذف
    echo "📋 العقارات قبل الحذف:\n";
    $properties = Property::with(['user'])->get();
    foreach ($properties as $property) {
        echo "- ID: {$property->id}, العنوان: {$property->title}, النوع: {$property->property_type}\n";
    }
    echo "\n";
    
    // حذف الشقة (ID: 3)
    $propertyToDelete = Property::find(3);
    if ($propertyToDelete) {
        echo "🗑️ حذف العقار ID: 3 - {$propertyToDelete->title}...\n";
        $propertyToDelete->delete();
        echo "✅ تم حذف العقار بنجاح\n\n";
    } else {
        echo "❌ العقار ID: 3 غير موجود\n\n";
    }
    
    // عرض جميع العقارات بعد الحذف
    echo "📋 العقارات بعد الحذف:\n";
    $propertiesAfter = Property::with(['user'])->get();
    foreach ($propertiesAfter as $property) {
        echo "- ID: {$property->id}, العنوان: {$property->title}, النوع: {$property->property_type}\n";
    }
    
    echo "\n✅ تم اختبار حذف العقار بنجاح\n";
    
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

?>