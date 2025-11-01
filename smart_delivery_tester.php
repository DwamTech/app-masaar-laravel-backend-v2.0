<?php

// سكربت ذكي لإنشاء طلبات توصيل بسرعة واختبار منطق الإسماعيلية
// الاستخدام (أمثلة):
// php smart_delivery_tester.php --base=http://127.0.0.1:8000/api --client_token=XYZ --governorate=الإسماعيلية --count=2
// php smart_delivery_tester.php --base=https://your-domain/api --client_email=user@example.com --client_password=pass --driver_token=ABC --governorate=الإسماعيلية --push=1 --device_token=FCM_TOKEN

ini_set('display_errors', '1');
error_reporting(E_ALL);

function println($msg) { echo $msg . "\n"; }

// قراءة المعاملات
$opts = getopt('', [
  'base::',
  'client_email::', 'client_password::', 'client_token::',
  'driver_email::', 'driver_password::', 'driver_token::',
  'governorate::', 'count::', 'push::', 'device_token::', 'latlon::',
  'price::', 'notes::', 'car_category::', 'payment_method::', 'now::'
]);

$base = $opts['base'] ?? 'http://127.0.0.1:8000/api';
$clientEmail = $opts['client_email'] ?? null;
$clientPassword = $opts['client_password'] ?? null;
$clientToken = $opts['client_token'] ?? null;
$driverEmail = $opts['driver_email'] ?? null;
$driverPassword = $opts['driver_password'] ?? null;
$driverToken = $opts['driver_token'] ?? null;
$governorate = $opts['governorate'] ?? 'الإسماعيلية';
$count = (int)($opts['count'] ?? 1);
$enablePush = isset($opts['push']) ? (bool)$opts['push'] : false;
$deviceToken = $opts['device_token'] ?? null;
$latlon = $opts['latlon'] ?? null; // "latMin,latMax,lngMin,lngMax"
$price = isset($opts['price']) ? floatval($opts['price']) : 50.0;
$notes = $opts['notes'] ?? ('اختبار طلب توصيل - ' . $governorate);
$carCategory = $opts['car_category'] ?? 'economy';
$paymentMethod = $opts['payment_method'] ?? 'cash';
$useNow = isset($opts['now']) ? (bool)$opts['now'] : false; // استخدم الوقت الحالي بدل +2 ساعة

if ($count < 1) { $count = 1; }

function httpRequest($method, $url, $data = null, $token = null) {
  $ch = curl_init();
  $headers = ['Accept: application/json'];
  if ($data !== null) {
    $headers[] = 'Content-Type: application/json';
  }
  if ($token) {
    $headers[] = 'Authorization: Bearer ' . $token;
  }
  curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 5,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
  ]);
  if ($data !== null) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
  }
  $body = curl_exec($ch);
  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $err = curl_error($ch);
  curl_close($ch);
  if ($err) { throw new Exception('cURL Error: ' . $err); }
  $json = json_decode($body, true);
  return ['code' => $code, 'body' => $body, 'json' => $json];
}

function loginOrToken($base, $email, $password, $existingToken = null, $actor = 'client') {
  if ($existingToken) { return $existingToken; }
  if (!$email || !$password) {
    throw new Exception("{$actor}: يجب تمرير client_token أو email/password");
  }
  println("{$actor}: تسجيل الدخول...");
  $res = httpRequest('POST', rtrim($base, '/') . '/login', ['email' => $email, 'password' => $password]);
  if ($res['code'] !== 200 || empty($res['json']['status'])) {
    throw new Exception("فشل تسجيل الدخول ({$actor}): HTTP {$res['code']} - " . ($res['json']['message'] ?? $res['body']));
  }
  $token = $res['json']['token'] ?? null;
  if (!$token) { throw new Exception("لم يتم استرجاع توكن للمستخدم {$actor}"); }
  println("{$actor}: تم تسجيل الدخول بنجاح");
  return $token;
}

function randomInRange($min, $max) {
  return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}

function pickCoords($latlon) {
  if ($latlon) {
    $parts = array_map('trim', explode(',', $latlon));
    if (count($parts) === 4) {
      [$latMin, $latMax, $lngMin, $lngMax] = array_map('floatval', $parts);
      $lat1 = randomInRange($latMin, $latMax);
      $lng1 = randomInRange($lngMin, $lngMax);
      $lat2 = randomInRange($latMin, $latMax);
      $lng2 = randomInRange($lngMin, $lngMax);
      return [$lat1, $lng1, $lat2, $lng2];
    }
  }
  // نطاق افتراضي للإسماعيلية
  $lat1 = randomInRange(30.585, 30.620);
  $lng1 = randomInRange(32.260, 32.295);
  $lat2 = randomInRange(30.585, 30.620);
  $lng2 = randomInRange(32.260, 32.295);
  return [$lat1, $lng1, $lat2, $lng2];
}

