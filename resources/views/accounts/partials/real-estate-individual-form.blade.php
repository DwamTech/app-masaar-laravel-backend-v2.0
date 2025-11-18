<div class="real-estate-individual-form">
  <div class="row g-3">
    <!-- البيانات الأساسية -->
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

    <!-- تفاصيل السمسار -->
    <div class="col-12"><h6 class="mb-0 text-muted">تفاصيل السمسار</h6><hr class="mt-2 mb-3"></div>
    <div class="col-md-6">
      <label class="form-label">اسم السمسار</label>
      <input type="text" name="agent_name" class="form-control" placeholder="اسم السمسار" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">صورة شخصية</label>
      <div id="preview_wrapper_profile_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_profile_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('profile_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="profile_image" onchange="uploadImage(event, 'profile_image')">
      <input type="hidden" name="profile_image">
    </div>

    <div class="col-md-6">
      <label class="form-label">بطاقة السمسار - أمامية</label>
      <div id="preview_wrapper_agent_id_front_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_agent_id_front_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('agent_id_front_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="agent_id_front_image" onchange="uploadImage(event, 'agent_id_front_image')">
      <input type="hidden" name="agent_id_front_image">
    </div>
    <div class="col-md-6">
      <label class="form-label">بطاقة السمسار - خلفية</label>
      <div id="preview_wrapper_agent_id_back_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_agent_id_back_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('agent_id_back_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="agent_id_back_image" onchange="uploadImage(event, 'agent_id_back_image')">
      <input type="hidden" name="agent_id_back_image">
    </div>

    <div class="col-md-6">
      <label class="form-label">بطاقة ضريبية - أمامية</label>
      <div id="preview_wrapper_tax_card_front_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_tax_card_front_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('tax_card_front_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="tax_card_front_image" onchange="uploadImage(event, 'tax_card_front_image')">
      <input type="hidden" name="tax_card_front_image">
    </div>
    <div class="col-md-6">
      <label class="form-label">بطاقة ضريبية - خلفية</label>
      <div id="preview_wrapper_tax_card_back_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_tax_card_back_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('tax_card_back_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="tax_card_back_image" onchange="uploadImage(event, 'tax_card_back_image')">
      <input type="hidden" name="tax_card_back_image">
    </div>

    <!-- موقع السمسار (اختياري) -->
    <div class="col-12">
      <label class="form-label">موقع السمسار (اختياري)</label>
      <div class="input-group">
        <input type="text" name="agent_address" id="individualAddressInput" class="form-control" placeholder="انقر لاختيار العنوان من الخريطة" readonly>
        <button type="button" class="btn btn-outline-secondary" id="openIndividualMapBtn">اختيار من الخريطة</button>
      </div>
      <small class="text-muted">استخدم البحث لتحديد العنوان بدقة عبر Google. الحقل اختياري.</small>
      <input type="hidden" name="agent_lat">
      <input type="hidden" name="agent_lng">
    </div>

    <input type="hidden" name="user_type" value="real_estate_individual">
  </div>

  <!-- نافذة اختيار الموقع من خرائط جوجل للسمسار -->
  <div id="individualMapPicker" style="position:fixed; inset:0; background:rgba(0,0,0,0.6); display:none; align-items:center; justify-content:center; z-index:2000;">
    <div style="background:#fff; width:90%; max-width:900px; border-radius:8px; overflow:hidden; box-shadow:0 0 20px rgba(0,0,0,0.3);">
      <div style="display:flex; align-items:center; gap:8px; padding:8px 12px; border-bottom:1px solid #eee;">
        <input id="individualMapSearch" type="text" class="form-control" placeholder="ابحث عن موقع، عنوان أو اسم" />
        <button id="closeIndividualMap" type="button" class="btn btn-outline-secondary">إغلاق</button>
      </div>
      <div id="individualMap" style="width:100%; height:500px;"></div>
      <div id="individualMapsKeyNote" style="padding:8px 12px; display:none;" class="text-danger">مفتاح خرائط جوجل غير مضبوط. أضف GOOGLE_MAPS_API_KEY في ملف .env.</div>
    </div>
    <span style="position:absolute; top:16px; left:16px; color:#fff; font-size:14px; opacity:.8;">اضغط خارج النافذة أو زر Esc للإغلاق</span>
  </div>
</div>