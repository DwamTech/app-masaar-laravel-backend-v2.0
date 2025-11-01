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
use App\Models\Car;
use App\Models\CarRentalOfficesDetail;
use Carbon\Carbon;

class CarServiceOrderController extends Controller
{
    /**
     * 1. إنشاء طلب جديد (من العميل).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // لا يُشترط تحديد مكتب التأجير عند إنشاء الطلب؛ يتم تعيينه عند قبول مقدم الخدمة
            'car_rental_id'     => 'nullable|exists:car_rentals,id',
            // نوع مقدم الخدمة اختياري، ويفترض "office" افتراضياً
            'provider_type'     => 'nullable|in:office,person',
            // بقية الحقول تُزال من الطلب وتُضبط افتراضياً إن لزم
            'requested_price'   => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation errors', 'errors' => $validator->errors()], 422);
        }

        $user = auth()->user();
        // هذا التدفق هو للتأجير فقط
        $orderType = 'rent';
        // نبدأ مباشرةً في حالة انتظار مقدم الخدمة، ليظهر الطلب لكل المكاتب فوراً
        $initialStatus = 'pending_provider';

        // نوع المزود يحدده العميل: office أو person (افتراضي office)
        $providerType = $request->input('provider_type', 'office');

        $payload = [
            'client_id'          => $user->id,
            // يُترك car_rental_id فارغاً عند الإنشاء ليُحدد عند قبول المكتب
            'car_rental_id'      => $request->input('car_rental_id'),
            // لا يوجد مقدم نهائي عند الإنشاء
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
        if ($request->has('status')) {
            $status = $request->status;
            // دعم القيم المستخدمة في الفلاتر من الفلاتر: in_progress/completed
            if ($status === 'in_progress') { $status = 'started'; }
            if ($status === 'completed') { $status = 'finished'; }
            $query->where('status', $status);
        }
        if ($request->has('order_type')) { $query->where('order_type', $request->order_type); }
        $orders = $query->with(['client', 'provider', 'carRental', 'offers', 'statusHistories'])->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => true, 'orders' => $orders]);
    }

    /**
     * [مزود الخدمة] عرض الطلبات المتاحة لجميع مكاتب التأجير
     * تُظهر جميع طلبات التأجير ذات الحالة pending_provider دون تقييد بالمحافظة.
     */
    public function availableForProviders(Request $request)
    {
        $orders = CarServiceOrder::where('order_type', 'rent')
            ->where('status', 'pending_provider')
            ->with(['client', 'provider', 'carRental', 'offers', 'statusHistories'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'orders' => $orders,
            'count'  => $orders->count(),
        ]);
    }

    /**
     * [مزود الخدمة] عرض الطلبات القيد التنفيذ المرتبطة بالمقدم الحالي (حسب التوكين).
     */
    public function inProgressForProvider(Request $request)
    {
        $user = Auth::user();
        $myCarRental = $user->car_rental ?? $user->carRental ?? null;

        $orders = CarServiceOrder::where('order_type', 'rent')
            ->where('status', 'started')
            ->where(function ($q) use ($user, $myCarRental) {
                $q->where('provider_id', $user->id);
                if ($myCarRental) { $q->orWhere('car_rental_id', $myCarRental->id); }
            })
            ->with(['client', 'provider', 'carRental', 'offers', 'statusHistories'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'orders' => $orders,
            'count'  => $orders->count(),
        ]);
    }

