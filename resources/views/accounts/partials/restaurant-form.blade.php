<div class="restaurant-form">
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

    <!-- تفاصيل المطعم -->
    <div class="col-12"><h6 class="mb-0 text-muted">تفاصيل المطعم</h6><hr class="mt-2 mb-3"></div>

    <div class="col-md-6">
      <label class="form-label">اسم المطعم</label>
      <input type="text" name="restaurant_name" class="form-control" placeholder="مثال: مطعم مسار" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">صورة الملف الشخصي</label>
      <div id="preview_wrapper_profile_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_profile_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('profile_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="profile_image" onchange="uploadImage(event, 'profile_image')">
      <input type="hidden" name="profile_image">
    </div>

    <div class="col-md-6">
      <label class="form-label">شعار المطعم</label>
      <div id="preview_wrapper_logo_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_logo_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('logo_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="logo_image" onchange="uploadImage(event, 'logo_image')">
      <input type="hidden" name="logo_image">
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

    <div class="col-md-6">
      <label class="form-label">هوية المالك - أمامية</label>
      <div id="preview_wrapper_owner_id_front_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_owner_id_front_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('owner_id_front_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="owner_id_front_image" onchange="uploadImage(event, 'owner_id_front_image')">
      <input type="hidden" name="owner_id_front_image">
    </div>

    <div class="col-md-6">
      <label class="form-label">هوية المالك - خلفية</label>
      <div id="preview_wrapper_owner_id_back_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_owner_id_back_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('owner_id_back_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="owner_id_back_image" onchange="uploadImage(event, 'owner_id_back_image')">
      <input type="hidden" name="owner_id_back_image">
    </div>

    <div class="col-md-6">
      <label class="form-label">رخصة المطعم - أمامية</label>
      <div id="preview_wrapper_license_front_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_license_front_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('license_front_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="license_front_image" onchange="uploadImage(event, 'license_front_image')">
      <input type="hidden" name="license_front_image">
    </div>

    <div class="col-md-6">
      <label class="form-label">رخصة المطعم - خلفية</label>
      <div id="preview_wrapper_license_back_image" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_license_back_image" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('license_back_image')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="license_back_image" onchange="uploadImage(event, 'license_back_image')">
      <input type="hidden" name="license_back_image">
    </div>

    <!-- VAT -->
    <div class="col-12"><h6 class="mb-0 text-muted">الضريبة VAT</h6><hr class="mt-2 mb-3"></div>
    <div class="col-md-4 d-flex align-items-center">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="vat_included" id="vat_included">
        <label class="form-check-label" for="vat_included">تشمل الأسعار ضريبة القيمة المضافة</label>
      </div>
    </div>
    <div class="col-md-4">
      <label class="form-label">شهادة ضريبة - أمامية</label>
      <div id="preview_wrapper_vat_image_front" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_vat_image_front" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('vat_image_front')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="vat_image_front" onchange="uploadImage(event, 'vat_image_front')">
      <input type="hidden" name="vat_image_front">
    </div>
    <div class="col-md-4">
      <label class="form-label">شهادة ضريبة - خلفية</label>
      <div id="preview_wrapper_vat_image_back" class="image-preview-wrapper mb-2" style="display:none; position:relative; width:max-content;">
        <img id="img_preview_vat_image_back" src="" style="max-width:80px;max-height:60px;" class="rounded border">
        <button type="button" class="btn btn-sm btn-danger preview-remove" onclick="removeImage('vat_image_back')" title="حذف">✕</button>
      </div>
      <input type="file" class="form-control" data-field="vat_image_back" onchange="uploadImage(event, 'vat_image_back')">
      <input type="hidden" name="vat_image_back">
    </div>

    <!-- خيارات الخدمة -->
    <div class="col-12"><h6 class="mb-0 text-muted">خيارات الخدمة</h6><hr class="mt-2 mb-3"></div>
    <div class="col-md-4 d-flex align-items-center">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="delivery_available" id="delivery_available">
        <label class="form-check-label" for="delivery_available">يدعم التوصيل</label>
      </div>
    </div>
    <div class="col-md-4">
      <label class="form-label">تكلفة التوصيل لكل كم</label>
      <input type="number" name="delivery_cost_per_km" class="form-control" min="0" step="0.1" placeholder="مثال: 5.5">
    </div>
    <div class="col-md-4 d-flex align-items-center">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="table_reservation_available" id="table_reservation_available">
        <label class="form-check-label" for="table_reservation_available">يدعم حجز الطاولات</label>
      </div>
    </div>
    <div class="col-md-4">
      <label class="form-label">الحد الأقصى للأفراد في الحجز</label>
      <input type="number" name="max_people_per_reservation" class="form-control" min="1" step="1" placeholder="مثال: 6">
    </div>
    <div class="col-md-8">
      <label class="form-label">ملاحظات الحجز</label>
      <input type="text" name="reservation_notes" class="form-control" placeholder="ملاحظات عامة للحجز">
    </div>
    <div class="col-md-4 d-flex align-items-center">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="deposit_required" id="deposit_required">
        <label class="form-check-label" for="deposit_required">يتطلب عربون</label>
      </div>
    </div>
    <div class="col-md-4">
      <label class="form-label">قيمة العربون</label>
      <input type="number" name="deposit_amount" class="form-control" min="0" step="0.1" placeholder="مثال: 100">
    </div>

    <!-- بيانات أخرى -->
    <div class="col-12"><h6 class="mb-0 text-muted">بيانات إضافية</h6><hr class="mt-2 mb-3"></div>
    <div class="col-12">
      <label class="form-label">أنواع المطبخ</label>
      <div class="row g-2" id="cuisineOptionsContainer"></div>
      <div class="mt-2" id="selectedCuisineTags"></div>
      <input type="hidden" name="cuisine_types">
    </div>
    <div class="col-12 mt-3">
      <label class="form-label">ساعات العمل</label>
      <div class="row g-2 align-items-end">
        <div class="col-md-3">
          <label class="form-label">اليوم</label>
          <select class="form-select" id="workDaySelect">
            <option value="sat">السبت</option>
            <option value="sun">الأحد</option>
            <option value="mon">الاثنين</option>
            <option value="tue">الثلاثاء</option>
            <option value="wed">الأربعاء</option>
            <option value="thu">الخميس</option>
            <option value="fri">الجمعة</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">من</label>
          <input type="time" class="form-control" id="workOpenTime" value="09:00">
        </div>
        <div class="col-md-3">
          <label class="form-label">إلى</label>
          <input type="time" class="form-control" id="workCloseTime" value="23:00">
        </div>
        <div class="col-md-3">
          <button type="button" class="btn btn-outline-primary w-100" id="addWorkHourBtn">
            <i class="bi bi-plus-circle me-1"></i> إضافة يوم عمل
          </button>
        </div>
      </div>
      <div class="mt-2" id="workHoursList"></div>
      <input type="hidden" name="working_hours">
    </div>
    <div class="col-12 mt-3">
      <label class="form-label">الفروع</label>
      <div class="row g-2 align-items-end">
        <div class="col-md-4">
          <label class="form-label">المحافظة</label>
          <select class="form-select" id="branchGovernorateSelect">
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
        <div class="col-md-4">
          <label class="form-label">المنطقة</label>
          <input type="text" class="form-control" id="branchAreaInput" placeholder="مثال: مدينة نصر">
        </div>
        <div class="col-md-4">
          <button type="button" class="btn btn-outline-primary w-100" id="addBranchBtn">
            <i class="bi bi-plus-circle me-1"></i> إضافة فرع
          </button>
        </div>
      </div>
      <div class="mt-2" id="branchesList"></div>
      <input type="hidden" name="branches">
    </div>

    <input type="hidden" name="user_type" value="restaurant">
  </div>
