<?php
// Quick checker for Security Permits seed data

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';

// Bootstrap Laravel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "== Security Permits Form Data Check ==\n";

try {
    $countriesCount = DB::table('countries')->count();
    $nationalitiesCount = DB::table('nationalities')->count();
    $settings = DB::table('security_permit_settings')->pluck('value', 'key');

    echo "Countries: $countriesCount\n";
    echo "Nationalities: $nationalitiesCount\n";

    $individualFee = $settings['individual_fee'] ?? null;
    echo "individual_fee setting: " . ($individualFee !== null ? $individualFee : 'NULL (missing)') . "\n";

    if ($countriesCount > 0) {
        $sampleCountries = DB::table('countries')->select('id','name_ar','name_en','code','is_active')->orderBy('id')->limit(3)->get();
        echo "Sample countries: " . json_encode($sampleCountries, JSON_UNESCAPED_UNICODE) . "\n";
    }
    if ($nationalitiesCount > 0) {
        $sampleNationalities = DB::table('nationalities')->select('id','name_ar','name_en','code','is_active')->orderBy('id')->limit(3)->get();
        echo "Sample nationalities: " . json_encode($sampleNationalities, JSON_UNESCAPED_UNICODE) . "\n";
    }
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Check completed.\n";