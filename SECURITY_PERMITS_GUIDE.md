# دليل نظام التصاريح الأمنية - منصة مسار

## نظرة عامة

نظام التصاريح الأمنية هو نظام شامل يسمح للمستخدمين بتقديم طلبات للحصول على تصاريح أمنية للسفر، مع إدارة كاملة من قبل الإدارة وإشعارات تلقائية.

## هيكل قاعدة البيانات

### الجداول الرئيسية

#### 1. جدول `security_permits`
```sql
- id: معرف الطلب
- user_id: معرف المستخدم
- travel_date: تاريخ السفر
- nationality_id: معرف الجنسية
- people_count: عدد الأفراد
- country_id: معرف الدولة القادم منها
- passport_image: صورة الجواز
- residence_images: صور الإقامة (JSON)
- payment_method: طريقة الدفع
- individual_fee: رسوم الفرد الواحد
- total_amount: المبلغ الإجمالي
- payment_status: حالة الدفع
- payment_reference: مرجع الدفع
- status: حالة الطلب
- notes: ملاحظات المستخدم
- admin_notes: ملاحظات الإدارة
- processed_at: تاريخ المعالجة
```

#### 2. جدول `countries`
```sql
- id: معرف الدولة
- name_ar: الاسم بالعربية
- name_en: الاسم بالإنجليزية
- code: كود الدولة (ISO)
- is_active: حالة النشاط
- sort_order: ترتيب العرض
```

#### 3. جدول `nationalities`
```sql
- id: معرف الجنسية
- name_ar: الاسم بالعربية
- name_en: الاسم بالإنجليزية
- code: كود الجنسية
- is_active: حالة النشاط
- sort_order: ترتيب العرض
```

#### 4. جدول `security_permit_settings`
```sql
- id: معرف الإعداد
- key: مفتاح الإعداد
- value: قيمة الإعداد
- type: نوع البيانات
- description: وصف الإعداد
- group: مجموعة الإعدادات
- is_editable: قابل للتعديل
```

## API Endpoints

### مسارات المستخدمين

#### الحصول على بيانات النموذج
```http
GET /api/security-permits/form-data
Authorization: Bearer {token}
```

**الاستجابة:**
```json
{
  "status": true,
  "data": {
    "countries": [...],
    "nationalities": [...],
    "individual_fee": 150.00,
    "payment_methods": [...]
  }
}
```

#### إنشاء طلب جديد
```http
POST /api/security-permits
Authorization: Bearer {token}
Content-Type: multipart/form-data

Form Data:
- travel_date: 2024-12-25
- nationality_id: 1
- people_count: 2
- country_id: 5
- passport_image: [FILE] (صورة الجواز)
- residence_images[]: [FILE] (صور الإقامة - اختيارية)
- payment_method: credit_card
- notes: ملاحظات إضافية
```

#### عرض طلبات المستخدم
```http
GET /api/security-permits/my?status=all&payment_status=all&page=1
Authorization: Bearer {token}
```

#### عرض تفاصيل طلب
```http
GET /api/security-permits/{id}
Authorization: Bearer {token}
```

#### تحديث طريقة الدفع
```http
PUT /api/security-permits/{id}/payment-method
Authorization: Bearer {token}

{
  "payment_method": "digital_wallet"
}
```

#### إلغاء طلب
```http
DELETE /api/security-permits/{id}
Authorization: Bearer {token}
```

### مسارات الإدارة

#### عرض جميع الطلبات
```http
GET /api/admin/security-permits?status=all&search=&date_from=&date_to=&page=1
Authorization: Bearer {admin_token}
```

#### تحديث حالة الطلب
```http
PUT /api/admin/security-permits/{id}/status
Authorization: Bearer {admin_token}

{
  "status": "approved",
  "admin_notes": "تم الموافقة على الطلب"
}
```

#### تحديث حالة الدفع
```http
PUT /api/admin/security-permits/{id}/payment-status
Authorization: Bearer {admin_token}

{
  "payment_status": "paid",
  "payment_reference": "PAY123456"
}
```

#### الإحصائيات
```http
GET /api/admin/security-permits/statistics/overview
Authorization: Bearer {admin_token}
```

#### إدارة الإعدادات
```http
GET /api/admin/security-permits-settings
PUT /api/admin/security-permits-settings

{
  "individual_fee": 200.00
}
```

