@extends('layouts.dashboard')
@section('title', 'إدارة العقارات')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">كل العقارات</h2>
        <a href="{{ route('admin.properties.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            إضافة عقار
        </a>
    </div>

    <div id="toast" style="position: fixed; top: 1rem; left: 50%; transform: translateX(-50%); z-index: 1050; display: none;">
        <div class="alert alert-success shadow">تم التنفيذ بنجاح</div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-end">#</th>
                        <th class="text-end">الصورة</th>
                        <th class="text-end">العنوان</th>
                        <th class="text-end">النوع</th>
                        <th class="text-end">السعر</th>
                        <th class="text-end">مميّز</th>
                        <th class="text-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody id="propertiesBody"></tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center p-3" id="pagination">
            <button id="prevPage" class="btn btn-outline-secondary" disabled>السابق</button>
            <span id="pageInfo" class="text-muted">صفحة 1</span>
            <button id="nextPage" class="btn btn-outline-secondary" disabled>التالي</button>
        </div>
    </div>
</div>
<!-- نافذة عرض الصورة بالحجم الكامل -->
<div id="imageViewer" style="position:fixed; inset:0; background:rgba(0,0,0,0.7); display:none; align-items:center; justify-content:center; z-index:2000; cursor: zoom-out;">
  <img id="imageViewerImg" src="" alt="عرض الصورة" style="max-width:90%; max-height:90%; border-radius:8px; box-shadow:0 0 20px rgba(0,0,0,0.5); cursor: default;" />
  <span style="position:absolute; top:16px; left:16px; color:#fff; font-size:14px; opacity:.8;">اضغط خارج الصورة أو Esc للإغلاق</span>