</div>
<script>
// ===== منطق التحقق وإرسال نموذج المطعم (مضمّن داخل الجزئي لضمان توفره) =====
// حالة واجهة المطعم
let cuisineTypesState = [];
let workingHoursState = [];
let branchesState = [];

const CUISINE_OPTIONS = [
  { value: 'egyptian', label: 'مصري' },
  { value: 'grill', label: 'مشويات' },
  { value: 'seafood', label: 'مأكولات بحرية' },
  { value: 'italian', label: 'إيطالي' },
  { value: 'pizza', label: 'بيتزا' },
  { value: 'burger', label: 'برجر' },
  { value: 'sandwiches', label: 'ساندويتشات' },
  { value: 'indian', label: 'هندي' },
  { value: 'chinese', label: 'صيني' },
  { value: 'syrian', label: 'سوري' },
  { value: 'turkish', label: 'تركي' },
  { value: 'lebanese', label: 'لبناني' },
  { value: 'desserts', label: 'حلويات' },
  { value: 'coffee', label: 'قهوة' },
  { value: 'shawarma', label: 'شاورما' },
  { value: 'healthy', label: 'صحي' }
];

function renderCuisineOptions() {
  const container = document.getElementById('cuisineOptionsContainer');
  if (!container) return;
  container.innerHTML = '';
  CUISINE_OPTIONS.forEach((opt, idx) => {
    const col = document.createElement('div');
    col.className = 'col-md-3';
    col.innerHTML = `
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="cuisine_${idx}" data-value="${opt.value}">
        <label class="form-check-label" for="cuisine_${idx}">${opt.label}</label>
      </div>
    `;
    container.appendChild(col);
  });
  container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
    cb.addEventListener('change', () => {
      const val = cb.getAttribute('data-value');
      if (cb.checked) {
        if (!cuisineTypesState.includes(val)) cuisineTypesState.push(val);
      } else {
        cuisineTypesState = cuisineTypesState.filter(x => x !== val);
      }
      updateCuisineHidden();
      renderCuisineTags();
      validateRestaurantForm();
    });
  });
}

