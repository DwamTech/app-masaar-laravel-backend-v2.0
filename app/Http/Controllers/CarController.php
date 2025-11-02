<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarRental;
use Illuminate\Http\Request;

class CarController extends Controller
{
    // [سائق] إضافة عربية جديدة مرتبطة بالسائق الحالي تلقائياً
    public function storeForDriver(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->user_type !== 'driver') {
            return response()->json([
                'status' => false,
                'message' => 'هذه العملية متاحة للسائقين فقط'
            ], 403);
        }

        // الحصول على سجل car_rental الخاص بالسائق أو إنشاؤه إن لم يوجد
        $carRental = $user->carRental;
        if (!$carRental) {
            $carRental = $user->carRental()->create(['rental_type' => 'driver']);
            $carRental->driverDetail()->create([]);
        }

        $validated = $request->validate([
            'license_front_image' => 'required|string',
            'license_back_image' => 'required|string',
            'car_license_front' => 'required|string',
            'car_license_back' => 'required|string',
            'car_image_front' => 'required|string',
            'car_image_back' => 'required|string',
            'car_type' => 'required|string',
            'car_model' => 'required|string',
            'car_color' => 'nullable|string',
            'car_plate_number' => 'required|string',
        ]);

        $payload = array_merge($validated, [
            'car_rental_id' => $carRental->id,
            'owner_type' => 'driver',
            'is_reviewed' => false,
        ]);

        $car = Car::create($payload);

        return response()->json([
            'status' => true,
            'message' => 'تم تقديم العربية للمراجعة بنجاح. في انتظار اعتماد الإدارة.',
            'car' => $car
        ], 201);
    }

    // [أدمن] عرض جميع العربيات مع العلاقات المطلوبة
    public function adminIndex(Request $request)
    {
        $query = Car::with(['carRental.user', 'carRental.officeDetail', 'carRental.driverDetail']);

        if ($request->has('is_reviewed')) {
            $query->where('is_reviewed', $request->boolean('is_reviewed'));
        }

        if ($request->has('owner_type')) {
            $query->where('owner_type', $request->input('owner_type'));
        }

        $cars = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => true,
            'cars' => $cars,
            'count' => $cars->count(),
        ]);
    }

    // استعراض جميع العربيات لمقدم خدمة معيّن
    public function index($carRentalId)
    {
         $cars = Car::where('car_rental_id', $carRentalId)
        ->orderBy('id', 'desc') // ترتيب تنازلي من الأحدث للأقدم
        ->get();
    return response()->json([
        'status' => true,
        'cars' => $cars
    ]);
    }

    // إضافة عربية جديدة
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_rental_id' => 'required|exists:car_rentals,id',
            'owner_type' => 'required|in:office,driver',
            'license_front_image' => 'required|string',
            'license_back_image' => 'required|string',
            'car_license_front' => 'required|string',
            'car_license_back' => 'required|string',
            'car_image_front' => 'required|string',
            'car_image_back' => 'required|string',
            'car_type' => 'required|string',
            'car_model' => 'required|string',
            'car_color' => 'nullable|string',
            'car_plate_number' => 'required|string',
        ]);

        // إجبار حالة المراجعة إلى false عند الإنشاء بغض النظر عن المدخل
        $payload = array_merge($validated, [
            'is_reviewed' => false,
        ]);

        $car = Car::create($payload);

        return response()->json([
            'status' => true,
            'message' => 'تم إضافة العربية بنجاح',
            'car' => $car
        ]);
    }

    // تحديث عربية
    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        // منع تعديل حالة المراجعة من طرف مزوّد الخدمة
        $updateData = $request->except(['is_reviewed']);

        $car->update($updateData);

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث بيانات العربية بنجاح',
            'car' => $car
        ]);
    }

    // حذف عربية
    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $car->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم حذف العربية بنجاح'
        ]);
    }

    // إرجاع تفاصيل عربية واحدة حسب الـ id
    public function show($id)
    {
        $car = Car::find($id);
        if (!$car) {
            return response()->json([
                'status' => false,
                'message' => 'العربية غير موجودة'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'car' => $car
        ]);
    }

    // جلب جميع موديلات السيارات المتاحة على النظام
    public function models()
    {
        $models = Car::select('car_model')
            ->distinct()
            ->orderBy('car_model')
            ->pluck('car_model');

        return response()->json([
            'status' => true,
            'models' => $models
        ]);
    }

    // ===================== مسارات عامة لعرض العربيات المعتمدة =====================
    // عرض جميع العربيات المعتمدة للجمهور
    public function publicIndex(Request $request)
    {
        $cars = Car::where('is_reviewed', 1)
            ->with(['carRental.officeDetail', 'carRental.driverDetail'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'cars' => $cars,
            'count' => $cars->count(),
        ]);
    }

    // [أدمن] عرض عربيات السائقين فقط
    public function adminDriverCars(Request $request)
    {
        $query = Car::where('owner_type', 'driver')
            ->with(['carRental.user', 'carRental.driverDetail']);

        if ($request->has('is_reviewed')) {
            $query->where('is_reviewed', $request->boolean('is_reviewed'));
        }

        $cars = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => true,
            'cars' => $cars,
            'count' => $cars->count(),
        ]);
    }

    // [أدمن] عرض عربيات مكاتب التأجير فقط
    public function adminOfficeCars(Request $request)
    {
        $query = Car::where('owner_type', 'office')
            ->with(['carRental.user', 'carRental.officeDetail']);

        if ($request->has('is_reviewed')) {
            $query->where('is_reviewed', $request->boolean('is_reviewed'));
        }

        $cars = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => true,
            'cars' => $cars,
            'count' => $cars->count(),
        ]);
    }

    // عرض تفاصيل عربية واحدة للجمهور (فقط إذا كانت معتمدة)
    public function publicShow($id)
    {
        $car = Car::with(['carRental.officeDetail', 'carRental.driverDetail'])
            ->where('id', $id)
            ->where('is_reviewed', 1)
            ->first();

        if (!$car) {
            return response()->json([
                'status' => false,
                'message' => 'العربية غير موجودة أو لم يتم اعتمادها بعد',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'car' => $car,
        ]);
    }

    // ===================== مسار أدمن لاعتماد/إلغاء اعتماد العربية =====================
    public function review(Request $request, $id)
    {
        $request->validate([
            'is_reviewed' => 'sometimes|boolean',
        ]);

        $car = Car::findOrFail($id);
        $newState = $request->has('is_reviewed') ? (bool) $request->boolean('is_reviewed') : true;

        $car->is_reviewed = $newState;
        $car->save();

        return response()->json([
            'status' => true,
            'message' => $newState ? 'تم اعتماد العربية بنجاح' : 'تم إعادة العربية إلى حالة تحت المراجعة',
            'car' => $car,
        ]);
    }
}
