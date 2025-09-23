<?php

// إعدادات الاختبار
$baseUrl = 'http://127.0.0.1:8000/api';

// دالة لإرسال طلبات HTTP
function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    
    // إضافة Accept header بشكل افتراضي
    $headers[] = 'Accept: application/json';
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $headers[] = 'Content-Type: application/json';
        }
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    if ($error) {
        throw new Exception('cURL Error: ' . $error);
    }
    
    return [
        'body' => $response,
        'http_code' => $httpCode
    ];
}

// بيانات تسجيل الدخول
$loginData = [
    'email' => 'test@example.com',
    'password' => 'password123'
];

echo "=== اختبار وظيفة المحافظة في طلبات التوصيل ===\n\n";

try {
    // 1. تسجيل الدخول
    echo "1. تسجيل الدخول...\n";
    $loginResponse = makeRequest($baseUrl . '/login', 'POST', $loginData);
    
    if ($loginResponse['http_code'] !== 200) {
        throw new Exception('HTTP Error: ' . $loginResponse['http_code'] . ' - ' . $loginResponse['body']);
    }
    
    $loginResult = json_decode($loginResponse['body'], true);
    
    echo "Response body: " . $loginResponse['body'] . "\n";
    echo "HTTP Code: " . $loginResponse['http_code'] . "\n";
    
    if (!$loginResult || !isset($loginResult['status']) || !$loginResult['status']) {
        throw new Exception('فشل في تسجيل الدخول: ' . json_encode($loginResult));
    }
    
    $token = $loginResult['token'];
    echo "✓ تم تسجيل الدخول بنجاح\n\n";
    
    // 2. إنشاء طلب توصيل مع المحافظة
    echo "2. إنشاء طلب توصيل مع محافظة الإسماعيلية...\n";
    
    $deliveryData = [
        'trip_type' => 'one_way',
        'delivery_time' => date('Y-m-d H:i:s', strtotime('+2 hours')),
        'car_category' => 'economy',
        'payment_method' => 'cash',
        'price' => 50.0,
        'notes' => 'اختبار طلب توصيل مع المحافظة',
        'governorate' => 'الإسماعيلية',
        'destinations' => [
            [
                'location_name' => 'نقطة الانطلاق - الإسماعيلية',
                'latitude' => 30.5965,
                'longitude' => 32.2715,
                'address' => 'الإسماعيلية، مصر',
                'is_pickup_point' => true,
                'is_dropoff_point' => false
            ],
            [
                'location_name' => 'نقطة الوصول - الإسماعيلية',
                'latitude' => 30.6041,
                'longitude' => 32.2723,
                'address' => 'وسط الإسماعيلية، مصر',
                'is_pickup_point' => false,
                'is_dropoff_point' => true
            ]
        ]
    ];
    
    $deliveryResponse = makeRequest(
        $baseUrl . '/delivery/requests', 
        'POST', 
        $deliveryData,
        [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]
    );
    
    if ($deliveryResponse['http_code'] !== 200 && $deliveryResponse['http_code'] !== 201) {
        throw new Exception('HTTP Error: ' . $deliveryResponse['http_code'] . ' - ' . $deliveryResponse['body']);
    }
    
    $deliveryResult = json_decode($deliveryResponse['body'], true);
    
    if (!$deliveryResult['status']) {
        throw new Exception('فشل في إنشاء طلب التوصيل: ' . json_encode($deliveryResult));
    }
    
    $requestId = $deliveryResult['delivery_request']['id'];
    echo "✓ تم إنشاء طلب التوصيل بنجاح (ID: {$requestId})\n";
    echo "✓ المحافظة المحفوظة: " . $deliveryResult['delivery_request']['governorate'] . "\n\n";
    
    // 3. التحقق من الطلبات المتاحة للسائقين (محاكاة سائق من الإسماعيلية)
    echo "3. فحص الطلبات المتاحة للسائقين...\n";
    
    $availableResponse = makeRequest(
        $baseUrl . '/delivery/available-requests', 
        'GET', 
        null,
        [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]
    );
    
    if ($availableResponse['http_code'] !== 200) {
        throw new Exception('HTTP Error: ' . $availableResponse['http_code'] . ' - ' . $availableResponse['body']);
    }
    
    $availableResult = json_decode($availableResponse['body'], true);
    
    if ($availableResult['status']) {
        $requests = $availableResult['available_requests']['data'] ?? [];
        echo "✓ تم جلب " . count($requests) . " طلب متاح\n";
        
        // البحث عن الطلب الذي أنشأناه
        $foundRequest = null;
        foreach ($requests as $request) {
            if ($request['id'] == $requestId) {
                $foundRequest = $request;
                break;
            }
        }
        
        if ($foundRequest) {
            echo "✓ تم العثور على الطلب في قائمة الطلبات المتاحة\n";
            echo "✓ محافظة الطلب: " . ($foundRequest['governorate'] ?? 'غير محدد') . "\n";
        } else {
            echo "⚠ لم يتم العثور على الطلب في قائمة الطلبات المتاحة\n";
        }
    } else {
        echo "⚠ فشل في جلب الطلبات المتاحة: " . json_encode($availableResult) . "\n";
    }
    
    echo "\n=== انتهى الاختبار بنجاح ===\n";
    
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}