<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Http\Request;

class PublicPropertyController extends Controller
{
    /**
     * Endpoint ذكي وشامل للبحث عن كل العقارات مع فلاتر اختيارية.
     */
    public function index(Request $request)
    {
        try {
            // نبدأ ببناء الاستعلام الأساسي.
            $query = Property::query();

            // **شرط أمني مهم:** جلب العقارات فقط من المستخدمين الموافق عليهم (is_approved).
            $query->whereHas('user', function ($q) {
                $q->where('is_approved', 1);
            });
            
            // -- الفلترة الديناميكية الذكية --

            // فلتر: هل العقار من "الأفضل" (the_best)؟
            $query->when($request->boolean('the_best'), function ($q) {
                return $q->where('the_best', 1);
            });

            // فلتر حسب نوع العقار
            $query->when($request->input('type'), function ($q, $type) {
                return $q->where('type', $type);
            });

            // **الأهم لتحسين الأداء:** تحميل العلاقات مسبقًا
            $query->with(['user:id,name,phone,governorate,city', 'realEstate']);
            
            // جلب النتائج مع تقسيمها إلى صفحات وترتيبها بالأحدث
            $properties = $query->latest()->paginate(15)->withQueryString();

            // إرجاع البيانات بعد تنسيقها باستخدام الـ Resource
            return PropertyResource::collection($properties);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في جلب العقارات',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * البحث الذكي في العقارات مع فلاتر متقدمة
     */
    public function search(Request $request)
    {
        try {
            $query = Property::query();

        // **شرط أمني مهم:** جلب العقارات فقط من المستخدمين الموافق عليهم
        $query->whereHas('user', function ($q) {
            $q->where('is_approved', 1);
        });

        // البحث النصي الذكي في العنوان والوصف
        $query->when($request->input('search'), function ($q, $search) {
            return $q->where(function ($subQuery) use ($search) {
                $subQuery->where('address', 'LIKE', "%{$search}%")
                         ->orWhere('description', 'LIKE', "%{$search}%")
                         ->orWhere('type', 'LIKE', "%{$search}%");
            });
        });

        // فلتر حسب نوع العقار
        $query->when($request->input('type'), function ($q, $type) {
            return $q->where('type', $type);
        });

        // فلتر حسب المحافظة
        $query->when($request->input('governorate'), function ($q, $governorate) {
            return $q->whereHas('user', function ($userQuery) use ($governorate) {
                $userQuery->where('governorate', $governorate);
            });
        });

        // فلتر حسب المدينة
        $query->when($request->input('city'), function ($q, $city) {
            return $q->whereHas('user', function ($userQuery) use ($city) {
                $userQuery->where('city', $city);
            });
        });

        // فلتر حسب نطاق السعر
        $query->when($request->input('min_price'), function ($q, $minPrice) {
            return $q->where('price', '>=', $minPrice);
        });

        $query->when($request->input('max_price'), function ($q, $maxPrice) {
            return $q->where('price', '<=', $maxPrice);
        });

        // فلتر حسب عدد الغرف
        $query->when($request->input('bedrooms'), function ($q, $bedrooms) {
            return $q->where('bedrooms', '>=', $bedrooms);
        });

        // فلتر حسب عدد الحمامات
        $query->when($request->input('bathrooms'), function ($q, $bathrooms) {
            return $q->where('bathrooms', '>=', $bathrooms);
        });

        // فلتر حسب المساحة
        $query->when($request->input('min_area'), function ($q, $minArea) {
            return $q->where('area', '>=', $minArea);
        });

        $query->when($request->input('max_area'), function ($q, $maxArea) {
            return $q->where('area', '<=', $maxArea);
        });

        // فلتر حسب طريقة الدفع
        $query->when($request->input('payment_method'), function ($q, $paymentMethod) {
            return $q->where('payment_method', $paymentMethod);
        });

        // فلتر حسب الإطلالة
        $query->when($request->input('view'), function ($q, $view) {
            return $q->where('view', 'LIKE', "%{$view}%");
        });

        // فلتر العقارات الجاهزة فقط
        $query->when($request->boolean('is_ready'), function ($q) {
            return $q->where('is_ready', 1);
        });

        // فلتر العقارات المميزة
        $query->when($request->boolean('the_best'), function ($q) {
            return $q->where('the_best', 1);
        });

        // ترتيب النتائج
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'price', 'area', 'bedrooms', 'bathrooms'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        // تحميل العلاقات مسبقًا لتحسين الأداء
        $query->with(['user:id,name,phone,governorate,city', 'realEstate']);

        // تحديد عدد النتائج في الصفحة
        $perPage = min($request->input('per_page', 15), 50); // حد أقصى 50 عقار في الصفحة
        
        $properties = $query->paginate($perPage)->withQueryString();

        return PropertyResource::collection($properties);
        
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في البحث عن العقارات',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}