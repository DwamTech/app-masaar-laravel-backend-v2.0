<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get test user token
$token = '21|sjlpfZbeGMkr3hf6lNCJyNyqfuS7LYipiewW5Wbkf5643112';
echo "🔑 التوكن: " . substr($token, 0, 20) . "...\n\n";

// Create test image
$imagePath = __DIR__ . '/test_image.jpg';
if (!file_exists($imagePath)) {
    $imageData = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A');
    file_put_contents($imagePath, $imageData);
}

function makeRequest($method, $url, $data = [], $files = []) {
    global $token;
    
    $ch = curl_init();
    $fullUrl = 'http://127.0.0.1:8000' . $url;
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $fullUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10
    ]);
    
    if ($method === 'POST' && (!empty($data) || !empty($files))) {
        $postData = [];
        
        // Add regular data
        foreach ($data as $key => $value) {
            $postData[$key] = $value;
        }
        
        // Add files
        foreach ($files as $key => $filePath) {
            if (file_exists($filePath)) {
                $postData[$key] = new CURLFile($filePath);
            }
        }
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    }
    
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['status' => $statusCode, 'data' => ['error' => $error]];
    }
    
    return ['status' => $statusCode, 'data' => json_decode($response, true)];
}

// Test apartment data with unique property code
$apartmentData = [
    'title' => 'شقة حديثة للإيجار في الشيخ زايد',
    'ownership_type' => 'leasehold',
    'property_price' => 15000,
    'currency' => 'EGP',
    'property_code' => 'APT_002', // Changed to unique code
    'advertiser_type' => 'broker',
    'contact_info' => json_encode(['phone' => '01117654321']),
    'location' => json_encode([
        'latitude' => 30.0074,
        'longitude' => 30.9839,
        'formatted_address' => 'الحي الثامن، الشيخ زايد'
    ]),
    'address' => 'الحي الثامن، الشيخ زايد، الجيزة',
    'old_type' => 'apartment',
    'bedrooms' => 3,
    'bathrooms' => 2,
    'size_in_sqm' => 180,
    'property_status' => 'available', // Changed from 'rented' to 'available'
    'property_type' => 'apartment'
];

echo "🏢 اختبار إضافة الشقة...\n";
$response = makeRequest('POST', '/api/properties', $apartmentData, ['main_image' => $imagePath]);
echo "Status: " . $response['status'] . "\n";
echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// Check all properties
echo "📋 عرض جميع العقارات...\n";
$response = makeRequest('GET', '/api/properties');
echo "Status: " . $response['status'] . "\n";
echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";