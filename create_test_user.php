<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

// Create test user
$user = User::firstOrCreate(
    ['email' => 'test@example.com'],
    [
        'name' => 'Test User',
        'password' => bcrypt('password'),
        'phone' => '+966501234567',
        'user_type' => 'normal_user',
        'email_verified_at' => now()
    ]
);

// Create token
$token = $user->createToken('test-token')->plainTextToken;

echo "User ID: " . $user->id . "\n";
echo "Token: " . $token . "\n";