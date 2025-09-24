# دليل رحلة التوصيل الكاملة - API Endpoints

هذا الدليل يوضح جميع الـ endpoints المطلوبة لتجربة رحلة توصيل كاملة من البداية حتى النهاية.

## المتطلبات الأساسية

- خادم Laravel يعمل على: `http://127.0.0.1:8000`
- مستخدم عادي (عميل) مسجل ومفعل
- سائق مسجل ومفعل
- Token صالح لكل مستخدم

---

## 1. تسجيل الدخول

### تسجيل دخول العميل
```http
POST /api/login
Content-Type: application/json
Accept: application/json

{
    "email": "client@example.com",
    "password": "password123"
}
```

**الاستجابة المتوقعة:**
```json
{
    "status": true,
    "message": "تم تسجيل الدخول بنجاح.",
    "token": "1|abc123...",
    "user": {
        "id": 1,
        "name": "اسم العميل",
        "email": "client@example.com",
        "governorate": "القاهرة",
        "user_type": "normal"
    }
}
```

### تسجيل دخول السائق
```http
POST /api/login
Content-Type: application/json
Accept: application/json

{
    "email": "driver@example.com",
    "password": "password123"
}
```

---

## 2. إنشاء طلب توصيل (العميل)

```http
POST /api/delivery-requests
Content-Type: application/json
Accept: application/json
Authorization: Bearer {client_token}

{
    "pickup_location": "شارع التحرير، القاهرة",
    "pickup_latitude": 30.0444,
    "pickup_longitude": 31.2357,
    "delivery_location": "مدينة نصر، القاهرة",
    "delivery_latitude": 30.0626,
    "delivery_longitude": 31.3219,
    "package_description": "طرد صغير - مستندات",
    "package_weight": "أقل من 1 كيلو",
    "delivery_fee": 50,
    "notes": "يرجى التعامل بحذر",
    "recipient_name": "أحمد محمد",
    "recipient_phone": "01234567890"
}
```

**الاستجابة المتوقعة:**
```json
{
    "status": true,
    "message": "تم إنشاء طلب التوصيل بنجاح",
    "data": {
        "id": 1,
        "status": "pending",
        "pickup_location": "شارع التحرير، القاهرة",
        "delivery_location": "مدينة نصر، القاهرة",
        "delivery_fee": 50,
        "governorate": "القاهرة"
    }
}
```

---

## 3. عرض الطلبات المتاحة (السائق)

```http
GET /api/delivery-requests/available
Accept: application/json
Authorization: Bearer {driver_token}
```

**الاستجابة المتوقعة:**
```json
{
    "status": true,
    "data": [
        {
            "id": 1,
            "pickup_location": "شارع التحرير، القاهرة",
            "delivery_location": "مدينة نصر، القاهرة",
            "package_description": "طرد صغير - مستندات",
            "delivery_fee": 50,
            "distance": "5.2 كم",
            "estimated_time": "20 دقيقة",
            "governorate": "القاهرة",
            "created_at": "2024-01-15T10:30:00Z"
        }
    ]
}
```

---

## 4. قبول طلب التوصيل (السائق)

```http
POST /api/delivery-requests/{request_id}/accept
Accept: application/json
Authorization: Bearer {driver_token}
```

**الاستجابة المتوقعة:**
```json
{
    "status": true,
    "message": "تم قبول طلب التوصيل بنجاح",
    "data": {
        "id": 1,
        "status": "accepted",
        "driver_id": 2,
        "driver_name": "محمد السائق",
        "driver_phone": "01098765432"
    }
}
```

---

## 5. تحديث حالة الطلب (السائق)

### بدء الرحلة - الذهاب لنقطة الاستلام
```http
PUT /api/delivery-requests/{request_id}/status
Content-Type: application/json
Accept: application/json
Authorization: Bearer {driver_token}

{
    "status": "on_way_to_pickup"
}
```

### وصول لنقطة الاستلام
```http
PUT /api/delivery-requests/{request_id}/status
Content-Type: application/json
Accept: application/json
Authorization: Bearer {driver_token}

{
    "status": "arrived_at_pickup"
}
```

### استلام الطرد
```http
PUT /api/delivery-requests/{request_id}/status
Content-Type: application/json
Accept: application/json
Authorization: Bearer {driver_token}

{
    "status": "picked_up"
}
```

### في الطريق للتسليم
```http
PUT /api/delivery-requests/{request_id}/status
Content-Type: application/json
Accept: application/json
Authorization: Bearer {driver_token}

{
    "status": "on_way_to_delivery"
}
```

### وصول لنقطة التسليم
```http
PUT /api/delivery-requests/{request_id}/status
Content-Type: application/json
Accept: application/json
Authorization: Bearer {driver_token}

{
    "status": "arrived_at_delivery"
}
```

