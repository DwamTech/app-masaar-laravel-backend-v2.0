@extends('layouts.dashboard')

@section('title', 'إدارة التصاريح الأمنية')

@section('content')
<h2 class="mb-4">إدارة التصاريح الأمنية</h2>

<!-- قسم إعدادات قيمة التصريح -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-cog me-2"></i>إعدادات التصريح الأمني
        </h5>
    </div>
    <div class="card-body">
        <div class="row align-items-end">
            <div class="col-md-4 mb-3 mb-md-0">
                <label for="individualFee" class="form-label fw-bold">
                    <i class="fas fa-money-bill-wave me-2 text-success"></i>قيمة الطلب (للفرد الواحد)
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        class="form-control form-control-lg" 
                        id="individualFee" 
                        placeholder="أدخل القيمة"
                        min="0"
                        step="0.01"
                    >
                    <span class="input-group-text">جنيه</span>
                </div>
            </div>
            <div class="col-md-3">
                <button 
                    class="btn btn-success btn-lg w-100" 
                    onclick="saveIndividualFee()"
                    id="saveFeeBtn"
                >
                    <i class="fas fa-save me-2"></i>حفظ التعديلات
                </button>
            </div>
        </div>
        
        <!-- رسالة النجاح/الخطأ -->
        <div id="feeMessage" class="mt-3" style="display: none;"></div>
    </div>
</div>

<div id="permitsContent"></div>

<!-- Modal لعرض الصور -->
<div class="modal fade" id="imgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <img id="imgModalSrc" src="" style="max-width:98vw;max-height:85vh;display:block;margin:auto;">
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const baseUrl = window.location.origin; // تم التعديل: استخدام أصل الموقع لتجنب Mixed Content
let permits = [];
let pollingInterval = null;
let currentIndividualFee = 0;

// جلب قيمة التصريح الحالية
async function fetchIndividualFee() {
    try {
        const token = localStorage.getItem('token');
        const headers = { 'Accept': 'application/json' };
        if (token) headers['Authorization'] = 'Bearer ' + token;

        const res = await fetch(`${baseUrl}/api/admin/security-permits-settings`, { headers });
        const data = await res.json();
        
        if (data.status && data.settings) {
            // البحث عن individual_fee في الإعدادات
            const allSettings = Object.values(data.settings).flat();
            const feeSetting = allSettings.find(s => s.key === 'individual_fee');
            
            if (feeSetting) {
                currentIndividualFee = parseFloat(feeSetting.value);
                document.getElementById('individualFee').value = currentIndividualFee;
            }
        }
    } catch (e) {
        console.error('Error fetching individual fee:', e);
    }
}

// حفظ قيمة التصريح الجديدة
async function saveIndividualFee() {
    const feeInput = document.getElementById('individualFee');
    const newFee = parseFloat(feeInput.value);
    
    if (isNaN(newFee) || newFee < 0) {
        showFeeMessage('يرجى إدخال قيمة صحيحة', 'danger');
        return;
    }
    
    const saveBtn = document.getElementById('saveFeeBtn');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
    
    try {
        const token = localStorage.getItem('token');
        const headers = { 
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };
        if (token) headers['Authorization'] = 'Bearer ' + token;

        const res = await fetch(`${baseUrl}/api/admin/security-permits-settings`, {
            method: 'PUT',
            headers,
            body: JSON.stringify({ individual_fee: newFee })
        });
        
        const data = await res.json();
        
        if (res.ok && data.status !== false) {
            currentIndividualFee = newFee;
            showFeeMessage('تم حفظ قيمة التصريح بنجاح ✓', 'success');
            // تحديث القيمة في الصفحة
            feeInput.value = newFee;
        } else {
            showFeeMessage(data.message || 'حدث خطأ أثناء الحفظ', 'danger');
        }
    } catch (e) {
        showFeeMessage('حدث خطأ في الاتصال بالخادم', 'danger');
        console.error('Error saving individual fee:', e);
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    }
}

