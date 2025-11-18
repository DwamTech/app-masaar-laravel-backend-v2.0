<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CarRental;
use App\Models\DriverDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Support\Notifier;
use App\Http\Resources\DriverProfileResource;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // استعراض كل المستخدمين مع العلاقات
    public function index()
    {
        $users = User::with([
            'normalUser',
            'realEstate.officeDetail',
            'realEstate.individualDetail',
            'restaurantDetail',
            'carRental.officeDetail',
            'carRental.driverDetail'
        ])->get();

        return response()->json([
            'status' => true,
            'users' => $users
        ]);
    }

    // إضافة مستخدم جديد (Admin Only)
    public function store(Request $request)
    {
        if (auth()->user()->user_type !== 'admin') {
            return response()->json(['status' => false, 'message' => 'غير مصرح لك'], 403);
        }

        // التحقق الأساسي
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20|unique:users,phone',
            'governorate' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'user_type' => 'required|string',
        ]);

        // تحقق إضافي حسب نوع المستخدم قبل الإنشاء
        switch ($request->input('user_type')) {
            case 'normal':
                // لا حقول إضافية مطلوبة
                break;
            case 'real_estate_office':
                $request->validate([
                    'office_name' => 'required|string|max:255',
                    'office_address' => 'required|string|max:255',
                    'office_phone' => 'required|string|max:20',
                    'logo_image' => 'required|string',
                    'owner_id_front_image' => 'required|string',
                    'owner_id_back_image' => 'required|string',
                    'office_image' => 'required|string',
                    'commercial_register_front_image' => 'required|string',
                    'commercial_register_back_image' => 'required|string',
                    'tax_enabled' => 'nullable|boolean',
                ]);
                break;
            // يمكن إضافة حالات أخرى لاحقًا
            default:
                break;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'governorate' => $validated['governorate'] ?? null,
            'city' => $validated['city'] ?? null,
            'user_type' => $validated['user_type'],
        ]);

        // إنشاء العلاقات حسب نوع المستخدم
        switch ($user->user_type) {
            case 'normal':
                $user->normalUser()->create([]);
                break;
            case 'real_estate_office':
                $realEstate = $user->realEstate()->create(['type' => 'office']);
                $realEstate->officeDetail()->create([
                    'office_name' => $request->input('office_name'),
                    'office_address' => $request->input('office_address'),
                    'office_phone' => $request->input('office_phone'),
                    'logo_image' => $request->input('logo_image'),
                    'owner_id_front_image' => $request->input('owner_id_front_image'),
                    'owner_id_back_image' => $request->input('owner_id_back_image'),
                    'office_image' => $request->input('office_image'),
                    'commercial_register_front_image' => $request->input('commercial_register_front_image'),
                    'commercial_register_back_image' => $request->input('commercial_register_back_image'),
                    'tax_enabled' => $request->boolean('tax_enabled'),
                ]);
                break;
            default:
                // لا شيء إضافي
                break;
        }

        return response()->json([
            'status' => true,
            'message' => 'تم إضافة المستخدم بنجاح',
            'user' => $user->load([
                'normalUser',
                'realEstate.officeDetail',
                'realEstate.individualDetail',
            ]),
        ], 201);
    }

    // تعديل بيانات مستخدم (Admin Only)
    public function update(Request $request, $id)
    {
        // if (auth()->user()->user_type !== 'admin') {
        //     return response()->json(['status' => false, 'message' => 'غير مصرح لك'], 403);
        // }

        $user = User::findOrFail($id);
        $wasApproved = $user->is_approved; // حفظ الحالة السابقة

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|required|string|max:20|unique:users,phone,' . $user->id,
            'governorate' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'user_type' => 'sometimes|required|string',
            'is_approved' => 'sometimes|in:0,1',
            'the_best' => 'sometimes|in:0,1',
            'profile_image' => 'nullable|string|max:2048',

        ]);

        $user->update($validated);

        // إرسال إشعار عند قبول الحساب لأول مرة
        if (isset($validated['is_approved']) && $validated['is_approved'] == 1 && !$wasApproved) {
            Notifier::send(
                $user,
                'account_approved',
                'تم قبول حسابك',
                'مبروك! تم قبول حسابك من قبل الإدارة. يمكنك الآن الاستفادة من جميع خدمات التطبيق.'
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث بيانات المستخدم بنجاح',
            'user' => $user,
        ]);
    }

    // حذف مستخدم (Admin Only)
    public function destroy($id)
    {
        if (auth()->user()->user_type !== 'admin') {
            return response()->json(['status' => false, 'message' => 'غير مصرح لك'], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم حذف المستخدم بنجاح',
        ]);
    }

    // تغيير كلمة المرور للمستخدم المسجل دخوله
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        // التحقق من كلمة المرور الحالية
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'كلمة المرور الحالية غير صحيحة',
            ], 400);
        }

        // تحديث كلمة المرور
        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        // إلغاء جميع الـ tokens الحالية لإجبار المستخدم على تسجيل الدخول مرة أخرى
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم تغيير كلمة المرور بنجاح. يرجى تسجيل الدخول مرة أخرى.',
        ]);
    }

    /**
     * تحديث الموقع الجغرافي للمستخدم
     */
    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'current_address' => 'nullable|string|max:500',
            'location_sharing_enabled' => 'boolean'
        ]);

        $user = Auth::user();
        
        $user->update([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'current_address' => $validated['current_address'] ?? null,
            'location_updated_at' => now(),
            'location_sharing_enabled' => $validated['location_sharing_enabled'] ?? true
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث الموقع بنجاح',
            'location' => [
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
                'current_address' => $user->current_address,
                'location_updated_at' => $user->location_updated_at
            ]
        ]);
    }

    /**
     * الحصول على الموقع الحالي للمستخدم
     */
    public function getLocation()
    {
        $user = Auth::user();
        
        if (!$user->location_sharing_enabled) {
            return response()->json([
                'status' => false,
                'message' => 'مشاركة الموقع غير مفعلة'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'location' => [
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
                'governorate' => $user->governorate,
                'city' => $user->city,
                'current_address' => $user->current_address,
                'location_updated_at' => $user->location_updated_at
            ]
        ]);
    }

    /**
     * الحصول على بيانات السائق الكاملة مع التقييم وبيانات السيارة
     */
    public function getDriverProfile($driverId)
    {
        $driver = User::where('user_type', 'driver')
            ->with([
                'carRental.driverDetail',
                'driverCars' => function($query) {
                    $query->first(); // الحصول على أول سيارة فقط
                }
            ])
            ->find($driverId);

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'السائق غير موجود'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'driver' => new DriverProfileResource($driver)
        ]);
    }

    /**
     * الحصول على قائمة السائقين المتاحين مع بياناتهم الكاملة
     */
    public function getAvailableDrivers(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:100', // نطاق البحث بالكيلومتر
        ]);

        $query = User::where('user_type', 'driver')
            ->where('is_approved', 1)
            ->where('is_available', 1)
            ->with([
                'carRental.driverDetail',
                'driverCars' => function($query) {
                    $query->limit(1); // الحصول على أول سيارة فقط
                }
            ]);

        // إضافة فلترة حسب الموقع إذا تم توفيره
        if (isset($validated['latitude']) && isset($validated['longitude'])) {
            $radius = $validated['radius'] ?? 10; // افتراضي 10 كم
            $lat = $validated['latitude'];
            $lng = $validated['longitude'];
            
            $query->whereRaw(
                "(
                    6371 * acos(
                        cos(radians(?)) * cos(radians(latitude)) * 
                        cos(radians(longitude) - radians(?)) + 
                        sin(radians(?)) * sin(radians(latitude))
                    )
                ) <= ?",
                [$lat, $lng, $lat, $radius]
            );
        }

        $drivers = $query->get();

        $driversData = $drivers->map(function ($driver) {
            $primaryCar = $driver->driverCars->first();
            
            return [
                'id' => $driver->id,
                'name' => $driver->name,
                'profile_image' => $driver->profile_image,
                'rating' => $driver->rating,
                'rating_count' => $driver->rating_count,
                'phone' => $driver->phone,
                'location' => [
                    'latitude' => $driver->latitude,
                    'longitude' => $driver->longitude,
                    'current_address' => $driver->current_address,
                ],
                'car_info' => $primaryCar ? [
                    'car_type' => $primaryCar->car_type,
                    'car_model' => $primaryCar->car_model,
                    'car_color' => $primaryCar->car_color,
                    'license_plate' => $primaryCar->car_plate_number,
                ] : null,
                'driver_details' => $driver->carRental?->driverDetail ? [
                    'cost_per_km' => $driver->carRental->driverDetail->cost_per_km,
                    'payment_methods' => $driver->carRental->driverDetail->payment_methods,
                ] : null
            ];
        });

        return response()->json([
            'status' => true,
            'drivers' => $driversData,
            'count' => $driversData->count()
        ]);
    }

    /**
     * تحديث تقييم السائق
     */
    public function updateDriverRating(Request $request, $driverId)
    {
        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        $driver = User::where('user_type', 'driver')->find($driverId);
        
        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'السائق غير موجود'
            ], 404);
        }

        // حساب التقييم الجديد
        $currentRating = $driver->rating;
        $currentCount = $driver->rating_count;
        $newRating = $validated['rating'];
        
        $totalRating = ($currentRating * $currentCount) + $newRating;
        $newCount = $currentCount + 1;
        $averageRating = $totalRating / $newCount;

        $driver->update([
            'rating' => round($averageRating, 2),
            'rating_count' => $newCount
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث التقييم بنجاح',
            'new_rating' => $driver->rating,
            'rating_count' => $driver->rating_count
        ]);
    }

    /**
     * تحديث حالة توفر السائق
     */
    public function updateAvailability(Request $request)
    {
        $validated = $request->validate([
            'is_available' => 'required|boolean',
        ]);

        $user = Auth::user();
        
        // التحقق من أن المستخدم سائق
        if ($user->user_type !== 'driver') {
            return response()->json([
                'status' => false,
                'message' => 'هذه الخدمة متاحة للسائقين فقط'
            ], 403);
        }

        // تحديث حالة التوفر
        $user->update([
            'is_available' => $validated['is_available']
        ]);

        return response()->json([
            'status' => true,
            'success' => true,
            'message' => 'تم تحديث حالة التوفر بنجاح',
            'is_available' => $user->is_available
        ]);
    }

    /**
     * تحديث موقع السائق مع المحافظة والمدينة
     */
    public function updateDriverLocation(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'governorate' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'current_address' => 'nullable|string|max:500',
            'location_sharing_enabled' => 'boolean'
        ]);

        $user = Auth::user();
        
        // التحقق من أن المستخدم سائق
        if ($user->user_type !== 'driver') {
            return response()->json([
                'status' => false,
                'message' => 'هذه الخدمة متاحة للسائقين فقط'
            ], 403);
        }

        // تحديث بيانات الموقع
        $user->update([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'governorate' => $validated['governorate'] ?? $user->governorate,
            'city' => $validated['city'] ?? $user->city,
            'current_address' => $validated['current_address'] ?? null,
            'location_updated_at' => now(),
            'location_sharing_enabled' => $validated['location_sharing_enabled'] ?? true
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث موقع السائق بنجاح',
            'location' => [
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
                'governorate' => $user->governorate,
                'city' => $user->city,
                'current_address' => $user->current_address,
                'location_updated_at' => $user->location_updated_at,
                'location_sharing_enabled' => $user->location_sharing_enabled
            ]
        ]);
    }

    /**
     * تحديث بروفايل مقدم خدمة السائق
     * الحقول المدعومة:
     * - صورة السائق: profile_image (مسار الصورة بعد الرفع)
     * - الاسم كامل: name
     * - الهاتف: phone
     * - المحافظة: governorate
     * - طريقة الدفع: payment_methods[]
     * - نوع التأجير: rental_type
     * - معلومات التسعير: cost_per_km, daily_driver_cost, max_km_per_day
     */
    public function updateDriverProfile(Request $request)
    {
        $user = Auth::user();

        if ($user->user_type !== 'driver') {
            return response()->json([
                'status' => false,
                'message' => 'هذه الخدمة متاحة للسائقين فقط'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20|unique:users,phone,' . $user->id,
            'governorate' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'profile_image' => 'nullable|string|max:2048',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'string|max:50',
            'rental_type' => 'nullable|string|max:50',
            'rental_options' => 'nullable|array',
            'rental_options.*' => 'string|max:50',
            'cost_per_km' => 'nullable|numeric|min:0',
            'daily_driver_cost' => 'nullable|numeric|min:0',
            'max_km_per_day' => 'nullable|integer|min:1',
        ]);

        // تحديث بيانات المستخدم الأساسية
        $userUpdates = [];
        foreach (['name','phone','governorate','city'] as $field) {
            if (array_key_exists($field, $validated)) {
                $userUpdates[$field] = $validated[$field];
            }
        }

        if (!empty($userUpdates)) {
            $user->update($userUpdates);
        }

        // التأكد من وجود سجل car_rental للسائق
        $carRental = $user->carRental;
        if (!$carRental) {
            $carRental = CarRental::create([
                'user_id' => $user->id,
                'rental_type' => $validated['rental_type'] ?? null,
            ]);
        } elseif (isset($validated['rental_type'])) {
            $carRental->update(['rental_type' => $validated['rental_type']]);
        }

        // تحديث تفاصيل السائق DriverDetail
        $driverDetail = $carRental->driverDetail;
        if (!$driverDetail) {
            $driverDetail = DriverDetail::create([
                'car_rental_id' => $carRental->id,
            ]);
        }

        $detailUpdates = [];
        if (isset($validated['profile_image'])) {
            $detailUpdates['profile_image'] = $validated['profile_image'];
            // مزامنة الصورة في جدول المستخدمين
            $user->profile_image = $validated['profile_image'];
            // اختياري: مزامنة مع avatar إذا كان يستخدم في واجهات أخرى
            $user->avatar = $validated['profile_image'];
            $user->save();
        }
        if (isset($validated['payment_methods'])) {
            $detailUpdates['payment_methods'] = $validated['payment_methods'];
        }
        if (isset($validated['rental_options'])) {
            $detailUpdates['rental_options'] = $validated['rental_options'];
        }
        if (array_key_exists('cost_per_km', $validated)) {
            $detailUpdates['cost_per_km'] = $validated['cost_per_km'];
        }
        if (array_key_exists('daily_driver_cost', $validated)) {
            $detailUpdates['daily_driver_cost'] = $validated['daily_driver_cost'];
        }
        if (array_key_exists('max_km_per_day', $validated)) {
            $detailUpdates['max_km_per_day'] = $validated['max_km_per_day'];
        }

        if (!empty($detailUpdates)) {
            $driverDetail->update($detailUpdates);
        }

        // إعادة تحميل العلاقات لضمان إرجاع بيانات محدثة
        $user->load(['carRental.driverDetail']);

        return response()->json([
            'status' => true,
            'success' => true,
            'message' => 'تم تحديث بروفايل السائق بنجاح',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'governorate' => $user->governorate,
                    'city' => $user->city,
                    'avatar' => $user->avatar ?? null,
                ],
                'car_rental' => [
                    'id' => $carRental->id,
                    'rental_type' => $carRental->rental_type,
                ],
                'driver_detail' => [
                    'profile_image' => $driverDetail->profile_image,
                    'payment_methods' => $driverDetail->payment_methods,
                    'rental_options' => $driverDetail->rental_options,
                    'cost_per_km' => $driverDetail->cost_per_km,
                    'daily_driver_cost' => $driverDetail->daily_driver_cost,
                    'max_km_per_day' => $driverDetail->max_km_per_day,
                ],
            ]
        ]);
    }
}
