<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\RealEstate;
use App\Http\Requests\CreatePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Http\Requests\PropertySearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * مقدم الخدمة (Service Provider) - إضافة عقار جديد
     * POST /api/properties
     */
    public function store(CreatePropertyRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            $realEstate = RealEstate::where('user_id', $user->id)->first();

            if (!$realEstate) {
                return response()->json([
                    'status' => false,
                    'message' => 'هذا المستخدم لا يمتلك حساب عقاري.'
                ], 403);
            }

            $validated = $request->validated();

            // توافقية مع مخطط قديم: حقول old_* بعد إعادة التسمية قد تكون غير قابلة للإفراغ
            // نضمن تعبئتها بقيم من الحقول الجديدة لتفادي أخطاء 1364
            if (!isset($validated['old_type']) && isset($validated['property_type'])) {
                $validated['old_type'] = $validated['property_type'];
            }
            if (!isset($validated['old_price']) && isset($validated['property_price'])) {
                $validated['old_price'] = $validated['property_price'];
            }
            
            // رفع الصورة الرئيسية
            if ($request->hasFile('main_image')) {
                $validated['main_image'] = $this->uploadImage($request->file('main_image'), 'properties/main');
            }

            // رفع صور المعرض
            $galleryUrls = [];
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $galleryUrls[] = $this->uploadImage($image, 'properties/gallery');
                }
            }
            $validated['gallery_image_urls'] = $galleryUrls;

            // إضافة البيانات التلقائية
            $validated['real_estate_id'] = $realEstate->id;
            $validated['user_id'] = $user->id;
            $validated['view_count'] = 0;

            $property = Property::create($validated);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'تم إضافة العقار بنجاح',
                'property' => $property->load(['realEstate', 'user'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء إضافة العقار',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * مقدم الخدمة (Service Provider) - تعديل بيانات عقار
     * PUT /api/properties/{id}
     */
    public function update(UpdatePropertyRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $property = Property::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $validated = $request->validated();

            // توافقية مع المخطط القديم عند التحديث أيضاً
            if (!isset($validated['old_type']) && isset($validated['property_type'])) {
                $validated['old_type'] = $validated['property_type'];
            }
            if (!isset($validated['old_price']) && isset($validated['property_price'])) {
                $validated['old_price'] = $validated['property_price'];
            }

            // رفع الصورة الرئيسية الجديدة
            if ($request->hasFile('main_image')) {
                // حذف الصورة القديمة
                if ($property->main_image) {
                    $this->deleteImage($property->main_image);
                }
                $validated['main_image'] = $this->uploadImage($request->file('main_image'), 'properties/main');
            }

            // إدارة صور المعرض
            $currentGallery = $property->gallery_image_urls ?? [];
            
            // إزالة الصور المحددة للحذف
            if ($request->has('remove_gallery_images')) {
                foreach ($request->remove_gallery_images as $imageUrl) {
                    $this->deleteImage($imageUrl);
                    $currentGallery = array_filter($currentGallery, fn($url) => $url !== $imageUrl);
                }
            }

            // إضافة صور جديدة
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $currentGallery[] = $this->uploadImage($image, 'properties/gallery');
                }
            }

            $validated['gallery_image_urls'] = array_values($currentGallery);

            $property->update($validated);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث العقار بنجاح',
                'property' => $property->fresh()->load(['realEstate', 'user'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء تحديث العقار',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * مقدم الخدمة (Service Provider) - حذف عقار
     * DELETE /api/properties/{id}
     */
    public function destroy(Request $request, $id)
    {
        try {
            $property = Property::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            // حذف الصور المرتبطة
            if ($property->main_image) {
                $this->deleteImage($property->main_image);
            }

            if ($property->gallery_image_urls) {
                foreach ($property->gallery_image_urls as $imageUrl) {
                    $this->deleteImage($imageUrl);
                }
            }

            $property->delete();

            return response()->json([
                'status' => true,
                'message' => 'تم حذف العقار بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء حذف العقار',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * المستخدم العام (Public User) - عرض قائمة العقارات
     * GET /api/properties
     */
    public function index(PropertySearchRequest $request)
    {
        $filters = $request->getSearchFilters();
        $query = Property::with(['realEstate', 'user'])
            ->where('property_status', 'available');

        // تطبيق الفلاتر
        $query = $this->applyFilters($query, $filters);

        // الترتيب
        $query = $this->applySorting($query, $filters['sort_by'] ?? 'date_desc');

        // الصفحات
        $properties = $query->paginate($filters['per_page'] ?? 20);

        return response()->json([
            'status' => true,
            'properties' => $properties->items(),
            'pagination' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'per_page' => $properties->perPage(),
                'total' => $properties->total(),
            ]
        ]);
    }

    /**
     * المستخدم العام (Public User) - بحث وفلترة العقارات
     * GET /api/properties/search
     */
    public function search(PropertySearchRequest $request)
    {
        return $this->index($request); // نفس المنطق
    }

    /**
     * المستخدم العام (Public User) - عرض العقارات المميزة
     * GET /api/properties/featured
     */
    public function featured(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        
        $properties = Property::with(['realEstate', 'user'])
            ->featured()
            ->available()
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'properties' => $properties->items(),
            'pagination' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'per_page' => $properties->perPage(),
                'total' => $properties->total(),
            ]
        ]);
    }

    /**
     * مقدم الخدمة (Service Provider) - عرض عقاراتي
     * GET /api/my/properties
     */
    public function myProperties(Request $request)
    {
        $user = $request->user();
        $perPage = $request->get('per_page', 20);

        // ابحث عن العقارات الخاصة بالمستخدم الحالي
        $query = Property::with(['realEstate', 'user'])
            ->where('user_id', $user->id)
            ->latest();

        // دعم إضافي للعقارات المربوطة بـ real_estate_id في حال وجود بيانات قديمة
        $realEstate = RealEstate::where('user_id', $user->id)->first();
        if ($realEstate) {
            $query->orWhere('real_estate_id', $realEstate->id);
        }

        $properties = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'properties' => $properties->items(),
            'pagination' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'per_page' => $properties->perPage(),
                'total' => $properties->total(),
            ]
        ]);
    }

    /**
     * المستخدم العام (Public User) - عرض تفاصيل عقار واحد
     * GET /api/properties/{id}
     */
    public function show($id)
    {
        try {
            $property = Property::with(['realEstate', 'user'])
                ->findOrFail($id);

            // زيادة عدد المشاهدات
            $property->incrementViewCount();

            return response()->json([
                'status' => true,
                'property' => $property
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'العقار غير موجود'
            ], 404);
        }
    }

    /**
     * مدير النظام (Admin) - عرض جميع العقارات
     * GET /api/admin/properties
     */
    public function adminIndex(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        
        $properties = Property::with(['realEstate', 'user'])
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'properties' => $properties->items(),
            'pagination' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'per_page' => $properties->perPage(),
                'total' => $properties->total(),
            ]
        ]);
    }

    /**
     * مدير النظام (Admin) - حذف أي عقار
     * DELETE /api/admin/properties/{id}
     */
    public function adminDestroy($id)
    {
        try {
            $property = Property::findOrFail($id);

            // حذف الصور المرتبطة
            if ($property->main_image) {
                $this->deleteImage($property->main_image);
            }

            if ($property->gallery_image_urls) {
                foreach ($property->gallery_image_urls as $imageUrl) {
                    $this->deleteImage($imageUrl);
                }
            }

            $property->delete();

            return response()->json([
                'status' => true,
                'message' => 'تم حذف العقار بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء حذف العقار',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * مدير النظام (Admin) - تمييز عقار (Featured)
     * PATCH /api/admin/properties/{id}/feature
     */
    public function toggleFeatured(Request $request, $id)
    {
        try {
            $property = Property::findOrFail($id);
            
            $validated = $request->validate([
                'is_featured' => 'required|boolean',
            ]);

            $property->is_featured = $validated['is_featured'];
            $property->save();

            return response()->json([
                'status' => true,
                'message' => $validated['is_featured'] ? 'تم تمييز العقار' : 'تم إلغاء تمييز العقار',
                'property' => $property
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء تحديث العقار',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper Methods
     */
    private function uploadImage($image, $path)
    {
        // تأكد من وجود المجلد على قرص public لتجنب أخطاء الأذونات/المسارات
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs($path, $filename, 'public');
        return Storage::url($imagePath);
    }

    private function deleteImage($imageUrl)
    {
        if ($imageUrl && Storage::disk('public')->exists(str_replace('/storage/', '', $imageUrl))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $imageUrl));
        }
    }

    private function applyFilters($query, $filters)
    {
        // البحث النصي
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('property_code', 'like', '%' . $filters['search'] . '%');
            });
        }

        // فلترة السعر
        if (!empty($filters['min_price'])) {
            $query->where('property_price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('property_price', '<=', $filters['max_price']);
        }

        // فلترة النوع
        if (!empty($filters['property_type'])) {
            $query->where('property_type', $filters['property_type']);
        }
        if (!empty($filters['ownership_type'])) {
            $query->where('ownership_type', $filters['ownership_type']);
        }
        if (!empty($filters['advertiser_type'])) {
            $query->where('advertiser_type', $filters['advertiser_type']);
        }

        // فلترة المساحة
        if (!empty($filters['min_size'])) {
            $query->where('size_in_sqm', '>=', $filters['min_size']);
        }
        if (!empty($filters['max_size'])) {
            $query->where('size_in_sqm', '<=', $filters['max_size']);
        }

        // فلترة الغرف
        if (!empty($filters['min_bedrooms'])) {
            $query->where('bedrooms', '>=', $filters['min_bedrooms']);
        }
        if (!empty($filters['max_bedrooms'])) {
            $query->where('bedrooms', '<=', $filters['max_bedrooms']);
        }

        // فلترة الموقع (البحث الجغرافي)
        if (!empty($filters['latitude']) && !empty($filters['longitude']) && !empty($filters['radius'])) {
            $lat = $filters['latitude'];
            $lng = $filters['longitude'];
            $radius = $filters['radius'];
            
            $query->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(JSON_EXTRACT(location, '$.latitude'))) 
                * cos(radians(JSON_EXTRACT(location, '$.longitude')) - radians(?)) 
                + sin(radians(?)) * sin(radians(JSON_EXTRACT(location, '$.latitude'))))) <= ?
            ", [$lat, $lng, $lat, $radius]);
        }

        // فلترة المميزات
        if (!empty($filters['features'])) {
            foreach ($filters['features'] as $feature) {
                $query->whereJsonContains('features', $feature);
            }
        }

        // فلترة الخدمات
        if (!empty($filters['amenities'])) {
            foreach ($filters['amenities'] as $amenity) {
                $query->whereJsonContains('amenities', $amenity);
            }
        }

        // العقارات المميزة فقط
        if (!empty($filters['is_featured'])) {
            $query->featured();
        }

        // العقارات التي تحتوي على صور فقط
        if (!empty($filters['with_images_only'])) {
            $query->whereNotNull('main_image');
        }

        return $query;
    }

    private function applySorting($query, $sortBy)
    {
        switch ($sortBy) {
            case 'price_asc':
                return $query->orderBy('property_price', 'asc');
            case 'price_desc':
                return $query->orderBy('property_price', 'desc');
            case 'size_asc':
                return $query->orderBy('size_in_sqm', 'asc');
            case 'size_desc':
                return $query->orderBy('size_in_sqm', 'desc');
            case 'date_asc':
                return $query->orderBy('created_at', 'asc');
            case 'views_desc':
                return $query->orderBy('view_count', 'desc');
            case 'date_desc':
            default:
                return $query->orderBy('created_at', 'desc');
        }
    }
}
