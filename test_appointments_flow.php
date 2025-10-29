<?php
// Full scenario test for appointments workflow:
// - Normal user creates appointment with preferred window
// - Admin schedules within that window
// - Admin reschedules to another time within window
// - Provider approves the appointment

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
    list($code, $json, $raw) = apiRequest('POST', '/api/login', null, [
        'email' => $email,
        'password' => $password,
    ]);
    if ($code !== 200 || empty($json['status'])) {
        fwrite(STDERR, "Login failed for {$email}: HTTP {$code}\n{$raw}\n");
        throw new Exception("Login failed for {$email}");
    }
    return $json; // contains token and extra info
}

function assertTrue($cond, $msg) {
    if (!$cond) { throw new Exception('Assertion failed: ' . $msg); }
}

function getNotifications($token) {
    list($code, $json, $raw) = apiRequest('GET', '/api/notifications', $token);
    if ($code !== 200 || empty($json['status'])) {
        fwrite(STDERR, "Fetch notifications failed: HTTP {$code}\n{$raw}\n");
        throw new Exception('Failed to fetch notifications');
    }
    return $json['notifications'] ?? [];
}

function countNotificationsByType($notifications, $type) {
    return array_reduce($notifications, function($carry, $n) use ($type) {
        return $carry + ((isset($n['type']) && $n['type'] === $type) ? 1 : 0);
    }, 0);
}

function findNotificationByTypeContains($notifications, $type, $needle) {
    foreach ($notifications as $n) {
        if (($n['type'] ?? null) === $type) {
            $title = $n['title'] ?? '';
            $msg   = $n['message'] ?? '';
            if ((is_string($title) && strpos($title, $needle) !== false) || (is_string($msg) && strpos($msg, $needle) !== false)) {
                return $n;
            }
        }
    }
    return null;
}

function matchesMinute($datetimeStr, $ymdhm) {
    if (!is_string($datetimeStr) || !is_string($ymdhm)) return false;
    $left = str_replace('T', ' ', $datetimeStr);
    $left = rtrim($left, 'Z');
    // إزالة جزء الميكروثواني إن وجد
    $dotPos = strpos($left, '.');
    if ($dotPos !== false) {
        $left = substr($left, 0, $dotPos);
    }
    $left = substr($left, 0, 16);
    $right = substr(str_replace('T', ' ', $ymdhm), 0, 16);
    return $left === $right;
}

