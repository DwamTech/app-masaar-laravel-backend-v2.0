<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;

class AppSettingController extends Controller
{
    // جلب كل الإعدادات
    public function index()
    {
        $settings = AppSetting::all()->pluck('value', 'key');
        // حول القيم من JSON إلى Array عند العرض
        $settings = $settings->map(function($v) {
            $decoded = json_decode($v, true);
            return $decoded ?? $v;
        });
        return response()->json([
            'status' => true,
            'settings' => $settings
        ]);
    }

    // جلب إعداد مفرد حسب المفتاح
    public function show($key)
    {
        $setting = AppSetting::where('key', $key)->first();
        if (!$setting) {
            return response()->json([
                'status' => true,
                'key' => $key,
                'value' => null,
                'message' => 'لم يتم ضبط هذا الإعداد بعد'
            ]);
        }

        $value = $setting->value;
        $decoded = json_decode($value, true);
        $value = $decoded ?? $value;

        return response()->json([
            'status' => true,
            'key' => $key,
            'value' => $value
        ]);
    }

    // تحديث/إضافة إعداد
    public function update(Request $request, $key)
    {
        $value = $request->input('value');
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        AppSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        return response()->json(['status' => true, 'message' => 'تم تحديث الإعدادات']);
    }

    // get/set صريحة لسعر الكيلومتر لتكون نقطة اتصال واضحة
    public function getPricePerKm()
    {
        // دعم كلا المفتاحين: underscore و hyphen لضمان التوافق
        $setting = AppSetting::where('key', 'price_per_km')->first();
        if (!$setting) {
            $setting = AppSetting::where('key', 'price-per-km')->first();
        }
        $value = $setting ? $setting->value : null;
        $decoded = json_decode($value, true);
        $value = $decoded ?? $value;

        // إرجاع قيمة رقمية إن أمكن
        $numeric = is_null($value) ? null : (is_numeric($value) ? (float) $value : $value);

        return response()->json([
            'status' => true,
            'key' => 'price_per_km',
            'value' => $numeric
        ]);
    }

    public function setPricePerKm(Request $request)
    {
        $request->validate(['value' => 'required|numeric|min:0']);
        // خزّن بالقيمة الموحدة underscore، واحذف النسخة القديمة إن وجدت بمفتاح hyphen
        AppSetting::updateOrCreate(
            ['key' => 'price_per_km'],
            ['value' => (string) $request->input('value')]
        );
        // تنظيف مفتاح قديم إن وجد
        AppSetting::where('key', 'price-per-km')->delete();
        return response()->json([
            'status' => true,
            'message' => 'تم ضبط السعر لكل كيلومتر',
            'key' => 'price_per_km',
            'value' => (float) $request->input('value')
        ]);
    }
}