function renderCuisineTags() {
  const tagsEl = document.getElementById('selectedCuisineTags');
  if (!tagsEl) return;
  tagsEl.innerHTML = cuisineTypesState.map(val => {
    const label = (CUISINE_OPTIONS.find(o => o.value === val)?.label) || val;
    return `<span class="badge bg-secondary me-1 mb-1">${label}</span>`;
  }).join('');
}

function updateCuisineHidden() {
  const form = document.getElementById('addRestaurantForm');
  const hidden = form?.querySelector('[name="cuisine_types"]');
  if (hidden) hidden.value = JSON.stringify(cuisineTypesState);
}

function addWorkHourEntry() {
  const day = document.getElementById('workDaySelect')?.value;
  const open = document.getElementById('workOpenTime')?.value;
  const close = document.getElementById('workCloseTime')?.value;
  if (!day || !open || !close) { alert('يرجى تحديد اليوم وساعات العمل.'); return; }
  const exists = workingHoursState.some(w => w.day === day);
  if (exists) {
    if (!confirm('يوجد يوم عمل بنفس اليوم. هل تريد استبداله؟')) return;
    workingHoursState = workingHoursState.filter(w => w.day !== day);
  }
  workingHoursState.push({ day, open, close });
  updateWorkingHoursHidden();
  renderWorkHoursList();
  validateRestaurantForm();
}

function renderWorkHoursList() {
  const listEl = document.getElementById('workHoursList');
  if (!listEl) return;
  const dayLabel = d => ({ sat: 'السبت', sun: 'الأحد', mon: 'الاثنين', tue: 'الثلاثاء', wed: 'الأربعاء', thu: 'الخميس', fri: 'الجمعة' })[d] || d;
  listEl.innerHTML = workingHoursState.map((w, idx) => `
    <div class="d-flex align-items-center justify-content-between border rounded p-2 mb-1">
      <div>${dayLabel(w.day)}: ${w.open} → ${w.close}</div>
      <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeWorkHour(${idx})"><i class="bi bi-x"></i></button>
    </div>
  `).join('');
}

function removeWorkHour(idx) {
  workingHoursState.splice(idx, 1);
  updateWorkingHoursHidden();
  renderWorkHoursList();
  validateRestaurantForm();
}

function updateWorkingHoursHidden() {
  const form = document.getElementById('addRestaurantForm');
  const hidden = form?.querySelector('[name="working_hours"]');
  if (hidden) hidden.value = JSON.stringify(workingHoursState);
}

