<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Property;
use App\Models\RealEstate;
use App\Models\CarRental;
use App\Models\RestaurantDetail;
use App\Models\Appointment;
use App\Models\CarServiceOrder;
use App\Models\DeliveryRequest;
use App\Models\Order;
use App\Models\SecurityPermit;

class MultiServiceRequestsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ar_SA');

        // Ensure essential tables exist to avoid errors on older schemas
        $requiredTables = [
            'users', 'properties', 'real_estates', 'car_rentals', 'restaurant_details',
            'appointments', 'car_service_orders', 'delivery_requests', 'orders', 'security_permits'
        ];
        foreach ($requiredTables as $table) {
            if (!Schema::hasTable($table)) {
                $this->command?->warn("Skipping seeding: table '{$table}' not found.");
                return;
            }
        }

        // 1) Ensure users have phone numbers so UI shows phones
        $this->ensureUserPhones();

        // 2) Build pools (fallback-create minimal prerequisites if empty)
        $customers = User::where('user_type', 'normal')->get();
        if ($customers->count() < 20) {
            for ($i = 0; $i < 20 - $customers->count(); $i++) {
                $customers->push(User::create([
                    'name' => $faker->name(),
                    'email' => 'normal+'.uniqid()."@example.com",
                    'user_type' => 'normal',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'phone' => '01'.mt_rand(100000000, 999999999),
                ]));
            }
        }

        $realEstates = RealEstate::with('user')->get();
        if ($realEstates->isEmpty()) {
            // Create one office real estate provider if none
            $reUser = User::firstOrCreate([
                'email' => 're_seed_'.uniqid().'@example.com'
            ], [
                'name' => 'Real Estate Provider',
                'user_type' => 'real_estate_office',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'phone' => '01'.mt_rand(100000000, 999999999),
            ]);
            $realEstates = collect([
                RealEstate::create(['user_id' => $reUser->id, 'type' => 'office'])
            ]);
        }

        $properties = Property::query()->get();
        if ($properties->isEmpty()) {
            // Create a handful of properties tied to the first real estate
            $re = $realEstates->first();
            for ($i = 0; $i < 10; $i++) {
                $properties->push(Property::create([
                    'real_estate_id' => $re->id,
                    'address' => $faker->address(),
                    'type' => 'apartment',
                    'price' => mt_rand(200000, 1500000),
                    'description' => $faker->sentence(8),
                    'image_url' => 'properties/main/seed-'.(($i % 6)+1).'.jpg',
                    'bedrooms' => mt_rand(1, 4),
                    'bathrooms' => mt_rand(1, 3),
                    'view' => 'city',
                    'payment_method' => 'cash',
                    'area' => mt_rand(80, 300).' sqm',
                    'submitted_by' => 'seeder',
                    'submitted_price' => null,
                    'is_ready' => 1,
                ]));
            }
        }

        $carRentals = CarRental::with('user')->get();
        if ($carRentals->isEmpty()) {
            // Create a driver rental provider if none
            $driverUser = User::firstOrCreate([
                'email' => 'driver_seed_'.uniqid().'@example.com'
            ], [
                'name' => 'Driver Provider',
                'user_type' => 'driver',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'phone' => '01'.mt_rand(100000000, 999999999),
            ]);
            $carRentals = collect([
                CarRental::create(['user_id' => $driverUser->id, 'rental_type' => 'driver'])
            ]);
        }

        $restaurants = RestaurantDetail::with('user')->get();
        if ($restaurants->isEmpty()) {
            $restUser = User::firstOrCreate([
                'email' => 'restaurant_seed_'.uniqid().'@example.com'
            ], [
                'name' => 'Restaurant Provider',
                'user_type' => 'restaurant',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'phone' => '01'.mt_rand(100000000, 999999999),
            ]);
            $restaurants = collect([
                RestaurantDetail::create([
                    'user_id' => $restUser->id,
                    'profile_image' => 'restaurant.png',
                    'restaurant_name' => 'Seeder Restaurant',
                    'logo_image' => 'logo.png',
                    'owner_id_front_image' => 'owner_front.png',
                    'owner_id_back_image' => 'owner_back.png',
                    'license_front_image' => 'license_front.png',
                    'license_back_image' => 'license_back.png',
                    'commercial_register_front_image' => 'cr_front.png',
                    'commercial_register_back_image' => 'cr_back.png',
                    'vat_included' => false,
                    'vat_image_front' => null,
                    'vat_image_back' => null,
                    'cuisine_types' => ['grill', 'pizza'],
                    'branches' => ['Main Branch'],
                    'delivery_available' => true,
                    'delivery_cost_per_km' => 5.00,
                    'table_reservation_available' => true,
                    'max_people_per_reservation' => 6,
                    'reservation_notes' => null,
                    'deposit_required' => false,
                    'deposit_amount' => null,
                    'working_hours' => [
                        'mon' => ['09:00', '22:00'],
                        'tue' => ['09:00', '22:00'],
                    ],
                ])
            ]);
        }

        // 3) Create large volumes per service type
        $appointmentCount = 200;
        $carServiceCount = 200;
        $deliveryCount = 200;
        $orderCount = 200;
        $permitCount = 200;

        DB::transaction(function () use ($faker, $customers, $properties, $realEstates, $carRentals, $restaurants, $appointmentCount, $carServiceCount, $deliveryCount, $orderCount, $permitCount) {
            // Appointments
            $appointmentStatuses = ['pending', 'admin_approved', 'provider_approved', 'rejected'];
            for ($i = 0; $i < $appointmentCount; $i++) {
                $customer = $customers[$i % $customers->count()];
                $providerRE = $realEstates[$i % $realEstates->count()];
                $providerUser = $providerRE->user;
                $property = $properties[$i % $properties->count()];

                $dt = Carbon::now()->addDays(mt_rand(-10, 20))->addHours(mt_rand(0, 23));
                Appointment::create([
                    'property_id' => $property->id,
                    'customer_id' => $customer->id,
                    'provider_id' => $providerUser->id,
                    'appointment_datetime' => $dt,
                    'preferred_from' => $dt->copy()->subHour(),
                    'preferred_to' => $dt->copy()->addHour(),
                    'note' => $faker->sentence(6),
                    'admin_note' => null,
                    'provider_note' => null,
                    'status' => $appointmentStatuses[array_rand($appointmentStatuses)],
                    'last_action_by' => $faker->randomElement(['customer', 'admin', 'provider']),
                ]);
            }

            // Car Service Orders
            $carStatuses = ['pending_admin', 'pending_provider', 'negotiation', 'accepted', 'started', 'finished', 'rejected', 'cancelled'];
            for ($i = 0; $i < $carServiceCount; $i++) {
                $customer = $customers[$i % $customers->count()];
                $carRental = $carRentals[$i % $carRentals->count()];
                $providerUser = $carRental->user; // may be office or driver

                CarServiceOrder::create([
                    'client_id' => $customer->id,
                    'car_rental_id' => $carRental->id,
                    'provider_id' => $providerUser?->id,
                    'order_type' => $faker->randomElement(['rent', 'ride']),
                    'car_category' => $faker->randomElement(['economy', 'comfort', 'premium', 'van']),
                    'payment_method' => $faker->randomElement(['cash', 'bank_transfer']),
                    'status' => $carStatuses[array_rand($carStatuses)],
                    'requested_price' => $faker->randomFloat(2, 100, 5000),
                    'agreed_price' => $faker->randomElement([null, $faker->randomFloat(2, 100, 5000)]),
                    'from_location' => $faker->streetAddress(),
                    'to_location' => $faker->streetAddress(),
                    'delivery_time' => Carbon::now()->addDays(mt_rand(-5, 10)),
                    'requested_date' => Carbon::now()->subDays(mt_rand(1, 20)),
                ]);
            }

            // Delivery Requests
            $deliveryStatuses = ['pending_offers', 'accepted_waiting_driver', 'driver_arrived', 'trip_started', 'trip_completed', 'cancelled', 'rejected'];
            for ($i = 0; $i < $deliveryCount; $i++) {
                $customer = $customers[$i % $customers->count()];
                $maybeDriver = $carRentals[$i % $carRentals->count()]->user; // assign occasionally
                DeliveryRequest::create([
                    'client_id' => $customer->id,
                    'driver_id' => ($i % 3 === 0) ? ($maybeDriver?->id) : null,
                    'trip_type' => $faker->randomElement(['one_way', 'round_trip', 'multiple_destinations']),
                    'delivery_time' => Carbon::now()->addDays(mt_rand(-7, 15)),
                    'status' => $deliveryStatuses[array_rand($deliveryStatuses)],
                    'price' => $faker->randomFloat(2, 50, 2000),
                    'agreed_price' => $faker->randomElement([null, $faker->randomFloat(2, 50, 2000)]),
                    'car_category' => $faker->randomElement(['economy', 'comfort', 'premium', 'van']),
                    'estimated_duration' => mt_rand(15, 240),
                    'payment_method' => $faker->randomElement(['cash', 'bank_transfer', 'card']),
                    'notes' => $faker->boolean(40) ? $faker->sentence(8) : null,
                    'governorate' => $faker->randomElement(['Cairo', 'Giza', 'Alexandria', 'Qalyubia']),
                ]);
            }

            // Restaurant Orders
            for ($i = 0; $i < $orderCount; $i++) {
                $customer = $customers[$i % $customers->count()];
                $restaurant = $restaurants[$i % $restaurants->count()];
                $subtotal = $faker->randomFloat(2, 50, 1000);
                $deliveryFee = $faker->randomFloat(2, 0, 50);
                $vat = round($subtotal * 0.14, 2);
                $total = $subtotal + $deliveryFee + $vat;

                Order::create([
                    'user_id' => $customer->id,
                    'restaurant_id' => $restaurant->id,
                    'status' => $faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
                    'order_number' => 'ORD-'.strtoupper(uniqid()),
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'vat' => $vat,
                    'total_price' => $total,
                    'note' => $faker->boolean(30) ? $faker->sentence(6) : null,
                ]);
            }

            // Security Permits
            $permitStatuses = ['new', 'pending', 'approved', 'rejected', 'expired'];
            for ($i = 0; $i < $permitCount; $i++) {
                $customer = $customers[$i % $customers->count()];
                SecurityPermit::create([
                    'user_id' => $customer->id,
                    'travel_date' => Carbon::now()->addDays(mt_rand(-30, 60))->toDateString(),
                    'nationality' => $faker->randomElement(['Egyptian', 'Saudi', 'Emirati', 'Sudanese']),
                    'people_count' => mt_rand(1, 6),
                    'coming_from' => $faker->randomElement(['Cairo', 'Riyadh', 'Dubai', 'Khartoum']),
                    'passport_image' => 'passport.png',
                    'other_document_image' => $faker->boolean(40) ? 'other_doc.png' : null,
                    'status' => $permitStatuses[array_rand($permitStatuses)],
                    'notes' => $faker->boolean(30) ? $faker->sentence(8) : null,
                ]);
            }
        });

        $this->command?->info('MultiServiceRequestsSeeder: created large volume of multi-service requests successfully.');
    }

    private function ensureUserPhones(): void
    {
        User::whereNull('phone')->orWhere('phone', '')->chunkById(500, function ($chunk) {
            foreach ($chunk as $user) {
                $user->phone = '01'.mt_rand(100000000, 999999999);
                $user->save();
            }
        });
    }
}