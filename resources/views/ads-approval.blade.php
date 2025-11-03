@extends('layouts.dashboard')

@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="page-title mb-1"><i class="bi bi-check-circle me-2"></i>الموافقة على الإعلانات <small class="text-muted">- Ads Approval</small></h2>
                            <p class="text-muted mb-0">إدارة الموافقة على الإعلانات قبل نشرها <small>- Manage ads approval before publishing</small></p>
                        </div>
                        <span class="badge bg-danger fs-6 px-3 py-2" id="pendingAdsCountBadge"><i class="bi bi-clock me-1"></i>في انتظار الموافقة: 0</span>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <!-- Approval Settings Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white"><h5 class="mb-0"><i class="bi bi-gear me-2"></i>إعدادات الموافقة - Approval Settings</h5></div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-2">وضع الموافقة اليدوية - Manual Approval Mode</h6>
                            <p class="text-muted mb-0">عند التفعيل: يتطلب موافقة يدوية على كل إعلان قبل النشر<br><small>When enabled: Requires manual approval for each ad before publishing</small></p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="approvalModeSwitch" disabled><label class="form-check-label fw-bold" for="approvalModeSwitch" id="switchLabel">جاري التحميل...</label></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Wrapper: shown/hidden based on approval mode -->
            <div id="approvalContentWrapper" style="display: none;">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mb-4" id="adsCategoryTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="car_sales-tab" data-bs-toggle="tab" data-bs-target="#car_sales-tab-pane" type="button" role="tab" data-category="car_sales">
                            <i class="bi bi-car-front-fill me-2"></i> 
                            <span class="tab-text">بيع السيارات</span><small class="d-block text-muted">Car Sales</small>
                            <span class="badge bg-primary ms-2" id="badge-car_sales">0</span>
                        </button>
                    </li>
                    {{-- ... Add other category tabs here when ready ... --}}
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="adsCategoryTabContent">
                    <div class="tab-pane fade show active" id="car_sales-tab-pane" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary text-white"><h5 class="mb-0">إعلانات بيع السيارات في انتظار الموافقة</h5></div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>الصورة</th><th>العنوان</th><th>المعلن</th><th>السعر</th><th>الباقة</th><th>التاريخ</th><th class="text-center">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody id="adsTableBody-car_sales">
                                            {{-- JS will render a loading state here --}}
                                        </tbody>
                                    </table>
                                    <div id="noAdsMessage-car_sales" class="text-center p-4 text-muted" style="display: none;">لا توجد إعلانات في انتظار الموافقة حالياً في هذا القسم.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ... Add other category tab-panes here when ready ... --}}
                </div>
            </div>

            <!-- Auto Approval Message -->
            <div class="card border-0 shadow-sm" id="autoApprovalCard" style="display: none;">
                <div class="card-body text-center py-5">
                    <div class="mb-4"><i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i></div>
                    <h4 class="text-success mb-3">الموافقة التلقائية مفعلة</h4>
                    <p class="text-muted mb-0">جميع الإعلانات الجديدة يتم قبولها ونشرها تلقائياً.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ad Details Modal -->
<div class="modal fade" id="adDetailsModal" tabindex="-1" aria-hidden="true">
    {{-- ... Modal HTML remains the same ... --}}
</div>
@endsection

@push('styles')
<style>
    /* ... Your full CSS code remains here ... */
</style>
@endpush

