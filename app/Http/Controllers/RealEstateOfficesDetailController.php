<?php

namespace App\Http\Controllers;

use App\Models\RealEstateOfficesDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RealEstateOfficesDetailController extends Controller
{
    public function show(Request $request, $id)
    {
        $officeDetail = RealEstateOfficesDetail::with('realEstate')->findOrFail($id);

        $user = $request->user();
        if (!$officeDetail->realEstate || $officeDetail->realEstate->user_id !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح بعرض تفاصيل هذا المكتب'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'data' => $officeDetail
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $officeDetail = RealEstateOfficesDetail::with('realEstate')->findOrFail($id);

        if (!$officeDetail->realEstate || $officeDetail->realEstate->user_id !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح بتعديل هذا المكتب'
            ], 403);
        }

        $request->validate([
            'office_name' => 'nullable|string|max:255',
            'office_address' => 'nullable|string|max:255',
            'office_phone' => 'nullable|string|max:255',
            'tax_enabled' => 'nullable|boolean',

            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'owner_id_front_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'owner_id_back_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'office_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'commercial_register_front_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'commercial_register_back_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',

            'remove_logo_image' => 'nullable|boolean',
            'remove_owner_id_front_image' => 'nullable|boolean',
            'remove_owner_id_back_image' => 'nullable|boolean',
            'remove_office_image' => 'nullable|boolean',
            'remove_commercial_register_front_image' => 'nullable|boolean',
            'remove_commercial_register_back_image' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $officeDetail->fill($request->only([
                'office_name',
                'office_address',
                'office_phone',
                'tax_enabled',
            ]));

            $this->processImageField($request, $officeDetail, 'logo_image', 'real-estate/offices/logo');
            $this->processImageField($request, $officeDetail, 'owner_id_front_image', 'real-estate/offices/owner-id/front');
            $this->processImageField($request, $officeDetail, 'owner_id_back_image', 'real-estate/offices/owner-id/back');
            $this->processImageField($request, $officeDetail, 'office_image', 'real-estate/offices/office');
            $this->processImageField($request, $officeDetail, 'commercial_register_front_image', 'real-estate/offices/commercial-register/front');
            $this->processImageField($request, $officeDetail, 'commercial_register_back_image', 'real-estate/offices/commercial-register/back');

            $officeDetail->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث بيانات مكتب العقارات بنجاح',
                'data' => $officeDetail->fresh()
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء التحديث',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function processImageField(Request $request, RealEstateOfficesDetail $officeDetail, string $fieldName, string $folder): void
    {
        if ($request->hasFile($fieldName)) {
            $this->deleteImage($officeDetail->$fieldName);
            $officeDetail->$fieldName = $this->storeImage($request->file($fieldName), $folder);
            return;
        }

        if ($request->boolean('remove_' . $fieldName)) {
            $this->deleteImage($officeDetail->$fieldName);
            $officeDetail->$fieldName = null;
        }
    }

    private function storeImage($image, string $path): string
    {
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
        $storedPath = $image->storeAs($path, $filename, 'public');
        return Storage::url($storedPath);
    }

    private function deleteImage(?string $imageUrl): void
    {
        if (!$imageUrl) return;
        $relative = str_replace('/storage/', '', $imageUrl);
        if (Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }
    }
}