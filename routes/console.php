<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
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
