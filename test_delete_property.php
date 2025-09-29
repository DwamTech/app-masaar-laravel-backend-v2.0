<?php

require_once 'vendor/autoload.php';

function makeRequest($method, $url, $data = null, $token = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $headers = ['Content-Type: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ['status' => $httpCode, 'response' => $response];
}

// تسجيل الدخول للحصول على التوكن
$loginData = [
    'email' => 'test@example.com',
    'password' => 'password123'
];

$loginResult = makeRequest('POST', 'http://127.0.0.1:8000/api/login', $loginData);
$loginResponse = json_decode($loginResult['response'], true);

if ($loginResult['status'] !== 200 || !isset($loginResponse['token'])) {
    echo "❌ فشل في تسجيل الدخول\n";
    echo "Status: " . $loginResult['status'] . "\n";
    echo "Response: " . $loginResult['response'] . "\n";
    exit;
}

$token = $loginResponse['token'];
echo "🔑 التوكن: " . substr($token, 0, 20) . "...\n\n";

// حذف الشقة (ID: 3)
echo "🗑️ اختبار حذف الشقة (ID: 3)...\n";
$deleteResult = makeRequest('DELETE', 'http://127.0.0.1:8000/api/properties/3', null, $token);
echo "Status: " . $deleteResult['status'] . "\n";
echo "Response: " . $deleteResult['response'] . "\n\n";

// عرض جميع العقارات للتأكد من الحذف
echo "📋 عرض جميع العقارات بعد الحذف...\n";
$propertiesResult = makeRequest('GET', 'http://127.0.0.1:8000/api/properties', null, $token);
echo "Status: " . $propertiesResult['status'] . "\n";
echo "Response: " . $propertiesResult['response'] . "\n";

?>