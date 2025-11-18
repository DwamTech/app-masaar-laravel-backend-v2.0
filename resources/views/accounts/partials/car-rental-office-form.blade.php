<div class="car-rental-office-form">
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

    <!-- تفاصيل مكتب التأجير -->
    <div class="col-12"><h6 class="mb-0 text-muted">تفاصيل مكتب التأجير</h6><hr class="mt-2 mb-3"></div>
    <div class="col-md-6">
      <label class="form-label">اسم المكتب</label>
      <input type="text" name="office_name" class="form-control" placeholder="اسم مكتب التأجير" required>
    </div>

    <!-- المستندات المطلوبة -->
    <div class="col-12"><h6 class="mb-0 text-muted">المستندات المطلوبة</h6><hr class="mt-2 mb-3"></div>
    <div class="col-md-4">
      <label class="form-label">شعار المكتب</label>
      <div id="preview_wrapper_logo_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_logo_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('logo_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="logo_image" onchange="uploadImage(event, 'logo_image')">
      <input type="hidden" name="logo_image">
    </div>
    <div class="col-md-4">
      <label class="form-label">السجل التجاري - أمامية</label>
      <div id="preview_wrapper_commercial_register_front_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_commercial_register_front_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('commercial_register_front_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="commercial_register_front_image" onchange="uploadImage(event, 'commercial_register_front_image')">
      <input type="hidden" name="commercial_register_front_image">
    </div>
    <div class="col-md-4">
      <label class="form-label">السجل التجاري - خلفية</label>
      <div id="preview_wrapper_commercial_register_back_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_commercial_register_back_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('commercial_register_back_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="commercial_register_back_image" onchange="uploadImage(event, 'commercial_register_back_image')">
      <input type="hidden" name="commercial_register_back_image">
    </div>

    <!-- طرق الدفع وخيارات التأجير -->
    <div class="col-md-6">
      <label class="form-label">طرق الدفع</label>
      <div class="d-flex flex-wrap gap-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="cash" id="co_pm_cash">
          <label class="form-check-label" for="co_pm_cash">نقدي</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="card" id="co_pm_card">
          <label class="form-check-label" for="co_pm_card">بطاقة</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="wallet" id="co_pm_wallet">
          <label class="form-check-label" for="co_pm_wallet">محفظة</label>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <label class="form-label">خيارات التأجير</label>
      <div class="d-flex flex-wrap gap-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="hourly" id="co_ro_hourly">
          <label class="form-check-label" for="co_ro_hourly">بالساعة</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="daily" id="co_ro_daily">
          <label class="form-check-label" for="co_ro_daily">يومي</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="monthly" id="co_ro_monthly">
          <label class="form-check-label" for="co_ro_monthly">شهري</label>
        </div>
      </div>
    </div>

    <!-- التسعير والسياسات -->
    <div class="col-md-4">
      <label class="form-label">التكلفة لكل كم</label>
      <input type="number" name="cost_per_km" class="form-control" min="0" step="0.1" placeholder="مثال: 3.5">
    </div>
    <div class="col-md-4">
      <label class="form-label">تكلفة السائق اليومية</label>
      <input type="number" name="daily_driver_cost" class="form-control" min="0" step="0.1" placeholder="مثال: 250">
    </div>
    <div class="col-md-4">
      <label class="form-label">أقصى كم/يوم</label>
      <input type="number" name="max_km_per_day" class="form-control" min="1" step="1" placeholder="مثال: 200">
    </div>

    <div class="col-md-6">
      <div class="form-check form-switch mt-3">
        <input class="form-check-input" type="checkbox" id="co_available_delivery" name="is_available_for_delivery" value="1">
        <label class="form-check-label" for="co_available_delivery">يدعم التوصيل؟</label>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-check form-switch mt-3">
        <input class="form-check-input" type="checkbox" id="co_available_rent" name="is_available_for_rent" value="1">
        <label class="form-check-label" for="co_available_rent">متاح للتأجير؟</label>
      </div>
    </div>

    <input type="hidden" name="user_type" value="car_rental_office">
  </div>
</div>