// عرض رسالة نجاح أو خطأ
function showFeeMessage(message, type) {
    const msgDiv = document.getElementById('feeMessage');
    msgDiv.className = `alert alert-${type} alert-dismissible fade show`;
    msgDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
    `;
    msgDiv.style.display = 'block';
    
    // إخفاء الرسالة تلقائياً بعد 5 ثوان
    setTimeout(() => {
        msgDiv.style.display = 'none';
    }, 5000);
}


function openImageModal(src, title = 'عرض الصورة') {
    // إزالة أي نافذة سابقة
    const existingModal = document.getElementById('dynamicImageModal');
    if (existingModal) {
        existingModal.remove();
    }

    // إنشاء النافذة المنبثقة
    const modalHTML = `
        <div class="modal fade" id="dynamicImageModal" tabindex="-1" aria-labelledby="dynamicImageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dynamicImageModalLabel">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="${src}" class="img-fluid" alt="${title}" style="max-height: 70vh; border-radius: 8px;">
                    </div>
                </div>
            </div>
        </div>
    `;

    // إضافة النافذة إلى الصفحة
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // عرض النافذة
    const modal = new bootstrap.Modal(document.getElementById('dynamicImageModal'));
    modal.show();

    // إزالة النافذة عند الإغلاق
    document.getElementById('dynamicImageModal').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
}

function openImgFull(src) {
    openImageModal(src, 'صورة المستند');
}

async function fetchPermits(auto = false) {
    const token = localStorage.getItem('token');
    if (!auto) {
        document.getElementById('permitsContent').innerHTML = `<div class="text-center py-5"><div class="spinner-border"></div></div>`;
    }
    try {
        const headers = { 'Accept': 'application/json' };
        if (token) headers['Authorization'] = 'Bearer ' + token;

        const res = await fetch(`${baseUrl}/api/all-security-permits`, { headers });
        const data = await res.json();
        // يدعم كلا الشكلين: مصفوفة مباشرة أو بيانات مُرقّمة (data)
        permits = Array.isArray(data.permits) ? data.permits : (Array.isArray(data.permits?.data) ? data.permits.data : []);
        renderPermits();
    } catch (e) {
        if (!auto) {
            document.getElementById('permitsContent').innerHTML = `<div class="alert alert-danger">تعذر تحميل التصاريح!</div>`;
        }
    }
}

function renderPermits() {
    const container = document.getElementById('permitsContent');
    if (!permits.length) {
        container.innerHTML = `<div class="alert alert-warning">لا توجد تصاريح حتى الآن.</div>`;
        return;
    }

    const header = `
      <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th class="text-center">#</th>
              <th>المستخدم</th>
              <th>الهاتف</th>
              <th>البريد</th>
              <th>قادمين من</th>
              <th class="text-center">عدد الأشخاص</th>
              <th>تاريخ السفر</th>
              <th>الحالة</th>
              <th>الدفع</th>
              <th>أُنشئ</th>
              <th class="text-center">إجراء</th>
            </tr>
          </thead>
          <tbody>
    `;

    const rows = permits.map(p => {
        const user = p.user || {};
        const travelDate = p.travel_date ? new Date(p.travel_date).toLocaleDateString('ar-EG') : '—';
        const createdAt = p.created_at ? new Date(p.created_at).toLocaleString('ar-EG') : '—';
        return `
          <tr>
            <td class="text-center">${p.id}</td>
            <td>${user.name || '—'}</td>
            <td>${user.phone || '—'}</td>
            <td>${user.email || '—'}</td>
            <td>${p.coming_from || '—'}</td>
            <td class="text-center">${p.people_count ?? '—'}</td>
            <td>${travelDate}</td>
            <td>${getPermitStatusLabel(p.status)}</td>
            <td>${getPaymentStatusLabel(p.payment_status)}</td>
            <td>${createdAt}</td>
            <td class="text-center">
              ${getActionButtons(p)}
            </td>
          </tr>
        `;
    }).join('');

    const footer = `
          </tbody>
        </table>
      </div>
    `;

    container.innerHTML = header + rows + footer;
}

function getActionButtons(p) {
    const docsBtn = `<button class="btn btn-sm btn-primary" onclick='openDocumentsModal(${JSON.stringify(p)})'>عرض المستندات</button>`;
    const acceptBtn = `<button class="btn btn-sm btn-success me-1" onclick="updatePermitStatus(${p.id}, 'approved')">قبول</button>`;
    const rejectBtn = `<button class="btn btn-sm btn-danger me-1" onclick="updatePermitStatus(${p.id}, 'rejected')">رفض</button>`;

    let actions = '';
    if (p.status === 'approved') {
        actions = rejectBtn;
    } else if (p.status === 'rejected') {
        actions = acceptBtn;
    } else {
        actions = acceptBtn + rejectBtn;
    }
    return actions + docsBtn;
}

async function updatePermitStatus(permitId, nextStatus) {
    try {
        const token = localStorage.getItem('token');
        const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json' };
        if (token) headers['Authorization'] = 'Bearer ' + token;

        // المسار الأساسي للأدمن
        const adminUrl = `${baseUrl}/api/admin/security-permits/${permitId}/status`;
        let res = await fetch(adminUrl, {
            method: 'PUT',
            headers,
            body: JSON.stringify({ status: nextStatus })
        });

        // في حال عدم الصلاحية، جرّب المسار القديم المحمي بالتوكن
        if (res.status === 401 || res.status === 403) {
            const legacyUrl = `${baseUrl}/api/security-permits/${permitId}/status`;
            res = await fetch(legacyUrl, {
                method: 'PUT',
                headers,
                body: JSON.stringify({ status: nextStatus })
            });
        }

        const data = await res.json();
        if (!res.ok || data.status === false) {
            alert(data.message || 'تعذر تحديث الحالة. تأكد من صلاحيات الأدمن.');
            return;
        }

        // تحديث العنصر محليًا وإعادة الرسم
        const updated = data.permit || null;
        if (updated) {
            permits = permits.map(x => x.id === updated.id ? updated : x);
        } else {
            // fallback: حدث فقط الحالة محليًا
            permits = permits.map(x => x.id === permitId ? { ...x, status: nextStatus } : x);
        }
        renderPermits();
    } catch (e) {
        alert('حدث خطأ أثناء تحديث الحالة');
    }
}

function resolveImageUrl(path) {
    if (!path) return null;
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    return `${window.location.origin}/storage/${path}`;
}

function openDocumentsModal(permit) {
    const imgs = [];
    if (permit.passport_image) imgs.push({label: 'صورة الجواز', src: resolveImageUrl(permit.passport_image)});
    if (permit.other_document_image) imgs.push({label: 'مستند إضافي', src: resolveImageUrl(permit.other_document_image)});
    if (Array.isArray(permit.residence_images)) {
        permit.residence_images.forEach((img, idx) => imgs.push({label: `إقامة ${idx+1}` , src: resolveImageUrl(img)}));
    }

    if (!imgs.length) {
        alert('لا توجد مستندات مرفقة لهذا الطلب');
        return;
    }

    const gallery = imgs.map(i => `
      <div class="col-12 col-md-6 col-xl-4 mb-3">
        <div class="border rounded p-2 h-100">
          <div class="small text-muted mb-2">${i.label}</div>
          <img src="${i.src}" alt="${i.label}" class="img-fluid rounded" style="cursor:pointer" onclick="openImgFull('${i.src}')" />
        </div>
      </div>
    `).join('');

    const modalHtml = `
      <div class="modal fade" id="docsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">المستندات المرفقة لطلب #${permit.id}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">${gallery}</div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
          </div>
        </div>
      </div>
    `;

    const holderId = 'docs-modal-holder';
    let holder = document.getElementById(holderId);
    if (!holder) {
        holder = document.createElement('div');
        holder.id = holderId;
        document.body.appendChild(holder);
    }
    holder.innerHTML = modalHtml;

    const modalEl = document.getElementById('docsModal');
    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

function getPermitStatusLabel(status) {
    switch (status) {
        case 'new': return `<span class="badge bg-secondary">جديد</span>`;
        case 'approved': return `<span class="badge bg-success">مقبول</span>`;
        case 'rejected': return `<span class="badge bg-danger">مرفوض</span>`;
        default: return `<span class="badge bg-light">${status}</span>`;
    }
}

function getPaymentStatusLabel(status) {
    switch (status) {
        case 'paid': return `<span class="badge bg-success">مدفوع</span>`;
        case 'unpaid': return `<span class="badge bg-danger">غير مدفوع</span>`;
        case 'pending': return `<span class="badge bg-warning text-dark">قيد الدفع</span>`;
        default: return `<span class="badge bg-light">${status || '-'}</span>`;
    }
}

function formatDateTime(dt) {
    if (!dt) return '-';
    return new Date(dt).toLocaleString('ar-EG', { dateStyle: 'short', timeStyle: 'short' });
}

function startPolling() {
    // تحديث البيانات كل ثانية (1000 مللي ثانية)
    pollingInterval = setInterval(() => {
        fetchPermits(true); // auto = true لتجنب رسائل التحميل
    }, 1000);
}

function stopPolling() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
    }
}

// تشغيل عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    fetchIndividualFee(); // جلب قيمة التصريح الحالية
    fetchPermits(); // تحميل أولي
    startPolling(); // بدء التحديث التلقائي
});

// إيقاف التحديث عند مغادرة الصفحة
window.addEventListener('beforeunload', function() {
    stopPolling();
});
</script>
@endsection
