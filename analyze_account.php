<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Base URL of your Laravel API
$baseUrl = 'http://localhost:8000'; // Adjust if your API is on a different port or domain

// Step 1: Login to get authentication token
$loginUrl = $baseUrl . '/api/login';
$loginData = [
    'email' => 'abdalrhman0mahmoud1@gmail.com',
    'password' => 'Abdo0101@',
];

echo "Attempting to log in...\n";
$loginResponse = Http::post($loginUrl, $loginData);

if ($loginResponse->successful()) {
    $loginResult = $loginResponse->json();
    $token = $loginResult['authorisation']['token'] ?? null;

    if ($token) {
        echo "Login successful. Token obtained.\n";

        // Step 2: Fetch full account data using the token
        $accountUrl = $baseUrl . '/api/user'; // Assuming /api/user returns the authenticated user's data
        echo "Fetching account data...\n";
        $accountResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get($accountUrl);

        if ($accountResponse->successful()) {
            $accountData = $accountResponse->json();
            echo "Account data fetched successfully.\n";

            // Step 3: Analyze the returned data and save to account_analysis.md
            $analysisContent = "## Account Analysis for abdalrhman0mahmoud1@gmail.com\n\n";
            $analysisContent .= "### Raw Account Data:\n";
            $analysisContent .= "```json\n" . json_encode($accountData, JSON_PRETTY_PRINT) . "\n```\n\n";

            $analysisContent .= "### Observations:\n";
            
            if (isset($accountData['user'])) {
                $user = $accountData['user'];
                $analysisContent .= "- User ID: " . ($user['id'] ?? 'N/A') . "\n";
                $analysisContent .= "- User Name: " . ($user['name'] ?? 'N/A') . "\n";
                $analysisContent .= "- User Email: " . ($user['email'] ?? 'N/A') . "\n";
                $analysisContent .= "- User Type: " . ($user['user_type'] ?? 'N/A') . "\n";
                
                if (($user['user_type'] ?? '') === 'real_estate_office') {
                    $analysisContent .= "- This account is identified as a 'real_estate_office'.\n";
                    // Check for specific fields expected for a real estate office
                    $expectedFields = ['office_name', 'license_number', 'address', 'phone_number'];
                    foreach ($expectedFields as $field) {
                        if (!isset($user[$field]) || empty($user[$field])) {
                            $analysisContent .= "- Missing or empty field for real estate office: {$field}\n";
                        } else {
                            $analysisContent .= "- {$field}: " . $user[$field] . "\n";
                        }
                    }
                } else {
                    $analysisContent .= "- User type is not 'real_estate_office'. Further investigation might be needed if this is unexpected.\n";
                }
            } else {
                $analysisContent .= "- 'user' key not found in the account data. The structure might be different than expected.\n";
            }

            $analysisContent .= "\n### Missing Fields or Essential Data:\n";
            $analysisContent .= "- [Add specific notes here after reviewing the raw data]\n";

            file_put_contents('e:\\Work\\Code\\Dwam Projects\\Msar\\masaar-laravel-backend\\account_analysis.md', $analysisContent);
            echo "Account analysis saved to account_analysis.md\n";

        } else {
            echo "Failed to fetch account data. Status: " . $accountResponse->status() . "\n";
            echo "Response: " . $accountResponse->body() . "\n";
        }

    } else {
        echo "Authentication token not found in login response.\n";
        echo "Response: " . $loginResponse->body() . "\n";
    }
} else {
    echo "Login failed. Status: " . $loginResponse->status() . "\n";
    echo "Response: " . $loginResponse->body() . "\n";
}