@push('scripts')
<script>
    // =========================================================
    // SECTION 0: PAGE GUARD
    // =========================================================
    (function() {
        const token = localStorage.getItem('token');
        const userJson = localStorage.getItem('user');
        let user = null;
        try { user = JSON.parse(userJson); } catch (e) { console.error("Error parsing user data:", e); }

        if (!token || !user || user.role !== 'admin') {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '{{ route("login") }}';
        }
    })();

    // =========================================================
    // SECTION 1: CONFIGURATION & GLOBAL STATE
    // =========================================================
    const BASE_URL = '{{ config("app.url") }}';
    let pendingAdsData = {};

    const API_ENDPOINTS = {
        settings: `${BASE_URL}/api/admin/system-settings`,
        pendingAds: `${BASE_URL}/api/admin/ads/pending`,
        approveAd: `${BASE_URL}/api/admin/ads/{id}/approve`,
        rejectAd: `${BASE_URL}/api/admin/ads/{id}/reject`,
        adDetails: `${BASE_URL}/api/car-sales-ads/{id}`
    };

    // =========================================================
    // SECTION 2: INITIALIZATION & EVENT LISTENERS
    // =========================================================
    document.addEventListener('DOMContentLoaded', function() {
        initializeApp();
        
        document.getElementById('approvalModeSwitch').addEventListener('change', handleApprovalSwitchToggle);

        document.getElementById('adsCategoryTabContent').addEventListener('click', function(event) {
            const button = event.target.closest('button[data-ad-id]');
            if (!button) return;
            
            const adId = button.dataset.adId;
            const category = 'car_sales';

            if (button.title.includes('View')) showAdDetails(adId, category);
            else if (button.title.includes('Approve')) handleAdAction(adId, category, 'approve');
            else if (button.title.includes('Reject')) handleAdAction(adId, category, 'reject');
        });
    });

    // =========================================================
    // SECTION 3: CORE LOGIC & API HANDLERS
    // =========================================================
    async function initializeApp() {
        showLoadingState('car_sales');
        await fetchInitialSettings();
        
        const isManualApproval = document.getElementById('approvalModeSwitch').checked;
        if (isManualApproval) {
            await fetchAllPendingAds();
            renderAllTables();
            updateAllBadges();
        }
        
        updateUIBasedOnApprovalMode();
    }

    async function fetchInitialSettings() {
        try {
            const response = await fetch(API_ENDPOINTS.settings, { headers: getAuthHeaders() });
            if (!response.ok) throw new Error('Failed to fetch settings');
            const settings = await response.json();
            const manualApprovalValue = settings['manual_approval_mode']?.value ?? 'true';
            document.getElementById('approvalModeSwitch').checked = (manualApprovalValue === 'true' || manualApprovalValue === '1');
        } catch (error) {
            console.error('Initialization Error:', error);
            showAlert('فشل تحميل الإعدادات الأولية.', 'danger');
            document.getElementById('approvalModeSwitch').checked = true; // Default to safe mode
        }
    }

    async function fetchAllPendingAds() {
        try {
            const response = await fetch(API_ENDPOINTS.pendingAds, { headers: getAuthHeaders() });
            if (!response.ok) throw new Error('Failed to fetch pending ads');
            const data = await response.json();
            pendingAdsData['car_sales'] = data.data || [];
        } catch (error) {
            console.error('Error fetching pending ads:', error);
            showAlert('فشل تحميل الإعلانات المعلقة.', 'danger');
            pendingAdsData['car_sales'] = [];
        }
    }

    async function handleApprovalSwitchToggle() {
        const isChecked = this.checked;
        try {
            const response = await fetch(`${API_ENDPOINTS.settings}/manual_approval_mode`, {
                method: 'PUT',
                headers: getAuthHeaders(),
                body: JSON.stringify({ value: String(isChecked) })
            });
            if (!response.ok) throw new Error('Failed to update setting');
            showAlert('تم تحديث إعدادات الموافقة بنجاح.', 'success');
            
            updateUIBasedOnApprovalMode();

            if (isChecked) {
                showLoadingState('car_sales');
                await fetchAllPendingAds();
                renderAllTables();
                updateAllBadges();
            }

        } catch (error) {
            this.checked = !isChecked;
            showAlert('فشل تحديث الإعدادات.', 'danger');
        }
    }

    async function handleAdAction(adId, category, actionType) {
        if (confirm(`هل أنت متأكد من ${actionType === 'approve' ? 'الموافقة على' : 'رفض'} هذا الإعلان؟`)) {
            try {
                const endpoint = API_ENDPOINTS[`${actionType}Ad`].replace('{id}', adId);
                const response = await fetch(endpoint, { method: 'POST', headers: getAuthHeaders() });
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'An unknown error occurred');
                }
                
                showAlert(`تم ${actionType === 'approve' ? 'الموافقة على' : 'رفض'} الإعلان بنجاح.`, 'success');
                
                pendingAdsData[category] = pendingAdsData[category].filter(ad => ad.id != adId);
                renderAdsTable(category, pendingAdsData[category]);
                updateAllBadges();
            } catch (error) {
                showAlert(`فشل الإجراء: ${error.message}`, 'danger');
            }
        }
    }
    
// =========================================================
// SECTION 4: RENDERING & UI HELPERS
// =========================================================

function showLoadingState(category) {
    const tableBody = document.getElementById(`adsTableBody-${category}`);
    if(tableBody) {
        tableBody.innerHTML = `<tr><td colspan="7" class="text-center p-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">جارٍ التحميل...</p></td></tr>`;
    }
}

function updateUIBasedOnApprovalMode() {
    const isManualApproval = document.getElementById('approvalModeSwitch').checked;
    const switchLabel = document.getElementById('switchLabel');
    const autoApprovalCard = document.getElementById('autoApprovalCard');
    const approvalContentWrapper = document.getElementById('approvalContentWrapper');

    if (isManualApproval) {
        switchLabel.textContent = 'مفعل - Enabled';
        switchLabel.className = 'form-check-label fw-bold text-success';
        autoApprovalCard.style.display = 'none';
        approvalContentWrapper.style.display = 'block';
    } else {
        switchLabel.textContent = 'معطل - Disabled';
        switchLabel.className = 'form-check-label fw-bold text-danger';
        autoApprovalCard.style.display = 'block';
        approvalContentWrapper.style.display = 'none';
    }
    document.getElementById('approvalModeSwitch').disabled = false;
}

