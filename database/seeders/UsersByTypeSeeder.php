<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersByTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $definitions = [
            [
                'name' => 'مدير النظام',
                'email' => 'admin@masar.app',
                'user_type' => 'admin',
                'approved' => true,
                'available' => true,
                'account_active' => true,
                'email_verified' => true,
            ],
            [
                'name' => 'Normal User',
                'email' => 'normal@example.com',
                'user_type' => 'normal',
                'approved' => true,
                'available' => true,
                'account_active' => true,
                'email_verified' => true,
            ],
            [
                'name' => 'Real Estate Office',
                'email' => 're_office@example.com',
                'user_type' => 'real_estate_office',
                'approved' => true,
                'available' => true,
                'account_active' => true,
                'email_verified' => true,
            ],
            [
                'name' => 'Real Estate Individual',
                'email' => 're_individual@example.com',
                'user_type' => 'real_estate_individual',
                'approved' => true,
                'available' => false,
                'account_active' => true,
                'email_verified' => true,
            ],
            [
                'name' => 'Restaurant Account',
                'email' => 'restaurant@example.com',
                'user_type' => 'restaurant',
                'approved' => true,
                'available' => false,
                'account_active' => true,
                'email_verified' => true,
            ],
            [
                'name' => 'Car Rental Office',
                'email' => 'car_rental_office@example.com',
                'user_type' => 'car_rental_office',
                'approved' => true,
                'available' => true,
                'account_active' => true,
                'email_verified' => true,
            ],
            [
                'name' => 'Car Rental Office 2',
                'email' => 'car_rental_office2@example.com',
                'user_type' => 'car_rental_office',
                'approved' => true,
                'available' => true,
                'account_active' => true,
                'email_verified' => true,
            ],
            [
                'name' => 'Driver Account',
                'email' => 'driver@example.com',
                'user_type' => 'driver',
                'approved' => true,
                'available' => true,
                'account_active' => true,
                'email_verified' => true,
            ],
        ];

        foreach ($definitions as $def) {
            $attributes = [
                'email' => $def['email'],
            ];

            $values = [
                'name' => $def['name'],
                'user_type' => $def['user_type'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ];

            // Set optional flags only if columns exist, to avoid SQL errors
            if (Schema::hasColumn('users', 'is_approved')) {
                $values['is_approved'] = $def['approved'] ? 1 : 0;
            }
            if (Schema::hasColumn('users', 'is_available')) {
                $values['is_available'] = $def['available'] ? 1 : 0;
            }
            if (Schema::hasColumn('users', 'account_active') && isset($def['account_active'])) {
                $values['account_active'] = $def['account_active'] ? 1 : 0;
            }
            if (Schema::hasColumn('users', 'is_email_verified') && isset($def['email_verified'])) {
                $values['is_email_verified'] = $def['email_verified'] ? 1 : 0;
            }

            // Create if missing
            $user = User::firstOrCreate($attributes, $values);

            // Ensure activation for existing users without overriding other fields
            $needsSave = false;
            if (Schema::hasColumn('users', 'account_active') && !$user->account_active) {
                $user->account_active = 1;
                $needsSave = true;
            }
            if (Schema::hasColumn('users', 'is_email_verified') && !$user->is_email_verified) {
                $user->is_email_verified = 1;
                $needsSave = true;
            }
            if ($needsSave) {
                $user->save();
            }
        }
    }
}
