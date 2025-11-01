<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\CarServiceOrder;
use App\Models\OrderStatusHistory;
use App\Support\Notifier;
use App\Models\DeliveryRequest;
use App\Models\DeliveryDestination;
use App\Models\DeliveryStatusHistory;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-accept all pending food orders and optionally notify users
Artisan::command('orders:auto-accept-pending {--notify}', function () {
    $notify = (bool) $this->option('notify');
    $updated = 0;

    $this->info('Scanning for pending orders...');

    Order::where('status', 'pending')
        ->orderBy('id')
        ->chunk(200, function ($orders) use (&$updated, $notify) {
            foreach ($orders as $order) {
                try {
                    $order->status = 'accepted_by_admin';
                    $order->save();
                    $updated++;

                    if ($notify) {
                        $order->loadMissing(['user', 'restaurant.user']);
                        $customer = $order->user;
                        $restaurantOwner = optional($order->restaurant)->user;

                        if ($customer) {
                            Notifier::send(
                                $customer,
                                'order_accepted',
                                'تم قبول طلبك!',
                                'تمت الموافقة على طلبك رقم ' . $order->order_number . ' وجاري إرساله للمطعم.'
                            );
                        }
                        if ($restaurantOwner) {
                            Notifier::send(
                                $restaurantOwner,
                                'new_order_for_restaurant',
                                'لديك طلب جديد!',
                                'يوجد طلب جديد برقم ' . $order->order_number . ' بانتظار التنفيذ.'
                            );
                        }
                    }
                } catch (\Throwable $e) {
                    Log::error('Failed to auto-accept order #' . $order->id . ': ' . $e->getMessage());
                }
            }
        });

    $this->info("Done. Updated {$updated} orders to accepted_by_admin.");
})->purpose('Force-accept all pending food orders (use --notify to send notifications)');

// Seed ready-to-display approved car rent service requests
Artisan::command('rent:seed-approved-requests {--count=5} {--user_id=} {--governorate=} {--note=}', function () {
    $count = (int) ($this->option('count') ?? 5);
    if ($count < 1) {
        $this->error('Invalid --count value. It must be >= 1.');
        return 1;
    }

    $client = null;
    $note = $this->option('note') ?? 'طلب تجريبي للتحقق من العرض لدى المكاتب';

    // Resolve client user
    if ($uid = $this->option('user_id')) {
        $client = User::find($uid);
        if (!$client) {
            $this->error("User with ID {$uid} not found.");
            return 1;
        }
    } else {
        // Fallback test client
        $client = User::firstOrCreate(
            ['email' => 'seed.rent.client@example.com'],
            [
                'name' => 'Seed Rent Client',
                'password' => bcrypt('password'),
                'phone' => '01000000000',
                'governorate' => 'القاهرة',
                'city' => 'مدينة نصر',
                'user_type' => 'normal',
                'is_approved' => true,
                'account_active' => true,
                'is_email_verified' => true,
            ]
        );
    }

    $gov = $this->option('governorate') ?: ($client->governorate ?: 'القاهرة');

    $this->info("Creating {$count} approved rent service requests for client #{$client->id} in governorate '{$gov}'...");

    $created = [];
    for ($i = 0; $i < $count; $i++) {
        try {
            $sr = ServiceRequest::create([
                'user_id' => $client->id,
                'governorate' => $gov,
                'type' => 'rent',
                'status' => 'approved', // visible to providers immediately
                'approved_by_admin' => true,
                'request_data' => [
                    'car_type' => ['Sedan', 'SUV', 'Hatchback'][array_rand(['Sedan','SUV','Hatchback'])],
                    'start_date' => now()->addDays(rand(0, 5))->toDateString(),
                    'duration_days' => rand(1, 7),
                    'pickup_location' => $client->city ?: 'القاهرة',
                    'price' => rand(300, 900),
                    'notes' => $note,
                ],
            ]);

            $created[] = $sr->id;
        } catch (\Throwable $e) {
            Log::error('Failed creating seed service request: ' . $e->getMessage());
        }
    }

    $this->info('Done. Created ' . count($created) . ' rent service requests: [' . implode(', ', $created) . ']');
    $this->info('You can view them via GET /api/provider/service-requests as a car_rental_office.');
    return 0;
})->purpose('Create approved rent service requests visible to car_rental_office providers');