#### إدارة الدول والجنسيات
```http
GET /api/admin/countries
PUT /api/admin/countries/{id}

GET /api/admin/nationalities
PUT /api/admin/nationalities/{id}
```

## حالات الطلب

### 1. `new` - جديد
- الطلب تم إنشاؤه للتو
- يمكن للمستخدم تعديل أو إلغاء الطلب
- يتم إرسال إشعار للإدارة

### 2. `pending` - قيد المراجعة
- الطلب قيد المراجعة من الإدارة
- لا يمكن للمستخدم إلغاء الطلب
- يمكن تعديل طريقة الدفع فقط

### 3. `approved` - مقبول
- تم قبول الطلب
- يتم إرسال إشعار للمستخدم
- لا يمكن التعديل

### 4. `rejected` - مرفوض
- تم رفض الطلب
- يتم إرسال إشعار للمستخدم مع السبب
- يمكن للمستخدم تقديم طلب جديد

### 5. `expired` - منتهي الصلاحية
- انتهت صلاحية التصريح
- يتم إرسال إشعار للمستخدم

## حالات الدفع

### 1. `pending` - في انتظار الدفع
- الحالة الافتراضية عند إنشاء الطلب

### 2. `paid` - تم الدفع
- تم تأكيد الدفع من الإدارة

### 3. `failed` - فشل الدفع
- فشل في عملية الدفع

### 4. `refunded` - تم الاسترداد
- تم استرداد المبلغ للمستخدم

## نظام الإشعارات

### إشعارات المستخدم
- **طلب جديد**: تأكيد تقديم الطلب
- **قيد المراجعة**: بدء مراجعة الطلب
- **مقبول**: قبول الطلب
- **مرفوض**: رفض الطلب مع السبب
- **منتهي الصلاحية**: انتهاء صلاحية التصريح

### إشعارات الإدارة
- **طلب جديد**: إشعار بوجود طلب جديد يحتاج مراجعة

## الإعدادات القابلة للتخصيص

### إعدادات التسعير
- `individual_fee`: رسوم الفرد الواحد (افتراضي: 150 جنيه)

### إعدادات الحدود
- `max_people_per_request`: الحد الأقصى للأفراد (افتراضي: 20)

### إعدادات المعالجة
- `processing_time_days`: مدة المعالجة (افتراضي: 7 أيام)
- `auto_expire_days`: مدة انتهاء الصلاحية (افتراضي: 30 يوم)

### إعدادات المتطلبات
- `require_residence_images`: إجبارية صور الإقامة (افتراضي: false)

### إعدادات الدفع
- `allowed_payment_methods`: طرق الدفع المسموحة

### إعدادات النظام
- `system_active`: تفعيل النظام
- `maintenance_message`: رسالة الصيانة

## تشغيل النظام

### 1. تشغيل المايجريشن
```bash
php artisan migrate
```

### 2. تشغيل السيدرز
```bash
php artisan db:seed --class=CountriesSeeder
php artisan db:seed --class=NationalitiesSeeder
php artisan db:seed --class=SecurityPermitSettingsSeeder
```

### 3. اختبار النظام
```bash
# اختبار إنشاء طلب
curl -X POST http://localhost:8000/api/security-permits \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "travel_date": "2024-12-25",
    "nationality_id": 1,
    "people_count": 2,
    "country_id": 5,
    "passport_image": "test.jpg",
    "payment_method": "credit_card"
  }'
```

## الميزات المتقدمة

### 1. حساب التكلفة التلقائي
- يتم حساب المبلغ الإجمالي تلقائياً: `عدد الأفراد × رسوم الفرد`

### 2. التحقق من صحة البيانات
- تاريخ السفر يجب أن يكون في المستقبل
- عدد الأفراد محدود بحد أقصى
- التحقق من وجود الدولة والجنسية

### 3. إدارة الصور
- دعم صور متعددة للإقامة
- التحقق من صحة روابط الصور

### 4. نظام الصلاحيات
- المستخدمون يمكنهم رؤية طلباتهم فقط
- الإدارة لها صلاحية كاملة على جميع الطلبات

### 5. الإشعارات التلقائية
- إشعارات فورية عند تغيير الحالة
- إشعارات للإدارة عند الطلبات الجديدة

