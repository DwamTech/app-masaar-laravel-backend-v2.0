# دليل استخدام Postman لنظام توصيل العملاء

هذا الدليل يوضح كيفية استخدام Postman لاختبار جميع endpoints الخاصة بنظام توصيل العملاء بالسيارات (مثل inDriver) في مشروع Masaar.

## الإعدادات الأساسية

### Base URL
```
http://localhost:8000/api
```

### Headers المطلوبة
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}
```

### متغيرات البيئة (Environment Variables)
```
base_url: http://localhost:8000/api
auth_token: {your_auth_token}
client_id: {client_user_id}
driver_id: {driver_user_id}
delivery_request_id: {delivery_request_id}
offer_id: {offer_id}
```

---

## 1. إدارة طلبات توصيل العملاء (Client Side)

### 1.1 إنشاء طلب توصيل عميل جديد

**Method:** `POST`  
**URL:** `{{base_url}}/service-requests`  
**Headers:**
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "Authorization": "Bearer {{auth_token}}"
}
```

**Body (JSON) - رحلة ذهاب فقط:**
```json
{
  "type": "delivery",
  "governorate": "القاهرة",
  "request_data": {
    "trip_type": "ذهاب فقط",
    "from_location": "مول العرب، شارع الهرم، الجيزة",
    "from_location_url": "https://maps.google.com/?q=30.0444,31.2357",
    "to_location": "مطار القاهرة الدولي، القاهرة الجديدة",
    "to_location_url": "https://maps.google.com/?q=30.1219,31.4056",
    "passengers": 2,
    "client_notes": "يرجى الاتصال عند الوصول",
    "governorate": "القاهرة",
    "delivery_time": "توصيل الآن",
    "car_category": "اقتصادية",
    "payment_method": "كاش",
    "estimated_duration": 45
  }
}
```

**Body (JSON) - رحلة ذهاب وعودة:**
```json
{
  "type": "delivery",
  "governorate": "القاهرة",
  "request_data": {
    "trip_type": "ذهاب وعودة",
    "from_location": "مول العرب، شارع الهرم، الجيزة",
    "from_location_url": "https://maps.google.com/?q=30.0444,31.2357",
    "to_location": "مطار القاهرة الدولي، القاهرة الجديدة",
    "to_location_url": "https://maps.google.com/?q=30.1219,31.4056",
    "passengers": 1,
    "client_notes": "انتظار ساعة في المطار",
    "governorate": "القاهرة",
    "delivery_time": "تحديد الوقت",
    "car_category": "مريحة",
    "payment_method": "تحويل بنكي",
    "estimated_duration": 120
  }
}
```

**Body (JSON) - وجهات متعددة:**
```json
{
  "type": "delivery",
  "governorate": "القاهرة",
  "request_data": {
    "trip_type": "وجهات متعددة",
    "starting_point": "مول العرب، شارع الهرم، الجيزة",
    "starting_point_url": "https://maps.google.com/?q=30.0444,31.2357",
    "destinations": [
      {
        "location": "جامعة القاهرة، الجيزة",
        "location_url": "https://maps.google.com/?q=30.0277,31.2089",
        "name": "المحطة الأولى"
      },
      {
        "location": "مطار القاهرة الدولي، القاهرة الجديدة",
        "location_url": "https://maps.google.com/?q=30.1219,31.4056",
        "name": "المحطة الثانية"
      }
    ],
    "passengers": 3,
    "client_notes": "توقف قصير في كل محطة",
    "governorate": "القاهرة",
    "delivery_time": "توصيل الآن",
    "car_category": "فان",
    "payment_method": "كاش",
    "estimated_duration": 90
  }
}
```

**Response Example:**
```json
{
  "status": true,
  "message": "تم إنشاء طلب التوصيل بنجاح",
  "data": {
    "id": 1,
    "type": "delivery",
    "governorate": "القاهرة",
    "request_data": {
      "trip_type": "ذهاب فقط",
      "from_location": "مول العرب، شارع الهرم، الجيزة",
      "to_location": "مطار القاهرة الدولي، القاهرة الجديدة",
      "passengers": 2,
      "delivery_time": "توصيل الآن",
      "car_category": "اقتصادية",
      "payment_method": "كاش"
    },
    "status": "pending",
    "created_at": "2024-12-20T10:00:00.000000Z"
  }
}
```

