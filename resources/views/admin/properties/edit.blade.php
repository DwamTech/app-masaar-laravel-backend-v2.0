@extends('layouts.dashboard')
@section('title', 'تعديل عقار')

@section('content')
<div class="container">
    <h2 class="h4 mb-4">تعديل بيانات العقار</h2>

    <div id="toast" style="position: fixed; top: 1rem; left: 50%; transform: translateX(-50%); z-index: 1050; display: none;">
        <div class="alert alert-success shadow">تم التنفيذ بنجاح</div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="editPropertyForm" class="row g-3">
                <input type="hidden" id="propertyId" value="{{ $propertyId }}" />

                <div class="alert alert-info">
                    ملكية هذا العقار منسوبة تلقائياً إلى "الإدارة".
                </div>

                <div class="col-12">
                    <label class="form-label">العنوان</label>
                    <input type="text" name="title" class="form-control" required />
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">النوع</label>
                        <select name="property_type" class="form-select" required>
                            <option value="apartment">شقة</option>
                            <option value="villa">فيلا</option>
                            <option value="townhouse">تاون هاوس</option>
                            <option value="office">مكتب</option>
                            <option value="shop">محل</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الحالة</label>
                        <select name="property_status" class="form-select" required>
                            <option value="available">متاح</option>
                            <option value="sold">مباع</option>
                            <option value="rented">مؤجر</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">نوع الملكية</label>
                        <select name="ownership_type" class="form-select" required>
                            <option value="freehold">تملك حر</option>
                            <option value="leasehold">إيجار طويل</option>
                            <option value="usufruct">انتفاع</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">الغرض من العرض</label>
                        <select name="listing_purpose" class="form-select" required>
                            <option value="sale">بيع</option>
                            <option value="rent">إيجار</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">نوع المعلن</label>
                        <select name="advertiser_type" class="form-select" required>
                            <option value="developer">مطوّر (الإدارة)</option>
                            <option value="owner">مالك</option>
                            <option value="broker">وسيط</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">السعر</label>
                        <input type="number" step="0.01" name="property_price" class="form-control" required />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">العملة</label>
                        <select name="currency" class="form-select" required>
                            <option value="SAR">ريال سعودي (SAR)</option>
                            <option value="AED">درهم إماراتي (AED)</option>
                            <option value="EGP">جنيه مصري (EGP)</option>
                            <option value="QAR">ريال قطري (QAR)</option>
                            <option value="KWD">دينار كويتي (KWD)</option>
                            <option value="BHD">دينار بحريني (BHD)</option>
                            <option value="OMR">ريال عماني (OMR)</option>
                            <option value="MAD">درهم مغربي (MAD)</option>
                            <option value="TRY">ليرة تركية (TRY)</option>
                            <option value="USD">دولار أمريكي (USD)</option>
                            <option value="EUR">يورو (EUR)</option>
                            <option value="GBP">جنيه إسترليني (GBP)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">المساحة (م²)</label>
                        <input type="number" step="0.01" name="size_in_sqm" class="form-control" required />
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">عدد الغرف</label>
                        <input type="number" name="bedrooms" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">عدد الحمامات</label>
                        <input type="number" name="bathrooms" class="form-control" required />
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">العنوان التفصيلي</label>
                    <input type="text" name="address" class="form-control" placeholder="انقر لاختيار العنوان من الخريطة" required />
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">خط العرض</label>
                        <input type="number" step="0.000001" name="location[latitude]" class="form-control" required readonly />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">خط الطول</label>
                        <input type="number" step="0.000001" name="location[longitude]" class="form-control" required readonly />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">العنوان المهيأ</label>
                        <input type="text" name="location[formatted_address]" class="form-control" required readonly />
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">هاتف التواصل</label>
                        <input type="text" name="contact_info[phone]" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">البريد الإلكتروني (اختياري)</label>
                        <input type="email" name="contact_info[email]" class="form-control" />
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">الصورة الرئيسية (رفع صورة جديدة يستبدل الحالية)</label>
                    <input type="file" name="main_image" accept="image/*" class="form-control" />
                    <input type="hidden" name="clear_main_image" value="0" />
                    <div id="mainImagePreview" class="mt-2" style="display:none; position:relative;">
                        <img alt="معاينة الصورة الرئيسية" style="max-width:200px; max-height:150px; object-fit:cover; border-radius:6px;" />
                        <button type="button" id="clearMainImageBtn" class="btn btn-sm btn-danger preview-remove-main" title="حذف">✕</button>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">إضافة صور معرض</label>
                    <input type="file" name="gallery_images[]" accept="image/*" multiple class="form-control" />
                    <div id="galleryPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                </div>

                <div class="col-12">
                    <label class="form-label">إزالة صور معرض (ضع الروابط مفصولة بسطر)</label>
                    <textarea name="remove_gallery_images" class="form-control" rows="2" placeholder="https://...\nhttps://...\n"></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary">رجوع</a>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- نافذة اختيار الموقع من خرائط جوجل -->