    /**
     * [مزود الخدمة] عرض الطلبات المنتهية للمقدم الحالي (حسب التوكين).
     */
    public function completedForProvider(Request $request)
    {
        $user = Auth::user();
        $myCarRental = $user->car_rental ?? $user->carRental ?? null;

        $orders = CarServiceOrder::where('order_type', 'rent')
            ->where('status', 'finished')
            ->where(function ($q) use ($user, $myCarRental) {
                $q->where('provider_id', $user->id);
                if ($myCarRental) { $q->orWhere('car_rental_id', $myCarRental->id); }
            })
            ->with(['client', 'provider', 'carRental', 'offers', 'statusHistories'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'orders' => $orders,
            'count'  => $orders->count(),
        ]);
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

        $currentUser = Auth::user();
        // إذا كان القابل مكتب تأجير، قم بتعيين car_rental_id الخاص به على الطلب
        if ($currentUser && $currentUser->user_type === 'car_rental_office') {
            $myCarRental = $currentUser->car_rental ?? $currentUser->carRental ?? null;
            if ($myCarRental && empty($order->car_rental_id)) {
                $order->car_rental_id = $myCarRental->id;
            }
        }

        // تعيين مقدم التنفيذ النهائي إن لزم (قد يكون سائقاً أو مستخدماً يمثل المكتب)
        $order->provider_id = $order->provider_id ?? ($currentUser ? $currentUser->id : Auth::id());
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
     * [مقدم خدمة] بدء تنفيذ الطلب (تحويل الحالة من accepted إلى in_progress).
     */
    public function startByProvider(Request $request, $id)
    {
        $order = CarServiceOrder::findOrFail($id);
        $currentUser = Auth::user();

        // تحقق من صلاحية مقدم الخدمة على هذا الطلب
        if (!$this->isOrderProvider($order, $currentUser)) {
            return response()->json(['status' => false, 'message' => 'غير مصرح لك بإدارة هذا الطلب'], 403);
        }

        if ($order->status !== 'accepted') {
            return response()->json(['status' => false, 'message' => 'لا يمكن البدء بهذا الطلب في حالته الحالية'], 409);
        }

        $order->status = 'started';
        $order->save();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'started',
            'changed_by' => $currentUser->id,
            'note' => 'بدأ تنفيذ الطلب من قبل مقدم الخدمة',
        ]);

