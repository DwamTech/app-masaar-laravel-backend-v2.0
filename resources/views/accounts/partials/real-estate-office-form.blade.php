<div class="real-estate-office-form">
  <div class="row g-3">
    <!-- بيانات المستخدم الأساسية -->
    <div class="col-12"><h6 class="mb-0 text-muted">البيانات الأساسية</h6><hr class="mt-2 mb-3"></div>
    <div class="col-md-6">
      <label class="form-label">الاسم الكامل</label>
      <input type="text" name="name" class="form-control" placeholder="أدخل الاسم" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">البريد الإلكتروني</label>
      <input type="email" name="email" class="form-control" placeholder="example@domain.com" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">كلمة المرور</label>
      <input type="password" name="password" class="form-control" placeholder="••••••" minlength="6" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">رقم الهاتف</label>
      <input type="text" name="phone" class="form-control" placeholder="مثال: +201234567890" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">المحافظة</label>
      <select name="governorate" class="form-select" required>
        <option value="" selected disabled>اختر المحافظة</option>
        <option>القاهرة</option>
        <option>الجيزة</option>
        <option>القليوبية</option>
        <option>الإسكندرية</option>
        <option>البحيرة</option>
        <option>مطروح</option>
        <option>كفر الشيخ</option>
        <option>الغربية</option>
        <option>الشرقية</option>
        <option>الدقهلية</option>
        <option>المنوفية</option>
        <option>دمياط</option>
        <option>بورسعيد</option>
        <option>الإسماعيلية</option>
        <option>السويس</option>
        <option>شمال سيناء</option>
        <option>جنوب سيناء</option>
        <option>بني سويف</option>
        <option>الفيوم</option>
        <option>المنيا</option>
        <option>أسيوط</option>
        <option>سوهاج</option>
        <option>قنا</option>
        <option>الأقصر</option>
        <option>أسوان</option>
        <option>البحر الأحمر</option>
        <option>الوادي الجديد</option>
      </select>
    </div>

    <!-- تفاصيل مكتب العقارات -->
    <div class="col-12"><h6 class="mb-0 text-muted">تفاصيل مكتب العقارات</h6><hr class="mt-2 mb-3"></div>
    <div class="col-md-6">
      <label class="form-label">اسم المكتب</label>
      <input type="text" name="office_name" class="form-control" placeholder="اسم مكتب العقارات" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">هاتف المكتب</label>
      <input type="text" name="office_phone" class="form-control" placeholder="مثال: 02xxxxxxx" required>
    </div>
    <div class="col-12">
      <label class="form-label">عنوان المكتب</label>
      <div class="input-group">
        <input type="text" name="office_address" id="officeAddressInput" class="form-control" placeholder="انقر لاختيار العنوان من الخريطة" required readonly>
        <button type="button" class="btn btn-outline-secondary" id="openOfficeMapBtn">اختيار من الخريطة</button>
      </div>
      <small class="text-muted">استخدم البحث لتحديد العنوان بدقة عبر Google.</small>
    </div>

    <!-- صور ومرفقات مطلوبة -->
    <div class="col-12"><h6 class="mb-0 text-muted">المستندات المطلوبة</h6><hr class="mt-2 mb-3"></div>
    <div class="col-md-6">
      <label class="form-label">شعار المكتب</label>
      <div id="preview_wrapper_logo_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_logo_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('logo_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="logo_image" onchange="uploadImage(event, 'logo_image')">
      <input type="hidden" name="logo_image">
    </div>
    <div class="col-md-6">
      <label class="form-label">صورة المكتب</label>
      <div id="preview_wrapper_office_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_office_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('office_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="office_image" onchange="uploadImage(event, 'office_image')">
      <input type="hidden" name="office_image">
    </div>

    <div class="col-md-6">
      <label class="form-label">بطاقة المالك - أمامية</label>
      <div id="preview_wrapper_owner_id_front_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_owner_id_front_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('owner_id_front_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="owner_id_front_image" onchange="uploadImage(event, 'owner_id_front_image')">
      <input type="hidden" name="owner_id_front_image">
    </div>
    <div class="col-md-6">
      <label class="form-label">بطاقة المالك - خلفية</label>
      <div id="preview_wrapper_owner_id_back_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_owner_id_back_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('owner_id_back_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="owner_id_back_image" onchange="uploadImage(event, 'owner_id_back_image')">
      <input type="hidden" name="owner_id_back_image">
    </div>

    <div class="col-md-6">
      <label class="form-label">السجل التجاري - أمامية</label>
      <div id="preview_wrapper_commercial_register_front_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_commercial_register_front_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('commercial_register_front_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="commercial_register_front_image" onchange="uploadImage(event, 'commercial_register_front_image')">
      <input type="hidden" name="commercial_register_front_image">
    </div>
    <div class="col-md-6">
      <label class="form-label">السجل التجاري - خلفية</label>
      <div id="preview_wrapper_commercial_register_back_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_commercial_register_back_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('commercial_register_back_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="commercial_register_back_image" onchange="uploadImage(event, 'commercial_register_back_image')">
      <input type="hidden" name="commercial_register_back_image">
    </div>

    <div class="col-12">
      <style>
        /* تحسين وضوح سويتش ضريبة القيمة المضافة */
        .vat-switch { background: #ffffff; border-color: #dee2e6; }
        .vat-switch .form-label { color: #212529; }
        .vat-switch .switch-wrapper { display: flex; align-items: center; gap: .5rem; }
        .vat-switch .form-check-input { width: 3rem; height: 1.6rem; cursor: pointer; }
        .vat-switch .form-check-input:focus { box-shadow: 0 0 0 .25rem rgba(252,135,0,.25); }
        .vat-switch .form-check-input { background-color: #cfd4da; border-color: #adb5bd; }
        .vat-switch .form-check-input:checked { background-color: #FC8700; border-color: #FC8700; }
        .vat-switch .switch-label { font-weight: 600; font-size: .9rem; }
        .vat-switch .switch-label.on { color: #FC8700; display: none; }
        .vat-switch .switch-label.off { color: #6c757d; }
        .vat-switch .form-check-input:checked ~ .switch-label.on { display: inline; }
        .vat-switch .form-check-input:checked ~ .switch-label.off { display: none; }

        /* إصلاح تداخل Bootstrap في RTL: إزالة الهوامش السالبة والتعويم */
        .vat-switch .form-switch { padding: 0 !important; }
        .vat-switch .form-switch .form-check-input { float: none; margin: 0 !important; }
        .vat-switch .form-switch .form-check-input { vertical-align: middle; }
      </style>
      <div class="vat-switch p-3 border rounded">
        <div class="d-flex align-items-center justify-content-between">
          <div class="me-3">
            <label id="taxEnabledLabel" class="form-label fw-bold mb-0" for="taxEnabledCheck">خاضع لضريبة القيمة المضافة</label>
            <div class="text-muted small mt-1">قم بتفعيل هذا الخيار إذا كان المكتب مسجلًا بضريبة القيمة المضافة.</div>
          </div>
          <div class="switch-wrapper form-check form-switch m-0">
            <input class="form-check-input" type="checkbox" id="taxEnabledCheck" name="tax_enabled" value="1">
            <span class="switch-label off" aria-hidden="true">غير مفعل</span>
            <span class="switch-label on" aria-hidden="true">مفعل</span>
          </div>
        </div>
      </div>
    </div>

    <input type="hidden" name="user_type" value="real_estate_office">
  </div>

  <!-- نافذة اختيار الموقع من خرائط جوجل لمكتب العقارات -->
  <div id="officeMapPicker" style="position:fixed; inset:0; background:rgba(0,0,0,0.6); display:none; align-items:center; justify-content:center; z-index:2000;">
    <div style="background:#fff; width:90%; max-width:900px; border-radius:8px; overflow:hidden; box-shadow:0 0 20px rgba(0,0,0,0.3);">
      <div style="display:flex; align-items:center; gap:8px; padding:8px 12px; border-bottom:1px solid #eee;">
        <input id="officeMapSearch" type="text" class="form-control" placeholder="ابحث عن موقع، عنوان أو اسم" />
        <button id="closeOfficeMap" type="button" class="btn btn-outline-secondary">إغلاق</button>
      </div>
      <div id="officeMap" style="width:100%; height:500px;"></div>
      <div id="officeMapsKeyNote" style="padding:8px 12px; display:none;" class="text-danger">مفتاح خرائط جوجل غير مضبوط. أضف GOOGLE_MAPS_API_KEY في ملف .env.</div>
    </div>
    <span style="position:absolute; top:16px; left:16px; color:#fff; font-size:14px; opacity:.8;">اضغط خارج النافذة أو زر Esc للإغلاق</span>
  </div>
</div>