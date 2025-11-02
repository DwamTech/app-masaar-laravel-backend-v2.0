<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Support\Notifier;

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

        // خزن الصور القادمة كـ Base64 إلى ملفات فعلية، وأرجع روابط عامة
        $imageFolder = 'uploads/images/cars';
        $validated['license_front_image'] = $this->storeImageString($validated['license_front_image'], $imageFolder);
        $validated['license_back_image']  = $this->storeImageString($validated['license_back_image'],  $imageFolder);
        $validated['car_license_front']   = $this->storeImageString($validated['car_license_front'],   $imageFolder);
        $validated['car_license_back']    = $this->storeImageString($validated['car_license_back'],    $imageFolder);
        $validated['car_image_front']     = $this->storeImageString($validated['car_image_front'],     $imageFolder);
        $validated['car_image_back']      = $this->storeImageString($validated['car_image_back'],      $imageFolder);

        // تحقق من نجاح تحويل الصور، في حالة فشل أي صورة نرجع خطأ واضح
        foreach (['license_front_image','license_back_image','car_license_front','car_license_back','car_image_front','car_image_back'] as $key) {
            if (!$validated[$key]) {
                return response()->json([
                    'status' => false,
                    'message' => 'صيغة الصورة غير صحيحة أو فشلت عملية التحويل: ' . $key,
                ], 422);
            }
        }

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

    /**
     * حفظ صورة قادمة كسلسلة نصية (رابط أو data URI Base64) وإرجاع رابط عام
     */
    private function storeImageString(?string $value, string $folder): ?string
    {
        if (!$value) return null;

        // لو القيمة بالفعل رابط http(s) أو مسار /storage فارجعها كما هي
        if (preg_match('#^https?://#', $value) || str_starts_with($value, '/storage/')) {
            return $value;
        }

        // لو القيمة بصيغة data URI
        if (preg_match('#^data:image/(png|jpg|jpeg|gif|webp);base64,#i', $value, $m)) {
            $ext = strtolower($m[1]);
            $base64 = substr($value, strpos($value, ',') + 1);
            $data = base64_decode($base64);
            if ($data === false) return null;

            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }

            $filename = Str::uuid()->toString() . '.' . ($ext === 'jpg' ? 'jpg' : ($ext === 'jpeg' ? 'jpeg' : ($ext === 'png' ? 'png' : $ext)));
            $path = $folder . '/' . $filename;
            Storage::disk('public')->put($path, $data);
            return Storage::url($path);
        }

        // أي نص آخر نعتبره مسار نسبي ونحاول إرجاعه كما هو
        return $value;
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

        // إرسال إشعار للسائق عند اعتماد العربية من قبل الأدمن
        if ($newState === true && $car->owner_type === 'driver') {
            try {
                $driver = $car->carRental->user; // مستخدم السائق المرتبط بمقدم خدمة السيارة
                if ($driver) {
                    $title = 'تم قبول عربيتك';
                    $msg   = sprintf(
                        'تم اعتماد عربيتك من قبل الإدارة: النوع %s، الموديل %s، رقم اللوحة %s. أصبحت الآن متاحة للخدمة.',
                        (string)($car->car_type ?? 'غير محدد'),
                        (string)($car->car_model ?? 'غير محدد'),
                        (string)($car->car_plate_number ?? 'غير محدد')
                    );
                    $data  = [
                        'type' => 'driver_car_approved',
                        'car_id' => (string)$car->id,
                        'owner_type' => $car->owner_type,
                        'is_reviewed' => '1',
                    ];
                    Notifier::send($driver, 'driver_car_approved', $title, $msg, $data, null);
                }
            } catch (\Throwable $e) {
                // عدم منع الاستجابة بسبب فشل الإشعار؛ نسجّل الخطأ فقط
                \Log::warning('Failed to send driver car approved notification', [
                    'car_id' => $car->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => $newState ? 'تم اعتماد العربية بنجاح' : 'تم إعادة العربية إلى حالة تحت المراجعة',
            'car' => $car,
        ]);
    }
}
