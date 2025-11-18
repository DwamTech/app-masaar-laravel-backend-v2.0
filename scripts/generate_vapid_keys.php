<?php
// Generate VAPID keys using Minishlink\WebPush and print them

require __DIR__ . '/../vendor/autoload.php';

use Minishlink\WebPush\VAPID;

function out($label, $value) {
    echo $label . ': ' . $value . PHP_EOL;
}

try {
    $keys = VAPID::createVapidKeys();
    out('WEB_PUSH_PUBLIC_KEY', $keys['publicKey']);
    out('WEB_PUSH_PRIVATE_KEY', $keys['privateKey']);
    out('NOTE', 'قم بنسخ القيم وضبطها كمتغيرات بيئة قبل تشغيل السيرفر.');
    exit(0);
} catch (Throwable $e) {
    out('ERROR', $e->getMessage());
    exit(1);
}