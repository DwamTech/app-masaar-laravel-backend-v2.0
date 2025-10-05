<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class VerifyUserEmail extends Command
{
    protected $signature = 'app:verify-user-email {email}';

    protected $description = 'Manually verifies a user\'s email address.';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->account_active = true;
            $user->save();
            $this->info("User {$email} has been verified.");
        } else {
            $this->error("User with email {$email} not found.");
        }
    }
}
