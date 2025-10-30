<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Property;

// ------------------------------------
// إعداد المستخدم والتوكن (حدث التوكن حسب بيئتك)
// ------------------------------------
$user = User::where('email', 'test@example.com')->first();
if (!$user) {
    echo "❌ المستخدم التجريبي غير موجود\n";
    exit(1);
}

$token = 'REPLACE_WITH_VALID_TOKEN';
if (str_starts_with($token, 'REPLACE')) {
    echo "⚠️ الرجاء تحديث قيمة التوكن في الملف قبل التجربة\n\n";
}

$baseUrl = 'http://127.0.0.1:8000/api';

function makeRequest($method, $url, $data = null, $token = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    $headers = ['Accept: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    if ($data && in_array($method, ['POST', 'DELETE'])) {
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'status_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

// ------------------------------------
// جلب معرفات لعناصر للتجربة
// ------------------------------------
$restaurant = User::where('user_type', 'restaurant')->first();
$property = Property::first();

if (!$restaurant || !$property) {
    echo "❌ يلزم وجود مطعم وعقار على الأقل في قاعدة البيانات للتجربة\n";
    exit(1);
}

echo "✅ مطعم تجريبي: ID=" . $restaurant->id . "\n";
echo "✅ عقار تجريبي: ID=" . $property->id . "\n\n";

// ------------------------------------
// إضافة للمفضلة
// ------------------------------------
echo "💖 إضافة المطعم إلى المفضلة...\n";
$addRestaurant = makeRequest('POST', $baseUrl . '/favorites', [
    'id' => $restaurant->id,
    'type' => 'restaurant',
], $token);
echo "Status: {$addRestaurant['status_code']}\n";
echo json_encode($addRestaurant['response'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";

echo "🏠 إضافة العقار إلى المفضلة...\n";
$addProperty = makeRequest('POST', $baseUrl . '/favorites', [
    'id' => $property->id,
    'type' => 'property',
], $token);
echo "Status: {$addProperty['status_code']}\n";
echo json_encode($addProperty['response'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";

// ------------------------------------
// عرض المفضلة
// ------------------------------------
echo "📋 عرض قائمة المفضلة...\n";
$listFavorites = makeRequest('GET', $baseUrl . '/favorites', null, $token);
echo "Status: {$listFavorites['status_code']}\n";
echo json_encode($listFavorites['response'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";

// ------------------------------------
// فحص حالة عنصر
// ------------------------------------
echo "🔍 فحص هل المطعم مُضاف للمفضلة؟\n";
$checkRestaurant = makeRequest('GET', $baseUrl . "/favorites/check?id={$restaurant->id}&type=restaurant", null, $token);
echo "Status: {$checkRestaurant['status_code']}\n";
echo json_encode($checkRestaurant['response'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";

echo "🔍 جلب معلومات مصغرة للعقار...\n";
$itemProperty = makeRequest('GET', $baseUrl . "/favorites/item?id={$property->id}&type=property", null, $token);
echo "Status: {$itemProperty['status_code']}\n";
echo json_encode($itemProperty['response'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";

// ------------------------------------
// إزالة من المفضلة
// ------------------------------------
echo "🗑️ إزالة العقار من المفضلة...\n";
$delProperty = makeRequest('DELETE', $baseUrl . '/favorites', [
    'id' => $property->id,
    'type' => 'property',
], $token);
echo "Status: {$delProperty['status_code']}\n";
echo json_encode($delProperty['response'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";

echo "🗑️ إزالة المطعم من المفضلة...\n";
$delRestaurant = makeRequest('DELETE', $baseUrl . '/favorites', [
    'id' => $restaurant->id,
    'type' => 'restaurant',
], $token);
echo "Status: {$delRestaurant['status_code']}\n";
echo json_encode($delRestaurant['response'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";

echo "✅ تم تنفيذ اختبار المفضلة بنجاح.\n";