### 1.2 عرض طلبات التوصيل الخاصة بالعميل

**Method:** `GET`  
**URL:** `{{base_url}}/service-requests`  
**Headers:** نفس الـ headers السابقة

**Query Parameters (اختيارية):**
```
type=delivery
status=pending
```

### 1.3 عرض تفاصيل طلب توصيل معين

**Method:** `GET`  
**URL:** `{{base_url}}/service-requests/{{request_id}}`  
**Headers:** نفس الـ headers السابقة

---

## 2. إدارة العروض (Client Side)

### 2.1 عرض العروض المقدمة على طلب معين

**Method:** `GET`  
**URL:** `{{base_url}}/delivery/requests/{{delivery_request_id}}/offers`  
**Headers:** نفس الـ headers السابقة

**Response Example:**
```json
{
  "status": true,
  "offers": [
    {
      "id": 1,
      "delivery_request_id": 1,
      "driver_id": 5,
      "offered_price": 45.00,
      "estimated_duration": 30,
      "offer_notes": "سأصل في الوقت المحدد لاستلامك",
      "status": "pending",
      "created_at": "2024-12-20T10:15:00.000000Z",
      "driver": {
        "id": 5,
        "name": "محمد السائق",
        "phone": "01111111111",
        "rating": 4.8
      }
    }
  ]
}
```

### 2.2 قبول عرض معين

**Method:** `POST`  
**URL:** `{{base_url}}/delivery/requests/{{delivery_request_id}}/offers/{{offer_id}}/accept`  
**Headers:** نفس الـ headers السابقة

**Response Example:**
```json
{
  "status": true,
  "message": "تم قبول العرض بنجاح",
  "delivery_request": {
    "id": 1,
    "status": "accepted_waiting_driver",
    "driver_id": 5,
    "agreed_price": 45.00,
    "accepted_at": "2024-12-20T10:30:00.000000Z",
    "driver": {
      "id": 5,
      "name": "محمد السائق",
      "phone": "01111111111"
    }
  }
}
```

---

## 3. إدارة الطلبات (Driver Side)

### 3.1 عرض طلبات توصيل العملاء المتاحة للسائقين

**Method:** `GET`  
**URL:** `{{base_url}}/delivery/available-requests`  
**Headers:** نفس الـ headers السابقة (مع token السائق)

**Query Parameters (اختيارية):**
```
trip_type=ذهاب فقط
governorate=القاهرة
car_category=اقتصادية
```

**Response Example:**
```json
{
  "status": true,
  "available_requests": {
    "data": [
      {
        "id": 1,
        "client_id": 1,
        "trip_type": "ذهاب فقط",
        "delivery_time": "توصيل الآن",
        "car_category": "اقتصادية",
        "payment_method": "كاش",
        "status": "pending",
        "client": {
          "id": 1,
          "name": "أحمد العميل",
          "phone": "01234567890"
        },
        "request_data": {
          "from_location": "مول العرب، شارع الهرم، الجيزة",
          "to_location": "مطار القاهرة الدولي، القاهرة الجديدة",
          "passengers": 2
        },
        "offers": []
      }
    ],
    "current_page": 1,
    "total": 5
  }
}
```

### 3.2 تقديم عرض على طلب توصيل عميل

**Method:** `POST`  
**URL:** `{{base_url}}/delivery/requests/{{delivery_request_id}}/offer`  
**Headers:** نفس الـ headers السابقة (مع token السائق)

**Body (JSON):**
```json
{
  "offered_price": 45.00,
  "estimated_duration": 30,
  "offer_notes": "سأصل خلال 10 دقائق لاستلامك، لدي خبرة 5 سنوات في توصيل العملاء"
}
```

