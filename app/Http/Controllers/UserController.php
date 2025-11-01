<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Support\Notifier;

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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20|unique:users,phone',
            'governorate' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'user_type' => 'required|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'governorate' => $validated['governorate'] ?? null,
            'city' => $validated['city'] ?? null,
            'user_type' => $validated['user_type'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم إضافة المستخدم بنجاح',
            'user' => $user,
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

        $primaryCar = $driver->driverCars->first();

        return response()->json([
            'status' => true,
            'driver' => [
                'id' => $driver->id,
                'name' => $driver->name,
                'profile_image' => $driver->profile_image,
                'rating' => $driver->rating,
                'rating_count' => $driver->rating_count,
                'phone' => $driver->phone,
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
            ]
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
}
