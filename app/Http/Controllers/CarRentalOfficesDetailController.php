<?php

namespace App\Http\Controllers;

use App\Models\CarRentalOfficesDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CarRentalOfficesDetailController extends Controller
{
    public function updateAvailability(Request $request, $id)
    {
        $request->validate([
            'is_available_for_delivery' => 'boolean',
            'is_available_for_rent' => 'boolean',
        ]);
        $officeDetail = CarRentalOfficesDetail::findOrFail($id);

        $this->authorizeOwnership($officeDetail);

        $officeDetail->update($request->only(['is_available_for_delivery', 'is_available_for_rent']));

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث توافر الخدمة بنجاح',
            'data' => $officeDetail
        ]);
    }

    // عرض تفاصيل المكتب بما في ذلك روابط المستندات
    public function show(Request $request, $id)
    {
        $officeDetail = CarRentalOfficesDetail::with('carRental')->findOrFail($id);
        $this->authorizeOwnership($officeDetail);

        return response()->json([
            'status' => true,
            'data' => $officeDetail,
        ], 200);
    }

    // تحديث روابط المستندات جزئيًا عبر JSON
    public function updateDocuments(Request $request, $id)
    {
        $allowedFields = [
            'logo_image',
            'owner_id_front_image',
            'owner_id_back_image',
            'license_front_image',
            'license_back_image',
            'commercial_register_front_image',
            'commercial_register_back_image',
            'vat_front_image',
            'vat_back_image',
            'includes_vat',
        ];

        $officeDetail = CarRentalOfficesDetail::with('carRental')->findOrFail($id);
        $this->authorizeOwnership($officeDetail);

        $rules = [];
        foreach ($allowedFields as $field) {
            if ($field === 'includes_vat') {
                $rules[$field] = 'nullable|boolean';
            } else {
                // نقبل روابط كاملة أو null للحذف
                $rules[$field] = 'nullable|string';
            }
        }
        $validated = $request->validate($rules);

        // إذا وصل null لأي حقل، نحذف القيمة المخزنة وربما الملف إذا كان محليًا
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $validated)) {
                $value = $validated[$field];
                if ($value === null && $officeDetail->{$field}) {
                    $this->tryDeleteStoredImage($officeDetail->{$field});
                }
            }
        }

        $officeDetail->fill($validated);
        $officeDetail->save();

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث المستندات بنجاح',
            'data' => $officeDetail,
        ], 200);
    }

    // رفع ملف واحد بنوع محدد ويُرجع رابط التخزين
    public function uploadDocument(Request $request, $id)
    {
        $allowedTypes = [
            'logo_image',
            'owner_id_front_image',
            'owner_id_back_image',
            'license_front_image',
            'license_back_image',
            'commercial_register_front_image',
            'commercial_register_back_image',
            'vat_front_image',
            'vat_back_image',
        ];

        $officeDetail = CarRentalOfficesDetail::with('carRental')->findOrFail($id);
        $this->authorizeOwnership($officeDetail);

        $request->validate([
            'type' => 'required|string|in:' . implode(',', $allowedTypes),
            'file' => 'required|file|mimes:jpeg,png,webp,pdf|max:5120',
        ]);

        $type = $request->input('type');
        $file = $request->file('file');

        $dir = 'car_rental/documents';
        $path = $file->store($dir, 'public');
        $url = asset('storage/' . $path);

        return response()->json([
            'status' => true,
            'type' => $type,
            'url' => $url,
        ], 200);
    }

    // حذف مستند بحسب النوع (ويُضبط الحقل إلى null)
    public function deleteDocument(Request $request, $id, $type)
    {
        $allowedTypes = [
            'logo_image',
            'owner_id_front_image',
            'owner_id_back_image',
            'license_front_image',
            'license_back_image',
            'commercial_register_front_image',
            'commercial_register_back_image',
            'vat_front_image',
            'vat_back_image',
        ];

        if (!in_array($type, $allowedTypes, true)) {
            return response()->json([
                'status' => false,
                'message' => 'نوع المستند غير مدعوم',
                'errors' => ['type' => ['نوع المستند غير مدعوم']],
            ], 422);
        }

        $officeDetail = CarRentalOfficesDetail::with('carRental')->findOrFail($id);
        $this->authorizeOwnership($officeDetail);

        $current = $officeDetail->{$type};
        if ($current) {
            $this->tryDeleteStoredImage($current);
        }
        $officeDetail->{$type} = null;
        $officeDetail->save();

        return response()->json([
            'status' => true,
            'message' => 'تم حذف المستند بنجاح',
            'data' => $officeDetail,
        ], 200);
    }

    private function authorizeOwnership(CarRentalOfficesDetail $officeDetail): void
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'غير مصرح');
        }
        $officeDetail->loadMissing('carRental');
        if (!$officeDetail->carRental || $officeDetail->carRental->user_id !== $user->id) {
            abort(403, 'لا تملك صلاحية الوصول لهذه البيانات');
        }
    }

    private function tryDeleteStoredImage(?string $urlOrPath): void
    {
        if (!$urlOrPath) return;
        // إذا كان رابطًا عامًا مثل /storage/xxx، سنحوّله لمسار نسبي على public disk ونحذف الملف
        $publicPrefix = asset('storage/');
        $prefix2 = url('/storage/');
        $relative = null;
        if (str_starts_with($urlOrPath, $publicPrefix)) {
            $relative = substr($urlOrPath, strlen($publicPrefix));
        } elseif (str_starts_with($urlOrPath, $prefix2)) {
            $relative = substr($urlOrPath, strlen($prefix2));
        } elseif (str_starts_with($urlOrPath, '/storage/')) {
            $relative = substr($urlOrPath, strlen('/storage/'));
        }
        if ($relative) {
            Storage::disk('public')->delete($relative);
        }
    }
}
