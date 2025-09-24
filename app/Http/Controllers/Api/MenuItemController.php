<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuItemController extends Controller
{
    /**
     * عرض جميع الوجبات لقسم معين
     */
    public function index($sectionId)
    {
        $section = MenuSection::findOrFail($sectionId);
        
        // التأكد من أن القسم ينتمي للمطعم المسجل دخوله
        if (Auth::user()->id !== $section->restaurant_id) {
            return response()->json(['message' => 'غير مصرح لك بالوصول لهذا القسم'], 403);
        }

        $items = MenuItem::where('section_id', $sectionId)->get();
        
        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * إضافة وجبة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:menu_sections,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string'
        ]);

        $section = MenuSection::findOrFail($request->section_id);
        
        // التأكد من أن القسم ينتمي للمطعم المسجل دخوله
        if (Auth::user()->id !== $section->restaurant_id) {
            return response()->json(['message' => 'غير مصرح لك بإضافة وجبات لهذا القسم'], 403);
        }

        $item = MenuItem::create([
            'restaurant_id' => Auth::user()->id,
            'section_id' => $request->section_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $request->image,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الوجبة بنجاح',
            'data' => $item
        ], 201);
    }

    /**
     * تحديث وجبة
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string'
        ]);

        $item = MenuItem::findOrFail($id);
        
        // التأكد من أن الوجبة تنتمي للمطعم المسجل دخوله
        if (Auth::user()->id !== $item->restaurant_id) {
            return response()->json(['message' => 'غير مصرح لك بتعديل هذه الوجبة'], 403);
        }

        $item->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $request->image,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الوجبة بنجاح',
            'data' => $item
        ]);
    }

    /**
     * حذف وجبة
     */
    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);
        
        // التأكد من أن الوجبة تنتمي للمطعم المسجل دخوله
        if (Auth::user()->id !== $item->restaurant_id) {
            return response()->json(['message' => 'غير مصرح لك بحذف هذه الوجبة'], 403);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الوجبة بنجاح'
        ]);
    }

    /**
     * البحث الذكي في الوجبات
     */
    public function search(Request $request)
    {
        $query = MenuItem::query();

        // البحث النصي الذكي في اسم الوجبة والوصف
        $query->when($request->input('search'), function ($q, $search) {
            return $q->where(function ($subQuery) use ($search) {
                $subQuery->where('title', 'LIKE', "%{$search}%")
                         ->orWhere('description', 'LIKE', "%{$search}%");
            });
        });

        // فلتر حسب المطعم
        $query->when($request->input('restaurant_id'), function ($q, $restaurantId) {
            return $q->where('restaurant_id', $restaurantId);
        });

        // فلتر حسب القسم
        $query->when($request->input('section_id'), function ($q, $sectionId) {
            return $q->where('section_id', $sectionId);
        });

        // فلتر حسب نطاق السعر
        $query->when($request->input('min_price'), function ($q, $minPrice) {
            return $q->where('price', '>=', $minPrice);
        });

        $query->when($request->input('max_price'), function ($q, $maxPrice) {
            return $q->where('price', '<=', $maxPrice);
        });

        // ترتيب النتائج
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'title', 'price'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        // تحميل العلاقات مسبقًا لتحسين الأداء
        $query->with(['section:id,title,restaurant_id']);

        // تحديد عدد النتائج في الصفحة
        $perPage = min($request->input('per_page', 20), 100); // حد أقصى 100 وجبة في الصفحة
        
        $items = $query->paginate($perPage)->withQueryString();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * البحث السريع في الوجبات بالأحرف الأولى
     */
    public function quickSearch(Request $request)
    {
        $search = $request->input('search');
        
        if (empty($search)) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $query = MenuItem::query();

        // البحث بالأحرف الأولى في اسم الوجبة
        $query->where('title', 'LIKE', "{$search}%");

        // فلتر حسب المطعم إذا تم تحديده
        $query->when($request->input('restaurant_id'), function ($q, $restaurantId) {
            return $q->where('restaurant_id', $restaurantId);
        });

        // تحميل العلاقات مسبقًا
        $query->with(['section:id,title,restaurant_id']);

        // ترتيب حسب الاسم وتحديد عدد النتائج
        $items = $query->orderBy('title')
                      ->limit(10) // حد أقصى 10 نتائج للبحث السريع
                      ->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }
}