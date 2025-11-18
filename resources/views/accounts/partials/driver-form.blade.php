<div class="driver-form">
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

    <!-- تفاصيل السائق -->
    <div class="col-12"><h6 class="mb-0 text-muted">تفاصيل السائق</h6><hr class="mt-2 mb-3"></div>

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
      <label class="form-label">طرق الدفع</label>
      <div class="d-flex flex-wrap gap-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="cash" id="pm_cash">
          <label class="form-check-label" for="pm_cash">نقدي</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="card" id="pm_card">
          <label class="form-check-label" for="pm_card">بطاقة</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="wallet" id="pm_wallet">
          <label class="form-check-label" for="pm_wallet">محفظة</label>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <label class="form-label">خيارات التأجير</label>
      <div class="d-flex flex-wrap gap-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="hourly" id="ro_hourly">
          <label class="form-check-label" for="ro_hourly">بالساعة</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="daily" id="ro_daily">
          <label class="form-check-label" for="ro_daily">يومي</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="monthly" id="ro_monthly">
          <label class="form-check-label" for="ro_monthly">شهري</label>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <label class="form-label">التكلفة لكل كم</label>
      <input type="number" name="cost_per_km" class="form-control" min="0" step="0.1" placeholder="مثال: 3.5" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">تكلفة السائق اليومية</label>
      <input type="number" name="daily_driver_cost" class="form-control" min="0" step="0.1" placeholder="مثال: 250" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">أقصى كم/يوم</label>
      <input type="number" name="max_km_per_day" class="form-control" min="1" step="1" placeholder="مثال: 200" required>
    </div>

    <input type="hidden" name="user_type" value="driver">
  </div>
</div>