**Response Example:**
```json
{
  "status": true,
  "message": "تم تقديم العرض بنجاح",
  "offer": {
    "id": 1,
    "delivery_request_id": 1,
    "driver_id": 5,
    "offered_price": 45.00,
    "estimated_duration": 30,
    "offer_notes": "سأصل خلال 10 دقائق لاستلامك، لدي خبرة 5 سنوات في توصيل العملاء",
    "status": "pending",
    "created_at": "2024-12-20T10:15:00.000000Z",
    "driver": {
      "id": 5,
      "name": "محمد السائق"
    }
  }
}
```

### 3.3 عرض العروض المقدمة من السائق

**Method:** `GET`  
**URL:** `{{base_url}}/delivery/my-offers`  
**Headers:** نفس الـ headers السابقة (مع token السائق)

---

## 4. إدارة حالة الرحلة

### 4.1 تحديث حالة طلب التوصيل

**Method:** `PATCH`  
**URL:** `{{base_url}}/delivery/requests/{{delivery_request_id}}/status`  
**Headers:** نفس الـ headers السابقة

**Body (JSON) - وصول السائق:**
```json
{
  "status": "driver_arrived",
  "note": "وصلت إلى نقطة استلام العميل"
}
```

**Body (JSON) - بداية الرحلة:**
```json
{
  "status": "trip_started",
  "note": "بدأت رحلة توصيل العميل"
}
```

**Body (JSON) - انتهاء الرحلة:**
```json
{
  "status": "trip_completed",
  "note": "تم توصيل العميل بنجاح"
}
```

**الحالات المتاحة:**
- `driver_arrived`: وصل السائق
- `trip_started`: بدأت الرحلة
- `trip_completed`: انتهت الرحلة
- `cancelled`: تم الإلغاء

### 4.2 عرض تاريخ حالات الطلب

**Method:** `GET`  
**URL:** `{{base_url}}/delivery/requests/{{delivery_request_id}}/status-history`  
**Headers:** نفس الـ headers السابقة

**Response Example:**
```json
{
  "status": true,
  "status_history": [
    {
      "id": 1,
      "delivery_request_id": 1,
      "status": "trip_completed",
      "note": "تم الوصول بنجاح",
      "created_at": "2024-12-20T15:00:00.000000Z",
      "changed_by": {
        "id": 5,
        "name": "محمد السائق"
      }
    },
    {
      "id": 2,
      "status": "trip_started",
      "note": "بدأت الرحلة",
      "created_at": "2024-12-20T14:30:00.000000Z"
    }
  ]
}
```

---

## 5. سيناريوهات الاختبار الكاملة

### سيناريو 1: دورة حياة طلب توصيل عميل كاملة

1. **العميل ينشئ طلب توصيل**
   - استخدم endpoint: `POST /service-requests`
   - احفظ `delivery_request_id` من الاستجابة

2. **السائق يعرض الطلبات المتاحة**
   - استخدم endpoint: `GET /delivery/available-requests`
   - تأكد من ظهور الطلب المُنشأ

3. **السائق يقدم عرض**
   - استخدم endpoint: `POST /delivery/requests/{id}/offer`
   - احفظ `offer_id` من الاستجابة

4. **العميل يعرض العروض**
   - استخدم endpoint: `GET /delivery/requests/{id}/offers`
   - تأكد من ظهور العرض المُقدم

5. **العميل يقبل العرض**
   - استخدم endpoint: `POST /delivery/requests/{id}/offers/{offer_id}/accept`
   - تأكد من تغيير حالة الطلب إلى `accepted_waiting_driver`

6. **السائق يصل لنقطة استلام العميل**
   - استخدم endpoint: `PATCH /delivery/requests/{id}/status`
   - Body: `{"status": "driver_arrived"}`

7. **السائق يبدأ رحلة توصيل العميل**
   - استخدم endpoint: `PATCH /delivery/requests/{id}/status`
   - Body: `{"status": "trip_started"}`

8. **السائق ينهي رحلة توصيل العميل**
   - استخدم endpoint: `PATCH /delivery/requests/{id}/status`
   - Body: `{"status": "trip_completed"}`

### سيناريو 2: رحلة وجهات متعددة