### تسليم الطرد
```http
PUT /api/delivery-requests/{request_id}/status
Content-Type: application/json
Accept: application/json
Authorization: Bearer {driver_token}

{
    "status": "delivered"
}
```

---

## 6. تتبع الطلب (العميل)

```http
GET /api/delivery-requests/{request_id}
Accept: application/json
Authorization: Bearer {client_token}
```

**الاستجابة المتوقعة:**
```json
{
    "status": true,
    "data": {
        "id": 1,
        "status": "on_way_to_delivery",
        "status_ar": "في الطريق للتسليم",
        "pickup_location": "شارع التحرير، القاهرة",
        "delivery_location": "مدينة نصر، القاهرة",
        "driver": {
            "name": "محمد السائق",
            "phone": "01098765432",
            "rating": "4.8"
        },
        "timeline": [
            {
                "status": "pending",
                "timestamp": "2024-01-15T10:30:00Z",
                "description": "تم إنشاء الطلب"
            },
            {
                "status": "accepted",
                "timestamp": "2024-01-15T10:35:00Z",
                "description": "تم قبول الطلب من السائق"
            },
            {
                "status": "on_way_to_pickup",
                "timestamp": "2024-01-15T10:36:00Z",
                "description": "السائق في الطريق لنقطة الاستلام"
            }
        ]
    }
}
```

---

## 7. تحديث موقع السائق (أثناء الرحلة)

```http
PUT /api/users/location
Content-Type: application/json
Accept: application/json
Authorization: Bearer {driver_token}

{
    "latitude": 30.0500,
    "longitude": 31.2400,
    "current_address": "شارع الجمهورية، القاهرة"
}
```

---

## 8. تقييم الرحلة (العميل)

```http
POST /api/delivery-requests/{request_id}/rate
Content-Type: application/json
Accept: application/json
Authorization: Bearer {client_token}

{
    "rating": 5,
    "comment": "خدمة ممتازة، سائق مهذب ووصل في الوقت المحدد"
}
```

---

## 9. عرض تاريخ الطلبات

### للعميل
```http
GET /api/delivery-requests/my-requests
Accept: application/json
Authorization: Bearer {client_token}
```

### للسائق
```http
GET /api/delivery-requests/my-deliveries
Accept: application/json
Authorization: Bearer {driver_token}
```

---

## 10. الإشعارات

### جلب الإشعارات
```http
GET /api/notifications
Accept: application/json
Authorization: Bearer {token}
```

### تحديد إشعار كمقروء
```http
PUT /api/notifications/{notification_id}/read
Accept: application/json
Authorization: Bearer {token}
```

---

## سيناريو الاختبار الكامل

### الخطوة 1: إعداد المستخدمين
1. تسجيل دخول العميل والحصول على token
2. تسجيل دخول السائق والحصول على token

### الخطوة 2: دورة الطلب الكاملة
1. العميل ينشئ طلب توصيل
2. السائق يعرض الطلبات المتاحة
3. السائق يقبل الطلب
4. السائق يحدث الحالة: "في الطريق للاستلام"
5. السائق يحدث الحالة: "وصل لنقطة الاستلام"
6. السائق يحدث الحالة: "تم الاستلام"
7. السائق يحدث الحالة: "في الطريق للتسليم"
8. السائق يحدث الحالة: "وصل لنقطة التسليم"
9. السائق يحدث الحالة: "تم التسليم"
10. العميل يقيم الرحلة

### الخطوة 3: التحقق
1. العميل يتتبع الطلب في كل مرحلة
2. كلا المستخدمين يتحققان من الإشعارات
3. مراجعة تاريخ الطلبات

---

## ملاحظات مهمة

- جميع الـ endpoints تتطلب `Accept: application/json` header
- الـ endpoints المحمية تتطلب `Authorization: Bearer {token}` header
- يتم فلترة الطلبات المتاحة للسائقين حسب المحافظة
- يتم إرسال إشعارات تلقائية عند تغيير حالة الطلب
- يمكن للعميل تتبع موقع السائق في الوقت الفعلي أثناء الرحلة

---

## رموز الحالات

- `pending`: في انتظار قبول السائق
- `accepted`: تم قبول الطلب
- `on_way_to_pickup`: السائق في الطريق للاستلام
- `arrived_at_pickup`: السائق وصل لنقطة الاستلام
- `picked_up`: تم استلام الطرد
- `on_way_to_delivery`: في الطريق للتسليم
- `arrived_at_delivery`: وصل لنقطة التسليم
- `delivered`: تم التسليم
- `cancelled`: تم إلغاء الطلب

---

*تم إنشاء هذا الدليل لتسهيل اختبار وتطوير نظام التوصيل*