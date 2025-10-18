<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\UsersByTypeSeeder;
use Database\Seeders\NormalUsersSeeder;
use Database\Seeders\RealEstateUsersSeeder;
use Database\Seeders\CarRentalUsersSeeder;
use Database\Seeders\RestaurantUsersSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'user_type' => 'normal',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Seed one user for each available user type
        $this->call(UsersByTypeSeeder::class);

        // Seed detail tables for each user type
        $this->call([
            NormalUsersSeeder::class,
            RealEstateUsersSeeder::class,
            CarRentalUsersSeeder::class,
            RestaurantUsersSeeder::class,
        ]);
    }
}
