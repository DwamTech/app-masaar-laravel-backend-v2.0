<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\Notifier;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * [خاص بالعميل] إنشاء طلب جديد بحالة "قيد المراجعة".
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'restaurant_id'      => 'required|exists:restaurant_details,id',
            'items'              => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'note'               => 'nullable|string|max:1000',
        ]);
        
        $user = auth()->user();
        $subtotal = 0;
        $orderItemsData = [];

        foreach ($validatedData['items'] as $item) {
            $menuItem = MenuItem::find($item['menu_item_id']);
            $unitPrice = $menuItem->price;
            $total = $unitPrice * $item['quantity'];
            $subtotal += $total;
            $orderItemsData[] = [
                'menu_item_id' => $menuItem->id, 'title' => $menuItem->title, 'quantity' => $item['quantity'],
                'unit_price' => $unitPrice, 'total_price' => $total, 'image' => $menuItem->image,
            ];
        }

        $deliveryFee = 0; $vat = 0; $total = $subtotal + $deliveryFee + $vat;
        $orderNumber = 'ORD-' . time() . rand(100, 999);
        
        $order = Order::create([
            'user_id' => $user->id,
            'restaurant_id' => $validatedData['restaurant_id'],
            'status' => 'accepted_by_admin', // يتم قبول الطلب تلقائياً وتوجيهه للمطعم
            'order_number' => $orderNumber,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'vat' => $vat,
            'total_price' => $total,
            'note' => $validatedData['note'] ?? null,
        ]);
        
        $order->items()->createMany($orderItemsData);

        // إرسال إشعار قبول الطلب تلقائياً وتوجيهه للمطعم
        $this->sendOrderNotifications($order, 'accepted_by_admin');

        return response()->json([
            'status' => true,
            'message' => 'تم إرسال طلبك إلى المطعم وبانتظار التنفيذ.',
            'order' => $order->load('items')
        ], 201);
    }

    /**
     * [خاص بالمشرف] عرض كل الطلبات في النظام.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user:id,name', 'restaurant:id,restaurant_name', 'items']);
        if ($request->has('status')) { $query->where('status', $request->status); }
        return response()->json(['status' => true, 'orders' => $query->latest()->get()]);
    }

    /**
     * [عام] عرض تفاصيل طلب معين.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'restaurant', 'items.menuItem']);
        return response()->json(['status' => true, 'order' => $order]);
    }
    
    /**
     * [خاص بالمشرف] الموافقة على طلب (تبقى كوظيفة احتياطية).
     */
    public function approve(Order $order)
    {
        if ($order->status !== 'pending') { return response()->json(['status' => false, 'message' => 'يمكن فقط الموافقة على الطلبات التي في حالة الانتظار.'], 409); }
        $order->update(['status' => 'accepted_by_admin']);
        $this->sendOrderNotifications($order, 'accepted_by_admin');
        return response()->json(['status' => true, 'message' => 'تمت الموافقة على الطلب بنجاح.', 'order' => $order]);
    }

    /**
     * [خاص بالمشرف] رفض طلب مع ذكر السبب (تبقى كوظيفة احتياطية).
     */
    public function reject(Request $request, Order $order)
    {
        if ($order->status !== 'pending') { return response()->json(['status' => false, 'message' => 'يمكن فقط رفض الطلبات التي في حالة الانتظار.'], 409); }
        
        $validated = $request->validate(['reason' => 'nullable|string|max:500']);
        $reason = $validated['reason'] ?? null;

        $order->update(['status' => 'rejected_by_admin', 'rejection_reason' => $reason]);
        $this->sendOrderNotifications($order, 'rejected_by_admin');
        return response()->json(['status' => true, 'message' => 'تم رفض الطلب.', 'order' => $order]);
    }

    // ===========================================
    //  !!     الدوال الخاصة بالمطعم فقط     !!
    // ===========================================
    
    public function restaurantOrders(Request $request)
    {
        $restaurantUser = Auth::user();
        if ($restaurantUser->user_type !== 'restaurant' || !$restaurantUser->restaurantDetail) {
            return response()->json(['status' => false, 'message' => 'هذا الحساب غير مصرح له بعرض الطلبات.'], 403);
        }
        $restaurantId = $restaurantUser->restaurantDetail->id;
        $query = Order::with(['user:id,name,phone', 'items'])->where('restaurant_id', $restaurantId);
        if ($request->has('status')) { $query->where('status', $request->status); }
        $orders = $query->latest()->get();
        return response()->json(['status' => true, 'orders' => $orders]);
    }

    public function process(Order $order)
    {
        $restaurantUser = Auth::user();
        if ($restaurantUser->user_type !== 'restaurant' || $restaurantUser->restaurantDetail?->id !== $order->restaurant_id) {
            return response()->json(['status' => false, 'message' => 'غير مصرح لك بتنفيذ هذا الإجراء.'], 403);
        }
        // السماح ببدء التنفيذ (قبول المطعم) مباشرة من حالة الانتظار
        if (!in_array($order->status, ['pending', 'accepted_by_admin'])) {
            return response()->json(['status' => false, 'message' => 'يمكن فقط تنفيذ الطلبات التي هي قيد المراجعة أو تمت الموافقة عليها من الإدارة.'], 409);
        }
        $order->update(['status' => 'processing']);
        // إشعار القبول للمستخدم (يُعتبر البدء في التجهيز قبولاً)
        $this->sendOrderNotifications($order, 'processing');
        return response()->json(['status' => true, 'message' => 'تم قبول طلبك وسيتم البدء في التجهيز.', 'order' => $order]);
    }

    public function complete(Order $order)
    {
        $restaurantUser = Auth::user();
        if ($restaurantUser->user_type !== 'restaurant' || $restaurantUser->restaurantDetail?->id !== $order->restaurant_id) {
            return response()->json(['status' => false, 'message' => 'غير مصرح لك بتنفيذ هذا الإجراء.'], 403);
        }
        if ($order->status !== 'processing') {
            return response()->json(['status' => false, 'message' => 'يمكن فقط إنهاء الطلبات التي هي قيد التنفيذ.'], 409);
        }
        $order->update(['status' => 'completed']);
        $this->sendOrderNotifications($order, 'completed');
        return response()->json(['status' => true, 'message' => 'تم إنهاء الطلب بنجاح.', 'order' => $order]);
    }

    /**
     * دالة مركزية لإرسال إشعارات حالة الطلب.
     */
    private function sendOrderNotifications(Order $order, string $newStatus): void
    {
        $order->load(['user', 'restaurant.user']);
        $customer = $order->user;
        $restaurant = $order->restaurant;
        $restaurantOwner = $restaurant?->user;
        $restaurantName = $restaurant?->restaurant_name ?? 'المطعم';

        try {
            switch ($newStatus) {
                case 'pending':
                    if ($customer) {
                        Notifier::send(
                            $customer,
                            'order_pending',
                            'تم إرسال طلبك',
                            "طلبك رقم #{$order->order_number} من {$restaurantName} قيد المراجعة."
                        );
                    }  
                    if ($restaurantOwner) {
                        Notifier::send(
                            $restaurantOwner,
                            'new_order_restaurant',
                            'طلب جديد!',
                            "لديك طلب جديد رقم #{$order->order_number} من {($customer?->name ?: 'العميل')}."
                        );
                    }
                    break;

                case 'accepted_by_admin':
                    if ($customer) {
                        Notifier::send(
                            $customer,
                            'order_approved_admin',
                            'تم قبول طلبك',
                            "تمت الموافقة على طلبك رقم #{$order->order_number} وتم إرساله إلى {$restaurantName}."
                        );
                    }
                    if ($restaurantOwner) {
                        Notifier::send(
                            $restaurantOwner,
                            'new_order_restaurant',
                            'طلب جديد معتمد',
                            "لديك طلب معتمد رقم #{$order->order_number} بانتظار التنفيذ."
                        );
                    }
                    break;

                case 'rejected_by_admin':
                    $reason = $order->rejection_reason ? "\n\nالسبب: {$order->rejection_reason}" : '';
                    if ($customer) {
                        Notifier::send(
                            $customer,
                            'order_rejected_admin',
                            'تم رفض طلبك',
                            "نأسف، تم رفض طلبك رقم #{$order->order_number}.{$reason}"
                        );
                    }
                    break;

                case 'processing':
                    if ($customer) {
                        Notifier::send(
                            $customer,
                            'order_processing',
                            'جاري تحضير طلبك',
                            "{$restaurantName} يقوم الآن بتحضير طلبك رقم #{$order->order_number}."
                        );
                    }
                    break;

                case 'completed':
                    if ($customer) {
                        Notifier::send(
                            $customer,
                            'order_completed',
                            'طلبك جاهز! 🎉',
                            "طلبك رقم #{$order->order_number} من {$restaurantName} جاهز للاستلام الآن."
                        );
                    }
                    if ($restaurantOwner) {
                        Notifier::send(
                            $restaurantOwner,
                            'order_completed_restaurant',
                            'تم إكمال الطلب',
                            "تم إكمال الطلب رقم #{$order->order_number} بنجاح."
                        );
                    }
                    break;

                case 'rejected_by_restaurant':
                    $reason = $order->rejection_reason ? "\n\nالسبب: {$order->rejection_reason}" : '';
                    if ($customer) {
                        Notifier::send(
                            $customer,
                            'order_rejected_restaurant',
                            'تم رفض طلبك',
                            "نأسف، {$restaurantName} رفض طلبك رقم #{$order->order_number}.{$reason}"
                        );
                    }
                    break;
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send notification for order #{$order->id} to status {$newStatus}: " . $e->getMessage());
        }
    }

    /**
     * رفض الطلب من قبل المطعم.
     */
    public function restaurantReject(Request $request, Order $order)
    {
        $restaurantUser = Auth::user();
        if ($restaurantUser->user_type !== 'restaurant' || $restaurantUser->restaurantDetail?->id !== $order->restaurant_id) {
            return response()->json(['status' => false, 'message' => 'غير مصرح لك بتنفيذ هذا الإجراء.'], 403);
        }
        if (!in_array($order->status, ['pending', 'accepted_by_admin'])) {
            return response()->json(['status' => false, 'message' => 'يمكن فقط رفض الطلبات التي هي قيد المراجعة أو تمت الموافقة عليها من الإدارة.'], 409);
        }
        $validated = $request->validate(['reason' => 'nullable|string|max:500']);
        $order->update(['status' => 'rejected_by_restaurant', 'rejection_reason' => $validated['reason'] ?? null]);
        $this->sendOrderNotifications($order, 'rejected_by_restaurant');
        return response()->json(['status' => true, 'message' => 'تم رفض الطلب من قبل المطعم.', 'order' => $order]);
    }
}