try {
    echo "== Step 0: Pick a property (public) ==\n";
    list($codeProps, $propsJson, $rawProps) = apiRequest('GET', '/api/properties');
    if ($codeProps !== 200 || empty($propsJson['status'])) {
        echo $rawProps . "\n";
        throw new Exception('Failed to list properties; ensure seeding and server running.');
    }
    $properties = $propsJson['properties'] ?? [];
    assertTrue(!empty($properties), 'No properties found; run seeds first.');
    $property = $properties[0];
    $propertyId = $property['id'];
    echo "Using property #{$propertyId}\n";

    echo "== Step 0.1: Fetch property details to resolve provider email ==\n";
    list($codeShowProp, $showPropJson, $rawShowProp) = apiRequest('GET', "/api/properties/{$propertyId}");
    if ($codeShowProp !== 200 || empty($showPropJson['status'])) {
        echo $rawShowProp . "\n";
        throw new Exception('Failed to get property details');
    }
    $propDetail = $showPropJson['property'] ?? [];
    $providerEmail = $propDetail['user']['email'] ?? null;
    $providerIdFromProperty = $propDetail['user']['id'] ?? null;
    assertTrue(!empty($providerEmail), 'Provider email missing on property');
    echo "Provider email for property: {$providerEmail}\n";

    echo "== Step 1: Login as normal user ==\n";
    $normalLogin = login('normal@example.com');
    $normalToken = $normalLogin['token'];
    echo "Logged in normal user.\n";

    echo "== Step 2: Create appointment with preferred window ==\n";
    $day = date('Y-m-d', strtotime('+1 day'));
    $preferredFrom = $day . ' 12:00';
    $preferredTo   = $day . ' 18:00';
    $createPayload = [
        'property_id'    => $propertyId,
        'preferred_from' => $preferredFrom,
        'preferred_to'   => $preferredTo,
        'note'           => 'I am available in the afternoon window.',
    ];
    list($codeCreate, $createJson, $rawCreate) = apiRequest('POST', '/api/appointments', $normalToken, $createPayload);
    echo "Create response code: {$codeCreate}\n";
    if (($codeCreate !== 200 && $codeCreate !== 201) || empty($createJson['status'])) {
        echo $rawCreate . "\n";
        throw new Exception('Failed to create appointment');
    }
    $appointment = $createJson['appointment'] ?? null;
    assertTrue(!empty($appointment), 'Appointment not returned');
    assertTrue(($appointment['status'] ?? null) === 'pending', 'Initial status should be pending');
    $appointmentId = $appointment['id'];
    echo "Appointment #{$appointmentId} created. Status={$appointment['status']}\n";

    echo "== Step 3: Login as admin and verify new request notification ==\n";
    $adminLogin = login('admin@masar.app');
    $adminToken = $adminLogin['token'];
    $adminNotifs = getNotifications($adminToken);
    $adminNewReq = findNotificationByTypeContains($adminNotifs, 'new_appointment', $property['title']);
    assertTrue(!empty($adminNewReq), 'Admin did not receive new_appointment notification containing property title');
    echo "Admin received new_appointment notification.\n";

    echo "== Step 3.1: Admin lists all appointments and sees the new one ==\n";
    list($codeAllApps, $allAppsJson, $rawAllApps) = apiRequest('GET', '/api/appointments', $adminToken);
    if ($codeAllApps !== 200 || empty($allAppsJson['status'])) {
        echo $rawAllApps . "\n";
        throw new Exception('Admin failed to list appointments');
    }
    $allApps = $allAppsJson['appointments'] ?? [];
    $idsAll = array_map(fn($a) => $a['id'], $allApps);
    assertTrue(in_array($appointmentId, $idsAll, true), 'Appointment not listed for admin');
    echo "Admin can see the appointment in /api/appointments.\n";

    echo "== Step 3.2: Admin schedules within window (first time) ==\n";
    $scheduledAt = $day . ' 15:00';
    list($codeSched, $schedJson, $rawSched) = apiRequest('PUT', "/api/appointments/{$appointmentId}/admin-schedule", $adminToken, [
        'scheduled_at' => $scheduledAt,
        'admin_note'   => 'Scheduled within client preferred window.'
    ]);
    echo "Admin schedule response code: {$codeSched}\n";
    if ($codeSched !== 200 || empty($schedJson['status'])) {
        echo $rawSched . "\n";
        throw new Exception('Admin failed to schedule appointment');
    }
    $scheduled = $schedJson['appointment'] ?? [];
    echo "Admin scheduled at {$scheduledAt}. API returned: " . ($scheduled['appointment_datetime'] ?? 'NULL') . "\n";
    assertTrue(($scheduled['status'] ?? null) === 'admin_approved', 'Status should be admin_approved after scheduling');
    assertTrue(matchesMinute(($scheduled['appointment_datetime'] ?? null), $scheduledAt), 'appointment_datetime should match scheduled_at (minute precision)');

    echo "== Step 3.3: Verify notifications after admin approval (customer + provider) ==\n";
    // Customer should get appointment_approved
    $clientNotifsAfterFirstSchedule = getNotifications($normalToken);
    $clientApproved1 = findNotificationByTypeContains($clientNotifsAfterFirstSchedule, 'appointment_approved', $property['title']);
    assertTrue(!empty($clientApproved1), 'Customer did not receive appointment_approved notification after scheduling');
    echo "Customer received appointment_approved.\n";
    // Provider should get new_appointment_request
    $providerLogin = login($providerEmail);
    $providerToken = $providerLogin['token'];
    $providerNotifs1 = getNotifications($providerToken);
    $providerNewReqCount1 = countNotificationsByType($providerNotifs1, 'new_appointment_request');
    $providerNewReqFound1 = findNotificationByTypeContains($providerNotifs1, 'new_appointment_request', $property['title']);
    assertTrue(!empty($providerNewReqFound1), 'Provider did not receive new_appointment_request notification');
    echo "Provider received new_appointment_request.\n";

    echo "== Step 3.4: Admin reschedules to another time within window ==\n";
    $rescheduledAt = $day . ' 16:30';
    list($codeSched2, $schedJson2, $rawSched2) = apiRequest('PUT', "/api/appointments/{$appointmentId}/admin-schedule", $adminToken, [
        'scheduled_at' => $rescheduledAt,
        'admin_note'   => 'Rescheduled test.'
    ]);
    echo "Admin reschedule response code: {$codeSched2}\n";
    if ($codeSched2 !== 200 || empty($schedJson2['status'])) {
        echo $rawSched2 . "\n";
        throw new Exception('Admin failed to reschedule appointment');
    }
    $scheduled2 = $schedJson2['appointment'] ?? [];
    echo "Admin rescheduled at {$rescheduledAt}. API returned: " . ($scheduled2['appointment_datetime'] ?? 'NULL') . "\n";
    assertTrue(matchesMinute(($scheduled2['appointment_datetime'] ?? null), $rescheduledAt), 'appointment_datetime should reflect rescheduled time (minute precision)');

    echo "== Step 3.5: Verify notifications increased after reschedule ==\n";
    // Customer gets another appointment_approved
    $clientNotifsAfterSecondSchedule = getNotifications($normalToken);
    $clientApproved2 = findNotificationByTypeContains($clientNotifsAfterSecondSchedule, 'appointment_approved', $property['title']);
    assertTrue(!empty($clientApproved2), 'Customer did not receive appointment_approved on reschedule');
    // Provider gets another new_appointment_request
    $providerNotifs2 = getNotifications($providerToken);
    $providerNewReqCount2 = countNotificationsByType($providerNotifs2, 'new_appointment_request');
    assertTrue($providerNewReqCount2 >= $providerNewReqCount1 + 1, 'Provider new_appointment_request count did not increase after reschedule');
    echo "Notifications verified after reschedule.\n";

    echo "== Step 4: Provider logs in and approves ==\n";
    $providerLogin = login($providerEmail);
    $providerToken = $providerLogin['token'];
    // Optional sanity check: provider id matches appointment provider_id
    $providerLoginUserId = $providerLogin['id'] ?? null;
    if ($providerIdFromProperty && $providerLoginUserId) {
        assertTrue((int)$providerLoginUserId === (int)$providerIdFromProperty, 'Provider login does not match property owner');
    }
    list($codeProv, $provJson, $rawProv) = apiRequest('PUT', "/api/appointments/{$appointmentId}/provider-decision", $providerToken, [
        'decision' => 'approve',
        'provider_note' => 'Confirmed. See you there.'
    ]);
    echo "Provider decision response code: {$codeProv}\n";
    if ($codeProv !== 200 || empty($provJson['status'])) {
        echo $rawProv . "\n";
        throw new Exception('Provider failed to approve');
    }
    $approved = $provJson['appointment'] ?? [];
    assertTrue(($approved['status'] ?? null) === 'provider_approved', 'Status should be provider_approved after approval');
    echo "Provider approved. Status={$approved['status']}\n";

    echo "== Step 4.1: Verify customer gets appointment_confirmed notification ==\n";
    $clientNotifsAfterProvider = getNotifications($normalToken);
    $clientConfirmed = findNotificationByTypeContains($clientNotifsAfterProvider, 'appointment_confirmed', $property['title']);
    assertTrue(!empty($clientConfirmed), 'Customer did not receive appointment_confirmed after provider approval');
    echo "Customer received appointment_confirmed.\n";

    echo "== Step 5: Verify in my-appointments for the client ==\n";
    list($codeMy, $myJson, $rawMy) = apiRequest('GET', '/api/my-appointments', $normalToken);
    assertTrue($codeMy === 200 && !empty($myJson['status']), 'Fetching my-appointments failed');
    $myApps = $myJson['appointments'] ?? [];
    $ids = array_map(fn($a) => $a['id'], $myApps);
    assertTrue(in_array($appointmentId, $ids, true), 'Appointment is not listed in my-appointments');
    echo "Client can see the appointment in my-appointments.\n";

    echo "== Step 5.1: Verify in provider-appointments for the provider ==\n";
    list($codeProvList, $provListJson, $rawProvList) = apiRequest('GET', '/api/provider-appointments', $providerToken);
    assertTrue($codeProvList === 200 && !empty($provListJson['status']), 'Fetching provider-appointments failed');
    $provApps = $provListJson['appointments'] ?? [];
    $provIds = array_map(fn($a) => $a['id'], $provApps);
    assertTrue(in_array($appointmentId, $provIds, true), 'Appointment is not listed in provider-appointments');
    echo "Provider can see the appointment in provider-appointments.\n";

    echo "== Scenario completed successfully ==\n";
} catch (Exception $e) {
    fwrite(STDERR, "ERROR: " . $e->getMessage() . "\n");
    exit(1);
}

?>