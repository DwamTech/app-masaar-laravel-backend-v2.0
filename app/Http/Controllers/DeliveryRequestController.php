<?php

namespace App\Http\Controllers;

use App\Models\DeliveryRequest;
use App\Models\DeliveryDestination;
use App\Models\DeliveryOffer;
use App\Models\DeliveryStatusHistory;
use App\Models\User;

use App\Models\DeliveryRequestDriverRejection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Support\Notifier;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
            'governorate' => 'nullable|string|max:100',
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
                'governorate' => $request->governorate,
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
                            ->where('push_notifications_enabled', 1)
                            ->where('is_available', 1)
                            ->whereHas('driverCars', function($q){
                                $q->where('owner_type', 'driver')->where('is_reviewed', 1);
                            })
                            ->get();
                    } else {
                        // إذا لم تكن المحافظة محددة، أرسل لجميع السائقين المؤهلين فقط
                        Log::warning("Client governorate not set, sending to all eligible drivers");
                        $availableDrivers = User::where('user_type', 'driver')
                            ->where('push_notifications_enabled', 1)
                            ->where('is_available', 1)
                            ->whereHas('driverCars', function($q){
                                $q->where('owner_type', 'driver')->where('is_reviewed', 1);
                            })
                            ->get();
                    }
                    
                    Log::info("Found " . count($availableDrivers) . " drivers in governorate: {$clientGovernorate}");
                    
                    $notifiedDrivers = 0;
                    foreach ($availableDrivers as $availableDriver) {
                        // التحقق من وجود device tokens نشطة
                        $hasActiveTokens = DB::table('device_tokens')
                            ->where('user_id', $availableDriver->id)
                            ->where('is_enabled', 1)
                            ->exists();
                            
                        if ($hasActiveTokens) {
                            $this->trySendNotification(
                                $availableDriver, 
                                'new_delivery_request', 
                                'طلب توصيل جديد في منطقتك', 
                                'يوجد طلب توصيل جديد متاح في ' . $clientGovernorate . ' - الطلب رقم #' . $deliveryRequest->id
                            );
                            $notifiedDrivers++;
                        } else {
                            Log::warning("Driver {$availableDriver->name} (ID: {$availableDriver->id}) has no active device tokens");
                        }
                    }
                    
                    Log::info("Successfully notified {$notifiedDrivers} out of " . count($availableDrivers) . " drivers in governorate: {$clientGovernorate}");
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
     * تطبيع اسم المحافظة لتقليل عدم التطابق بين الصيغ المختلفة
     * مثال: إزالة بادئة "محافظة" إن وجدت
     */
    private function normalizeGovernorate(?string $name): string
    {
        if (!$name) return '';
        $n = trim($name);
        // إزالة "محافظة " في بداية الاسم إن وجدت
        $n = preg_replace('/^\s*محافظة\s+/u', '', $n);
        return $n;
    }

    /**
     * عرض الطلبات المتاحة للسائقين
     */
    public function availableRequests(Request $request)
    {
        $driver = Auth::user();
        
        $query = DeliveryRequest::query()
            ->where('status', 'pending_offers')
            ->whereNull('driver_id')
            ->whereDoesntHave('driverRejections', function($q) use ($driver) {
                $q->where('driver_id', $driver->id);
            });

        // فلترة حسب المحافظة - إظهار الطلبات من نفس محافظة السائق فقط
        if ($driver && $driver->governorate) {
            $driverGov = $driver->governorate;
            $normalizedGov = $this->normalizeGovernorate($driverGov);
            $govVariants = [$driverGov];
            if ($normalizedGov !== $driverGov) {
                $govVariants[] = $normalizedGov;
            }
            // إضافة صيغة "محافظة <الاسم>" لضمان التوافق مع بيانات العملاء/المصدر
            $prefixedNormalized = 'محافظة ' . $normalizedGov;
            if (!in_array($prefixedNormalized, $govVariants, true)) {
                $govVariants[] = $prefixedNormalized;
            }
            $query->whereIn('governorate', $govVariants);
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

        // توحيد صيغة الاستجابة مع بقية الواجهات المستخدمة في تطبيق السائق
        // إبقاء المفتاح القديم "available_requests" لضمان عدم كسر التوافق مع سكربتات الاختبار
        return response()->json([
            'status' => true,
            'success' => true,
            // القائمة المسطحة التي يستهلكها تطبيق السائق
            'data' => $availableRequests->items(),
            // تفاصيل الترقيم
            'pagination' => [
                'current_page' => $availableRequests->currentPage(),
                'last_page' => $availableRequests->lastPage(),
                'per_page' => $availableRequests->perPage(),
                'total' => $availableRequests->total()
            ],
            // الحقل القديم المتوافق مع أدوات الاختبار الداخلية
            'available_requests' => $availableRequests
        ]);
    }

    /**
     * عرض العروض المقدمة على طلب توصيل معين
     */
    public function getOffers($deliveryRequestId)
    {
        $deliveryRequest = DeliveryRequest::findOrFail($deliveryRequestId);

        // قراءة باراميتر الفرز
        $sortKey = strtolower(request()->query('sort', 'price_asc'));
        $sortMap = [
            'price_asc'     => ['offered_price', 'asc'],
            'price_desc'    => ['offered_price', 'desc'],
            'duration_asc'  => ['estimated_duration', 'asc'],
            'duration_desc' => ['estimated_duration', 'desc'],
            'created_asc'   => ['created_at', 'asc'],
            'created_desc'  => ['created_at', 'desc'],
        ];
        [$column, $direction] = $sortMap[$sortKey] ?? ['offered_price', 'asc'];

        $offers = $deliveryRequest
            ->offers()
            ->with('driver')
            ->orderBy($column, $direction)
            ->get();

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
            // قراءة باراميتر الفرز
            $sortKey = strtolower(request()->query('sort', 'price_asc'));
            $sortMap = [
                'price_asc'     => ['offered_price', 'asc'],
                'price_desc'    => ['offered_price', 'desc'],
                'duration_asc'  => ['estimated_duration', 'asc'],
                'duration_desc' => ['estimated_duration', 'desc'],
                'created_asc'   => ['created_at', 'asc'],
                'created_desc'  => ['created_at', 'desc'],
            ];
            [$column, $direction] = $sortMap[$sortKey] ?? ['offered_price', 'asc'];

            $deliveryRequest = DeliveryRequest::with([
                'client',
                'driver',
                'destinations',
                'offers' => function ($query) use ($column, $direction) {
                    $query->with('driver')->orderBy($column, $direction);
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
            if ($deliveryRequest->client_id !== $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا الطلب'
                ], 403);
            }

            // تحديد الرسالة بناءً على حالة العروض
            $offersCount = $deliveryRequest->offers->count();
            $message = 'تم جلب البيانات بنجاح';
            $lastMessage = null;

            if ($offersCount === 0) {
                if ($deliveryRequest->status === 'pending_offers') {
                    $message = 'لم يتم تقديم أي عروض على هذا الطلب بعد';
                    $lastMessage = 'لا توجد عروض متاحة في منطقتك حالياً. جاري البحث عن سائقين متاحين...';
                } else {
                    $message = 'لا توجد عروض متاحة لهذا الطلب';
                    $lastMessage = 'تم تغيير حالة الطلب ولا يمكن استقبال عروض جديدة';
                }
            } else {
                $message = "تم العثور على {$offersCount} عرض لهذا الطلب";
                $lastMessage = "يوجد {$offersCount} عرض متاح للاختيار من بينها";
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'delivery_request' => $deliveryRequest,
                    'offers' => $deliveryRequest->offers,
                    'offers_count' => $offersCount,
                    'has_offers' => $offersCount > 0
                ],
                'message' => $message,
                'lastMessage' => $lastMessage
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching delivery request with offers: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في الاتصال بالخادم يرجي التحقق من اتصال الإنترنت والمحاولة مرة أخري'
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

    /**
     * عرض العروض المقدمة من السائق (طلباته)
     */
    public function myOffers(Request $request)
    {
        $driver = Auth::user();
        
        $offers = DeliveryOffer::where('driver_id', $driver->id)
            ->with([
                'deliveryRequest' => function($query) {
                    $query->with(['client', 'destinations']);
                },
                'driver'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // تحويل البيانات لتتوافق مع التطبيق
        $requests = $offers->getCollection()->map(function($offer) {
            $deliveryRequest = $offer->deliveryRequest;
            $deliveryRequest->offer_status = $offer->status;
            $deliveryRequest->offered_price = $offer->offered_price;
            $deliveryRequest->offer_id = $offer->id;
            return $deliveryRequest;
        });

        return response()->json([
            'status' => true,
            'data' => $requests,
            'pagination' => [
                'current_page' => $offers->currentPage(),
                'last_page' => $offers->lastPage(),
                'per_page' => $offers->perPage(),
                'total' => $offers->total()
            ]
        ]);
    }

    /**
     * عرض الطلبات المنتهية للسائق
     */
    public function completedRequests(Request $request)
    {
        $driver = Auth::user();
        
        $completedRequests = DeliveryRequest::where('driver_id', $driver->id)
            ->whereIn('status', [
                DeliveryRequest::STATUS_TRIP_COMPLETED,
                DeliveryRequest::STATUS_CANCELLED
            ])
            ->with(['client', 'destinations', 'offers' => function($query) use ($driver) {
                $query->where('driver_id', $driver->id);
            }])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return response()->json([
            'status' => true,
            'success' => true,
            'data' => $completedRequests->items(),
            'pagination' => [
                'current_page' => $completedRequests->currentPage(),
                'last_page' => $completedRequests->lastPage(),
                'per_page' => $completedRequests->perPage(),
                'total' => $completedRequests->total()
            ]
        ]);
    }

    /**
     * رفض السائق لطلب معيّن (لن يظهر له لاحقاً)
     */
    public function declineRequest(Request $request, $id)
    {
        $driver = Auth::user();
        $deliveryRequest = DeliveryRequest::findOrFail($id);

        if ($driver->user_type !== 'driver') {
            return response()->json(['status' => false, 'message' => 'هذه الخدمة مخصصة للسائقين فقط'], 403);
        }

        if ($deliveryRequest->status !== DeliveryRequest::STATUS_PENDING_OFFERS || $deliveryRequest->driver_id) {
            return response()->json([
                'status' => false,
                'message' => 'لا يمكن رفض هذا الطلب في حالته الحالية'
            ], 400);
        }

        $record = DeliveryRequestDriverRejection::firstOrCreate([
            'delivery_request_id' => $deliveryRequest->id,
            'driver_id' => $driver->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم رفض الطلب ولن يظهر لك مرة أخرى',
            'declined' => true
        ]);
    }

    /**
     * إحصائيات شاملة تهم مقدم خدمة التوصيل (السائق)
     * Endpoint: GET /delivery/driver/stats
     * Query (اختياري): ?from=YYYY-MM-DD&to=YYYY-MM-DD&period=day|week|month
     */
    public function driverStats(Request $request)
    {
        try {
            $driver = Auth::user();

            $from = $request->query('from');
            $to = $request->query('to');
            $period = $request->query('period');

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

            $availableQuery = DeliveryRequest::query()
                ->where('status', DeliveryRequest::STATUS_PENDING_OFFERS)
                ->whereNull('driver_id')
                ->whereDoesntHave('driverRejections', function($q) use ($driver) {
                    $q->where('driver_id', $driver->id);
                });
            if ($driver && $driver->governorate) {
                $driverGov = $driver->governorate;
                $normalizedGov = $this->normalizeGovernorate($driverGov);
                $govVariants = [$driverGov];
                if ($normalizedGov !== $driverGov) { $govVariants[] = $normalizedGov; }
                $prefixedNormalized = 'محافظة ' . $normalizedGov;
                if (!in_array($prefixedNormalized, $govVariants, true)) { $govVariants[] = $prefixedNormalized; }
                $availableQuery->whereIn('governorate', $govVariants);
            }
            $availableRequestsCount = $availableQuery->count();

            $baseOffersQuery = DeliveryOffer::where('driver_id', $driver->id);
            if ($fromDate && $toDate) {
                $baseOffersQuery->whereBetween('created_at', [$fromDate, $toDate]);
            } elseif ($fromDate) {
                $baseOffersQuery->where('created_at', '>=', $fromDate);
            } elseif ($toDate) {
                $baseOffersQuery->where('created_at', '<=', $toDate);
            }
            $offersTotal = (clone $baseOffersQuery)->count();
            $offersByStatus = (clone $baseOffersQuery)
                ->select('status', DB::raw('COUNT(*) as cnt'))
                ->groupBy('status')
                ->pluck('cnt', 'status')
                ->toArray();
            $offersPending = intval($offersByStatus[DeliveryOffer::STATUS_PENDING] ?? 0);
            $offersAccepted = intval($offersByStatus[DeliveryOffer::STATUS_ACCEPTED] ?? 0);
            $offersRejected = intval($offersByStatus[DeliveryOffer::STATUS_REJECTED] ?? 0);
            $offersWithdrawn = intval($offersByStatus[DeliveryOffer::STATUS_WITHDRAWN] ?? 0);

            $consideredOffers = max(1, $offersPending + $offersAccepted + $offersRejected + $offersWithdrawn);
            $acceptanceRate = round(($offersAccepted / $consideredOffers) * 100, 2);

            $activeStatuses = [
                DeliveryRequest::STATUS_ACCEPTED_WAITING_DRIVER,
                DeliveryRequest::STATUS_DRIVER_ARRIVED,
                DeliveryRequest::STATUS_TRIP_STARTED,
            ];
            $activeAssignmentsCount = DeliveryRequest::where('driver_id', $driver->id)
                ->whereIn('status', $activeStatuses)
                ->count();
            $completedAssignmentsCount = DeliveryRequest::where('driver_id', $driver->id)
                ->where('status', DeliveryRequest::STATUS_TRIP_COMPLETED)
                ->count();
            $cancelledAssignmentsCount = DeliveryRequest::where('driver_id', $driver->id)
                ->where('status', DeliveryRequest::STATUS_CANCELLED)
                ->count();

            $now = Carbon::now();
            $revenueTotal = (float) DeliveryRequest::where('driver_id', $driver->id)
                ->where('status', DeliveryRequest::STATUS_TRIP_COMPLETED)
                ->sum('agreed_price');
            $revenueToday = (float) DeliveryRequest::where('driver_id', $driver->id)
                ->where('status', DeliveryRequest::STATUS_TRIP_COMPLETED)
                ->whereBetween('completed_at', [$now->copy()->startOfDay(), $now->copy()->endOfDay()])
                ->sum('agreed_price');
            $revenueWeek = (float) DeliveryRequest::where('driver_id', $driver->id)
                ->where('status', DeliveryRequest::STATUS_TRIP_COMPLETED)
                ->whereBetween('completed_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])
                ->sum('agreed_price');
            $revenueMonth = (float) DeliveryRequest::where('driver_id', $driver->id)
                ->where('status', DeliveryRequest::STATUS_TRIP_COMPLETED)
                ->whereBetween('completed_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
                ->sum('agreed_price');
            $revenueInRange = null;
            if ($fromDate && $toDate) {
                $revenueInRange = (float) DeliveryRequest::where('driver_id', $driver->id)
                    ->where('status', DeliveryRequest::STATUS_TRIP_COMPLETED)
                    ->whereBetween('completed_at', [$fromDate, $toDate])
                    ->sum('agreed_price');
            }

            // حساب متوسط زمن الاستجابة للعروض (مع معالجة أعطال قاعدة البيانات)
            $avgResponseMinutes = 0;
            try {
                $avgResponseMinutesRaw = DeliveryOffer::where('driver_id', $driver->id)
                    ->join('delivery_requests', 'delivery_requests.id', '=', 'delivery_offers.delivery_request_id');
                if ($fromDate && $toDate) {
                    $avgResponseMinutesRaw->whereBetween('delivery_offers.created_at', [$fromDate, $toDate]);
                } elseif ($fromDate) {
                    $avgResponseMinutesRaw->where('delivery_offers.created_at', '>=', $fromDate);
                } elseif ($toDate) {
                    $avgResponseMinutesRaw->where('delivery_offers.created_at', '<=', $toDate);
                }
                $avgResponseMinutesVal = $avgResponseMinutesRaw
                    ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, delivery_requests.created_at, delivery_offers.created_at)) as avg_min'))
                    ->value('avg_min');
                $avgResponseMinutes = $avgResponseMinutesVal ? intval(round($avgResponseMinutesVal)) : 0;
            } catch (\Throwable $calcEx) {
                Log::warning('driverStats avg_response_minutes calculation failed', ['message' => $calcEx->getMessage()]);
                $avgResponseMinutes = 0; // fallback آمن
            }

            $avgEstimatedDurationAccepted = DeliveryOffer::where('driver_id', $driver->id)
                ->where('status', DeliveryOffer::STATUS_ACCEPTED)
                ->avg('estimated_duration');
            $avgEstimatedDurationAccepted = $avgEstimatedDurationAccepted ? intval(round($avgEstimatedDurationAccepted)) : 0;

            $availability = [
                'is_available' => (bool) ($driver->is_available ?? false),
                'governorate' => $driver->governorate ?? null,
                'is_eligible' => true,
            ];

            $recentOffers = DeliveryOffer::where('driver_id', $driver->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'delivery_request_id', 'status', 'offered_price', 'estimated_duration', 'created_at']);
            $recentActiveRequests = DeliveryRequest::where('driver_id', $driver->id)
                ->whereIn('status', $activeStatuses)
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get(['id', 'status', 'agreed_price', 'estimated_duration', 'delivery_time']);
            $recentCompletedRequests = DeliveryRequest::where('driver_id', $driver->id)
                ->where('status', DeliveryRequest::STATUS_TRIP_COMPLETED)
                ->orderBy('completed_at', 'desc')
                ->limit(5)
                ->get(['id', 'status', 'agreed_price', 'estimated_duration', 'completed_at']);

            return response()->json([
                'status' => true,
                'stats' => [
                    'counts' => [
                        'available_requests' => $availableRequestsCount,
                        'my_offers' => [
                            'total' => $offersTotal,
                            'pending' => $offersPending,
                            'accepted' => $offersAccepted,
                            'rejected' => $offersRejected,
                            'withdrawn' => $offersWithdrawn,
                        ],
                        'assignments' => [
                            'active' => $activeAssignmentsCount,
                            'completed' => $completedAssignmentsCount,
                            'cancelled' => $cancelledAssignmentsCount,
                        ],
                    ],
                    'revenue' => [
                        'total' => $revenueTotal,
                        'today' => $revenueToday,
                        'week' => $revenueWeek,
                        'month' => $revenueMonth,
                        'in_range' => $revenueInRange,
                    ],
                    'performance' => [
                        'acceptance_rate_percent' => $acceptanceRate,
                        'avg_offer_response_minutes' => $avgResponseMinutes,
                        'avg_estimated_duration_accepted' => $avgEstimatedDurationAccepted,
                    ],
                    'availability' => $availability,
                    'recent_activity' => [
                        'recent_offers' => $recentOffers,
                        'active_requests' => $recentActiveRequests,
                        'completed_requests' => $recentCompletedRequests,
                    ],
                    'filters' => [
                        'range' => [
                            'from' => $fromDate ? $fromDate->toDateString() : null,
                            'to' => $toDate ? $toDate->toDateString() : null,
                        ],
                        'period' => $period,
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('driverStats error', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to load driver statistics',
            ], 500);
        }
    }
}
