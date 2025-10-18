<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Property;
use App\Http\Controllers\PropertyController;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// --- الإعداد للاختبار ---

// العثور على مستخدم للاختبار
$user = User::first();
if (!$user) {
    echo "لم يتم العثور على أي مستخدم. يرجى التأكد من وجود مستخدمين في قاعدة البيانات.\n";
    exit(1);
}

// تعيين بعض العقارات كمميزة
Property::whereIn('id', [1, 2])->update(['is_featured' => false, 'property_status' => 'available']); // إعادة تعيين
Property::where('id', 1)->update(['is_featured' => true]);

echo "تم تعيين العقار رقم 1 كمميز.\n";

// --- تنفيذ الاختبار ---

$controller = new PropertyController();
$request = new Request();

echo "--- عرض العقارات المميزة قبل التعديل ---\n";
$response_before = $controller->featured($request);
$data_before = json_decode($response_before->getContent(), true);

if ($data_before['status']) {
    echo "تم العثور على " . count($data_before['properties']) . " عقار مميز.\n";
    foreach ($data_before['properties'] as $property) {
        echo "  - العقار رقم: " . $property['id'] . " (مميز: " . ($property['is_featured'] ? 'نعم' : 'لا') . ")\n";
    }
} else {
    echo "فشل في جلب العقارات المميزة.\n";
}

// تعديل العقارات المميزة
Property::where('id', 1)->update(['is_featured' => false]);
Property::where('id', 2)->update(['is_featured' => true]);

echo "\nتم تغيير العقار المميز إلى العقار رقم 2.\n";

echo "\n--- عرض العقارات المميزة بعد التعديل ---\n";
$response_after = $controller->featured($request);
$data_after = json_decode($response_after->getContent(), true);

if ($data_after['status']) {
    echo "تم العثور على " . count($data_after['properties']) . " عقار مميز.\n";
    $featured_property_id = null;
    foreach ($data_after['properties'] as $property) {
        echo "  - العقار رقم: " . $property['id'] . " (مميز: " . ($property['is_featured'] ? 'نعم' : 'لا') . ")\n";
        if ($property['is_featured']) {
            $featured_property_id = $property['id'];
        }
    }

    // --- التحقق من النتيجة ---
    if ($featured_property_id == 2) {
        echo "\n>> الاختبار نجح: تم عرض العقار المميز الصحيح.\n";
    } else {
        echo "\n>> الاختبار فشل: لم يتم عرض العقار المميز الصحيح.\n";
    }
} else {
    echo "فشل في جلب العقارات المميزة بعد التعديل.\n";
}