1. **العميل ينشئ طلب توصيل بوجهات متعددة**
2. **السائق يقدم عرض**
3. **العميل يقبل العرض**
4. **السائق ينفذ الرحلة بالتوقف في كل وجهة**

### سيناريو 3: إلغاء طلب توصيل

1. **العميل ينشئ طلب توصيل**
2. **العميل يلغي الطلب**
   - استخدم endpoint: `PATCH /delivery/requests/{id}/cancel`
   - Body: `{"reason": "تغيير في الخطط"}`

---

## 6. رموز الاستجابة والأخطاء

### رموز النجاح
- `200`: تم بنجاح
- `201`: تم الإنشاء بنجاح

### رموز الأخطاء
- `400`: خطأ في البيانات المرسلة
- `401`: غير مصرح (مشكلة في التوكن)
- `403`: ممنوع (لا تملك الصلاحية)
- `404`: غير موجود
- `422`: خطأ في التحقق من البيانات
- `500`: خطأ في الخادم

### أمثلة على رسائل الأخطاء

**خطأ في التحقق من البيانات (422):**
```json
{
  "status": false,
  "message": "خطأ في البيانات المدخلة",
  "errors": {
    "trip_type": ["حقل نوع الرحلة مطلوب"],
    "delivery_time": ["يجب أن يكون وقت التوصيل في المستقبل"]
  }
}
```

**خطأ في الصلاحية (403):**
```json
{
  "status": false,
  "message": "غير مصرح لك بهذا الإجراء"
}
```

---

## 7. نصائح للاختبار

### إعداد Collection في Postman
1. أنشئ Collection جديدة باسم "Delivery System API"
2. أضف Environment Variables للـ base_url والـ tokens
3. أنشئ مجلدات منفصلة لكل نوع من الـ endpoints
4. استخدم Tests في Postman لحفظ المتغيرات تلقائياً

### مثال على Test Script لحفظ delivery_request_id:
```javascript
if (pm.response.code === 201) {
    const response = pm.response.json();
    if (response.delivery_request && response.delivery_request.id) {
        pm.environment.set("delivery_request_id", response.delivery_request.id);
    }
}
```

### مثال على Pre-request Script للتوكن:
```javascript
const token = pm.environment.get("auth_token");
if (token) {
    pm.request.headers.add({
        key: "Authorization",
        value: "Bearer " + token
    });
}
```

---

## 8. البيانات التجريبية

### بيانات عميل تجريبي
```json
{
  "name": "أحمد محمد",
  "email": "client@test.com",
  "phone": "01234567890",
  "user_type": "client",
  "governorate": "القاهرة"
}
```

### بيانات سائق تجريبي
```json
{
  "name": "محمد السائق",
  "email": "driver@test.com",
  "phone": "01111111111",
  "user_type": "driver",
  "governorate": "القاهرة",
  "car_model": "تويوتا كورولا 2020",
  "license_plate": "أ ب ج 123",
  "is_available": true
}
```

### مواقع تجريبية
```json
[
  {
    "name": "مول العرب",
    "latitude": 30.0444,
    "longitude": 31.2357,
    "address": "شارع الهرم، الجيزة"
  },
  {
    "name": "مطار القاهرة الدولي",
    "latitude": 30.1219,
    "longitude": 31.4056,
    "address": "مطار القاهرة الدولي، القاهرة الجديدة"
  },
  {
    "name": "جامعة القاهرة",
    "latitude": 30.0277,
    "longitude": 31.2089,
    "address": "شارع الجامعة، الجيزة"
  }
]
```

---

## الخلاصة

هذا الدليل يغطي جميع endpoints نظام طلب التوصيل ويوفر أمثلة شاملة لكل حالة استخدام. تأكد من:

1. ✅ إعداد Environment Variables بشكل صحيح
2. ✅ استخدام التوكن المناسب لكل نوع مستخدم
3. ✅ اختبار جميع السيناريوهات المختلفة
4. ✅ التحقق من رسائل الأخطاء والاستجابات
5. ✅ حفظ المتغيرات المهمة تلقائياً باستخدام Tests

للمساعدة أو الاستفسارات، يرجى مراجعة الكود المصدري أو التواصل مع فريق التطوير.