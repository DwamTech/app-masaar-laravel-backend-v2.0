<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== تحقق من السائقين والإشعارات ===\n";

// عدد السائقين
$driversCount = User::where('user_type', 'driver')->count();
echo "عدد السائقين المسجلين: {$driversCount}\n";

if ($driversCount > 0) {
    $drivers = User::where('user_type', 'driver')->get();
    
    foreach ($drivers as $driver) {
        $tokensCount = DB::table('device_tokens')
            ->where('user_id', $driver->id)
            ->where('is_enabled', 1)
            ->count();
            
        echo "السائق: {$driver->name} (ID: {$driver->id}) - المحافظة: {$driver->governorate} - Tokens: {$tokensCount}\n";
    }
}

// عدد طلبات التوصيل
$requestsCount = DB::table('delivery_requests')->count();
echo "\nعدد طلبات التوصيل: {$requestsCount}\n";

// عدد العروض
$offersCount = DB::table('delivery_offers')->count();
echo "عدد العروض المقدمة: {$offersCount}\n";

echo "\n=== انتهى التحقق ===\n";