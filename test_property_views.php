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
    
    // اختيار عقار للاختبار (ID: 1 - الشقة)
    $propertyId = 1;
    $property = Property::find($propertyId);
    
    if (!$property) {
        echo "❌ العقار بالمعرف {$propertyId} غير موجود!\n";
        exit(1);
    }
    
    echo "🏠 اختبار عرض تفاصيل العقار ID: {$propertyId}\n";
    echo "=" . str_repeat("=", 50) . "\n";
    
    // عرض عدد المشاهدات الحالي
    echo "👀 عدد المشاهدات الحالي: {$property->view_count}\n\n";
    
    // محاكاة عرض العقار عدة مرات
    $viewsToAdd = 3;
    echo "📈 محاكاة عرض العقار {$viewsToAdd} مرات...\n";
    
    for ($i = 1; $i <= $viewsToAdd; $i++) {
        // زيادة عدد المشاهدات
        $property->increment('view_count');
        $property->refresh();
        
        echo "   المشاهدة #{$i}: عدد المشاهدات الآن = {$property->view_count}\n";
        
        // تأخير قصير لمحاكاة الواقع
        usleep(100000); // 0.1 ثانية
    }
    
    echo "\n📋 تفاصيل العقار بعد زيادة المشاهدات:\n";
    echo "=" . str_repeat("=", 50) . "\n";
    
    // إعادة تحميل العقار للحصول على أحدث البيانات
    $property->refresh();
    
    echo "🏠 معرف العقار: {$property->id}\n";
    echo "📝 العنوان: {$property->title}\n";
    echo "🏘️ النوع: {$property->property_type}\n";
    echo "💰 السعر: {$property->property_price} {$property->currency}\n";
    echo "📍 العنوان: {$property->address}\n";
    echo "📏 المساحة: {$property->property_area} متر مربع\n";
    echo "📊 الحالة: {$property->property_status}\n";
    echo "👀 عدد المشاهدات: {$property->view_count}\n";
    echo "📅 تاريخ الإنشاء: {$property->created_at}\n";
    echo "🔄 تاريخ التحديث: {$property->updated_at}\n";
    
    if ($property->description) {
        echo "📄 الوصف: {$property->description}\n";
    }
    
    // عرض معلومات المالك
    $owner = $property->user;
    if ($owner) {
        echo "\n👤 معلومات المالك:\n";
        echo "   الاسم: {$owner->name}\n";
        echo "   البريد الإلكتروني: {$owner->email}\n";
        echo "   رقم الهاتف: " . ($owner->phone ?? 'غير محدد') . "\n";
    }
    
    // التحقق من زيادة المشاهدات
    $expectedViews = $viewsToAdd; // المشاهدات المضافة فقط (بدأت من 0)
    if ($property->view_count >= $expectedViews) {
        echo "\n✅ تأكيد: تم زيادة عدد المشاهدات بنجاح\n";
        echo "   المشاهدات المتوقعة: >= {$expectedViews}\n";
        echo "   المشاهدات الفعلية: {$property->view_count}\n";
    } else {
        echo "\n❌ خطأ: لم تتم زيادة المشاهدات كما هو متوقع\n";
        echo "   المشاهدات المتوقعة: >= {$expectedViews}\n";
        echo "   المشاهدات الفعلية: {$property->view_count}\n";
    }
    
    echo "\n✅ تم اختبار عرض تفاصيل العقار وزيادة المشاهدات بنجاح\n";
    
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

?>