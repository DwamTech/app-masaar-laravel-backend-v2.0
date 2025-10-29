<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\CarRental;
use App\Models\CarRentalOfficesDetail;
use App\Models\DriverDetail;

class CarRentalUsersSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('car_rentals')) {
            return;
        }

        $officeUsers = User::where('user_type', 'car_rental_office')->get();
        foreach ($officeUsers as $user) {
            $cr = CarRental::firstOrCreate([
                'user_id' => $user->id,
                'rental_type' => 'office',
            ]);

            if (Schema::hasTable('car_rental_offices_details')) {
                CarRentalOfficesDetail::firstOrCreate([
                    'car_rental_id' => $cr->id,
                ], [
                    'office_name' => 'Demo Car Rental Office',
                    'logo_image' => 'logo.png',
                    'commercial_register_front_image' => 'cr_front.png',
                    'commercial_register_back_image' => 'cr_back.png',
                    'payment_methods' => ['cash', 'card'],
                    'rental_options' => ['daily', 'weekly'],
                    'cost_per_km' => 1.50,
                    'daily_driver_cost' => 200.00,
                    'max_km_per_day' => 200,
                    'is_available_for_delivery' => true,
                    'is_available_for_rent' => true,
                ]);
            }
        }

        $driverUsers = User::where('user_type', 'driver')->get();
        foreach ($driverUsers as $user) {
            $cr = CarRental::firstOrCreate([
                'user_id' => $user->id,
                'rental_type' => 'driver',
            ]);

            if (Schema::hasTable('driver_details')) {
                DriverDetail::firstOrCreate([
                    'car_rental_id' => $cr->id,
                ], [
                    'profile_image' => 'driver.png',
                    'payment_methods' => ['cash'],
                    'rental_options' => ['daily'],
                    'cost_per_km' => 1.25,
                    'daily_driver_cost' => 150.00,
                    'max_km_per_day' => 180,
                ]);
            }
        }
    }
}