<?php
// Full scenario test for car orders: create by normal user, visible to two car rental offices, accept by first, verify status, attempt accept by second.

$baseUrl = getenv('BASE_URL') ?: 'http://127.0.0.1:8000';

function apiRequest($method, $path, $token = null, $data = null) {
    global $baseUrl;
    $url = rtrim($baseUrl, '/') . $path;
    $ch = curl_init();
    $headers = [
        'Accept: application/json',
        'Content-Type: application/json'
    ];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_HTTPHEADER => $headers,
    ]);
    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    $responseBody = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) {
        throw new Exception('cURL error: ' . $err);
    }
    $json = json_decode($responseBody, true);
    return [$httpCode, $json, $responseBody];
}

function login($email, $password = 'password') {
    list($code, $json) = apiRequest('POST', '/api/login', null, [
        'email' => $email,
        'password' => $password,
    ]);
    if ($code !== 200 || empty($json['status'])) {
        throw new Exception("Login failed for {$email}: HTTP {$code} - " . ($json['message'] ?? 'Unknown error'));
    }
    return $json;
}

function assertTrue($cond, $msg) {
    if (!$cond) { throw new Exception('Assertion failed: ' . $msg); }
}

try {
    echo "== Step 1: Login as normal user ==\n";
    $normalLogin = login('normal@example.com');
    $normalToken = $normalLogin['token'];
    echo "Logged in normal user. Token acquired.\n";

    echo "== Step 2: Create car rent order (no car_rental_id) ==\n";
    $orderPayload = [
        'provider_type' => 'office',
        'with_driver' => false,
        'car_category' => 'economy',
        'car_model' => 'Toyota Yaris',
        'rental_start_at' => date('Y-m-d H:i:s', strtotime('+1 day')),
        'rental_end_at' => date('Y-m-d H:i:s', strtotime('+4 days')),
        'requested_price' => 200,
        'notes' => 'A/C, automatic',
    ];
    list($codeCreate, $createJson, $rawCreate) = apiRequest('POST', '/api/car-orders', $normalToken, $orderPayload);
    echo "Create response code: {$codeCreate}\n";
    if (($codeCreate !== 200 && $codeCreate !== 201) || empty($createJson['status'])) {
        echo $rawCreate . "\n";
        throw new Exception('Failed to create car order');
    }
    $order = $createJson['order'] ?? null;
    assertTrue(!empty($order), 'Order not returned');
    assertTrue(($order['status'] ?? null) === 'pending_provider', 'Initial status should be pending_provider');
    $orderId = $order['id'];
    echo "Order #{$orderId} created with status: {$order['status']}\n";

    echo "== Step 3: Login as two car rental offices ==\n";
    $office1Login = login('car_rental_office@example.com');
    $office2Login = login('car_rental_office2@example.com');
    $office1Token = $office1Login['token'];
    $office2Token = $office2Login['token'];
    $office1CarRentalId = $office1Login['car_rental_id'] ?? null;
    $office2CarRentalId = $office2Login['car_rental_id'] ?? null;
    assertTrue(!empty($office1CarRentalId), 'Office1 car_rental_id missing');
    assertTrue(!empty($office2CarRentalId), 'Office2 car_rental_id missing');
    echo "Office1 car_rental_id={$office1CarRentalId}, Office2 car_rental_id={$office2CarRentalId}\n";

    echo "== Step 4: Both offices list pending_provider orders ==\n";
    list($codeList1, $list1Json, $rawList1) = apiRequest('GET', '/api/car-orders?status=pending_provider', $office1Token);
    list($codeList2, $list2Json, $rawList2) = apiRequest('GET', '/api/car-orders?status=pending_provider', $office2Token);
    assertTrue($codeList1 === 200 && !empty($list1Json['status']), 'Office1 list failed');
    assertTrue($codeList2 === 200 && !empty($list2Json['status']), 'Office2 list failed');
    $ids1 = array_map(fn($o) => $o['id'], $list1Json['orders'] ?? []);
    $ids2 = array_map(fn($o) => $o['id'], $list2Json['orders'] ?? []);
    assertTrue(in_array($orderId, $ids1, true), 'Order not visible to office1');
    assertTrue(in_array($orderId, $ids2, true), 'Order not visible to office2');
    echo "Order #{$orderId} visible to both offices.\n";

    echo "== Step 5: Office1 accepts the order ==\n";
    list($codeAccept1, $accept1Json, $rawAccept1) = apiRequest('POST', "/api/car-orders/{$orderId}/accept-by-provider", $office1Token, [
        'agreed_price' => 220,
    ]);
    echo "Office1 accept response: {$codeAccept1}\n";
    if ($codeAccept1 !== 200 || empty($accept1Json['status'])) {
        echo $rawAccept1 . "\n";
        throw new Exception('Office1 failed to accept order');
    }
    $acceptedOrder = $accept1Json['order'] ?? [];
    assertTrue(($acceptedOrder['status'] ?? null) === 'accepted', 'Order status should be accepted after Office1 acceptance');
    assertTrue(($acceptedOrder['car_rental_id'] ?? null) === $office1CarRentalId, 'car_rental_id should match Office1');
    echo "Order accepted by Office1. Status={$acceptedOrder['status']}, car_rental_id={$acceptedOrder['car_rental_id']}\n";

    echo "== Step 6: Fetch order details after acceptance ==\n";
    list($codeShow, $showJson, $rawShow) = apiRequest('GET', "/api/car-orders/{$orderId}", $normalToken);
    assertTrue($codeShow === 200 && !empty($showJson['status']), 'Show order failed');
    $detail = $showJson['order'] ?? [];
    echo "Order status after acceptance: " . ($detail['status'] ?? 'N/A') . "\n";

    echo "== Step 7: Office2 tries to accept the same order (should fail) ==\n";
    list($codeAccept2, $accept2Json, $rawAccept2) = apiRequest('POST', "/api/car-orders/{$orderId}/accept-by-provider", $office2Token, [
        'agreed_price' => 210,
    ]);
    echo "Office2 accept response: {$codeAccept2}\n";
    assertTrue($codeAccept2 === 409 || ($accept2Json['status'] ?? false) === false, 'Second acceptance should be blocked');
    echo "Blocking second acceptance works as expected.\n";

    echo "== Scenario completed successfully ==\n";
} catch (Exception $e) {
    fwrite(STDERR, "ERROR: " . $e->getMessage() . "\n");
    exit(1);
}

?>