// Seed pending car rent orders (CarServiceOrder) suitable for provider acceptance testing
Artisan::command('car-orders:seed-pending {--count=5} {--user_id=} {--note=} {--car_rental_id=}', function () {
    $count = (int) ($this->option('count') ?? 5);
    if ($count < 1) {
        $this->error('Invalid --count value. It must be >= 1.');
        return 1;
    }

    $client = null;
    $note = $this->option('note') ?? 'طلب تجريبي لتجربة قبول مقدم الخدمة';

    // Resolve client user
    if ($uid = $this->option('user_id')) {
        $client = User::find($uid);
        if (!$client) {
            $this->error("User with ID {$uid} not found.");
            return 1;
        }
    } else {
        // Fallback test client for car orders
        $client = User::firstOrCreate(
            ['email' => 'seed.car.client@example.com'],
            [
                'name' => 'Seed Car Client',
                'password' => bcrypt('password'),
                'phone' => '01000000001',
                'governorate' => 'القاهرة',
                'city' => 'مدينة نصر',
                'user_type' => 'normal',
                'is_approved' => true,
                'account_active' => true,
                'is_email_verified' => true,
            ]
        );
    }

    // Optional car_rental_id for legacy schemas where column is NOT NULL
    $providedCarRentalId = $this->option('car_rental_id');
    $carRentalId = null;
    if (!empty($providedCarRentalId)) {
        $carRentalId = (int) $providedCarRentalId;
        // Validate existence to avoid foreign key errors
        if (!\App\Models\CarRental::find($carRentalId)) {
            $this->error("car_rental_id={$carRentalId} not found. Proceeding with NULL car_rental_id.");
            $carRentalId = null;
        }
    }

    $this->info("Creating {$count} pending_provider car rent orders for client #{$client->id}..." . ($carRentalId ? " Using car_rental_id={$carRentalId}." : ''));

    $carCategories = ['economy', 'compact', 'midsize', 'suv'];
    $carModels = ['Toyota Yaris', 'Hyundai Elantra', 'Kia Cerato', 'Toyota Corolla', 'Nissan Sunny'];
    $created = [];

    for ($i = 0; $i < $count; $i++) {
        try {
            $duration = rand(1, 7);
            $startAt = now()->addDays(rand(1, 5));
            $endAt = (clone $startAt)->addDays($duration);

            $order = CarServiceOrder::create([
                'client_id'           => $client->id,
                'car_rental_id'       => $carRentalId, // may be NULL in modern schema
                'order_type'          => 'rent',
                'provider_type'       => 'office',
                'with_driver'         => false,
                'car_category'        => $carCategories[array_rand($carCategories)],
                'car_model'           => $carModels[array_rand($carModels)],
                'payment_method'      => 'cash',
                // استخدم قيم ENUM الصحيحة: daily/weekly/monthly
                'rental_period_type'  => 'daily',
                'rental_duration'     => $duration,
                'status'              => 'pending_provider',
                'requested_price'     => rand(200, 800),
                'from_location'       => $client->city ?: 'القاهرة',
                // بعض البيئات قد تجعل to_location غير قابل لأن يكون NULL
                'to_location'         => $client->city ?: 'القاهرة',
                'delivery_location'   => $client->city ?: 'القاهرة',
                'requested_date'      => now(),
                'rental_start_at'     => $startAt,
                'rental_end_at'       => $endAt,
            ]);

            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'status'     => 'pending_provider',
                'changed_by' => $client->id,
                'note'       => $note,
            ]);

            $created[] = $order->id;
        } catch (\Throwable $e) {
            $this->error('Failed creating seed car order: ' . $e->getMessage());
            Log::error('Failed creating seed car order: ' . $e->getMessage());
        }
    }

    $this->info('Done. Created ' . count($created) . ' car orders: [' . implode(', ', $created) . ']');
    $this->info('Providers can view them via GET /api/provider/car-orders/available or /api/car-orders?status=pending_provider.');
    return 0;
})->purpose('Create pending_provider car rent orders visible to car_rental_office providers');