function addBranchEntry() {
  const gov = document.getElementById('branchGovernorateSelect')?.value;
  const area = document.getElementById('branchAreaInput')?.value?.trim();
  if (!gov) { alert('يرجى اختيار المحافظة.'); return; }
  if (!area) { alert('يرجى إدخال المنطقة.'); return; }
  branchesState.push({ governorate: gov, area });
  updateBranchesHidden();
  renderBranchesList();
  document.getElementById('branchAreaInput').value = '';
  validateRestaurantForm();
}

function renderBranchesList() {
  const listEl = document.getElementById('branchesList');
  if (!listEl) return;
  listEl.innerHTML = branchesState.map((b, idx) => `
    <div class="d-inline-flex align-items-center gap-2 border rounded-pill px-3 py-1 me-2 mb-2">
      <span><i class="bi bi-geo-alt me-1"></i>${b.governorate} - ${b.area}</span>
      <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBranch(${idx})"><i class="bi bi-x"></i></button>
    </div>
  `).join('');
}

function removeBranch(idx) {
  branchesState.splice(idx, 1);
  updateBranchesHidden();
  renderBranchesList();
  validateRestaurantForm();
}

function updateBranchesHidden() {
  const form = document.getElementById('addRestaurantForm');
  const hidden = form?.querySelector('[name="branches"]');
  if (hidden) hidden.value = JSON.stringify(branchesState);
}
function attachRestaurantFormListeners() {
  const form = document.getElementById('addRestaurantForm');
  if (!form) return;
  const inputs = form.querySelectorAll('input, select, textarea');
  inputs.forEach(el => {
    el.addEventListener('input', validateRestaurantForm);
    el.addEventListener('change', validateRestaurantForm);
  });
  // تفعيل واجهات الأنواع، ساعات العمل، الفروع
  renderCuisineOptions();
  renderCuisineTags();
  document.getElementById('addWorkHourBtn')?.addEventListener('click', addWorkHourEntry);
  renderWorkHoursList();
  document.getElementById('addBranchBtn')?.addEventListener('click', addBranchEntry);
  renderBranchesList();
}

function validateRestaurantForm() {
  const form = document.getElementById('addRestaurantForm');
  const submitBtn = document.getElementById('addUserSubmitBtn');
  if (!form || !submitBtn) return;
  const name = form.querySelector('[name="name"]')?.value?.trim();
  const email = form.querySelector('[name="email"]')?.value?.trim();
  const password = form.querySelector('[name="password"]')?.value || '';
  const phone = form.querySelector('[name="phone"]')?.value?.trim();
  const governorate = form.querySelector('[name="governorate"]')?.value?.trim();
  const restaurant_name = form.querySelector('[name="restaurant_name"]')?.value?.trim();
  const baseValid = !!(name && email && email.includes('@') && password.length >= 6 && phone && governorate && restaurant_name);
  const cuisinesOk = cuisineTypesState.length > 0;
  const hoursOk = workingHoursState.length > 0;
  const branchesOk = branchesState.length > 0;
  submitBtn.disabled = !(baseValid && cuisinesOk && hoursOk && branchesOk);
}