function ensureDriverSetup($base, $driverToken, $governorate, $enablePush = false, $deviceToken = null) {
  println('السائق: تحديث المحافظة وتفعيل التوفر...');
  $locRes = httpRequest('POST', rtrim($base, '/') . '/driver/update-location', [
    'governorate' => $governorate,
    'city' => $governorate,
  ], $driverToken);
  if (($locRes['code'] ?? 0) >= 300) {
    throw new Exception('تحديث موقع السائق فشل: HTTP ' . $locRes['code'] . ' - ' . $locRes['body']);
  }

  $availRes = httpRequest('POST', rtrim($base, '/') . '/driver/update-availability', [
    'is_available' => true,
  ], $driverToken);
  if (($availRes['code'] ?? 0) >= 300) {
    throw new Exception('تفعيل توفر السائق فشل: HTTP ' . $availRes['code'] . ' - ' . $availRes['body']);
  }

  if ($enablePush) {
    println('السائق: تفعيل إشعارات الدفع...');
    $pushRes = httpRequest('POST', rtrim($base, '/') . '/notifications/push', null, $driverToken);
    if (($pushRes['code'] ?? 0) >= 300) {
      throw new Exception('تفعيل الإشعارات فشل: HTTP ' . $pushRes['code'] . ' - ' . $pushRes['body']);
    }
    if ($deviceToken) {
      println('السائق: تسجيل توكن الجهاز...');
      $devRes = httpRequest('POST', rtrim($base, '/') . '/device-tokens', [
        'token' => $deviceToken,
        'platform' => 'android',
      ], $driverToken);
      if (($devRes['code'] ?? 0) >= 300) {
        throw new Exception('تسجيل توكن الجهاز فشل: HTTP ' . $devRes['code'] . ' - ' . $devRes['body']);
      }
    }
  }
}

function createDelivery($base, $clientToken, $governorate, $price, $notes, $carCategory, $paymentMethod, $useNow, $latlon) {
  [$lat1, $lng1, $lat2, $lng2] = pickCoords($latlon);
  $deliveryTime = $useNow ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime('+2 hours'));
  $payload = [
    'trip_type' => 'one_way',
    'delivery_time' => $deliveryTime,
    'car_category' => $carCategory,
    'payment_method' => $paymentMethod,
    'price' => $price,
    'notes' => $notes,
    'governorate' => $governorate,
    'destinations' => [
      [
        'location_name' => 'نقطة الانطلاق - ' . $governorate,
        'latitude' => $lat1,
        'longitude' => $lng1,
        'address' => $governorate . '، مصر',
        'is_pickup_point' => true,
        'is_dropoff_point' => false,
      ],
      [
        'location_name' => 'نقطة الوصول - ' . $governorate,
        'latitude' => $lat2,
        'longitude' => $lng2,
        'address' => 'وسط ' . $governorate . '، مصر',
        'is_pickup_point' => false,
        'is_dropoff_point' => true,
      ],
    ],
  ];
  $res = httpRequest('POST', rtrim($base, '/') . '/delivery/requests', $payload, $clientToken);
  if (!in_array($res['code'], [200, 201]) || empty($res['json']['status'])) {
    throw new Exception('فشل إنشاء طلب التوصيل: HTTP ' . $res['code'] . ' - ' . ($res['json']['message'] ?? $res['body']));
  }
  $req = $res['json']['delivery_request'] ?? null;
  if (!$req || !isset($req['id'])) {
    throw new Exception('استجابة غير متوقعة عند إنشاء الطلب: ' . $res['body']);
  }
  println("✓ تم إنشاء طلب بنجاح (ID: {$req['id']}) | المحافظة: " . ($req['governorate'] ?? 'غير محدد'));
  return $req['id'];
}

function checkAvailable($base, $driverToken, $createdIds) {
  println('السائق: فحص الطلبات المتاحة...');
  $res = httpRequest('GET', rtrim($base, '/') . '/delivery/available-requests', null, $driverToken);
  if ($res['code'] !== 200 || empty($res['json']['status'])) {
    throw new Exception('فشل جلب الطلبات المتاحة: HTTP ' . $res['code'] . ' - ' . ($res['json']['message'] ?? $res['body']));
  }
  $list = $res['json']['available_requests']['data'] ?? [];
  println('عدد الطلبات المتاحة: ' . count($list));
  $found = [];
  foreach ($list as $r) {
    if (isset($r['id']) && in_array($r['id'], $createdIds, true)) {
      $found[] = $r['id'];
    }
  }
  if (!empty($found)) {
    println('✓ تم العثور على الطلبات التي أنشأناها: ' . implode(', ', $found));
  } else {
    println('⚠ لم يتم العثور على طلباتنا في قائمة المتاحة (تحقق من محافظة السائق، توفره، ومراجعة السيارة).');
  }
}

// تشغيل التدفق
println('=== Smart Delivery Tester ===');
println('Base: ' . $base);
println('Governorate: ' . $governorate);
println('Count: ' . $count);

try {
  // 1) الحصول على التوكن للعميل
  $clientToken = loginOrToken($base, $clientEmail, $clientPassword, $clientToken, 'client');

  // 2) إذا توفر توكن سائق: ضبطه وتجهيز الإشعارات
  if ($driverEmail || $driverPassword || $driverToken) {
    $driverToken = loginOrToken($base, $driverEmail, $driverPassword, $driverToken, 'driver');
    ensureDriverSetup($base, $driverToken, $governorate, $enablePush, $deviceToken);
  }

  // 3) إنشاء الطلبات
  $createdIds = [];
  for ($i = 1; $i <= $count; $i++) {
    println("إنشاء طلب رقم {$i}...");
    $createdIds[] = createDelivery($base, $clientToken, $governorate, $price, $notes, $carCategory, $paymentMethod, $useNow, $latlon);
  }

  // 4) التحقق من الطلبات المتاحة عند السائق (إن توفر)
  if (!empty($driverToken)) {
    checkAvailable($base, $driverToken, $createdIds);
  }

  println("\n=== انتهى بنجاح ===");
} catch (Exception $e) {
  println('خطأ: ' . $e->getMessage());
  exit(1);
}

?>