<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

// الحصول على المستخدم والتوكن
$user = User::where('email', 'test@example.com')->first();
if (!$user) {
    echo "❌ المستخدم غير موجود\n";
    exit(1);
}

// Get test user token
$token = '21|sjlpfZbeGMkr3hf6lNCJyNyqfuS7LYipiewW5Wbkf5643112';
echo "🔑 التوكن: " . substr($token, 0, 20) . "...\n\n";

// إعداد البيانات
$baseUrl = 'http://127.0.0.1:8000/api';

// إنشاء صورة وهمية للاختبار
$imagePath = __DIR__ . '/test_image.jpg';
if (!file_exists($imagePath)) {
    // إنشاء صورة بسيطة 100x100 بكسل
    $image = imagecreate(100, 100);
    $backgroundColor = imagecolorallocate($image, 255, 255, 255);
    $textColor = imagecolorallocate($image, 0, 0, 0);
    imagestring($image, 5, 30, 40, 'TEST', $textColor);
    imagejpeg($image, $imagePath);
    imagedestroy($image);
}

// Villa data
$villaData = [
    'title' => 'فيلا فاخرة للبيع في القاهرة الجديدة',
    'ownership_type' => 'freehold',
    'property_price' => 5000000,
    'currency' => 'EGP',
    'property_code' => 'VILLA_001',
    'advertiser_type' => 'developer',
    'contact_info' => json_encode(['phone' => '01001234567']),
    'location' => json_encode([
        'latitude' => 30.033333,
        'longitude' => 31.233334,
        'formatted_address' => 'التجمع الخامس، القاهرة الجديدة'
    ]),
    'address' => 'التجمع الخامس، القاهرة الجديدة', // إضافة حقل address المطلوب
    'old_type' => 'villa',
    'bedrooms' => 5,
    'bathrooms' => 6,
    'size_in_sqm' => 450,
    'property_status' => 'available',
    'property_type' => 'villa'
];

// Apartment data
$apartmentData = [
    'title' => 'شقة حديثة للإيجار في الشيخ زايد',
    'ownership_type' => 'leasehold',
    'property_price' => 15000,
    'currency' => 'EGP',
    'property_code' => 'APT_001',
    'advertiser_type' => 'broker',
    'contact_info' => json_encode(['phone' => '01117654321']),
    'location' => json_encode([
        'latitude' => 30.0074,
        'longitude' => 30.9839,
        'formatted_address' => 'الحي الثامن، الشيخ زايد'
    ]),
    'address' => 'الحي الثامن، الشيخ زايد', // إضافة حقل address المطلوب
    'old_type' => 'apartment',
    'bedrooms' => 3,
    'bathrooms' => 2,
    'size_in_sqm' => 180,
    'property_status' => 'rented',
    'property_type' => 'apartment'
];

function makeRequest($method, $url, $data = null, $token = null, $files = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    $headers = [
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    if ($files) {
        // استخدام multipart/form-data للملفات
        $postData = $data;
        foreach ($files as $key => $filePath) {
            $postData[$key] = new CURLFile($filePath);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    } else {
        // استخدام JSON للبيانات العادية
        $headers[] = 'Content-Type: application/json';
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
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

echo "🏠 اختبار إضافة الفيلا الأولى...\n";
$result1 = makeRequest('POST', $baseUrl . '/properties', $villaData, $token, ['main_image' => $imagePath]);
echo "Status: " . $result1['status_code'] . "\n";
echo "Response: " . json_encode($result1['response'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";

echo "🏢 اختبار إضافة الشقة الثانية...\n";
$result2 = makeRequest('POST', $baseUrl . '/properties', $apartmentData, $token, ['main_image' => $imagePath]);
echo "Status: " . $result2['status_code'] . "\n";
echo "Response: " . json_encode($result2['response'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";

echo "📋 عرض جميع العقارات...\n";
$result3 = makeRequest('GET', $baseUrl . '/properties');
echo "Status: " . $result3['status_code'] . "\n";
echo "Response: " . json_encode($result3['response'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";