</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
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

  // عنوان الأساس من .env لضمّه لمسارات الصور النسبية
  const BASE_URL = "{{ rtrim(config('app.url'), '/') }}";
  // تطبيع مسار الصورة ليصبح كاملاً وبصيغة /storage/properties/...
  const toAbsoluteUrl = (u) => {
    if (!u) return '';
    // إذا كان الرابط كاملاً http/https نعيده كما هو
    if (/^https?:\/\//i.test(u)) return u;
    let path = u.trim();
    // تأكد من وجود شرطة مائلة في البداية
    if (!path.startsWith('/')) path = '/' + path;
    // تحويل المسارات القديمة /properties/... إلى /storage/properties/...
    if (path.startsWith('/properties/')) {
      path = '/storage' + path; // => /storage/properties/...
    }
    // عند هذه النقطة، إذا كان يبدأ بـ /storage/ أو تم تحويله، نركّب الرابط كاملاً باستخدام APP_URL
    return BASE_URL + path;
  };

  let currentPage = 1;
  const tbody = document.getElementById('propertiesBody');
  const prevBtn = document.getElementById('prevPage');
  const nextBtn = document.getElementById('nextPage');
  const pageInfo = document.getElementById('pageInfo');

  const toast = document.getElementById('toast');
  const showToast = (msg = 'تم التنفيذ بنجاح', isError = false) => {
    toast.innerHTML = `<div class="alert ${isError ? 'alert-danger' : 'alert-success'} shadow">${msg}</div>`;
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 2000);
  };

  const ensureCsrf = async () => {
    try { await window.axios.get('/sanctum/csrf-cookie'); } catch (e) {}
  };

  const loadPage = async (page = 1) => {
    try {
      await ensureCsrf();
      const { data } = await window.axios.get(`/api/admin/properties?page=${page}`);
      // هيكل الاستجابة من السيرفر:
      // { status: true, properties: [..], pagination: { current_page, last_page, per_page, total } }
      const items = Array.isArray(data?.properties) ? data.properties : [];
      const meta = data?.pagination || { current_page: page, last_page: 1, per_page: items.length, total: items.length };

      tbody.innerHTML = '';
      items.forEach((p, idx) => {
        const row = document.createElement('tr');
        const imgCandidate = p?.image_url || p?.main_image || (Array.isArray(p?.gallery_image_urls) && p.gallery_image_urls[0]) || '';
        const imgSrc = toAbsoluteUrl(imgCandidate);
        row.innerHTML = `
          <td class="text-end">${((page - 1) * (meta.per_page || items.length)) + idx + 1}</td>
          <td class="text-end">${imgCandidate ? `<img src="${imgSrc}" alt="صورة العقار" class="img-thumbnail" style="width:64px;height:48px;object-fit:cover;cursor:pointer" data-img="${imgSrc}">` : '—'}</td>
          <td class="text-end">${p.title || '—'}</td>
          <td class="text-end">${p.property_type || p.old_type || '—'}</td>
          <td class="text-end">${p.property_price ?? p.old_price ?? '—'} ${p.currency || ''}</td>
          <td class="text-end">${p.is_featured ? '<span class="text-success">نعم</span>' : '<span class="text-muted">لا</span>'}</td>
          <td class="text-end">
            <div class="d-flex gap-2 justify-content-end">
              <a href="/admin/properties/${p.id}/edit" class="btn btn-sm btn-warning text-white">تعديل</a>
              <button data-id="${p.id}" class="btn btn-sm btn-danger btn-delete">حذف</button>
              <button data-id="${p.id}" data-featured="${p.is_featured ? 1 : 0}" class="btn btn-sm ${p.is_featured ? 'btn-secondary' : 'btn-indigo'} btn-toggle-feature">
                ${p.is_featured ? 'إلغاء التمييز' : 'تمييز'}
              </button>
            </div>
          </td>
        `;
        tbody.appendChild(row);
      });

      pageInfo.textContent = `صفحة ${meta.current_page || page} من ${meta.last_page || 1}`;
      prevBtn.disabled = (meta.current_page || page) <= 1;
      nextBtn.disabled = (meta.current_page || page) >= (meta.last_page || 1);
      currentPage = meta.current_page || page;
    } catch (e) {
      console.error(e);
      if (e?.response?.status === 401) {
        showToast('غير مسجّل كمسؤول. الرجاء تسجيل الدخول.', true);
      } else {
        showToast('فشل تحميل العقارات', true);
      }
    }
  };

  tbody.addEventListener('click', async (e) => {
    const thumb = e.target.closest('img[data-img]');
    if (thumb) {
      const viewer = document.getElementById('imageViewer');
      const viewerImg = document.getElementById('imageViewerImg');
      if (viewer && viewerImg) {
        viewerImg.src = thumb.getAttribute('data-img');
        viewer.style.display = 'flex';
      }
      return;
    }
    const delBtn = e.target.closest('.btn-delete');
    const featBtn = e.target.closest('.btn-toggle-feature');

    if (delBtn) {
      const id = delBtn.getAttribute('data-id');
      if (!confirm('تأكيد حذف العقار؟')) return;
      try {
        await ensureCsrf();
        await window.axios.delete(`/api/admin/properties/${id}`);
        showToast('تم حذف العقار');
        loadPage(currentPage);
      } catch (err) {
        console.error(err);
        showToast('فشل حذف العقار', true);
      }
    }

    if (featBtn) {
      const id = featBtn.getAttribute('data-id');
      const isCurrentlyFeatured = featBtn.getAttribute('data-featured') === '1';
      const willSet = !isCurrentlyFeatured;
      try {
        await ensureCsrf();
        await window.axios.patch(`/api/admin/properties/${id}/feature`, { is_featured: willSet });
        showToast(willSet ? 'تم تمييز العقار' : 'تم إلغاء تمييز العقار');
        loadPage(currentPage);
      } catch (err) {
        console.error(err);
        const msg = err?.response?.data?.message || 'فشل تعديل حالة التمييز';
        showToast(msg, true);
      }
    }
  });

  // إغلاق نافذة عرض الصورة عند الضغط خارج الصورة أو زر Esc
  const viewer = document.getElementById('imageViewer');
  const viewerImg = document.getElementById('imageViewerImg');
  if (viewer) {
    viewer.addEventListener('click', (ev) => {
      if (ev.target === viewer) {
        viewer.style.display = 'none';
        if (viewerImg) viewerImg.src = '';
      }
    });
  }
  document.addEventListener('keydown', (ev) => {
    if (ev.key === 'Escape') {
      const v = document.getElementById('imageViewer');
      const vi = document.getElementById('imageViewerImg');
      if (v && v.style.display === 'flex') {
        v.style.display = 'none';
        if (vi) vi.src = '';
      }
    }
  });

  prevBtn.addEventListener('click', () => loadPage(currentPage - 1));
  nextBtn.addEventListener('click', () => loadPage(currentPage + 1));

  loadPage(1);
});
</script>
@endsection
@endsection