<div id="mapPicker" style="position:fixed; inset:0; background:rgba(0,0,0,0.6); display:none; align-items:center; justify-content:center; z-index:2000;">
  <div style="background:#fff; width:90%; max-width:900px; border-radius:8px; overflow:hidden; box-shadow:0 0 20px rgba(0,0,0,0.3);">
    <div style="display:flex; align-items:center; gap:8px; padding:8px 12px; border-bottom:1px solid #eee;">
      <input id="mapSearch" type="text" class="form-control" placeholder="ابحث عن موقع، عنوان أو اسم" />
      <button id="closeMap" type="button" class="btn btn-outline-secondary">إغلاق</button>
    </div>
    <div id="map" style="width:100%; height:500px;"></div>
    <div id="mapsKeyNote" style="padding:8px 12px; display:none;" class="text-danger">مفتاح خرائط جوجل غير مضبوط. أضف GOOGLE_MAPS_API_KEY في ملف .env.</div>
  </div>
  <span style="position:absolute; top:16px; left:16px; color:#fff; font-size:14px; opacity:.8;">اضغط خارج النافذة أو زر Esc للإغلاق</span>
  
</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
  // إعداد Axios
  window.axios = axios;
  axios.defaults.withCredentials = true;
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  // إرفاق توكن الأدمن في الهيدر (Bearer)
  try {
    const token = localStorage.getItem('token');
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }
  } catch (_) {}

  const form = document.getElementById('editPropertyForm');
  const propertyId = document.getElementById('propertyId').value;
  const toast = document.getElementById('toast');
  const showToast = (msg = 'تم التنفيذ بنجاح', isError = false) => {
    toast.innerHTML = `<div class="alert ${isError ? 'alert-danger' : 'alert-success'} shadow">${msg}</div>`;
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 2000);
  };

  const ensureCsrf = async () => {
    try { await window.axios.get('/sanctum/csrf-cookie'); } catch (e) {}
  };

  // معاينة الصورة الرئيسية
  const mainInput = form.querySelector('[name="main_image"]');
  const mainPrev = document.getElementById('mainImagePreview');
  const mainPrevImg = mainPrev ? mainPrev.querySelector('img') : null;
  const clearMainBtn = document.getElementById('clearMainImageBtn');
  const clearMainHidden = form.querySelector('[name="clear_main_image"]');
  let mainPreviewIsExisting = false;
  if (mainInput) {
    mainInput.addEventListener('change', () => {
      const file = mainInput.files && mainInput.files[0];
      if (file && mainPrev && mainPrevImg) {
        const url = URL.createObjectURL(file);
        mainPrevImg.src = url;
        mainPrev.style.display = 'block';
        mainPreviewIsExisting = false;
      } else if (mainPrev && mainPrevImg) {
        mainPrevImg.src = '';
        mainPrev.style.display = 'none';
      }
    });
  }
  if (clearMainBtn && mainInput && mainPrev && mainPrevImg) {
    clearMainBtn.addEventListener('click', () => {
      mainInput.value = '';
      mainPrevImg.src = '';
      mainPrev.style.display = 'none';
      if (clearMainHidden) clearMainHidden.value = mainPreviewIsExisting ? '1' : '0';
    });
  }

  // معاينة صور المعرض
  const galleryInput = form.querySelector('[name="gallery_images[]"]');
  const galleryPrev = document.getElementById('galleryPreview');
  let galleryFilesState = [];
  function renderNewGalleryPreviews() {
    if (!galleryPrev) return;
    // احذف معاينات الصور الجديدة السابقة فقط
    Array.from(galleryPrev.querySelectorAll('.preview-item[data-type="new"]')).forEach(n => n.remove());
    // أضف معاينات الصور الجديدة الحالية
    galleryFilesState.forEach((f, i) => {
      const url = URL.createObjectURL(f);
      const wrap = document.createElement('div');
      wrap.className = 'preview-item';
      wrap.dataset.type = 'new';
      const img = document.createElement('img');
      img.src = url;
      img.alt = 'معاينة صورة المعرض';
      img.style.width = '96px';
      img.style.height = '72px';
      img.style.objectFit = 'cover';
      img.style.borderRadius = '6px';
      img.style.border = '1px solid #ddd';
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'preview-remove btn btn-sm btn-danger';
      btn.title = 'حذف';
      btn.textContent = '✕';
      btn.dataset.type = 'new';
      btn.dataset.index = String(i);
      wrap.appendChild(img);
      wrap.appendChild(btn);
      galleryPrev.appendChild(wrap);
    });
  }
  if (galleryInput && galleryPrev) {
    galleryInput.addEventListener('change', () => {
      galleryFilesState = Array.from(galleryInput.files || []);
      renderNewGalleryPreviews();
    });
    galleryPrev.addEventListener('click', (e) => {
      const btn = e.target.closest('.preview-remove');
      if (!btn) return;
      const type = btn.dataset.type;
      if (type === 'new') {
        const idx = parseInt(btn.dataset.index || '-1', 10);
        if (idx < 0) return;
        galleryFilesState.splice(idx, 1);
        const dt = new DataTransfer();
        galleryFilesState.forEach(f => dt.items.add(f));
        galleryInput.files = dt.files;
        renderNewGalleryPreviews();
      } else if (type === 'existing') {
        const url = btn.dataset.url || '';
        // أخفِ المعاينة وأضف مدخلًا مخفيًا ليُزال من الخادم
        const wrap = btn.parentElement;
        if (wrap) wrap.remove();
        if (url) {
          const hidden = document.createElement('input');
          hidden.type = 'hidden';
          hidden.name = 'remove_gallery_images[]';
          hidden.value = url;
          form.appendChild(hidden);
        }
      }
    });
  }

  // مُلتقط خرائط جوجل للعناوين
  const addressInput = form.querySelector('[name="address"]');
  const latInput = form.querySelector('[name="location[latitude]"]');
  const lngInput = form.querySelector('[name="location[longitude]"]');
  const fmtInput = form.querySelector('[name="location[formatted_address]"]');
  const mapOverlay = document.getElementById('mapPicker');
  const closeMapBtn = document.getElementById('closeMap');
  const mapsKeyNote = document.getElementById('mapsKeyNote');
  const GOOGLE_MAPS_API_KEY = "{{ config('services.google.maps_api_key') }}";

  function openMapPicker() {
    if (!mapOverlay) return;
    mapOverlay.style.display = 'flex';
    if (!GOOGLE_MAPS_API_KEY) {
      if (mapsKeyNote) mapsKeyNote.style.display = 'block';
      return;
    }
    if (!window.__mapsLoaded) {
      const s = document.createElement('script');
      s.src = `https://maps.googleapis.com/maps/api/js?key=${GOOGLE_MAPS_API_KEY}&libraries=places&callback=initMap`;
      s.async = true; s.defer = true;
      window.__mapsLoaded = true;
      document.head.appendChild(s);
    } else if (typeof window.initMap === 'function' && !window.__mapInitialized) {
      window.initMap();
    }
  }

  if (addressInput) {
    addressInput.addEventListener('focus', openMapPicker);
    addressInput.addEventListener('click', openMapPicker);
  }
  if (closeMapBtn && mapOverlay) {
    closeMapBtn.addEventListener('click', () => { mapOverlay.style.display = 'none'; });
    mapOverlay.addEventListener('click', (ev) => { if (ev.target === mapOverlay) mapOverlay.style.display = 'none'; });
  }

  // دوال خرائط جوجل
  let map, marker, geocoder, autocomplete;
  window.initMap = function() {
    const center = { lat: 30.0444, lng: 31.2357 }; // القاهرة كافتراضي
    const mapEl = document.getElementById('map');
    if (!mapEl) return;
    map = new google.maps.Map(mapEl, { center, zoom: 12 });
    geocoder = new google.maps.Geocoder();
    const input = document.getElementById('mapSearch');
    if (input) {
      // تحسين تهيئة الـ Autocomplete لضمان توفر الحقول المطلوبة
      autocomplete = new google.maps.places.Autocomplete(input, {
        fields: ['geometry', 'name', 'formatted_address']
      });
      autocomplete.bindTo('bounds', map);
      autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        if (!place || !place.geometry) return;
        map.panTo(place.geometry.location);
        map.setZoom(15);
        placeMarker(place.geometry.location);
        fillFields(place.geometry.location, place.formatted_address || place.name || '');
        window.__mapInitialized = true;
      });
    }
    map.addListener('click', (e) => {
      placeMarker(e.latLng);
      reverseGeocode(e.latLng);
      window.__mapInitialized = true;
    });
  };

  function placeMarker(latLng) {
    if (!marker) marker = new google.maps.Marker({ map, position: latLng });
    else marker.setPosition(latLng);
  }

  function reverseGeocode(latLng) {
    geocoder.geocode({ location: latLng }, (results, status) => {
      const formatted = (status === 'OK' && results && results[0]) ? results[0].formatted_address : '';
      fillFields(latLng, formatted);
    });
  }

  function fillFields(latLng, formatted) {
    try {
      const lat = (typeof latLng.lat === 'function') ? latLng.lat() : latLng.lat;
      const lng = (typeof latLng.lng === 'function') ? latLng.lng() : latLng.lng;
      if (latInput) latInput.value = lat;
      if (lngInput) lngInput.value = lng;
      if (fmtInput) fmtInput.value = formatted;
      if (addressInput && formatted) addressInput.value = formatted;
    } catch (_) {}
  }

  const fillForm = (p) => {
    form.title.value = p.title || '';
    setSelectValue(form.property_type, p.property_type || p.old_type || '');
    setSelectValue(form.property_status, p.property_status || '');
    form.property_price.value = p.property_price ?? p.old_price ?? '';
    setSelectValue(form.currency, p.currency || '');
    form.size_in_sqm.value = p.size_in_sqm ?? '';
    form.bedrooms.value = p.bedrooms ?? '';
    form.bathrooms.value = p.bathrooms ?? '';
    form.address.value = p.address || '';
    form['location[latitude]'].value = p.location?.latitude ?? '';
    form['location[longitude]'].value = p.location?.longitude ?? '';
    form['location[formatted_address]'].value = p.location?.formatted_address ?? '';
    form['contact_info[phone]'].value = p.contact_info?.phone ?? '';
    form['contact_info[email]'].value = p.contact_info?.email ?? '';
    // الحقول الإضافية لمطابقة صفحة الإضافة
    if (form.ownership_type) setSelectValue(form.ownership_type, p.ownership_type || '');
    if (form.listing_purpose) setSelectValue(form.listing_purpose, p.listing_purpose || '');
    if (form.advertiser_type) setSelectValue(form.advertiser_type, p.advertiser_type || 'developer');
    // لا حاجة لتعديل real_estate_id؛ يتم تثبيته للإدارة تلقائياً

    // معاينة الصور الحالية (إن وجدت)
    const normalizeImageUrl = (u) => {
      if (!u) return '';
      try {
        const s = String(u).trim();
        if (s.startsWith('http://') || s.startsWith('https://')) return s;
        if (s.startsWith('/storage/')) return s;
        if (s.startsWith('storage/')) return '/' + s;
        if (s.startsWith('/properties/')) return '/storage' + s;
        if (s.startsWith('properties/')) return '/storage/' + s;
        return '/storage/' + s.replace(/^\/?/, '');
      } catch (_) { return ''; }
    };

    const mainCandidate = p.image_url || p.main_image || '';
    const mainSrc = normalizeImageUrl(mainCandidate);
    if (mainSrc && mainPrev && mainPrevImg) {
      mainPrevImg.src = mainSrc;
      mainPrev.style.display = 'block';
      mainPreviewIsExisting = true;
      if (clearMainHidden) clearMainHidden.value = '0';
    }

    const gallery = Array.isArray(p.gallery_image_urls) ? p.gallery_image_urls : [];
    if (galleryPrev && gallery.length) {
      galleryPrev.innerHTML = '';
      gallery.forEach((g) => {
        const src = normalizeImageUrl(g);
        if (!src) return;
        const wrap = document.createElement('div');
        wrap.className = 'preview-item';
        wrap.dataset.type = 'existing';
        const img = document.createElement('img');
        img.src = src;
        img.alt = 'صورة المعرض الحالية';
        img.style.width = '96px';
        img.style.height = '72px';
        img.style.objectFit = 'cover';
        img.style.borderRadius = '6px';
        img.style.border = '1px solid #ddd';
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'preview-remove btn btn-sm btn-danger';
        btn.title = 'حذف';
        btn.textContent = '✕';
        btn.dataset.type = 'existing';
        btn.dataset.url = src;
        wrap.appendChild(img);
        wrap.appendChild(btn);
        galleryPrev.appendChild(wrap);
      });
    }
  };

  function setSelectValue(selectEl, value) {
    if (!selectEl) return;
    const v = value ?? '';
    const hasOption = Array.from(selectEl.options).some(opt => opt.value === v);
    if (!hasOption && v) {
      const opt = document.createElement('option');
      opt.value = v; opt.textContent = v;
      selectEl.appendChild(opt);
    }
    selectEl.value = v;
  }

  try {
    await ensureCsrf();
    const { data } = await window.axios.get(`/api/properties/${propertyId}`);
    const p = data?.property || data?.data || data;
    fillForm(p);
  } catch (err) {
    console.error(err);
    showToast('فشل تحميل بيانات العقار', true);
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(form);

    // تحويل قائمة الروابط إلى مصفوفة إذا وُجدت
    const removeInput = form.querySelector('[name="remove_gallery_images"]');
    if (removeInput) {
      const removeList = removeInput.value.split(/\n|\r/).map(s => s.trim()).filter(Boolean);
      if (removeList.length) {
        removeList.forEach((url) => fd.append('remove_gallery_images[]', url));
      }
    }

    try {
      await ensureCsrf();
      await window.axios.post(`/api/admin/properties/${propertyId}?_method=PUT`, fd, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      showToast('تم تحديث العقار بنجاح');
      // لا نعيد تعيين المعاينات هنا حتى لا نفقد صور العرض الحالية
    } catch (err) {
      console.error(err);
      const msg = err?.response?.data?.message || 'فشل تحديث العقار';
      showToast(msg, true);
    }
  });
});
</script>
<style>
  /* رفع طبقة اقتراحات Google Places فوق النافذة العائمة */
  .pac-container { z-index: 3000 !important; }
  .preview-item { position: relative; display: inline-block; }
  .preview-remove, .preview-remove-main {
    position: absolute; top: 6px; right: 6px;
    background: rgba(0,0,0,0.6); color: #fff; border: none;
    width: 24px; height: 24px; border-radius: 50%; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    line-height: 1; padding: 0;
  }
  .preview-remove:hover, .preview-remove-main:hover { background: rgba(220,53,69,0.9); }
</style>
@endsection
@endsection