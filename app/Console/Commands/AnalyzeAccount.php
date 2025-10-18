<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class AnalyzeAccount extends Command
{
    protected $signature = 'app:analyze-account';

    protected $description = 'Analyzes the service provider account and saves the analysis to a file.';

    public function handle()
    {
        $baseUrl = config('app.url');

        $this->info('Attempting to log in...');
        $loginResponse = Http::withHeaders(['Accept' => 'application/json'])->post("{$baseUrl}/api/login", [
            'email' => 'abdalrhman0mahmoud1@gmail.com',
            'password' => 'Abdo0101@',
        ]);

        if ($loginResponse->failed()) {
            $this->error('Login failed. Status: ' . $loginResponse->status());
            $this->line($loginResponse->body());
            return 1;
        }

        $token = $loginResponse->json('authorisation.token');

        if (!$token) {
            $this->error('Authentication token not found in login response.');
            $this->line($loginResponse->body());
            return 1;
        }

        $this->info('Login successful. Token obtained.');

        $this->info('Fetching account data...');
        $accountResponse = Http::withToken($token)->withHeaders(['Accept' => 'application/json'])->get("{$baseUrl}/api/user");

        if ($accountResponse->failed()) {
            $this->error('Failed to fetch account data. Status: ' . $accountResponse->status());
            $this->line($accountResponse->body());
            return 1;
        }

        $accountData = $accountResponse->json();
        $this->info('Account data fetched successfully.');

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
                $expectedFields = ['office_name', 'license_number', 'address', 'phone_number'];
                foreach ($expectedFields as $field) {
                    if (!isset($user[$field]) || empty($user[$field])) {
                        $analysisContent .= "- Missing or empty field for real estate office: {$field}\n";
                    }
                }
            } else {
                $analysisContent .= "- User type is not 'real_estate_office'.\n";
            }
        } else {
            $analysisContent .= "- 'user' key not found in the account data.\n";
        }

        $analysisContent .= "\n### Missing Fields or Essential Data:\n";
        $analysisContent .= "- Based on the analysis, the following fields are missing or need attention: [Specify missing fields here after review]\n";

        File::put(base_path('account_analysis.md'), $analysisContent);
        $this->info('Account analysis saved to account_analysis.md');

        return 0;
    }
}