        return response()->json(['status' => true, 'message' => 'تم بدء تنفيذ الطلب', 'order' => $order]);
    }

    /**
     * [مقدم خدمة] إنهاء تنفيذ الطلب (تحويل الحالة من in_progress إلى finished).
     */
    public function completeByProvider(Request $request, $id)
    {
        $order = CarServiceOrder::findOrFail($id);
        $currentUser = Auth::user();

        // تحقق من صلاحية مقدم الخدمة على هذا الطلب
        if (!$this->isOrderProvider($order, $currentUser)) {
            return response()->json(['status' => false, 'message' => 'غير مصرح لك بإدارة هذا الطلب'], 403);
        }

        if ($order->status !== 'started') {
            return response()->json(['status' => false, 'message' => 'لا يمكن إنهاء هذا الطلب في حالته الحالية'], 409);
        }

        $order->status = 'finished';
        $order->save();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'finished',
            'changed_by' => $currentUser->id,
            'note' => 'تم إنهاء الطلب من قبل مقدم الخدمة',
        ]);

        return response()->json(['status' => true, 'message' => 'تم إنهاء الطلب بنجاح', 'order' => $order]);
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
        // قد يكون car_rental_id غير معيّن عند الإنشاء؛ نتعامل مع القيمة بشكل آمن
        $rentalOwner = $order->carRental ? $order->carRental->user : null;

        Log::info("[Car Order Notification] Customer found: " . ($customer ? "ID {$customer->id}" : 'NULL'));
        Log::info("[Car Order Notification] Provider (final driver) found: " . ($provider ? "ID {$provider->id}" : 'NULL'));
        Log::info("[Car Order Notification] Rental Owner found: " . ($rentalOwner ? "ID {$rentalOwner->id}" : 'NULL'));
        Log::info("[Car Order Notification] Entering SWITCH case for status: '{$triggerStatus}'");

        try {
            switch ($triggerStatus) {
                case 'created':
                    if ($customer) {
                        $this->trySendNotification($customer, 'car_order_placed', 'تم استلام طلبك', 'طلبك رقم #' . $order->id . ' قيد المراجعة.');
                    }
                    if ($order->order_type === 'ride' && $rentalOwner) {
                        $this->trySendNotification($rentalOwner, 'new_ride_request', 'يوجد طلب توصيلة جديد!', 'لديك طلب توصيلة جديد.');
                    } elseif ($order->order_type === 'rent') {
                        // إخطار جميع مكاتب التأجير بوجود طلب جديد متاح
                        $rentalOffices = User::where('user_type', 'car_rental_office')->get();
                        foreach ($rentalOffices as $officeUser) {
                            $this->trySendNotification($officeUser, 'new_rent_request', 'طلب حجز سيارة جديد', 'يوجد طلب حجز سيارة جديد متاح في النظام.');
                        }
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
     * إحصائيات مزود الخدمة: السيارات، الطلبات، الإيرادات، والأزمنة.
     * Endpoint: GET /api/provider/stats
     * Query: ?from=YYYY-MM-DD&to=YYYY-MM-DD&period=day|week|month
     */
    public function providerStats(Request $request)
    {
        try {
            $user = Auth::user();
            $carRental = $user->car_rental ?? $user->carRental ?? null;
            $carRentalId = $carRental ? $carRental->id : null;

            $from = $request->query('from');
            $to = $request->query('to');
            $period = $request->query('period');

            // Resolve date range
            $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
            $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
            if ($period && (!$fromDate || !$toDate)) {
                $now = Carbon::now();
                if ($period === 'day') {
                    $fromDate = $fromDate ?: $now->copy()->startOfDay();
                    $toDate = $toDate ?: $now->copy()->endOfDay();
                } elseif ($period === 'week') {
                    $fromDate = $fromDate ?: $now->copy()->startOfWeek();
                    $toDate = $toDate ?: $now->copy()->endOfWeek();
                } elseif ($period === 'month') {
                    $fromDate = $fromDate ?: $now->copy()->startOfMonth();
                    $toDate = $toDate ?: $now->copy()->endOfMonth();
                }
            }

            // Provider ownership filter (by provider_id OR car_rental_id)
            $providerFilter = function ($q) use ($user, $carRentalId) {
                $q->where('provider_id', $user->id);
                if ($carRentalId) { $q->orWhere('car_rental_id', $carRentalId); }
            };

            $baseOrdersQuery = CarServiceOrder::query()
                ->where('order_type', 'rent')
                ->where($providerFilter);

            if ($fromDate && $toDate) {
                $baseOrdersQuery->whereBetween('created_at', [$fromDate, $toDate]);
            } elseif ($fromDate) {
                $baseOrdersQuery->where('created_at', '>=', $fromDate);
            } elseif ($toDate) {
                $baseOrdersQuery->where('created_at', '<=', $toDate);
            }

            $totalOrders = (clone $baseOrdersQuery)->count();

            // Counts by status
            $statusCountsRaw = (clone $baseOrdersQuery)
                ->select('status', DB::raw('COUNT(*) as cnt'))
                ->groupBy('status')
                ->pluck('cnt', 'status')
                ->toArray();

            $byStatus = [];
            foreach (['pending_provider', 'negotiation', 'accepted', 'started', 'finished', 'rejected', 'cancelled'] as $st) {
                $byStatus[$st] = intval($statusCountsRaw[$st] ?? 0);
            }

            $currentInProgress = $byStatus['started'] ?? 0;
            $completedCount = $byStatus['finished'] ?? 0;

            // Global available (not restricted to provider) in selected range
            $availableQuery = CarServiceOrder::query()
                ->where('order_type', 'rent')
                ->where('status', 'pending_provider');
            if ($fromDate && $toDate) {
                $availableQuery->whereBetween('created_at', [$fromDate, $toDate]);
            } elseif ($fromDate) {
                $availableQuery->where('created_at', '>=', $fromDate);
            } elseif ($toDate) {
                $availableQuery->where('created_at', '<=', $toDate);
            }
            $newAvailableGlobal = $availableQuery->count();

            // Cars summary for this office
            $carsTotal = 0; $carsReviewed = 0; $carsUnreviewed = 0;
            $officeAvailableForRent = null; $officeAvailableForDelivery = null;
            if ($carRentalId) {
                $carsTotal = Car::where('car_rental_id', $carRentalId)->count();
                $carsReviewed = Car::where('car_rental_id', $carRentalId)->where('is_reviewed', true)->count();
                $carsUnreviewed = max(0, $carsTotal - $carsReviewed);
                $officeDetails = CarRentalOfficesDetail::where('car_rental_id', $carRentalId)->first();
                if ($officeDetails) {
                    $officeAvailableForRent = $officeDetails->is_available_for_rent;
                    $officeAvailableForDelivery = $officeDetails->is_available_for_delivery;
                }
            }

            // Revenue based on finished orders in the range
            $providerOrderIds = (clone $baseOrdersQuery)->pluck('id');
            $finishedHistQuery = OrderStatusHistory::whereIn('order_id', $providerOrderIds)
                ->where('status', 'finished');
            if ($fromDate && $toDate) {
                $finishedHistQuery->whereBetween('created_at', [$fromDate, $toDate]);
            } elseif ($fromDate) {
                $finishedHistQuery->where('created_at', '>=', $fromDate);
            } elseif ($toDate) {
                $finishedHistQuery->where('created_at', '<=', $toDate);
            }
            $finishedOrderIds = $finishedHistQuery->pluck('order_id')->unique();

            $revenueTotal = CarServiceOrder::whereIn('id', $finishedOrderIds)->sum('agreed_price');
            $finishedCount = $finishedOrderIds->count();
            $revenueAvg = $finishedCount > 0 ? round($revenueTotal / $finishedCount, 2) : 0.0;

            // Average acceptance duration (created_at -> accepted_at)
            $acceptQuery = (clone $baseOrdersQuery)->whereNotNull('accepted_at');
            if ($fromDate && $toDate) {
                $acceptQuery->whereBetween('accepted_at', [$fromDate, $toDate]);
            } elseif ($fromDate) {
                $acceptQuery->where('accepted_at', '>=', $fromDate);
            } elseif ($toDate) {
                $acceptQuery->where('accepted_at', '<=', $toDate);
            }
            $avgAcceptMinutes = $acceptQuery
                ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, accepted_at)) as avg_accept'))
                ->value('avg_accept');
            $avgAcceptMinutes = $avgAcceptMinutes ? intval(round($avgAcceptMinutes)) : 0;

            // Average execution duration (started -> finished) per order
            $avgExecutionMinutes = 0;
            if ($providerOrderIds->count() > 0) {
                $histories = OrderStatusHistory::whereIn('order_id', $providerOrderIds)
                    ->whereIn('status', ['started', 'finished'])
                    ->orderBy('order_id')
                    ->orderBy('created_at')
                    ->get()
                    ->groupBy('order_id');

                $durations = [];
                foreach ($histories as $oid => $group) {
                    $startedAt = null; $finishedAt = null;
                    foreach ($group as $h) {
                        if ($h->status === 'started' && !$startedAt) { $startedAt = $h->created_at; }
                        if ($h->status === 'finished' && !$finishedAt) { $finishedAt = $h->created_at; }
                    }
                    if ($startedAt && $finishedAt) {
                        if ($fromDate && $finishedAt->lt($fromDate)) { continue; }
                        if ($toDate && $finishedAt->gt($toDate)) { continue; }
                        $durations[] = $finishedAt->diffInMinutes($startedAt);
                    }
                }
                $avgExecutionMinutes = count($durations) ? intval(round(array_sum($durations) / count($durations))) : 0;
            }

            return response()->json([
                'status' => true,
                'summary' => [
                    'cars_total' => $carsTotal,
                    'cars_reviewed' => $carsReviewed,
                    'cars_unreviewed' => $carsUnreviewed,
                    'office_available_for_rent' => $officeAvailableForRent,
                    'office_available_for_delivery' => $officeAvailableForDelivery,
                ],
                'orders' => [
                    'total' => $totalOrders,
                    'by_status' => $byStatus,
                    'new_available_global' => $newAvailableGlobal,
                    'current_in_progress' => $currentInProgress,
                    'completed_count' => $completedCount,
                ],
                'revenue' => [
                    'total' => (float) $revenueTotal,
                    'average_per_order' => (float) $revenueAvg,
                ],
                'durations' => [
                    'avg_accept_minutes' => $avgAcceptMinutes,
                    'avg_execution_minutes' => $avgExecutionMinutes,
                ],
                'filters' => [
                    'range' => [
                        'from' => $fromDate ? $fromDate->toDateString() : null,
                        'to' => $toDate ? $toDate->toDateString() : null,
                    ],
                    'period' => $period,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('providerStats error', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to load provider statistics',
            ], 500);
        }
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

    /**
     * التحقق من كون المستخدم الحالي هو مقدم الخدمة لهذا الطلب (سواء المعرّف user_id أو car_rental_id).
     */
    private function isOrderProvider(CarServiceOrder $order, User $user): bool
    {
        if (!empty($order->provider_id) && $order->provider_id === $user->id) {
            return true;
        }
        $myCarRental = $user->car_rental ?? $user->carRental ?? null;
        if ($myCarRental && !empty($order->car_rental_id) && $order->car_rental_id === $myCarRental->id) {
            return true;
        }
        return false;
    }
}