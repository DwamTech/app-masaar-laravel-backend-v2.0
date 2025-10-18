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
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'user_type' => 'admin',
                'approved' => true,
                'available' => false,
            ],
            [
                'name' => 'Normal User',
                'email' => 'normal@example.com',
                'user_type' => 'normal',
                'approved' => true,
                'available' => false,
            ],
            [
                'name' => 'Real Estate Office',
                'email' => 're_office@example.com',
                'user_type' => 'real_estate_office',
                'approved' => true,
                'available' => true,
            ],
            [
                'name' => 'Real Estate Individual',
                'email' => 're_individual@example.com',
                'user_type' => 'real_estate_individual',
                'approved' => true,
                'available' => false,
            ],
            [
                'name' => 'Restaurant Account',
                'email' => 'restaurant@example.com',
                'user_type' => 'restaurant',
                'approved' => true,
                'available' => false,
            ],
            [
                'name' => 'Car Rental Office',
                'email' => 'car_rental_office@example.com',
                'user_type' => 'car_rental_office',
                'approved' => true,
                'available' => true,
            ],
            [
                'name' => 'Driver Account',
                'email' => 'driver@example.com',
                'user_type' => 'driver',
                'approved' => true,
                'available' => true,
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

            User::firstOrCreate($attributes, $values);
        }
    }
}