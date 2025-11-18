<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

use App\Models\Appointment;
use App\Models\CarServiceOrder;
use App\Models\DeliveryRequest;
use App\Models\Order;
use App\Models\SecurityPermit;
use App\Models\User;

class AllSystemRequestsController extends Controller
{
    /**
     * Unified listing of system requests for admins.
     * Supports basic filters: type, status_category, date_from, date_to.
     */
    public function index(Request $request)
    {
        // Ensure admin via middleware; double-check here to be safe
        $user = Auth::user();
        if (!$user || $user->user_type !== 'admin') {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $typeFilter = $request->get('type', 'all'); // appointment|car_order|delivery_request|restaurant_order|security_permit|all
        $statusCategoryFilter = $request->get('status_category', 'all'); // pending|in_progress|completed|rejected|approved|expired|review|all
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $perPageParam = $request->get('per_page', 20);
        $noPagination = ($perPageParam === 'all') || ((int)$perPageParam <= 0);
        $perPage = $noPagination ? null : (int)$perPageParam;
        $page = (int)($request->get('page', 1));

        $items = collect();

        // Helper to apply date filter on created_at-like fields
        $applyDateFilter = function ($query, $dateField = 'created_at') use ($dateFrom, $dateTo) {
            if ($dateFrom) { $query->whereDate($dateField, '>=', $dateFrom); }
            if ($dateTo) { $query->whereDate($dateField, '<=', $dateTo); }
            return $query;
        };

        // Appointments
        if ($typeFilter === 'appointment' || $typeFilter === 'all') {
            $appsQuery = Appointment::with([
                'property',
                'customer:id,name,phone',
                'provider:id,name,phone'
            ])->orderBy('created_at', 'desc');
            $applyDateFilter($appsQuery);
            $appointments = $noPagination ? $appsQuery->get() : $appsQuery->limit(100)->get();
            foreach ($appointments as $a) {
                $items->push($this->normalizeItem('appointment', [
                    'id' => $a->id,
                    'status' => $a->status,
                    'title' => optional($a->property)->type ?? 'موعد معاينة عقار',
                    'customer_name' => optional($a->customer)->name,
                    'provider_name' => optional($a->provider)->name,
                    'customer_phone' => optional($a->customer)->phone,
                    'provider_phone' => optional($a->provider)->phone,
                    'time' => $a->appointment_datetime ?? $a->created_at,
                    'raw' => $a,
                ]));
            }
        }

        // Car Service Orders
        if ($typeFilter === 'car_order' || $typeFilter === 'all') {
            $carQuery = CarServiceOrder::with([
                'client:id,name,phone',
                'provider:id,name,phone',
                'carRental'
            ])->orderBy('created_at', 'desc');
            $applyDateFilter($carQuery);
            $carOrders = $noPagination ? $carQuery->get() : $carQuery->limit(100)->get();
            foreach ($carOrders as $o) {
                $items->push($this->normalizeItem('car_order', [
                    'id' => $o->id,
                    'status' => $o->status,
                    'title' => $o->car_model ?: 'طلب تأجير سيارة',
                    'customer_name' => optional($o->client)->name,
                    'provider_name' => optional($o->provider)->name,
                    'customer_phone' => optional($o->client)->phone,
                    'provider_phone' => optional($o->provider)->phone,
                    'time' => $o->created_at,
                    'price' => $o->agreed_price ?? $o->requested_price,
                    'raw' => $o,
                ]));
            }
        }

        // Delivery Requests
        if ($typeFilter === 'delivery_request' || $typeFilter === 'all') {
            $delQuery = DeliveryRequest::with([
                'client:id,name,phone',
                'driver:id,name,phone'
            ])->orderBy('created_at', 'desc');
            $applyDateFilter($delQuery);
            $deliveryRequests = $noPagination ? $delQuery->get() : $delQuery->limit(100)->get();
            foreach ($deliveryRequests as $d) {
                $items->push($this->normalizeItem('delivery_request', [
                    'id' => $d->id,
                    'status' => $d->status,
                    'title' => $d->trip_type,
                    'customer_name' => optional($d->client)->name,
                    'provider_name' => optional($d->driver)->name,
                    'customer_phone' => optional($d->client)->phone,
                    'provider_phone' => optional($d->driver)->phone,
                    'time' => $d->delivery_time ?? $d->created_at,
                    'price' => $d->agreed_price ?? $d->price,
                    'raw' => $d,
                ]));
            }
        }

        // Restaurant Orders
        if ($typeFilter === 'restaurant_order' || $typeFilter === 'all') {
            $ordQuery = Order::with([
                'user:id,name,phone',
                'restaurant:id,restaurant_name,user_id',
                'restaurant.user:id,phone'
            ])->orderBy('created_at', 'desc');
            $applyDateFilter($ordQuery);
            $orders = $noPagination ? $ordQuery->get() : $ordQuery->limit(100)->get();
            foreach ($orders as $r) {
                $items->push($this->normalizeItem('restaurant_order', [
                    'id' => $r->id,
                    'status' => $r->status,
                    'title' => optional($r->restaurant)->restaurant_name,
                    'customer_name' => optional($r->user)->name,
                    'provider_name' => optional($r->restaurant)->restaurant_name,
                    'customer_phone' => optional($r->user)->phone,
                    'provider_phone' => optional(optional($r->restaurant)->user)->phone,
                    'time' => $r->created_at,
                    'price' => $r->total_price,
                    'raw' => $r,
                ]));
            }
        }

        // Security Permits
        if ($typeFilter === 'security_permit' || $typeFilter === 'all') {
            $permitQuery = SecurityPermit::with(['user:id,name,phone', 'country', 'nationality'])->orderBy('created_at', 'desc');
            $applyDateFilter($permitQuery);
            $permits = $noPagination ? $permitQuery->get() : $permitQuery->limit(100)->get();
            foreach ($permits as $p) {
                $items->push($this->normalizeItem('security_permit', [
                    'id' => $p->id,
                    'status' => $p->status,
                    'status_label' => $p->status_label,
                    'title' => 'تصريح أمني - ' . (optional($p->country)->name_ar ?? ''),
                    'customer_name' => optional($p->user)->name,
                    'provider_name' => 'الإدارة',
                    'customer_phone' => optional($p->user)->phone,
                    'provider_phone' => null,
                    'time' => $p->created_at,
                    'price' => $p->total_amount,
                    'raw' => $p,
                ]));
            }
        }

        // Apply status category filter if provided
        if ($statusCategoryFilter !== 'all') {
            $items = $items->filter(function ($item) use ($statusCategoryFilter) {
                return $item['status_category'] === $statusCategoryFilter;
            });
        }

        // Sort by time desc
        $items = $items->sortByDesc(function ($i) {
            return $i['time'] instanceof \DateTimeInterface ? $i['time']->getTimestamp() : strtotime((string)$i['time']);
        })->values();

        // Paginate manually (or return all if noPagination)
        $total = $items->count();
        if ($noPagination) {
            $paged = $items->values();
        } else {
            $start = max(0, ($page - 1) * ($perPage ?? 1));
            $paged = $items->slice($start, $perPage)->values();
        }

        return response()->json([
            'status' => true,
            'data' => $paged,
            'pagination' => [
                'current_page' => $noPagination ? 1 : $page,
                'per_page' => $noPagination ? 'all' : $perPage,
                'total' => $total,
                'last_page' => $noPagination ? 1 : (int)ceil($total / max(1, $perPage ?? 1)),
            ]
        ]);
    }

    /**
     * Normalize item into unified shape and add status category mapping.
     */
    protected function normalizeItem(string $type, array $payload): array
    {
        $status = $payload['status'] ?? null;
        [$category, $label] = $this->normalizeStatus($type, $status);

        return [
            'id' => $payload['id'],
            'type' => $type,
            'status' => $status,
            'status_category' => $category,
            'status_label' => $payload['status_label'] ?? $label,
            'title' => $payload['title'] ?? null,
            'customer_name' => $payload['customer_name'] ?? null,
            'provider_name' => $payload['provider_name'] ?? null,
            'customer_phone' => $payload['customer_phone'] ?? null,
            'provider_phone' => $payload['provider_phone'] ?? null,
            'time' => $payload['time'] ?? null,
            'price' => $payload['price'] ?? null,
        ];
    }

    /**
     * Map raw status to a unified category and label.
     */
    protected function normalizeStatus(string $type, ?string $status): array
    {
        $label = $status;
        $category = 'pending';

        $s = (string)($status ?? '');
        switch ($type) {
            case 'appointment':
                // statuses: pending, admin_approved, provider_approved, rejected
                if (in_array($s, ['pending'])) { $category = 'pending'; $label = 'قيد المراجعة'; }
                elseif (in_array($s, ['admin_approved', 'provider_approved'])) { $category = 'approved'; $label = 'مقبول'; }
                elseif ($s === 'rejected') { $category = 'rejected'; $label = 'مرفوض'; }
                break;

            case 'car_order':
                // statuses: pending_provider, negotiation, accepted, started, in_progress, completed, finished, rejected
                if (in_array($s, ['pending_provider', 'negotiation'])) { $category = 'pending'; $label = 'قيد المراجعة'; }
                elseif (in_array($s, ['accepted'])) { $category = 'approved'; $label = 'مقبول'; }
                elseif (in_array($s, ['started', 'in_progress'])) { $category = 'in_progress'; $label = 'قيد التنفيذ'; }
                elseif (in_array($s, ['completed', 'finished'])) { $category = 'completed'; $label = 'مكتمل'; }
                elseif ($s === 'rejected') { $category = 'rejected'; $label = 'مرفوض'; }
                break;

            case 'delivery_request':
                // statuses: pending_offers, accepted_waiting_driver, driver_arrived, trip_started, trip_completed, cancelled, rejected
                if ($s === DeliveryRequest::STATUS_PENDING_OFFERS) { $category = 'pending'; $label = 'في انتظار العروض'; }
                elseif (in_array($s, [DeliveryRequest::STATUS_ACCEPTED_WAITING_DRIVER, DeliveryRequest::STATUS_DRIVER_ARRIVED, DeliveryRequest::STATUS_TRIP_STARTED])) { $category = 'in_progress'; $label = 'قيد التنفيذ'; }
                elseif ($s === DeliveryRequest::STATUS_TRIP_COMPLETED) { $category = 'completed'; $label = 'مكتمل'; }
                elseif (in_array($s, [DeliveryRequest::STATUS_CANCELLED, DeliveryRequest::STATUS_REJECTED])) { $category = 'rejected'; $label = 'مرفوض/ملغي'; }
                break;

            case 'restaurant_order':
                // statuses: pending, accepted_by_admin, processing, completed, rejected_by_admin
                if ($s === 'pending') { $category = 'pending'; $label = 'قيد المراجعة'; }
                elseif (in_array($s, ['accepted_by_admin', 'processing'])) { $category = 'in_progress'; $label = 'قيد التنفيذ'; }
                elseif ($s === 'completed') { $category = 'completed'; $label = 'مكتمل'; }
                elseif ($s === 'rejected_by_admin') { $category = 'rejected'; $label = 'مرفوض'; }
                break;

            case 'security_permit':
                // statuses: new, pending, approved, rejected, expired
                if (in_array($s, ['new', 'pending'])) { $category = 'pending'; $label = 'قيد المراجعة'; }
                elseif ($s === 'approved') { $category = 'approved'; $label = 'مقبول'; }
                elseif ($s === 'rejected') { $category = 'rejected'; $label = 'مرفوض'; }
                elseif ($s === 'expired') { $category = 'expired'; $label = 'منتهي'; }
                break;
        }

        return [$category, $label];
    }
}