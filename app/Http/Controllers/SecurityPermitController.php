<?php

namespace App\Http\Controllers;

use App\Models\SecurityPermit;
use App\Models\Country;
use App\Models\Nationality;
use App\Models\SecurityPermitSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SecurityPermitController extends Controller
{
    /**
     * الحصول على البيانات الأساسية للنموذج
     */
    public function getFormData()
    {
        try {
            $countries = Country::active()->ordered()->get(['id', 'name_ar', 'name_en', 'code']);
            $nationalities = Nationality::active()->ordered()->get(['id', 'name_ar', 'name_en', 'code']);
            $individualFee = SecurityPermitSetting::getSetting('individual_fee', 100);

            return response()->json([
                'status' => true,
                'data' => [
                    'countries' => $countries,
                    'nationalities' => $nationalities,
                    'individual_fee' => $individualFee,
                    'payment_methods' => [
                        ['key' => 'credit_card', 'label' => 'بطاقة ائتمان'],
                        ['key' => 'digital_wallet', 'label' => 'محفظة إلكترونية'],
                    ],
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في جلب البيانات',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * إنشاء طلب تصريح جديد
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'travel_date' => 'required|date|after:today',
                'nationality_id' => 'required|exists:nationalities,id',
                'people_count' => 'required|integer|min:1|max:20',
                'country_id' => 'required|exists:countries,id',
                'passport_image' => 'required|file|image|mimes:jpeg,png,jpg|max:5120',
                'residence_images' => 'nullable|array|max:5',
                'residence_images.*' => 'file|image|mimes:jpeg,png,jpg|max:5120',
                'payment_method' => 'required|in:credit_card,digital_wallet',
                'notes' => 'nullable|string|max:1000',
            ]);

            // رفع صورة الجواز
            $passportPath = null;
            if ($request->hasFile('passport_image')) {
                $passportPath = $request->file('passport_image')->store('security-permits/passports', 'public');
            }

            // رفع صور الإقامة
            $residenceImages = [];
            if ($request->hasFile('residence_images')) {
                foreach ($request->file('residence_images') as $image) {
                    $residenceImages[] = $image->store('security-permits/residence', 'public');
                }
            }

            // الحصول على بيانات الجنسية والدولة للحفظ في الحقول النصية
            $nationality = Nationality::find($validated['nationality_id']);
            $country = Country::find($validated['country_id']);

            // حساب الرسوم
            $individualFee = SecurityPermitSetting::getSetting('individual_fee', 100);
            $totalAmount = $individualFee * $validated['people_count'];

            $permit = SecurityPermit::create([
                'user_id' => Auth::id(),
                'travel_date' => $validated['travel_date'],
                'nationality' => $nationality->name_ar, // حفظ النص للتوافق مع النظام القديم
                'nationality_id' => $validated['nationality_id'],
                'people_count' => $validated['people_count'],
                'coming_from' => $country->name_ar, // حفظ النص للتوافق مع النظام القديم
                'country_id' => $validated['country_id'],
                'passport_image' => $passportPath,
                'residence_images' => $residenceImages,
                'payment_method' => $validated['payment_method'],
                'individual_fee' => $individualFee,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'],
                'status' => 'new',
                'payment_status' => 'pending',
            ]);

            // تحميل العلاقات
            $permit->load(['user', 'country', 'nationality']);

            // إرسال إشعار للإدارة
            $permit->notifyAdminOfNewRequest();

            return response()->json([
                'status' => true,
                'message' => 'تم تقديم طلب التصريح الأمني بنجاح',
                'permit' => $permit
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في تقديم الطلب',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * عرض طلبات المستخدم الحالي
     */
    public function myPermits(Request $request)
    {
        try {
            $query = SecurityPermit::with(['country', 'nationality'])
                ->forUser(Auth::id())
                ->latest();

            // فلترة حسب الحالة
            if ($request->has('status') && $request->status !== 'all') {
                $query->status($request->status);
            }

            // فلترة حسب حالة الدفع
            if ($request->has('payment_status') && $request->payment_status !== 'all') {
                $query->where('payment_status', $request->payment_status);
            }

            $permits = $query->paginate(10);

            return response()->json([
                'status' => true,
                'permits' => $permits
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
        try {
            $permit = SecurityPermit::with(['user', 'country', 'nationality'])
                ->forUser(Auth::id())
                ->findOrFail($id);

            return response()->json([
                'status' => true,
                'permit' => $permit
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'الطلب غير موجود أو غير مسموح لك بالوصول إليه'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في جلب تفاصيل الطلب',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * تحديث طريقة الدفع (قبل الموافقة)
     */
    public function updatePaymentMethod(Request $request, $id)
    {
        try {
            $permit = SecurityPermit::forUser(Auth::id())->findOrFail($id);

            // التحقق من إمكانية التعديل
            if (!in_array($permit->status, ['new', 'pending'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'لا يمكن تعديل طريقة الدفع بعد معالجة الطلب'
                ], 400);
            }

            $validated = $request->validate([
                'payment_method' => 'required|in:credit_card,digital_wallet',
            ]);

            $permit->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث طريقة الدفع بنجاح',
                'permit' => $permit
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في تحديث طريقة الدفع',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * إلغاء طلب (فقط للطلبات الجديدة)
     */
    public function cancel($id)
    {
        try {
            $permit = SecurityPermit::forUser(Auth::id())->findOrFail($id);

            if ($permit->status !== 'new') {
                return response()->json([
                    'status' => false,
                    'message' => 'لا يمكن إلغاء الطلب بعد بدء المراجعة'
                ], 400);
            }

            $permit->delete();

            return response()->json([
                'status' => true,
                'message' => 'تم إلغاء الطلب بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في إلغاء الطلب',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
