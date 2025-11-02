# واجهة تحديث بروفايل مقدم خدمة السائق

## المسار
- `POST /api/driver/update-profile`

## التوثيق
- يتطلب `auth:sanctum`.
- مخصص للمستخدمين من نوع `driver` فقط.

## الحقول المدعومة في الطلب
- `profile_image` (اختياري): مسار الصورة بعد الرفع.
- `name` (اختياري): الاسم الكامل.
- `phone` (اختياري): رقم الهاتف؛ يجب أن يكون فريدًا.
- `governorate` (اختياري): المحافظة.
- `city` (اختياري): المدينة.
- `payment_methods` (اختياري): مصفوفة طرق الدفع، مثل `cash`, `visa`, `wallet`.
- `rental_type` (اختياري): نوع التأجير، مثل `daily`, `per_km`.
- `rental_options` (اختياري): خيارات التأجير الإضافية كمصفوفة.
- `cost_per_km` (اختياري): التكلفة لكل كيلومتر.
- `daily_driver_cost` (اختياري): تكلفة السائق اليومية.
- `max_km_per_day` (اختياري): الحد الأقصى للكيلومترات يوميًا.

## مثال طلب
```json
{
  "profile_image": "uploads/drivers/123.jpg",
  "name": "أحمد محمد علي",
  "phone": "+201234567890",
  "governorate": "القاهرة",
  "payment_methods": ["cash", "visa"],
  "rental_type": "daily",
  "cost_per_km": 3.5,
  "daily_driver_cost": 250,
  "max_km_per_day": 200
}
```

## استجابة ناجحة
```json
{
  "status": true,
  "success": true,
  "message": "تم تحديث بروفايل السائق بنجاح",
  "data": {
    "user": {
      "id": 5,
      "name": "أحمد محمد علي",
      "phone": "+201234567890",
      "governorate": "القاهرة",
      "city": null,
      "avatar": "uploads/drivers/123.jpg"
    },
    "car_rental": {
      "id": 8,
      "rental_type": "daily"
    },
    "driver_detail": {
      "profile_image": "uploads/drivers/123.jpg",
      "payment_methods": ["cash", "visa"],
      "rental_options": [],
      "cost_per_km": 3.5,
      "daily_driver_cost": 250,
      "max_km_per_day": 200
    }
  }
}
```

## ملاحظات
- في حال عدم وجود سجل `car_rental` للسائق سيتم إنشاؤه تلقائيًا.
- يتم مزامنة `profile_image` مع `avatar` في جدول المستخدم لضمان عرض الصورة في كل الواجهات.