function renderAllTables() {
    // حاليًا، قسم واحد فقط
    renderAdsTable('car_sales', pendingAdsData['car_sales']);
}

function updateAllBadges() {
     let totalCount = 0;
     Object.values(pendingAdsData).forEach(adsArray => { totalCount += (adsArray?.length || 0); });
     
     updatePendingAdsCountBadge(totalCount);
     
     // حاليًا، قسم واحد فقط
     updateCategoryBadge('car_sales', pendingAdsData['car_sales']?.length || 0);
}

function renderAdsTable(category, ads) {
    const tableBody = document.getElementById(`adsTableBody-${category}`);
    const noAdsMessage = document.getElementById(`noAdsMessage-${category}`);
    if (!tableBody || !noAdsMessage) return;

    tableBody.innerHTML = '';
    if (!ads || ads.length === 0) {
        noAdsMessage.style.display = 'block';
        return;
    }

    noAdsMessage.style.display = 'none';
    ads.forEach(ad => {
        const row = document.createElement('tr');
        const adImage = ad.main_image ? `${BASE_URL}/storage/${ad.main_image}` : `https://via.placeholder.com/60x60/eee/999?text=N/A`;
        
        row.innerHTML = `
            <td class="text-center align-middle"><img src="${adImage}" alt="Ad Image" class="rounded ad-image"></td>
            <td class="align-middle"><div class="fw-bold">${ad.title}</div><small class="text-muted">ID: ${ad.id}</small></td>
            <td class="align-middle">${ad.advertiser_name || 'N/A'}</td>
            <td class="align-middle">${parseFloat(ad.price).toFixed(2)} AED</td>
            <td class="align-middle"><span class="badge bg-secondary">${ad.plan_type || 'Free'}</span></td>
            <td class="align-middle">${new Date(ad.created_at).toLocaleDateString('ar-EG')}</td>
            <td class="text-center align-middle">
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-info" title="View Details" data-ad-id="${ad.id}"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-sm btn-success" title="Approve Ad" data-ad-id="${ad.id}"><i class="bi bi-check-lg"></i></button>
                    <button class="btn btn-sm btn-danger" title="Reject Ad" data-ad-id="${ad.id}"><i class="bi bi-x-lg"></i></button>
                </div>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

async function showAdDetails(adId, category) {
    const modalBody = document.getElementById('adDetailsModalBody');
    modalBody.innerHTML = `<div class="text-center p-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading...</p></div>`;
    const adDetailsModal = new bootstrap.Modal(document.getElementById('adDetailsModal'));
    adDetailsModal.show();
    
    try {
        const response = await fetch(API_ENDPOINTS.adDetails.replace('{id}', adId), { headers: getAuthHeaders() });
        if (!response.ok) throw new Error('Ad not found or error fetching details.');
        
        const ad = await response.json();
        
        modalBody.innerHTML = `
            <h5>${ad.title || ''}</h5>
            <p>${ad.description || 'No description provided.'}</p>
            <hr>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><strong>السعر:</strong> <span>${ad.price || 'N/A'} AED</span></li>
                <li class="list-group-item d-flex justify-content-between"><strong>الشركة:</strong> <span>${ad.make || 'N/A'}</span></li>
                <li class="list-group-item d-flex justify-content-between"><strong>الموديل:</strong> <span>${ad.model || 'N/A'}</span></li>
                <li class="list-group-item d-flex justify-content-between"><strong>السنة:</strong> <span>${ad.year || 'N/A'}</span></li>
                <li class="list-group-item d-flex justify-content-between"><strong>المسافة المقطوعة:</strong> <span>${ad.km || 'N/A'} KM</span></li>
                <li class="list-group-item d-flex justify-content-between"><strong>ناقل الحركة:</strong> <span>${ad.trans_type || 'N/A'}</span></li>
            </ul>
        `;

    } catch (error) {
        console.error("Error fetching ad details:", error);
        modalBody.innerHTML = `<p class="text-danger">Failed to load ad details. ${error.message}</p>`;
    }
}

// =========================================================
// SECTION 5: UTILITY & HELPER FUNCTIONS
// =========================================================
function getAuthHeaders() {
    const token = localStorage.getItem('token');
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }
    return headers;
}

function updateCategoryBadge(category, count) {
    const badge = document.getElementById(`badge-${category}`);
    if (badge) badge.textContent = count;
}

function updatePendingAdsCountBadge(totalCount) {
    const badge = document.getElementById('pendingAdsCountBadge');
    if (badge) badge.innerHTML = `<i class="bi bi-clock me-1"></i> في انتظار الموافقة: ${totalCount}`;
}

function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alertContainer');
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show m-3`;
    alertDiv.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    alertContainer.prepend(alertDiv);
    setTimeout(() => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alertDiv);
        if(bsAlert) bsAlert.close();
    }, 5000);
}
</script>
@endpush