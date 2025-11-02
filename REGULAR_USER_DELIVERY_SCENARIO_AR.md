# سيناريو المستخدم العادي لطلب توصيلة جديدة

هذا الملف يوضّح رحلة المستخدم العادي لطلب توصيلة جديدة: إنشاء الطلب، استقبال العروض، قبول عرض، ثم استعراض تفاصيل الطلب وحالته. جميع الأمثلة باللغة العربية وتشمل المسارات (endpoints)، الهيدر (headers)، الجسم (body)، والرد (response).

## المتطلبات العامة
- جميع الطلبات تحتاج `Authorization: Bearer {token}` لحساب العميل.
- استخدم `Accept: application/json`.
- قاعدة الروابط: `{{base_url}}/delivery`.

---

## 1) إنشاء طلب توصيلة جديد (Client)
- Method: `POST`
- URL: `{{base_url}}/requests`
- Headers:
  - `Accept: application/json`
  - `Authorization: Bearer {{client_token}}`

### الحقول المطلوبة والاختيارية
- `trip_type` (مطلوب): أحد القيم `one_way`, `round_trip`, `multiple_destinations`.
- `delivery_time` (مطلوب): تاريخ/وقت بصيغة ISO، مثل `2025-11-05T14:30:00Z`.
- `car_category` (مطلوب): أحد القيم `economy`, `comfort`, `premium`, `van`.
- `payment_method` (مطلوب): أحد القيم `cash`, `bank_transfer`, `card`.
- `governorate` (اختياري): اسم المحافظة نصيًا.
- `price` (اختياري): سعر مبدئي إن لزم (رقمي ≥ 0).
- `notes` (اختياري): ملاحظات العميل.
- `destinations[]` (مطلوب): مصفوفة الوجهات وفي كل عنصر:
  - `location_name` (مطلوب)
  - `latitude` (اختياري)
  - `longitude` (اختياري)
  - `address` (اختياري)
  - `contact_name` (اختياري)
  - `contact_phone` (اختياري)
  - `notes` (اختياري)
  - `is_pickup_point` (اختياري، Boolean)
  - `is_dropoff_point` (اختياري، Boolean)

### مثال Body (طلب ذهاب فقط من المنزل إلى المطار)
```json
{
  "trip_type": "one_way",
  "delivery_time": "2025-11-05T14:30:00Z",
  "car_category": "economy",
  "payment_method": "cash",
  "governorate": "القاهرة",
  "notes": "من الباب إلى الباب، بدون انتظار",
  "destinations": [
    {
      "location_name": "المنزل",
      "address": "شارع التحرير، الدقي، الجيزة",
      "latitude": 30.042,
      "longitude": 31.215,
      "contact_name": "أحمد",
      "contact_phone": "01000000000",
      "is_pickup_point": true,
      "is_dropoff_point": false
    },
    {
      "location_name": "المطار",
      "address": "مطار القاهرة الدولي - مبنى 2",
      "latitude": 30.121,
      "longitude": 31.405,
      "contact_name": "أحمد",
      "contact_phone": "01000000000",
      "notes": "سأكون عند البوابة الخارجية",
      "is_pickup_point": false,
      "is_dropoff_point": true
    }
  ]
}
```

### مثال Response (نجاح)
```json
{
  "status": true,
  "message": "تم إنشاء طلب التوصيل بنجاح",
  "delivery_request": {
    "id": 123,
    "client_id": 33,
    "trip_type": "one_way",
    "delivery_time": "2025-11-05T14:30:00.000000Z",
    "status": "pending_offers",
    "car_category": "economy",
    "payment_method": "cash",
    "governorate": "القاهرة",
    "notes": "من الباب إلى الباب، بدون انتظار",
    "price": null,
    "agreed_price": null,
    "driver_id": null,
    "estimated_duration": null,
    "created_at": "2025-11-02T10:10:00.000000Z",
    "updated_at": "2025-11-02T10:10:00.000000Z",
    "destinations": [
      {
        "id": 991,
        "delivery_request_id": 123,
        "location_name": "المنزل",
        "address": "شارع التحرير، الدقي، الجيزة",
        "latitude": 30.042,
        "longitude": 31.215,
        "contact_name": "أحمد",
        "contact_phone": "01000000000",
        "notes": null,
        "is_pickup_point": true,
        "is_dropoff_point": false,
        "created_at": "2025-11-02T10:10:00.000000Z"
      },
      {
        "id": 992,
        "delivery_request_id": 123,
        "location_name": "المطار",
        "address": "مطار القاهرة الدولي - مبنى 2",
        "latitude": 30.121,
        "longitude": 31.405,
        "contact_name": "أحمد",
        "contact_phone": "01000000000",
        "notes": "سأكون عند البوابة الخارجية",
        "is_pickup_point": false,
        "is_dropoff_point": true,
        "created_at": "2025-11-02T10:10:00.000000Z"
      }
    ]
  }
}
```

---

## 2) استقبال العروض (Offers)
- Method: `GET`
- URL: `{{base_url}}/requests/{id}/offers`
- Headers:
  - `Accept: application/json`
  - `Authorization: Bearer {{client_token}}`
- Query Param اختياري: `sort`
  - القيم المدعومة: `price_asc`, `price_desc`, `time_asc`, `time_desc`, `newest`, `oldest`.

### مثال Request
```
GET {{base_url}}/requests/123/offers?sort=price_asc
```

