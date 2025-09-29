<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\RealEstate;

// Find the test user
$user = User::where('email', 'test@example.com')->first();

if (!$user) {
    echo "Test user not found!\n";
    exit(1);
}

// Update user type to real estate office
$user->update(['user_type' => 'real_estate_office']);

// Create or get real estate record
$realEstate = $user->realEstate;
if (!$realEstate) {
    $realEstate = RealEstate::create([
        'user_id' => $user->id,
        'type' => 'office'
    ]);
}

echo "User type updated to: " . $user->user_type . "\n";
echo "Real estate ID: " . $realEstate->id . "\n";
echo "User ID: " . $user->id . "\n";
echo "User token: " . $user->tokens()->first()->plainTextToken ?? 'No token found' . "\n";