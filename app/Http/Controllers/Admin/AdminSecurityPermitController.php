<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityPermit;
use App\Models\Country;
use App\Models\Nationality;
use App\Models\SecurityPermitSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminSecurityPermitController extends Controller
{
    /**
     * عرض جميع الطلبات مع الفلترة والبحث
     */
    public function index(Request $request)
    {
        // التحقق من صلاحية الإدارة
        if (Auth::user()->user_type !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالوصول'
            ], 403);
        }

        try {
            $query = SecurityPermit::with(['user', 'country', 'nationality'])
                ->latest();

            // فلترة حسب الحالة
            if ($request->has('status') && $request->status !== 'all') {
                $query->status($request->status);
            }

            // فلترة حسب حالة الدفع
            if ($request->has('payment_status') && $request->payment_status !== 'all') {
                $query->where('payment_status', $request->payment_status);
            }

            // البحث في اسم المستخدم
            if ($request->has('search') && !empty($request->search)) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            }

            // فلترة حسب التاريخ
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $permits = $query->paginate(15);

            return response()->json([
                'status' => true,
                'permits' => $permits,
                'statistics' => $this->getStatistics()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في جلب الطلبات',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * عرض تفاصيل طلب محدد
     */
    public function show($id)
    {
        // التحقق من صلاحية الإدارة
        if (Auth::user()->user_type !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالوصول'
            ], 403);
        }

        try {
            $permit = SecurityPermit::with(['user', 'country', 'nationality'])
                ->findOrFail($id);

            return response()->json([
                'status' => true,
                'permit' => $permit
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'الطلب غير موجود'
            ], 404);
        }
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateStatus(Request $request, $id)
    {
        // التحقق من صلاحية الإدارة مع التعامل مع المستخدم غير المصادق
        if (!Auth::check() || Auth::user()->user_type !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالوصول'
            ], 403);
        }

        try {
            $permit = SecurityPermit::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:new,pending,approved,rejected,expired',
                'admin_notes' => 'nullable|string|max:1000',
            ]);

            // تمرير ملاحظة الأدمن كـ null إذا لم تُرسل لتفادي Undefined array key
            $permit->updateStatus($validated['status'], $validated['admin_notes'] ?? null);

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث حالة الطلب بنجاح',
                'permit' => $permit->fresh(['user', 'country', 'nationality'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في تحديث الحالة',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * تحديث حالة الدفع
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        // التحقق من صلاحية الإدارة
        if (Auth::user()->user_type !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالوصول'
            ], 403);
        }

        try {
            $permit = SecurityPermit::findOrFail($id);

            $validated = $request->validate([
                'payment_status' => 'required|in:pending,paid,failed,refunded',
                'payment_reference' => 'nullable|string',
            ]);

            $permit->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث حالة الدفع بنجاح',
                'permit' => $permit
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في تحديث حالة الدفع',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * حذف طلب
     */
    public function destroy($id)
    {
        // التحقق من صلاحية الإدارة
        if (Auth::user()->user_type !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالوصول'
            ], 403);
        }

        try {
            $permit = SecurityPermit::findOrFail($id);
            $permit->delete();

            return response()->json([
                'status' => true,
                'message' => 'تم حذف الطلب بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في حذف الطلب',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * إحصائيات الطلبات
     */
    public function getStatistics()
    {
        return [
            'total' => SecurityPermit::count(),
            'new' => SecurityPermit::status('new')->count(),
            'pending' => SecurityPermit::status('pending')->count(),
            'approved' => SecurityPermit::status('approved')->count(),
            'rejected' => SecurityPermit::status('rejected')->count(),
            'expired' => SecurityPermit::status('expired')->count(),
            'total_revenue' => SecurityPermit::where('payment_status', 'paid')->sum('total_amount'),
            'pending_payments' => SecurityPermit::where('payment_status', 'pending')->sum('total_amount'),
        ];
    }

    /**
     * إدارة الإعدادات
     */
    public function getSettings()
    {
        // التحقق من صلاحية الإدارة
        if (Auth::user()->user_type !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالوصول'
            ], 403);
        }

        try {
            $settings = SecurityPermitSetting::editable()->get()->groupBy('group');
            
            return response()->json([
                'status' => true,
                'settings' => $settings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في جلب الإعدادات',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * تحديث الإعدادات
     */
    public function updateSettings(Request $request)
    {
        // التحقق من صلاحية الإدارة
        if (Auth::user()->user_type !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالوصول'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'individual_fee' => 'required|numeric|min:0',
            ]);

            SecurityPermitSetting::setSetting('individual_fee', $validated['individual_fee'], 'number');

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث الإعدادات بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في تحديث الإعدادات',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * إدارة الدول
     */
    public function getCountries()
    {
        // التحقق من صلاحية الإدارة
        if (Auth::user()->user_type !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالوصول'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'countries' => Country::ordered()->get()
        ]);
    }

    public function updateCountry(Request $request, $id)
    {
        // التحقق من صلاحية الإدارة
        if (Auth::user()->user_type !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالوصول'
            ], 403);
        }

        try {
            $country = Country::findOrFail($id);
            
            $validated = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'code' => 'required|string|max:3|unique:countries,code,' . $id,
                'is_active' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);

            $country->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث الدولة بنجاح',
                'country' => $country
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في تحديث الدولة',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * إدارة الجنسيات
     */
    public function getNationalities()
    {
        // التحقق من صلاحية الإدارة
        if (Auth::user()->user_type !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالوصول'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'nationalities' => Nationality::ordered()->get()
        ]);
    }

    public function updateNationality(Request $request, $id)
    {
        // التحقق من صلاحية الإدارة
        if (Auth::user()->user_type !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالوصول'
            ], 403);
        }

        try {
            $nationality = Nationality::findOrFail($id);
            
            $validated = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'code' => 'required|string|max:3|unique:nationalities,code,' . $id,
                'is_active' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);

            $nationality->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث الجنسية بنجاح',
                'nationality' => $nationality
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في تحديث الجنسية',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}