// Seed pending delivery requests (DeliveryRequest) for quick driver-visibility testing
Artisan::command('delivery:seed-pending {--count=5} {--client_id=} {--client_email=} {--governorate=} {--note=} {--category=economy} {--payment=cash} {--price=} {--hours=2}', function () {
    $count = (int) ($this->option('count') ?? 5);
    if ($count < 1) {
        $this->error('Invalid --count value. It must be >= 1.');
        return 1;
    }

    // Resolve client
    $client = null;
    if ($cid = $this->option('client_id')) {
        $client = User::find($cid);
        if (!$client) {
            $this->error("Client with ID {$cid} not found.");
            return 1;
        }
    } elseif ($cem = $this->option('client_email')) {
        $client = User::where('email', $cem)->first();
        if (!$client) {
            $this->error("Client with email {$cem} not found.");
            return 1;
        }
    } else {
        // Fallback seed client
        $client = User::firstOrCreate(
            ['email' => 'seed.delivery.client@example.com'],
            [
                'name' => 'Seed Delivery Client',
                'password' => bcrypt('password'),
                'phone' => '01000000002',
                'governorate' => 'القاهرة',
                'city' => 'مدينة نصر',
                'user_type' => 'normal',
                'is_approved' => true,
                'account_active' => true,
                'is_email_verified' => true,
            ]
        );
    }

    $gov = $this->option('governorate') ?: ($client->governorate ?: 'القاهرة');
    $note = $this->option('note') ?? 'طلب توصيل تجريبي';
    $category = $this->option('category') ?? 'economy';
    $payment = $this->option('payment') ?? 'cash';
    $price = $this->option('price');
    $hours = (int) ($this->option('hours') ?? 2);
    if ($hours < 1) { $hours = 1; }

    $this->info("Creating {$count} pending_offers delivery requests for client #{$client->id} in governorate '{$gov}'...");

    $created = [];
    for ($i = 0; $i < $count; $i++) {
        try {
            // Create delivery request
            $dr = DeliveryRequest::create([
                'client_id' => $client->id,
                'trip_type' => 'one_way',
                'delivery_time' => now()->addHours($hours),
                'car_category' => $category,
                'payment_method' => $payment,
                'price' => $price ?? rand(40, 120),
                'notes' => $note,
                'governorate' => $gov,
                'status' => DeliveryRequest::STATUS_PENDING_OFFERS,
            ]);

            // Add two destinations (pickup + dropoff)
            DeliveryDestination::create([
                'delivery_request_id' => $dr->id,
                'order' => 1,
                'location_name' => 'نقطة الانطلاق - ' . $gov,
                'latitude' => null,
                'longitude' => null,
                'address' => $client->city ?: $gov,
                'is_pickup_point' => true,
                'is_dropoff_point' => false,
            ]);

            DeliveryDestination::create([
                'delivery_request_id' => $dr->id,
                'order' => 2,
                'location_name' => 'نقطة الوصول - ' . $gov,
                'latitude' => null,
                'longitude' => null,
                'address' => $gov,
                'is_pickup_point' => false,
                'is_dropoff_point' => true,
            ]);

            // Status history
            DeliveryStatusHistory::create([
                'delivery_request_id' => $dr->id,
                'status' => DeliveryRequest::STATUS_PENDING_OFFERS,
                'changed_by' => $client->id,
                'note' => 'تم إنشاء طلب توصيل تجريبي عبر الأمر'
            ]);

            $created[] = $dr->id;
        } catch (\Throwable $e) {
            $this->error('Failed creating seed delivery request: ' . $e->getMessage());
            Log::error('Failed creating seed delivery request: ' . $e->getMessage());
        }
    }

    $this->info('Done. Created ' . count($created) . ' delivery requests: [' . implode(', ', $created) . ']');
    $this->info('Drivers from the same governorate can view them via GET /api/delivery/available-requests.');
    return 0;
})->purpose('Create pending_offers delivery requests visible to drivers in the same governorate');
