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
    
    // البحث عن الفيلا (ID: 2)
    $villa = Property::find(2);
    if (!$villa) {
        echo "❌ الفيلا ID: 2 غير موجودة\n";
        exit(1);
    }
    
    echo "🏠 العقار قبل التعديل:\n";
    echo "- ID: {$villa->id}\n";
    echo "- العنوان: {$villa->title}\n";
    echo "- السعر: {$villa->property_price} {$villa->currency}\n";
    echo "- الحالة: {$villa->property_status}\n";
    echo "- النوع: {$villa->property_type}\n\n";
    
    // تعديل العقار
    echo "✏️ تعديل العقار...\n";
    $villa->update([
        'property_price' => 6500000.00,
        'property_status' => 'sold',
        'title' => 'فيلا فاخرة مباعة في القاهرة الجديدة',
        'description' => 'فيلا فاخرة تم بيعها بنجاح في منطقة التجمع الخامس'
    ]);
    
    // إعادة تحميل العقار من قاعدة البيانات
    $villa->refresh();
    
    echo "🏠 العقار بعد التعديل:\n";
    echo "- ID: {$villa->id}\n";
    echo "- العنوان: {$villa->title}\n";
    echo "- السعر: {$villa->property_price} {$villa->currency}\n";
    echo "- الحالة: {$villa->property_status}\n";
    echo "- النوع: {$villa->property_type}\n";
    echo "- الوصف: {$villa->description}\n\n";
    
    echo "✅ تم تعديل العقار بنجاح\n";
    
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

?>