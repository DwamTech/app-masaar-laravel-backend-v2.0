<?php

namespace App\Http\Controllers;

use App\Models\DeliveryRequest;
use App\Models\DeliveryDestination;
use App\Models\DeliveryOffer;
use App\Models\DeliveryStatusHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Support\Notifier;
use Illuminate\Support\Facades\Log;

class DeliveryRequestController extends Controller
{
    /**
     * إنشاء طلب توصيل جديد
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_type' => 'required|in:one_way,round_trip,multiple_destinations',
            'delivery_time' => 'required|date|after:now',
            'car_category' => 'required|in:economy,comfort,premium,van',
            'payment_method' => 'required|in:cash,bank_transfer,card',
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'destinations' => 'required|array|min:1',
            'destinations.*.location_name' => 'required|string|max:255',
            'destinations.*.latitude' => 'nullable|numeric|between:-90,90',
            'destinations.*.longitude' => 'nullable|numeric|between:-180,180',
            'destinations.*.address' => 'nullable|string|max:500',
            'destinations.*.contact_name' => 'nullable|string|max:255',
            'destinations.*.contact_phone' => 'nullable|string|max:20',
            'destinations.*.notes' => 'nullable|string|max:500',
            'destinations.*.is_pickup_point' => 'boolean',
            'destinations.*.is_dropoff_point' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            
            // إنشاء طلب التوصيل
            $deliveryRequest = DeliveryRequest::create([
                'client_id' => $user->id,
                'trip_type' => $request->trip_type,
                'delivery_time' => $request->delivery_time,
                'car_category' => $request->car_category,
                'payment_method' => $request->payment_method,
                'price' => $request->price,
                'notes' => $request->notes,
                'status' => DeliveryRequest::STATUS_PENDING_OFFERS
            ]);

            // إضافة الوجهات
            foreach ($request->destinations as $index => $destination) {
                DeliveryDestination::create(array_merge($destination, [
                    'delivery_request_id' => $deliveryRequest->id,
                    'order' => $index + 1
                ]));
            }

            // إضافة سجل في تاريخ الحالة
            DeliveryStatusHistory::create([
                'delivery_request_id' => $deliveryRequest->id,
                'status' => DeliveryRequest::STATUS_PENDING_OFFERS,
                'changed_by' => $user->id,
                'note' => 'تم إنشاء طلب التوصيل'
            ]);

            DB::commit();

            // إرسال الإشعارات
            $this->sendDeliveryNotifications($deliveryRequest, 'created');

            return response()->json([
                'status' => true,
                'message' => 'تم إنشاء طلب التوصيل بنجاح',
                'delivery_request' => $deliveryRequest->load('destinations')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating delivery request: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء إنشاء طلب التوصيل'
            ], 500);
        }
    }

    /**
     * عرض جميع طلبات التوصيل مع الفلترة
     */
    public function index(Request $request)
    {
        $query = DeliveryRequest::query();

        // فلترة حسب العميل
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // فلترة حسب السائق
        if ($request->has('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        // فلترة حسب الحالة
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب نوع الرحلة
        if ($request->has('trip_type')) {
            $query->where('trip_type', $request->trip_type);
        }

        // فلترة حسب التاريخ
        if ($request->has('date_from')) {
            $query->whereDate('delivery_time', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('delivery_time', '<=', $request->date_to);
        }

        $deliveryRequests = $query->with([
            'client',
            'driver',
            'destinations',
            'offers.driver',
            'statusHistories.changedBy'
        ])->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'status' => true,
            'delivery_requests' => $deliveryRequests
        ]);
    }

    /**
     * عرض تفاصيل طلب توصيل معين
     */
    public function show($id)
    {
        $deliveryRequest = DeliveryRequest::with([
            'client',
            'driver',
            'destinations',
            'offers.driver',
            'statusHistories.changedBy'
        ])->findOrFail($id);

        return response()->json([
            'status' => true,
            'delivery_request' => $deliveryRequest
        ]);
    }

    /**
     * تقديم عرض على طلب توصيل
     */
    public function submitOffer(Request $request, $deliveryRequestId)
    {
        $validator = Validator::make($request->all(), [
            'offered_price' => 'required|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:1',
            'offer_notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors()
            ], 422);
        }

        $deliveryRequest = DeliveryRequest::findOrFail($deliveryRequestId);
        $driver = Auth::user();

        // التحقق من أن الطلب يقبل عروض
        if (!$deliveryRequest->canReceiveOffers()) {
            return response()->json([
                'status' => false,
                'message' => 'هذا الطلب لا يقبل عروض في الوقت الحالي'
            ], 400);
        }

        // التحقق من عدم وجود عرض سابق من نفس السائق
        $existingOffer = DeliveryOffer::where('delivery_request_id', $deliveryRequestId)
            ->where('driver_id', $driver->id)
            ->first();

        if ($existingOffer) {
            return response()->json([
                'status' => false,
                'message' => 'لقد قدمت عرضاً على هذا الطلب مسبقاً'
            ], 400);
        }

        try {
            $offer = DeliveryOffer::create([
                'delivery_request_id' => $deliveryRequestId,
                'driver_id' => $driver->id,
                'offered_price' => $request->offered_price,
                'estimated_duration' => $request->estimated_duration,
                'offer_notes' => $request->offer_notes,
                'status' => DeliveryOffer::STATUS_PENDING
            ]);

            // إرسال إشعار للعميل
            $this->sendDeliveryNotifications($deliveryRequest, 'new_offer', $offer);

            return response()->json([
                'status' => true,
                'message' => 'تم تقديم العرض بنجاح',
                'offer' => $offer->load('driver')
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error submitting offer: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء تقديم العرض'
            ], 500);
        }
    }

