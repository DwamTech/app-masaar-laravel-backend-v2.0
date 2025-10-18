<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Property;

try {
    // البحث عن المستخدم التجريبي
    $user = User::where('email', 'test@example.com')->first();
    
    if (!$user) {
        echo "❌ المستخدم التجريبي غير موجود!\n";
        exit(1);
    }
    
    echo "🔑 المستخدم: {$user->name}\n\n";
    
    // عرض جميع العقارات المتاحة
    echo "📋 جميع العقارات المتاحة:\n";
    echo "=" . str_repeat("=", 50) . "\n";
    
    $properties = Property::with(['user'])->get();
    
    if ($properties->isEmpty()) {
        echo "❌ لا توجد عقارات في قاعدة البيانات\n";
    } else {
        echo "📊 إجمالي العقارات: " . $properties->count() . "\n\n";
        
        foreach ($properties as $property) {
            echo "🏠 العقار ID: {$property->id}\n";
            echo "   العنوان: {$property->title}\n";
            echo "   النوع: {$property->property_type}\n";
            echo "   السعر: {$property->property_price} {$property->currency}\n";
            echo "   الحالة: {$property->property_status}\n";
            echo "   المالك: {$property->user->name}\n";
            echo "   تاريخ الإنشاء: {$property->created_at}\n";
            echo "   تاريخ التحديث: {$property->updated_at}\n";
            echo "   " . str_repeat("-", 40) . "\n";
        }
        
        // التحقق من عدم وجود العقار المحذوف (ID: 3)
        $deletedProperty = Property::find(3);
        if ($deletedProperty) {
            echo "❌ خطأ: العقار المحذوف (ID: 3) ما زال موجوداً!\n";
        } else {
            echo "✅ تأكيد: العقار المحذوف (ID: 3) غير موجود كما هو متوقع\n";
        }
        
        // عرض ملخص العقارات حسب النوع
        echo "\n📊 ملخص العقارات حسب النوع:\n";
        $propertyTypes = $properties->groupBy('property_type');
        foreach ($propertyTypes as $type => $typeProperties) {
            echo "   {$type}: " . $typeProperties->count() . " عقار\n";
        }
        
        // عرض ملخص العقارات حسب الحالة
        echo "\n📊 ملخص العقارات حسب الحالة:\n";
        $propertyStatuses = $properties->groupBy('property_status');
        foreach ($propertyStatuses as $status => $statusProperties) {
            echo "   {$status}: " . $statusProperties->count() . " عقار\n";
        }
    }
    
    echo "\n✅ تم عرض جميع العقارات بنجاح\n";
    
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

?>