## استكشاف الأخطاء

### مشاكل شائعة وحلولها

#### 1. خطأ في إنشاء الطلب
```
"nationality_id field is required"
```
**الحل**: التأكد من إرسال `nationality_id` صحيح موجود في جدول `nationalities`

#### 2. خطأ في حساب التكلفة
```
"individual_fee is null"
```
**الحل**: التأكد من تشغيل `SecurityPermitSettingsSeeder`

#### 3. عدم وصول الإشعارات
**الحل**: التأكد من تكوين FCM وتسجيل device tokens

## التطوير المستقبلي

### ميزات مقترحة
1. **دفع إلكتروني**: ربط مع بوابات الدفع
2. **تتبع الطلب**: نظام تتبع مفصل
3. **تقارير متقدمة**: تقارير إحصائية شاملة
4. **تصدير البيانات**: تصدير الطلبات كـ PDF/Excel
5. **نظام التقييم**: تقييم الخدمة من المستخدمين

## الأمان والحماية

### 1. التحقق من الهوية
- جميع المسارات محمية بـ `auth:sanctum`
- التحقق من نوع المستخدم للمسارات الإدارية

### 2. التحقق من الصلاحيات
- المستخدمون يمكنهم الوصول لطلباتهم فقط
- الإدارة لها صلاحية كاملة

### 3. التحقق من صحة البيانات
- تحقق شامل من جميع البيانات المدخلة
- حماية من SQL Injection و XSS

## الاختبار

### اختبار المستخدم العادي
```bash
# 1. الحصول على بيانات النموذج
curl -H "Authorization: Bearer {user_token}" \
     http://localhost:8000/api/security-permits/form-data

# 2. إنشاء طلب جديد
curl -X POST -H "Authorization: Bearer {user_token}" \
     -H "Content-Type: application/json" \
     -d '{"travel_date":"2024-12-25","nationality_id":1,"people_count":2,"country_id":5,"passport_image":"test.jpg","payment_method":"credit_card"}' \
     http://localhost:8000/api/security-permits

# 3. عرض طلباتي
curl -H "Authorization: Bearer {user_token}" \
     http://localhost:8000/api/security-permits/my
```

### اختبار الإدارة
```bash
# 1. عرض جميع الطلبات
curl -H "Authorization: Bearer {admin_token}" \
     http://localhost:8000/api/admin/security-permits

# 2. تحديث حالة طلب
curl -X PUT -H "Authorization: Bearer {admin_token}" \
     -H "Content-Type: application/json" \
     -d '{"status":"approved","admin_notes":"تم الموافقة"}' \
     http://localhost:8000/api/admin/security-permits/1/status

# 3. عرض الإحصائيات
curl -H "Authorization: Bearer {admin_token}" \
     http://localhost:8000/api/admin/security-permits/statistics/overview
```

## الصيانة والتحديث

### تحديث الإعدادات
```php
// تحديث رسوم الفرد
SecurityPermitSetting::setSetting('individual_fee', 200.00, 'number');

// تحديث الحد الأقصى للأفراد
SecurityPermitSetting::setSetting('max_people_per_request', 25, 'number');
```

### إضافة دولة جديدة
```php
Country::create([
    'name_ar' => 'دولة جديدة',
    'name_en' => 'New Country',
    'code' => 'NC',
    'is_active' => true,
    'sort_order' => 100
]);
```

### إضافة جنسية جديدة
```php
Nationality::create([
    'name_ar' => 'جنسية جديدة',
    'name_en' => 'New Nationality',
    'code' => 'NN',
    'is_active' => true,
    'sort_order' => 100
]);
```

## المراقبة والتسجيل

### سجلات مهمة
- إنشاء الطلبات الجديدة
- تحديث حالات الطلبات
- عمليات الدفع
- الإشعارات المرسلة

### مراقبة الأداء
- عدد الطلبات اليومية
- معدل القبول/الرفض
- متوسط وقت المعالجة
- إجمالي الإيرادات

---

## الدعم الفني

للحصول على المساعدة أو الإبلاغ عن مشاكل، يرجى التواصل مع فريق التطوير.

**تاريخ آخر تحديث**: يناير 2024  
**الإصدار**: 2.0