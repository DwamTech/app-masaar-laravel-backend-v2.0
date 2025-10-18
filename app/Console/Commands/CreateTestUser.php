<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-test-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Delete existing test user if exists
        User::where('email', 'test@example.com')->delete();
        
        // Create new test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'phone' => '01000000001',
            'governorate' => 'الإسماعيلية',
            'user_type' => 'normal',
            'account_active' => true,
            'is_email_verified' => true,
            'is_approved' => true
        ]);
        
        $this->info('Test user created successfully!');
        $this->info('Email: ' . $user->email);
        $this->info('Password: password123');
        
        return 0;
    }
}
