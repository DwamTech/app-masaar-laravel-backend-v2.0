<?php

namespace App\Http\Controllers;

use App\Models\CarServiceOrder;
use App\Models\CarRental;
use App\Models\OrderOffer;
use App\Models\OrderStatusHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Support\Notifier;
use Illuminate\Support\Facades\Log;

class CarServiceOrderController extends Controller
{
    /**
     * 1. إنشاء طلب جديد (من العميل).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_rental_id'     => 'required|exists:car_rentals,id',
            'provider_type'     => 'required|in:office,person',
            // بقية الحقول تُزال من الطلب وتُضبط افتراضياً إن لزم
            'requested_price'   => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation errors', 'errors' => $validator->errors()], 422);
        }

        $user = auth()->user();
        // هذا التدفق هو للتأجير فقط
        $orderType = 'rent';
        $initialStatus = 'pending_admin';

        // نوع المزود يحدده العميل: office أو person
        $providerType = $request->input('provider_type');

        $payload = [
            'client_id'          => $user->id,
            'car_rental_id'      => $request->input('car_rental_id'),
            'provider_id'        => $request->input('provider_id'),
            'order_type'         => $orderType,
            'provider_type'      => $providerType,
            'with_driver'        => (bool) $request->input('with_driver', false),
            'car_category'       => $request->input('car_category', 'economy'),
            'car_model'          => $request->input('car_model'),
            'payment_method'     => $request->input('payment_method', 'cash'),
            'rental_period_type' => $request->input('rental_period_type'),
            'rental_duration'    => $request->input('rental_duration'),
            'status'             => $initialStatus,
            'requested_price'    => $request->input('requested_price'),
            'from_location'      => null,
            'to_location'        => null,
            'delivery_location'  => $request->input('delivery_location'),
            'delivery_time'      => $request->input('delivery_time'),
            'requested_date'     => $request->input('requested_date'),
            'rental_start_at'    => $request->input('rental_start_at'),
            'rental_end_at'      => $request->input('rental_end_at'),
        ];

        $order = CarServiceOrder::create($payload);

        OrderStatusHistory::create(['order_id' => $order->id, 'status' => $initialStatus, 'changed_by' => $user->id, 'note' => 'تم إنشاء الطلب.']);
        
        $this->sendCarOrderNotifications($order, 'created');

        return response()->json(['status' => true, 'message' => 'تم إرسال طلبك بنجاح.', 'order' => $order], 201);
    }

    /**
     * 2. عرض كل الطلبات (مع الفلترة).
     */
    public function index(Request $request)
    {
        $query = CarServiceOrder::query();
        if ($request->has('client_id')) { $query->where('client_id', $request->client_id); }
        if ($request->has('provider_id')) { $query->where('provider_id', $request->provider_id); }
        if ($request->has('status')) { $query->where('status', $request->status); }
        if ($request->has('order_type')) { $query->where('order_type', $request->order_type); }
        $orders = $query->with(['client', 'provider', 'carRental', 'offers', 'statusHistories'])->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => true, 'orders' => $orders]);
    }

    /**
     * 3. عرض تفاصيل طلب معين.
     */
    public function show($id)
    {
        $order = CarServiceOrder::with(['client', 'provider', 'carRental', 'offers', 'statusHistories'])->findOrFail($id);
        return response()->json(['status' => true, 'order' => $order]);
    }

    /**
     * [خاص بالأدمن] تحديث حالة الطلب (موافقة/رفض).
     */
    public function updateStatusByAdmin(Request $request, $id)
    {
        $order = CarServiceOrder::findOrFail($id);
        if ($order->status !== 'pending_admin') { return response()->json(['status' => false, 'message' => 'لا يمكن تغيير حالة هذا الطلب حاليًا.'], 409); }
        
        $validated = $request->validate([
            'status' => 'required|in:pending_provider,rejected',
            'reason' => 'nullable|string|max:500',
        ]);
        
        $newStatus = $validated['status'];
        $order->status = $newStatus;
        if ($newStatus === 'rejected') {
            $order->rejection_reason = $validated['reason'] ?? null;
            $order->rejected_at = now();
        }
        $order->save();
        
        $note = ($newStatus === 'rejected') ? 'تم رفض الطلب من الإدارة.' : 'تم اعتماد الطلب من الإدارة.';
        OrderStatusHistory::create(['order_id' => $order->id, 'status' => $newStatus, 'changed_by' => auth()->id(), 'note' => $note]);

        $this->sendCarOrderNotifications($order, $newStatus);
        return response()->json(['status' => true, 'message' => 'تم تحديث حالة الطلب بنجاح.']);
    }

    /**
     * [عام] تقديم عرض سعر.
     */
    public function offer(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [ 'offered_by' => 'required|in:provider,client', 'price' => 'required|numeric', 'offer_note' => 'nullable|string|max:500']);
        if ($validator->fails()) { return response()->json(['status' => false, 'errors' => $validator->errors()], 422); }

        $order = CarServiceOrder::findOrFail($orderId);
        if (!in_array($order->status, ['pending_provider', 'negotiation'])) { return response()->json(['status' => false, 'message' => 'لا يمكن التفاوض على هذا الطلب حالياً'], 400); }

        $offer = OrderOffer::create(['order_id' => $orderId, 'offered_by' => $request->offered_by, 'price' => $request->price, 'offer_note' => $request->offer_note]);
        $order->status = 'negotiation';
        $order->save();
        OrderStatusHistory::create(['order_id' => $order->id, 'status' => 'negotiation', 'changed_by' => Auth::id(), 'note' => 'تقديم عرض جديد.']);
        return response()->json(['status' => true, 'message' => 'تم تقديم العرض بنجاح', 'offer' => $offer]);
    }

    /**
     * [عام] قبول العرض النهائي.
     */
    public function acceptOffer(Request $request, $orderId, $offerId)
    {
        $order = CarServiceOrder::findOrFail($orderId);
        $offer = OrderOffer::where('order_id', $orderId)->findOrFail($offerId);

        $order->agreed_price = $offer->price;
        $order->status = 'accepted';
        $order->provider_id = $order->provider_id ?? Auth::id();
        $order->accepted_at = now();
        $order->save();

        OrderStatusHistory::create(['order_id' => $order->id, 'status' => 'accepted', 'changed_by' => Auth::id(), 'note' => 'تم قبول العرض والاتفاق على السعر النهائي']);
        
        $this->sendCarOrderNotifications($order, 'accepted');
        return response()->json(['status' => true, 'message' => 'تم قبول العرض، والطلب جاهز للتنفيذ', 'order' => $order]);
    }

    /**
     * [مقدم خدمة] قبول الطلب بدون تفاوض (تعيين مقدم الخدمة واعتماد الطلب).
     */
    public function acceptByProvider(Request $request, $id)
    {
        $order = CarServiceOrder::findOrFail($id);
        if (!in_array($order->status, ['pending_provider', 'negotiation'])) {
            return response()->json(['status' => false, 'message' => 'لا يمكن قبول هذا الطلب في حالته الحالية'], 409);
        }

        $order->provider_id = $order->provider_id ?? Auth::id();
        if ($request->filled('agreed_price')) {
            $order->agreed_price = $request->input('agreed_price');
        }
        $order->status = 'accepted';
        $order->accepted_at = now();
        $order->save();

        OrderStatusHistory::create(['order_id' => $order->id, 'status' => 'accepted', 'changed_by' => Auth::id(), 'note' => 'قبول الطلب من مقدم الخدمة بدون تفاوض']);
        $this->sendCarOrderNotifications($order, 'accepted');
        return response()->json(['status' => true, 'message' => 'تم قبول الطلب بنجاح', 'order' => $order]);
    }

    /**
     * [أدمن] جلب كل طلبات التأجير مع تفاصيلها وحالاتها.
     */
    public function adminIndex(Request $request)
    {
        $query = CarServiceOrder::query()->where('order_type', 'rent');
        if ($request->has('status')) { $query->where('status', $request->status); }
        if ($request->has('car_rental_id')) { $query->where('car_rental_id', $request->car_rental_id); }
        $orders = $query->with(['client', 'provider', 'carRental', 'offers', 'statusHistories'])->orderBy('created_at', 'desc')->get();

        // إضافة علم يوضح هل الطلب جديد أو تم أخذه
        $orders = $orders->map(function ($order) {
            $order->is_new = ($order->status === 'pending_admin');
            $order->is_taken = ($order->status === 'accepted' && !empty($order->provider_id));
            return $order;
        });

        return response()->json(['status' => true, 'orders' => $orders]);
    }

    /**
     * دالة مركزية لإرسال إشعارات طلبات السيارات [نسخة تشخيصية].
     */
    private function sendCarOrderNotifications(CarServiceOrder $order, string $triggerStatus): void
    {
        Log::info("--- [Car Order Notification] Triggered for Order #{$order->id} with status '{$triggerStatus}' ---");
        $order->load(['client', 'provider', 'carRental.user']);
        $customer = $order->client;
        $provider = $order->provider;
        $rentalOwner = $order->carRental->user;

        Log::info("[Car Order Notification] Customer found: " . ($customer ? "ID {$customer->id}" : 'NULL'));
        Log::info("[Car Order Notification] Provider (final driver) found: " . ($provider ? "ID {$provider->id}" : 'NULL'));
        Log::info("[Car Order Notification] Rental Owner found: " . ($rentalOwner ? "ID {$rentalOwner->id}" : 'NULL'));
        Log::info("[Car Order Notification] Entering SWITCH case for status: '{$triggerStatus}'");

        try {
            switch ($triggerStatus) {
                case 'created':
                    if ($customer) $this->trySendNotification($customer, 'car_order_placed', 'تم استلام طلبك', 'طلبك رقم #' . $order->id . ' قيد المراجعة.');
                    if ($order->order_type === 'ride' && $rentalOwner) $this->trySendNotification($rentalOwner, 'new_ride_request', 'يوجد طلب توصيلة جديد!', 'لديك طلب توصيلة جديد.');
                    elseif ($order->order_type === 'rent') {
                        $admins = User::where('user_type', 'admin')->get();
                        foreach ($admins as $admin) $this->trySendNotification($admin, 'new_rent_request', 'طلب حجز سيارة جديد', 'يوجد طلب حجز سيارة جديد.');
                    }
                    break;
                case 'pending_provider':
                    if ($customer) $this->trySendNotification($customer, 'car_order_admin_approved', 'تم قبول طلبك من الإدارة', 'تمت الموافقة على طلبك رقم #' . $order->id);
                    if ($rentalOwner) $this->trySendNotification($rentalOwner, 'new_rent_request', 'لديك طلب حجز سيارة جديد!', 'لديك طلب حجز سيارة جديد.');
                    break;
                case 'rejected':
                    $reason = $order->rejection_reason ? ' السبب: ' . $order->rejection_reason : '';
                    if ($customer) $this->trySendNotification($customer, 'car_order_rejected', 'تم رفض طلبك', 'نأسف، تم رفض طلبك رقم #' . $order->id . '.' . $reason);
                    break;
                case 'accepted':
                    if ($customer) $this->trySendNotification($customer, 'car_order_accepted', 'تم تأكيد طلبك!', 'تم تأكيد طلبك رقم #' . $order->id);
                    if ($provider) $this->trySendNotification($provider, 'car_order_confirmed', 'لديك خدمة مؤكدة!', 'تم تأكيد خدمتك للطلب رقم #' . $order->id);
                    break;
            }
        } catch (\Throwable $e) {
            Log::error("[Car Order Notification] A global exception occurred: " . $e->getMessage());
        }
        
        Log::info("--- [Car Order Notification] Process finished for Order #{$order->id} ---");
    }

    /**
     * دالة مساعدة تحاول إرسال الإشعار وتتحقق من وجود التوكين أولاً.
     */
    private function trySendNotification(User $user, string $type, string $title, string $message): void
    {
        Log::info("[Notification Helper] Preparing to notify User #{$user->id} ({$user->name}) with title '{$title}'.");
        $tokens = DB::table('device_tokens')->where('user_id', '==', $user->id)->where('is_enabled', 1)->pluck('token')->all();
        
        if (empty($tokens)) {
            Log::warning("[Notification Helper] SKIPPING: No active device tokens found for User #{$user->id}.");
            return;
        }
        
        Log::info("[Notification Helper] Found " . count($tokens) . " token(s) for User #{$user->id}. Attempting to send...");
        Notifier::send($user, $type, $title, $message);
        Log::info("[Notification Helper] SUCCESS: Notifier::send called for User #{$user->id}.");
    }
}