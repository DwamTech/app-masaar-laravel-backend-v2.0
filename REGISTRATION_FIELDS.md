# دليل حقول التسجيل حسب نوع المستخدم

هذا الدليل يحدد حقول تسجيل الحساب الجديد لكل نوع مستخدم، ويبين نوع القيمة المتوقعة لكل حقل وما إذا كان Required أم Optional. الهدف هو تمكين مطوّر Flutter من تطبيق التحقق المناسب لكل نموذج.

> ملاحظة: كل الصور تُرسل كـ `string` تمثل مسارًا مخزّنًا/رابطًا (أو تُرفع عبر `multipart/form-data` بنفس أسماء الحقول)، وحقول JSON تُرسل كمصفوفات.

---

## الحقول العامة المشتركة (جدول users)
- `name` (string) — Required — الاسم الكامل، أقصى 255 حرف.
- `email` (string: email) — Required — فريد في جدول `users`.
- `password` (string) — Required — طول أدنى 6.
- `phone` (string) — Required — أقصى 20 حرف، فريد في جدول `users`.
- `governorate` (string) — Optional — اسم المحافظة.
- `user_type` (enum) — Required — أحد القيم: 
  - `normal`, `real_estate_office`, `real_estate_individual`, `restaurant`, `car_rental_office`, `driver`

> مخرجات النظام: يتم تفعيل/تحقق البريد تلقائيًا لحسابات `admin` فقط (غير داخلة في هذا الدليل)، و`normal` يحصل على موافقة تلقائية (`is_approved = true`).

---

## مستخدم عادي (normal)
- لا توجد حقول إضافية.

مثال طلب:
```json
{
  "name": "Ahmed Ali",
  "email": "ahmed@example.com",
  "password": "secret123",
  "phone": "+20123456789",
  "governorate": "Cairo",
  "user_type": "normal"
}
```

---

## مكتب عقارات (real_estate_office)
ينشئ علاقة `real_estate` بقيمة `type = office`، ثم تفاصيل المكتب في جدول `real_estate_offices_details`.

حقول تفاصيل المكتب:
- `office_name` (string) — Required — اسم المكتب.
- `office_address` (string) — Required — عنوان المكتب.
- `office_phone` (string) — Required — رقم هاتف المكتب.
- `logo_image` (string: URL/path) — Required — شعار المكتب.
- `owner_id_front_image` (string: URL/path) — Required — صورة بطاقة المالك أمامية.
- `owner_id_back_image` (string: URL/path) — Required — صورة بطاقة المالك خلفية.
- `office_image` (string: URL/path) — Required — صورة لمقر المكتب.
- `commercial_register_front_image` (string: URL/path) — Required — السجل التجاري (أمامية).
- `commercial_register_back_image` (string: URL/path) — Required — السجل التجاري (خلفية).
- `tax_enabled` (boolean) — Optional — مفعّل ضريبة القيمة المضافة (افتراضي false).

مثال طلب:
```json
{
  "name": "Masar Office",
  "email": "office@example.com",
  "password": "Strong@123",
  "phone": "+201111111111",
  "governorate": "Giza",
  "user_type": "real_estate_office",

  "office_name": "Masar Real Estate",
  "office_address": "123 Nile St, Giza",
  "office_phone": "+201222222222",
  "logo_image": "https://cdn.example.com/logo.png",
  "owner_id_front_image": "https://cdn.example.com/owner_id_front.jpg",
  "owner_id_back_image": "https://cdn.example.com/owner_id_back.jpg",
  "office_image": "https://cdn.example.com/office.jpg",
  "commercial_register_front_image": "https://cdn.example.com/cr_front.jpg",
  "commercial_register_back_image": "https://cdn.example.com/cr_back.jpg",
  "tax_enabled": true
}
```

---

## سمسار فردي (real_estate_individual)
ينشئ علاقة `real_estate` بقيمة `type = individual`، ثم تفاصيل الفرد في جدول `real_estate_individuals_details`.

حقول تفاصيل الفرد:
- `profile_image` (string: URL/path) — Required — صورة الملف الشخصي.
- `agent_name` (string) — Required — اسم السمسار.
- `agent_id_front_image` (string: URL/path) — Required — بطاقة السمسار أمامية.
- `agent_id_back_image` (string: URL/path) — Required — بطاقة السمسار خلفية.
 - `tax_card_front_image` (string: URL/path) — Optional — بطاقة ضريبية أمامية.
 - `tax_card_back_image` (string: URL/path) — Optional — بطاقة ضريبية خلفية.

