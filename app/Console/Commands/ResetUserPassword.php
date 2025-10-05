<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    protected $signature = 'app:reset-user-password';

    protected $description = 'Resets the password for a specific user.';

    public function handle()
    {
        $this->info('Resetting user password...');

        $user = User::where('email', 'abdalrhman0mahmoud1@gmail.com')->first();

        if ($user) {
            $user->password = Hash::make('Abdo0101@');
            $user->save();
            $this->info('Password reset successfully.');
        } else {
            $this->error('User not found.');
        }
    }
}