### مثال Response
```json
{
  "status": true,
  "offers": [
    {
      "id": 7,
      "delivery_request_id": 123,
      "driver_id": 5,
      "offered_price": 240.00,
      "estimated_duration": 35,
      "offer_notes": "سأصل خلال 20 دقيقة",
      "status": "pending",
      "created_at": "2025-11-02T10:20:00.000000Z",
      "driver": {
        "id": 5,
        "name": "محمد السائق",
        "phone": "01111111111",
        "rating": 4.8
      }
    },
    {
      "id": 9,
      "delivery_request_id": 123,
      "driver_id": 8,
      "offered_price": 260.00,
      "estimated_duration": 40,
      "offer_notes": "سيارة مكيّفة",
      "status": "pending",
      "created_at": "2025-11-02T10:23:00.000000Z",
      "driver": {
        "id": 8,
        "name": "سعيد",
        "phone": "01022222222",
        "rating": 4.5
      }
    }
  ]
}
```

---

## 3) الموافقة على عرض معيّن (Accept Offer)
- Method: `POST`
- URL: `{{base_url}}/requests/{deliveryRequestId}/offers/{offerId}/accept`
- Headers:
  - `Accept: application/json`
  - `Authorization: Bearer {{client_token}}`

### مثال Request
```
POST {{base_url}}/requests/123/offers/7/accept
```

### مثال Response
```json
{
  "status": true,
  "message": "تم قبول العرض بنجاح",
  "delivery_request": {
    "id": 123,
    "status": "accepted_waiting_driver",
    "driver_id": 5,
    "agreed_price": 240.00,
    "estimated_duration": 35,
    "accepted_at": "2025-11-02T10:30:00.000000Z",
    "driver": {
      "id": 5,
      "name": "محمد السائق",
      "phone": "01111111111"
    }
  }
}
```

ملاحظات:
- عند قبول عرض، يتم رفض باقي العروض المعلّقة تلقائيًا.
- تتغيّر حالة الطلب إلى `accepted_waiting_driver`.

---

## 4) استعراض تفاصيل الطلب وحالته (Show)
- Method: `GET`
- URL: `{{base_url}}/requests/{id}`
- Headers:
  - `Accept: application/json`
  - `Authorization: Bearer {{client_token}}`

### مثال Request
```
GET {{base_url}}/requests/123
```

### مثال Response
```json
{
  "status": true,
  "delivery_request": {
    "id": 123,
    "client": { "id": 33, "name": "أحمد" },
    "driver": { "id": 5, "name": "محمد السائق", "phone": "01111111111" },
    "trip_type": "one_way",
    "delivery_time": "2025-11-05T14:30:00.000000Z",
    "status": "accepted_waiting_driver",
    "car_category": "economy",
    "payment_method": "cash",
    "governorate": "القاهرة",
    "notes": "من الباب إلى الباب، بدون انتظار",
    "agreed_price": 240.00,
    "estimated_duration": 35,
    "destinations": [
      { "id": 991, "location_name": "المنزل", "is_pickup_point": true, "is_dropoff_point": false },
      { "id": 992, "location_name": "المطار", "is_pickup_point": false, "is_dropoff_point": true }
    ],
    "offers": [
      { "id": 7, "driver_id": 5, "offered_price": 240.00, "status": "accepted" },
      { "id": 9, "driver_id": 8, "offered_price": 260.00, "status": "rejected" }
    ],
    "status_histories": [
      { "id": 2001, "status": "pending_offers", "note": null, "created_at": "2025-11-02T10:10:00.000000Z", "changed_by": { "id": 33, "name": "أحمد" } },
      { "id": 2002, "status": "accepted_waiting_driver", "note": "تم قبول عرض رقم 7", "created_at": "2025-11-02T10:30:00.000000Z", "changed_by": { "id": 33, "name": "أحمد" } }
    ]
  }
}
```

### عرض تاريخ الحالات فقط (اختياري)
- Method: `GET`
- URL: `{{base_url}}/requests/{id}/status-history`
- Headers: نفس السابقة

مثال Response:
```json
{
  "status": true,
  "status_history": [
    { "id": 2001, "status": "pending_offers", "note": null, "created_at": "2025-11-02T10:10:00.000000Z", "changed_by": { "id": 33, "name": "أحمد" } },
    { "id": 2002, "status": "accepted_waiting_driver", "note": "تم قبول عرض رقم 7", "created_at": "2025-11-02T10:30:00.000000Z", "changed_by": { "id": 33, "name": "أحمد" } }
  ]
}
```

---

## قيم قياسية مفيدة
- أنواع الرحلات `trip_type`:
  - `one_way`، `round_trip`، `multiple_destinations`.
- حالات الطلب `status`:
  - `pending_offers`, `accepted_waiting_driver`, `driver_arrived`, `trip_started`, `trip_completed`, `cancelled`, `rejected`.
- طرق الدفع `payment_method`:
  - `cash`, `bank_transfer`, `card`.
- فئات السيارات `car_category`:
  - `economy`, `comfort`, `premium`, `van`.
- الفرز في العروض `sort`:
  - السعر: `price_asc`, `price_desc`
  - المدة المقدّرة: `time_asc`, `time_desc`
  - التاريخ: `newest`, `oldest`

---

## ملاحظات إضافية (اختياري)
- يمكن للعميل إلغاء الطلب قبل بدء الرحلة:
  - Method: `PATCH`
  - URL: `{{base_url}}/requests/{id}/cancel`
  - الرد يتضمّن تحديث حالة الطلب إلى `cancelled` إذا نجح الإلغاء.
- يمكن تحديث حالة الطلب (للسائق أو العميل حسب الحالة):
  - Method: `PATCH`
  - URL: `{{base_url}}/requests/{id}/status`
  - Body: `{ "status": "driver_arrived|trip_started|trip_completed|cancelled", "note": "اختياري" }`