مثال طلب:
```json
{
  "name": "Broker User",
  "email": "broker@example.com",
  "password": "Strong@123",
  "phone": "+201333333333",
  "governorate": "Alexandria",
  "user_type": "real_estate_individual",

  "profile_image": "https://cdn.example.com/profile.jpg",
  "agent_name": "Mohamed Broker",
  "agent_id_front_image": "https://cdn.example.com/id_front.jpg",
  "agent_id_back_image": "https://cdn.example.com/id_back.jpg",
  "tax_card_front_image": "https://cdn.example.com/tax_front.jpg",
  "tax_card_back_image": "https://cdn.example.com/tax_back.jpg"
}
```

---

## مطعم (restaurant)
ينشئ تفاصيل مطعم في جدول `restaurant_details`.

حقول تفاصيل المطعم:
- `profile_image` (string: URL/path) — Required — صورة الملف الشخصي.
- `restaurant_name` (string) — Required — اسم المطعم.
- `logo_image` (string: URL/path) — Required — شعار المطعم.
- `owner_id_front_image` (string: URL/path) — Required — بطاقة المالك أمامية.
- `owner_id_back_image` (string: URL/path) — Required — بطاقة المالك خلفية.
- `license_front_image` (string: URL/path) — Required — الرخصة أمامية.
- `license_back_image` (string: URL/path) — Required — الرخصة خلفية.
- `commercial_register_front_image` (string: URL/path) — Required — السجل التجاري أمامية.
- `commercial_register_back_image` (string: URL/path) — Required — السجل التجاري خلفية.
- `vat_included` (boolean) — Optional — هل الأسعار تشمل الضريبة؟ افتراضي false.
- `vat_image_front` (string: URL/path) — Optional — مستند ضريبة أمامية (يُفضل توفيره إن كانت الضريبة مفعلة).
- `vat_image_back` (string: URL/path) — Optional — مستند ضريبة خلفية.
- `cuisine_types` (array<string>) — Required — أنواع المطبخ، مثل `["egyptian","italian"]`.
- `branches` (array<any>) — Required — قائمة الفروع كمصفوفة JSON (الشكل مرن حسب الواجهة الأمامية). مثال مبدئي لكل فرع: `{ "name": "Main Branch", "address": "..", "lat": 30.1, "lng": 31.2 }`.
- `delivery_available` (boolean) — Optional — توصيل متاح؟ افتراضي false.
- `delivery_cost_per_km` (number) — Optional — مطلوب منطقيًا إذا كان `delivery_available = true`.
- `table_reservation_available` (boolean) — Optional — حجز طاولات متاح؟ افتراضي false.
- `max_people_per_reservation` (integer) — Optional — مطلوب منطقيًا إذا كان الحجز متاح.
- `reservation_notes` (string) — Optional — ملاحظات عامة للحجز.
- `deposit_required` (boolean) — Optional — هل يتطلب حجز بعربون؟ افتراضي false.
- `deposit_amount` (number) — Optional — مطلوب منطقيًا إذا كان العربون مطلوب.
- `working_hours` (array<any>) — Required — جدول ساعات العمل كمصفوفة JSON. مثال: `{ "day": "sat", "open": "09:00", "close": "23:00" }`.

مثال طلب:
```json
{
  "name": "Restaurant Owner",
  "email": "rest@example.com",
  "password": "Strong@123",
  "phone": "+201444444444",
  "governorate": "Cairo",
  "user_type": "restaurant",

  "profile_image": "https://cdn.example.com/profile.jpg",
  "restaurant_name": "Masar Dine",
  "logo_image": "https://cdn.example.com/logo.png",
  "owner_id_front_image": "https://cdn.example.com/owner_id_front.jpg",
  "owner_id_back_image": "https://cdn.example.com/owner_id_back.jpg",
  "license_front_image": "https://cdn.example.com/license_front.jpg",
  "license_back_image": "https://cdn.example.com/license_back.jpg",
  "commercial_register_front_image": "https://cdn.example.com/cr_front.jpg",
  "commercial_register_back_image": "https://cdn.example.com/cr_back.jpg",
  "vat_included": true,
  "cuisine_types": ["egyptian", "grill"],
  "branches": [{"name": "Main Branch", "address": "Nasr City"}],
  "delivery_available": true,
  "delivery_cost_per_km": 5.5,
  "table_reservation_available": true,
  "max_people_per_reservation": 6,
  "deposit_required": false,
  "working_hours": [{"day": "sat", "open": "09:00", "close": "23:00"}]
}
```

---