    /**
     * قبول عرض معين
     */
    public function acceptOffer(Request $request, $deliveryRequestId, $offerId)
    {
        $deliveryRequest = DeliveryRequest::findOrFail($deliveryRequestId);
        $offer = DeliveryOffer::where('delivery_request_id', $deliveryRequestId)
            ->findOrFail($offerId);

        // التحقق من صلاحية العميل
        if ($deliveryRequest->client_id !== Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بهذا الإجراء'
            ], 403);
        }

        // التحقق من حالة الطلب
        if (!$deliveryRequest->canReceiveOffers()) {
            return response()->json([
                'status' => false,
                'message' => 'لا يمكن قبول عروض على هذا الطلب'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // تحديث العرض
            $offer->update([
                'status' => DeliveryOffer::STATUS_ACCEPTED,
                'accepted_at' => now()
            ]);

            // تحديث طلب التوصيل
            $deliveryRequest->update([
                'driver_id' => $offer->driver_id,
                'agreed_price' => $offer->offered_price,
                'estimated_duration' => $offer->estimated_duration,
                'status' => DeliveryRequest::STATUS_ACCEPTED_WAITING_DRIVER,
                'accepted_at' => now()
            ]);

            // رفض باقي العروض
            DeliveryOffer::where('delivery_request_id', $deliveryRequestId)
                ->where('id', '!=', $offerId)
                ->where('status', DeliveryOffer::STATUS_PENDING)
                ->update([
                    'status' => DeliveryOffer::STATUS_REJECTED,
                    'rejected_at' => now(),
                    'rejection_reason' => 'تم قبول عرض آخر'
                ]);

            // إضافة سجل في تاريخ الحالة
            DeliveryStatusHistory::create([
                'delivery_request_id' => $deliveryRequest->id,
                'status' => DeliveryRequest::STATUS_ACCEPTED_WAITING_DRIVER,
                'changed_by' => Auth::id(),
                'note' => 'تم قبول العرض من السائق: ' . $offer->driver->name
            ]);

            DB::commit();

            // إرسال الإشعارات
            $this->sendDeliveryNotifications($deliveryRequest, 'offer_accepted', $offer);

            return response()->json([
                'status' => true,
                'message' => 'تم قبول العرض بنجاح',
                'delivery_request' => $deliveryRequest->load(['driver', 'destinations'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error accepting offer: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء قبول العرض'
            ], 500);
        }
    }

    /**
     * تحديث حالة طلب التوصيل
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:driver_arrived,trip_started,trip_completed,cancelled',
            'note' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors()
            ], 422);
        }

        $deliveryRequest = DeliveryRequest::findOrFail($id);
        $user = Auth::user();
        $newStatus = $request->status;

        // التحقق من الصلاحيات
        if ($deliveryRequest->driver_id !== $user->id && $deliveryRequest->client_id !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بهذا الإجراء'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // تحديث الحالة مع الوقت المناسب
            $updateData = ['status' => $newStatus];
            
            switch ($newStatus) {
                case DeliveryRequest::STATUS_DRIVER_ARRIVED:
                    $updateData['driver_arrived_at'] = now();
                    break;
                case DeliveryRequest::STATUS_TRIP_STARTED:
                    $updateData['started_at'] = now();
                    break;
                case DeliveryRequest::STATUS_TRIP_COMPLETED:
                    $updateData['completed_at'] = now();
                    break;
            }

            $deliveryRequest->update($updateData);

            // إضافة سجل في تاريخ الحالة
            DeliveryStatusHistory::create([
                'delivery_request_id' => $deliveryRequest->id,
                'status' => $newStatus,
                'changed_by' => $user->id,
                'note' => $request->note ?? 'تم تحديث حالة الطلب'
            ]);

            DB::commit();

            // إرسال الإشعارات
            $this->sendDeliveryNotifications($deliveryRequest, $newStatus);

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث حالة الطلب بنجاح',
                'delivery_request' => $deliveryRequest
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating status: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء تحديث الحالة'
            ], 500);
        }
    }

    /**
     * إلغاء طلب التوصيل
     */
    public function cancel(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors()
            ], 422);
        }

        $deliveryRequest = DeliveryRequest::findOrFail($id);
        $user = Auth::user();

        // التحقق من الصلاحيات
        if ($deliveryRequest->client_id !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بإلغاء هذا الطلب'
            ], 403);
        }

        // التحقق من إمكانية الإلغاء
        if ($deliveryRequest->isCompleted()) {
            return response()->json([
                'status' => false,
                'message' => 'لا يمكن إلغاء طلب مكتمل'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $deliveryRequest->update([
                'status' => DeliveryRequest::STATUS_CANCELLED,
                'rejection_reason' => $request->reason
            ]);

            // إضافة سجل في تاريخ الحالة
            DeliveryStatusHistory::create([
                'delivery_request_id' => $deliveryRequest->id,
                'status' => DeliveryRequest::STATUS_CANCELLED,
                'changed_by' => $user->id,
                'note' => 'تم إلغاء الطلب من قبل العميل. السبب: ' . ($request->reason ?? 'غير محدد')
            ]);

            DB::commit();

            // إرسال الإشعارات
            $this->sendDeliveryNotifications($deliveryRequest, 'cancelled');

            return response()->json([
                'status' => true,
                'message' => 'تم إلغاء الطلب بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling delivery request: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء إلغاء الطلب'
            ], 500);
        }
    }

    /**
     * دالة مركزية لإرسال إشعارات طلبات التوصيل
     */
    private function sendDeliveryNotifications(DeliveryRequest $deliveryRequest, string $triggerStatus, $offer = null): void
    {
        Log::info("--- [Delivery Notification] Triggered for Request #{$deliveryRequest->id} with status '{$triggerStatus}' ---");
        
        $deliveryRequest->load(['client', 'driver']);
        $client = $deliveryRequest->client;
        $driver = $deliveryRequest->driver;

        try {
            switch ($triggerStatus) {
                case 'created':
                case 'pending_offers':
                    if ($client) {
                        $this->trySendNotification($client, 'delivery_request_created', 'تم إنشاء طلب التوصيل', 'تم إنشاء طلبك رقم #' . $deliveryRequest->id . ' بنجاح وهو في انتظار العروض');
                    }
                    
                    // إشعار السائقين من نفس المحافظة فقط
                    $clientGovernorate = $client ? $client->governorate : null;
                    Log::info("Client governorate: {$clientGovernorate}");
                    
                    if ($clientGovernorate) {
                        $availableDrivers = User::where('user_type', 'driver')
                            ->where('governorate', $clientGovernorate)
                            ->get();
                    } else {
                        // إذا لم تكن المحافظة محددة، أرسل لجميع السائقين
                        Log::warning("Client governorate not set, sending to all drivers");
                        $availableDrivers = User::where('user_type', 'driver')->get();
                    }
                            
                    foreach ($availableDrivers as $availableDriver) {
                        $this->trySendNotification(
                            $availableDriver, 
                            'new_delivery_request', 
                            'طلب توصيل جديد في منطقتك', 
                            'يوجد طلب توصيل جديد متاح في ' . $clientGovernorate . ' - الطلب رقم #' . $deliveryRequest->id
                        );
                    }
                    
                    Log::info("Sent notifications to " . count($availableDrivers) . " drivers in governorate: {$clientGovernorate}");
                    break;

                case 'new_offer':
                    if ($client && $offer) {
                        $this->trySendNotification($client, 'new_offer_received', 'عرض جديد على طلبك', 'تم تقديم عرض جديد على طلبك رقم #' . $deliveryRequest->id);
                    }
                    break;

                case 'offer_accepted':
                case 'accepted_waiting_driver':
                    if ($driver) {
                        $this->trySendNotification($driver, 'offer_accepted', 'تم قبول عرضك', 'تم قبول عرضك على الطلب رقم #' . $deliveryRequest->id . ' - يرجى التوجه لنقطة الاستلام');
                    }
                    if ($client) {
                        $this->trySendNotification($client, 'offer_accepted_client', 'تم قبول العرض', 'تم قبول العرض وسيتوجه السائق إليك قريباً');
                    }
                    break;

                case 'driver_arrived':
                    if ($client) {
                        $this->trySendNotification($client, 'driver_arrived', 'وصل السائق', 'وصل السائق إلى نقطة الاستلام');
                    }
                    if ($driver) {
                        $this->trySendNotification($driver, 'driver_arrived_confirmation', 'تأكيد الوصول', 'تم تأكيد وصولك لنقطة الاستلام');
                    }
                    break;

                case 'trip_started':
                    if ($client) {
                        $this->trySendNotification($client, 'trip_started', 'بدأت الرحلة', 'بدأت رحلتك رقم #' . $deliveryRequest->id);
                    }
                    if ($driver) {
                        $this->trySendNotification($driver, 'trip_started_driver', 'بدأت الرحلة', 'بدأت تنفيذ الطلب رقم #' . $deliveryRequest->id);
                    }
                    break;

                case 'trip_completed':
                    if ($client) {
                        $this->trySendNotification($client, 'trip_completed', 'انتهت الرحلة', 'انتهت رحلتك رقم #' . $deliveryRequest->id . ' بنجاح');
                    }
                    if ($driver) {
                        $this->trySendNotification($driver, 'trip_completed', 'انتهت الرحلة', 'انتهت الرحلة رقم #' . $deliveryRequest->id . ' بنجاح');
                    }
                    break;

                case 'cancelled':
                    if ($driver) {
                        $this->trySendNotification($driver, 'trip_cancelled', 'تم إلغاء الرحلة', 'تم إلغاء الرحلة رقم #' . $deliveryRequest->id);
                    }
                    if ($client) {
                        $this->trySendNotification($client, 'trip_cancelled_client', 'تم إلغاء الطلب', 'تم إلغاء طلبك رقم #' . $deliveryRequest->id);
                    }
                    break;

                case 'rejected':
                    if ($client) {
                        $this->trySendNotification($client, 'trip_rejected', 'تم رفض الطلب', 'تم رفض طلبك رقم #' . $deliveryRequest->id);
                    }
                    break;
            }
        } catch (\Throwable $e) {
            Log::error("[Delivery Notification] Exception occurred: " . $e->getMessage());
        }
        
        Log::info("--- [Delivery Notification] Process finished for Request #{$deliveryRequest->id} ---");
    }

    /**
     * دالة مساعدة لإرسال الإشعارات
     */
    private function trySendNotification(User $user, string $type, string $title, string $message): void
    {
        Log::info("[Notification Helper] Preparing to notify User #{$user->id} ({$user->name}) with title '{$title}'.");
        
        $tokens = DB::table('device_tokens')
            ->where('user_id', $user->id)
            ->where('is_enabled', 1)
            ->pluck('token')
            ->all();
        
        if (empty($tokens)) {
            Log::warning("[Notification Helper] SKIPPING: No active device tokens found for User #{$user->id}.");
            return;
        }
        
        Log::info("[Notification Helper] Found " . count($tokens) . " token(s) for User #{$user->id}. Attempting to send...");
        Notifier::send($user, $type, $title, $message);
        Log::info("[Notification Helper] SUCCESS: Notifier::send called for User #{$user->id}.");
    }

    /**
     * عرض الطلبات المتاحة للسائقين
     */
    public function availableRequests(Request $request)
    {
        $driver = Auth::user();
        
        $query = DeliveryRequest::query()
            ->where('status', 'pending_offers')
            ->whereNull('driver_id');

        // فلترة حسب المحافظة - إظهار الطلبات من نفس محافظة السائق فقط
        if ($driver && $driver->governorate) {
            $query->whereHas('client', function($q) use ($driver) {
                $q->where('governorate', $driver->governorate);
            });
        }

        // فلترة حسب نوع الرحلة
        if ($request->has('trip_type')) {
            $query->where('trip_type', $request->trip_type);
        }

        // فلترة حسب المنطقة الجغرافية باستخدام الإحداثيات
        if ($request->has('latitude') && $request->has('longitude') && $request->has('radius')) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $radius = $request->radius; // بالكيلومتر
            
            $query->whereHas('client', function($q) use ($lat, $lng, $radius) {
                $q->whereNotNull('latitude')
                  ->whereNotNull('longitude')
                  ->whereRaw(
                      "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?",
                      [$lat, $lng, $lat, $radius]
                  );
            });
        }

        $availableRequests = $query->with([
            'client',
            'destinations',
            'offers' => function($query) {
                $query->where('status', 'pending');
            }
        ])->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'status' => true,
            'available_requests' => $availableRequests
        ]);
    }

    /**
     * عرض العروض المقدمة على طلب توصيل معين
     */
    public function getOffers($deliveryRequestId)
    {
        $deliveryRequest = DeliveryRequest::findOrFail($deliveryRequestId);
        
        $offers = $deliveryRequest->offers()->with('driver')->get();

        return response()->json([
            'status' => true,
            'offers' => $offers
        ]);
    }

    /**
     * جلب تفاصيل طلب التوصيل مع العروض المرسلة
     */
    public function getDeliveryRequestWithOffers($deliveryRequestId)
    {
        try {
            $deliveryRequest = DeliveryRequest::with([
                'client',
                'driver',
                'destinations',
                'offers' => function($query) {
                    $query->with('driver')->orderBy('created_at', 'desc');
                }
            ])->find($deliveryRequestId);

            if (!$deliveryRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'لم يتم العثور على الطلب المحدد'
                ], 404);
            }

            // التحقق من صلاحية المستخدم للوصول إلى هذا الطلب
            $user = Auth::user();
            if ($deliveryRequest->client_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'status' => false,
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا الطلب'
                ], 403);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'delivery_request' => $deliveryRequest,
                    'offers' => $deliveryRequest->offers
                ],
                'message' => 'تم جلب البيانات بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching delivery request with offers: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات'
            ], 500);
        }
    }

    /**
     * عرض تاريخ حالات طلب التوصيل
     */
    public function getStatusHistory($deliveryRequestId)
    {
        $deliveryRequest = DeliveryRequest::findOrFail($deliveryRequestId);
        
        $statusHistory = $deliveryRequest->statusHistories()
            ->with('changedBy')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'status_history' => $statusHistory
        ]);
    }
}