<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Support\Notifier;

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