## مكتب تأجير سيارات (car_rental_office)
ينشئ سجل في `car_rentals` بقيمة `rental_type = office`، ثم تفاصيل المكتب في جدول `car_rental_offices_details`.

حقول تفاصيل مكتب التأجير:
- `office_name` (string) — Required — اسم المكتب.
- `logo_image` (string: URL/path) — Required — شعار المكتب.
- `commercial_register_front_image` (string: URL/path) — Required — السجل التجاري أمامية.
- `commercial_register_back_image` (string: URL/path) — Required — السجل التجاري خلفية.
- `payment_methods` (array<string>) — Required — طرق الدفع مثل `["cash","visa","wallet"]`.
- `rental_options` (array<string>) — Required — خيارات التأجير مثل `["with_driver","without_driver"]`.
- `cost_per_km` (number) — Required — تكلفة الكيلو متر.
- `daily_driver_cost` (number) — Required — تكلفة السائق اليومية.
- `max_km_per_day` (integer) — Required — الحد الأقصى للكيلومترات يوميًا.
- `is_available_for_delivery` (boolean) — Optional — متاح للتوصيل (افتراضي true).
- `is_available_for_rent` (boolean) — Optional — متاح للتأجير (افتراضي true).
- حقول وثائق إضافية (Optional، إن وُجدت):
  - `owner_id_front_image`, `owner_id_back_image`, `license_front_image`, `license_back_image`, `vat_front_image`, `vat_back_image`, `includes_vat`.

مثال طلب:
```json
{
  "name": "Car Rental Office",
  "email": "rental.office@example.com",
  "password": "Strong@123",
  "phone": "+201555555555",
  "governorate": "Cairo",
  "user_type": "car_rental_office",

  "office_name": "Masar Rent",
  "logo_image": "https://cdn.example.com/logo.png",
  "commercial_register_front_image": "https://cdn.example.com/cr_front.jpg",
  "commercial_register_back_image": "https://cdn.example.com/cr_back.jpg",
  "payment_methods": ["cash", "visa"],
  "rental_options": ["with_driver", "without_driver"],
  "cost_per_km": 3.75,
  "daily_driver_cost": 250,
  "max_km_per_day": 200,
  "is_available_for_delivery": true,
  "is_available_for_rent": true
}
```

---

## سائق (driver)
ينشئ سجل في `car_rentals` بقيمة `rental_type = driver`، ثم تفاصيل السائق في جدول `driver_details`.

حقول تفاصيل السائق:
- `profile_image` (string: URL/path) — Required — صورة الملف الشخصي.
- `payment_methods` (array<string>) — Required — طرق الدفع.
- `rental_options` (array<string>) — Required — خيارات التأجير.
- `cost_per_km` (number) — Required — تكلفة الكيلو متر.
- `daily_driver_cost` (number) — Required — تكلفة السائق اليومية.
- `max_km_per_day` (integer) — Required — الحد الأقصى للكيلومترات يوميًا.

مثال طلب:
```json
{
  "name": "Driver User",
  "email": "driver@example.com",
  "password": "Strong@123",
  "phone": "+201666666666",
  "governorate": "Cairo",
  "user_type": "driver",

  "profile_image": "https://cdn.example.com/profile.jpg",
  "payment_methods": ["cash"],
  "rental_options": ["with_driver"],
  "cost_per_km": 2.5,
  "daily_driver_cost": 200,
  "max_km_per_day": 150
}
```

---

## ملاحظات إضافية للواجهات الأمامية (Flutter)
- استخدم `multipart/form-data` لرفع الصور مباشرة بأسماء الحقول المذكورة، أو أرسل روابط/مسارات مخزّنة.
- تحقّق شرطيًا للحقول:
  - المطاعم: إن كان `delivery_available = true` تحقّق من وجود `delivery_cost_per_km`، وإن كان `table_reservation_available = true` تحقّق من `max_people_per_reservation`، وإن كان `deposit_required = true` تحقّق من `deposit_amount`.
- أنواع JSON:
  - `payment_methods` و`rental_options`: مصفوفة من النصوص.
  - `cuisine_types`: مصفوفة نصوص.
  - `branches`, `working_hours`: مصفوفات JSON مرنة؛ اتفقوا على شكل ثابت للواجهة حسب الحاجة.
- القيم الرقمية:
  - `cost_per_km`, `daily_driver_cost`, `deposit_amount`, `delivery_cost_per_km`: تُرسل أرقامًا عشرية.
  - `max_km_per_day`, `max_people_per_reservation`: تُرسل أعدادًا صحيحة.