async function submitRestaurantForm() {
  const form = document.getElementById('addRestaurantForm');
  if (!form) return;
  const token = localStorage.getItem('token');
  if (!token) { alert('يرجى تسجيل الدخول أولًا.'); window.location.href = '/login'; return; }

  const requiredDocs = [
    'logo_image','commercial_register_front_image','commercial_register_back_image',
    'owner_id_front_image','owner_id_back_image','license_front_image','license_back_image'
  ];
  const missingDocs = [];
  requiredDocs.forEach(f => { const v = form.querySelector(`[name="${f}"]`)?.value?.trim(); if (!v) missingDocs.push(f); });
  const vatIncluded = !!form.querySelector('[name="vat_included"]')?.checked;
  if (vatIncluded) {
    ['vat_image_front','vat_image_back'].forEach(f => { const v = form.querySelector(`[name="${f}"]`)?.value?.trim(); if (!v) missingDocs.push(f); });
  }
  if (missingDocs.length) {
    const labels = {
      'logo_image': 'شعار المطعم',
      'commercial_register_front_image': 'السجل التجاري (أمامي)',
      'commercial_register_back_image': 'السجل التجاري (خلفي)',
      'owner_id_front_image': 'هوية المالك (أمامي)',
      'owner_id_back_image': 'هوية المالك (خلفي)',
      'license_front_image': 'رخصة المطعم (أمامي)',
      'license_back_image': 'رخصة المطعم (خلفي)',
      'vat_image_front': 'شهادة VAT (أمامي)',
      'vat_image_back': 'شهادة VAT (خلفي)'
    };
    alert('من فضلك قم برفع المستندات التالية: ' + missingDocs.map(k => labels[k] || k).join('، '));
    return;
  }

  // جلب القيم من الواجهات الديناميكية
  const cuisine_types = cuisineTypesState.slice();
  const working_hours = workingHoursState.slice();
  const branches = branchesState.slice();
  if (!cuisine_types.length) { alert('يرجى اختيار نوع مطبخ واحد على الأقل.'); return; }
  if (!working_hours.length) { alert('يرجى إضافة يوم عمل واحد على الأقل.'); return; }
  if (!branches.length) { alert('يرجى إضافة فرع واحد على الأقل.'); return; }

  const payload = {
    name: form.querySelector('[name="name"]').value.trim(),
    email: form.querySelector('[name="email"]').value.trim(),
    password: form.querySelector('[name="password"]').value,
    phone: form.querySelector('[name="phone"]').value.trim(),
    governorate: form.querySelector('[name="governorate"]').value.trim(),
    user_type: 'restaurant',

    restaurant_name: form.querySelector('[name="restaurant_name"]').value.trim(),
    profile_image: form.querySelector('[name="profile_image"]').value.trim(),
    logo_image: form.querySelector('[name="logo_image"]').value.trim(),
    commercial_register_front_image: form.querySelector('[name="commercial_register_front_image"]').value.trim(),
    commercial_register_back_image: form.querySelector('[name="commercial_register_back_image"]').value.trim(),
    owner_id_front_image: form.querySelector('[name="owner_id_front_image"]').value.trim(),
    owner_id_back_image: form.querySelector('[name="owner_id_back_image"]').value.trim(),
    license_front_image: form.querySelector('[name="license_front_image"]').value.trim(),
    license_back_image: form.querySelector('[name="license_back_image"]').value.trim(),

    vat_included: vatIncluded,
    vat_image_front: form.querySelector('[name="vat_image_front"]')?.value?.trim() || undefined,
    vat_image_back: form.querySelector('[name="vat_image_back"]')?.value?.trim() || undefined,

    delivery_available: !!form.querySelector('[name="delivery_available"]')?.checked,
    delivery_cost_per_km: parseFloat(form.querySelector('[name="delivery_cost_per_km"]')?.value || '0'),
    table_reservation_available: !!form.querySelector('[name="table_reservation_available"]')?.checked,
    max_people_per_reservation: parseInt(form.querySelector('[name="max_people_per_reservation"]')?.value || '0', 10),
    reservation_notes: form.querySelector('[name="reservation_notes"]')?.value?.trim() || '',
    deposit_required: !!form.querySelector('[name="deposit_required"]')?.checked,
    deposit_amount: parseFloat(form.querySelector('[name="deposit_amount"]')?.value || '0'),

    cuisine_types,
    working_hours,
    branches
  };

  try {
    const res = await fetch('/api/users', {
      method: 'POST',
      headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(payload)
    });
    if (res.ok) {
      if (typeof showToast === 'function') { showToast('تم إضافة المطعم بنجاح', 'success'); }
      else { alert('تم إضافة المطعم بنجاح'); }
      const modalInstance = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
      if (modalInstance) modalInstance.hide();
      form.reset();
      const submitBtn = document.getElementById('addUserSubmitBtn');
      if (submitBtn) submitBtn.disabled = true;
      await fetchUsers();
    } else {
      let msg = 'فشل الإضافة';
      try { const data = await res.json(); msg = data.message || msg; } catch {}
      if (typeof showToast === 'function') { showToast(msg, 'danger'); } else { alert(msg); }
    }
  } catch (err) {
    console.error('Add restaurant error:', err);
    alert('حدث خطأ غير متوقع أثناء الإضافة');
  }
}

// تهيئة الحالة الأولى
try {
  renderCuisineOptions();
  renderCuisineTags();
  renderWorkHoursList();
  renderBranchesList();
  validateRestaurantForm();
} catch (_) {}
</script>