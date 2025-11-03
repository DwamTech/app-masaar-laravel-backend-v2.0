@extends('layouts.dashboard')

@section('title', 'إدارة الإعلانات - Ads Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1 text-primary fw-bold">
                                <i class="bi bi-megaphone me-2"></i>
                                إدارة الإعلانات
                                <small class="text-muted fs-6">Ads Management</small>
                            </h2>
                            <p class="text-muted mb-0">إدارة وتتبع جميع الإعلانات في النظام</p>
                            <small class="text-muted">Manage and track all advertisements in the system</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                156 إعلان
                                <br><small>Ads</small>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Alert Messages -->
    <div id="alertContainer"></div>

    <!-- Section Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3 text-primary fw-bold text-center">
                        <i class="bi bi-grid-3x3-gap me-2"></i>
                        أقسام الإعلانات
                        <small class="text-muted ms-2">Advertisement Sections</small>
                    </h5>
                    <ul class="nav nav-tabs mb-4" id="sectionTabs" role="tablist" style="border: none; background: #f8f9fa; border-radius: 15px; padding: 0.5rem;">
                        <li class="nav-item" style="flex: 1;">
                            <button class="nav-link active" id="restaurants-tab" data-bs-toggle="tab" data-bs-target="#restaurants" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                                <i class="bi bi-shop d-block mb-1" style="font-size: 1.5rem;"></i>
                                <span>المطاعم</span>
                                <small class="d-block">Restaurants</small>
                            </button>
                        </li>
                        <li class="nav-item" style="flex: 1;">
                            <button class="nav-link" id="car-services-tab" data-bs-toggle="tab" data-bs-target="#car-services" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                                <i class="bi bi-tools d-block mb-1" style="font-size: 1.5rem;"></i>
                                <span>خدمات السيارات</span>
                                <small class="d-block">Car Services</small>
                            </button>
                        </li>
                        <li class="nav-item" style="flex: 1;">
                            <button class="nav-link" id="car-rent-tab" data-bs-toggle="tab" data-bs-target="#car-rent" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                                <i class="bi bi-car-front d-block mb-1" style="font-size: 1.5rem;"></i>
                                <span>تأجير السيارات</span>
                                <small class="d-block">Car Rent</small>
                            </button>
                        </li>
                        <li class="nav-item" style="flex: 1;">
                            <button class="nav-link" id="jobs-tab" data-bs-toggle="tab" data-bs-target="#jobs" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                                <i class="bi bi-briefcase d-block mb-1" style="font-size: 1.5rem;"></i>
                                <span>الوظائف</span>
                                <small class="d-block">Jobs</small>
                            </button>
                        </li>
                        <li class="nav-item" style="flex: 1;">
                            <button class="nav-link" id="electronics-tab" data-bs-toggle="tab" data-bs-target="#electronics" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                                <i class="bi bi-tv d-block mb-1" style="font-size: 1.5rem;"></i>
                                <span>الإلكترونيات</span>
                                <small class="d-block">Electronics</small>
                            </button>
                        </li>
                        <li class="nav-item" style="flex: 1;">
                            <button class="nav-link" id="real-estate-tab" data-bs-toggle="tab" data-bs-target="#real-estate" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                                <i class="bi bi-building d-block mb-1" style="font-size: 1.5rem;"></i>
                                <span>العقارات</span>
                                <small class="d-block">Real Estate</small>
                            </button>
                        </li>
                        <li class="nav-item" style="flex: 1;">
                            <button class="nav-link" id="car-sale-tab" data-bs-toggle="tab" data-bs-target="#car-sale" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                                <i class="bi bi-car-front-fill d-block mb-1" style="font-size: 1.5rem;"></i>
                                <span>بيع السيارات</span>
                                <small class="d-block">Car Sale</small>
                            </button>
                        </li>
                        <li class="nav-item" style="flex: 1;">
                            <button class="nav-link" id="other-services-tab" data-bs-toggle="tab" data-bs-target="#other-services" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                                <i class="bi bi-gear d-block mb-1" style="font-size: 1.5rem;"></i>
                                <span>خدمات أخرى</span>
                                <small class="d-block">Other Services</small>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="sectionTabContent">
        <!-- Restaurants Tab -->
        <div class="tab-pane fade show active" id="restaurants" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>
                        جدول الإعلانات
                        <small class="ms-2">Ads Table</small>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <!-- Search and Control Section -->
                    <div class="p-4 border-bottom bg-gradient-light">
                        <!-- Search Box -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="search-container">
                                    <div class="search-box-wrapper">
                                        <div class="search-icon">
                                            <i class="bi bi-search"></i>
                                        </div>
                                        <input type="text" 
                                               id="searchInput" 
                                               class="form-control search-input" 
                                               placeholder="ابحث في الإعلانات.. Search in ads..."
                                               onkeyup="searchAds()"
                                               autocomplete="off">
                                        <div class="search-clear" id="searchClear" onclick="clearSearch()" style="display: none;">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </div>
                                    </div>
                                    <div class="search-suggestions" id="searchSuggestions" style="display: none;">
                                        <!-- Search suggestions will be populated here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Control Buttons -->
                        <div class="row align-items-center">
                            <div class="col-12 text-center">
                                <h6 class="mb-3 text-primary fw-semibold">
                                    <i class="bi bi-sliders me-2"></i>
                                    أدوات التحكم في الجدول
                                    <small class="text-muted">Table Controls</small>
                                </h6>
                                <div class="btn-group animated-buttons" role="group" aria-label="Table Controls">
                                    <button type="button" class="btn btn-outline-primary animated-btn" id="showAllDataBtn" onclick="showAllData()">
                                        <i class="bi bi-list-ul me-1"></i>
                                        عرض جميع البيانات
                                        <br><small>Show All Data</small>
                                    </button>
                                    <button type="button" class="btn btn-outline-success animated-btn" id="sortHighestViewsBtn" onclick="sortByHighestViews()">
                                        <i class="bi bi-sort-numeric-up me-1"></i>
                                        الأعلى مشاهدة
                                        <br><small>Highest Views</small>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger animated-btn" id="sortLowestViewsBtn" onclick="sortByLowestViews()">
                                        <i class="bi bi-sort-numeric-down me-1"></i>
                                        الأقل مشاهدة
                                        <br><small>Lowest Views</small>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="allAdsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">صورة الإعلان - Ad Image</th>
                                    <th>عنوان الإعلان - Ad Title</th>
                                    <th>اسم المعلن - Advertiser Name</th>
                                    <th>تكلفة الإعلان - Ad Cost</th>
                                    <th>القسم - Category</th>
                                    <th>نوع الباقة - Package Type</th>
                                    <th>تاريخ الإنشاء - Created Date</th>
                                    <th>تاريخ الانتهاء - Expiry Date</th>
                                    <th class="sortable-header" style="cursor: pointer;" onclick="sortAllAdsTable('views')">
                                        عدد المشاهدات - Views Count
                                        <i class="bi bi-arrow-down-up ms-1 text-primary"></i>
                                    </th>
                                    <th class="text-center">الإجراءات - Actions</th>
                                </tr>
                            </thead>
                            <tbody id="allAdsTableBody">
                                        <!-- Sample Data -->
                                        <tr>
                                            <td class="text-center">
                                                <img src="https://via.placeholder.com/60x60/3490dc/ffffff?text=AD" 
                                                     alt="Ad Image" class="rounded ad-image">
                                            </td>
                                            <td>
                                                <div class="fw-bold text-primary">سيارة BMW للبيع</div>
                                                <small class="text-muted">BMW Car for Sale</small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">أحمد محمد</div>
                                                <small class="text-muted">Ahmed Mohamed</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">500 ريال</span>
                                                <small class="d-block text-muted">500 SAR</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">سيارات</span>
                                                <small class="d-block text-muted">Cars</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">مميز</span>
                                                <small class="d-block text-muted">Featured</small>
                                            </td>
                                            <td>
                                                <div>2024/01/15</div>
                                                <small class="text-muted">15 Jan 2024</small>
                                            </td>
                                            <td>
                                                <div>2024/02/15</div>
                                                <small class="text-muted">15 Feb 2024</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary fs-6">1,250</span>
                                                <small class="d-block text-muted">Views</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-info" title="عرض التفاصيل - View Details" onclick="viewAdDetails(1)">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-warning" title="تعديل - Edit" onclick="editAd(1)">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-success" title="تحديث - Refresh" onclick="refreshAd(1)">
                                                        <i class="bi bi-arrow-clockwise"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" title="حذف - Delete" onclick="deleteAd(1)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <img src="https://via.placeholder.com/60x60/3490dc/ffffff?text=AD" 
                                                     alt="Ad Image" class="rounded ad-image">
                                            </td>
                                            <td>
                                                <div class="fw-bold text-primary">شقة للإيجار</div>
                                                <small class="text-muted">Apartment for Rent</small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">فاطمة علي</div>
                                                <small class="text-muted">Fatima Ali</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">300 ريال</span>
                                                <small class="d-block text-muted">300 SAR</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">عقارات</span>
                                                <small class="d-block text-muted">Real Estate</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">بريميوم</span>
                                                <small class="d-block text-muted">Premium</small>
                                            </td>
                                            <td>
                                                <div>2024/01/20</div>
                                                <small class="text-muted">20 Jan 2024</small>
                                            </td>
                                            <td>
                                                <div>2024/02/20</div>
                                                <small class="text-muted">20 Feb 2024</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary fs-6">890</span>
                                                <small class="d-block text-muted">Views</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-info" title="عرض التفاصيل - View Details" onclick="viewAdDetails(2)">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-warning" title="تعديل - Edit" onclick="editAd(2)">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-success" title="تحديث - Refresh" onclick="refreshAd(2)">
                                                        <i class="bi bi-arrow-clockwise"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" title="حذف - Delete" onclick="deleteAd(2)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <img src="https://via.placeholder.com/60x60/3490dc/ffffff?text=AD" 
                                                     alt="Ad Image" class="rounded ad-image">
                                            </td>
                                            <td>
                                                <div class="fw-bold text-primary">جهاز لابتوب للبيع</div>
                                                <small class="text-muted">Laptop for Sale</small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">محمد سعد</div>
                                                <small class="text-muted">Mohamed Saad</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">150 ريال</span>
                                                <small class="d-block text-muted">150 SAR</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">إلكترونيات</span>
                                                <small class="d-block text-muted">Electronics</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">عادي</span>
                                                <small class="d-block text-muted">Standard</small>
                                            </td>
                                            <td>
                                                <div>2024/01/25</div>
                                                <small class="text-muted">25 Jan 2024</small>
                                            </td>
                                            <td>
                                                <div>2024/02/25</div>
                                                <small class="text-muted">25 Feb 2024</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary fs-6">650</span>
                                                <small class="d-block text-muted">Views</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-info" title="عرض التفاصيل - View Details" onclick="viewAdDetails(3)">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-warning" title="تعديل - Edit" onclick="editAd(3)">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-success" title="تحديث - Refresh" onclick="refreshAd(3)">
                                                        <i class="bi bi-arrow-clockwise"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" title="حذف - Delete" onclick="deleteAd(3)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Car Services Tab -->
        <div class="tab-pane fade" id="car-services" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-tools me-2"></i>
                        إعلانات خدمات السيارات
                        <small class="ms-2">Car Services Ads</small>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">صورة الإعلان - Ad Image</th>
                                    <th>عنوان الإعلان - Ad Title</th>
                                    <th>اسم المعلن - Advertiser Name</th>
                                    <th>تكلفة الإعلان - Ad Cost</th>
                                    <th>نوع الباقة - Package Type</th>
                                    <th>تاريخ الإنشاء - Created Date</th>
                                    <th>عدد المشاهدات - Views Count</th>
                                    <th class="text-center">الإجراءات - Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center py-4" style="color: #6b7280;">
                                        <i class="bi bi-tools" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-2 mb-0">لا توجد إعلانات في قسم خدمات السيارات</p>
                                        <small>No ads in Car Services section</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Car Rent Tab -->
        <div class="tab-pane fade" id="car-rent" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-car-front me-2"></i>
                        إعلانات تأجير السيارات
                        <small class="ms-2">Car Rent Ads</small>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">صورة الإعلان - Ad Image</th>
                                    <th>عنوان الإعلان - Ad Title</th>
                                    <th>اسم المعلن - Advertiser Name</th>
                                    <th>تكلفة الإعلان - Ad Cost</th>
                                    <th>نوع الباقة - Package Type</th>
                                    <th>تاريخ الإنشاء - Created Date</th>
                                    <th>عدد المشاهدات - Views Count</th>
                                    <th class="text-center">الإجراءات - Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center py-4" style="color: #6b7280;">
                                        <i class="bi bi-car-front" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-2 mb-0">لا توجد إعلانات في قسم تأجير السيارات</p>
                                        <small>No ads in Car Rent section</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jobs Tab -->
        <div class="tab-pane fade" id="jobs" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-briefcase me-2"></i>
                        إعلانات الوظائف
                        <small class="ms-2">Jobs Ads</small>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">صورة الإعلان - Ad Image</th>
                                    <th>عنوان الإعلان - Ad Title</th>
                                    <th>اسم المعلن - Advertiser Name</th>
                                    <th>تكلفة الإعلان - Ad Cost</th>
                                    <th>نوع الباقة - Package Type</th>
                                    <th>تاريخ الإنشاء - Created Date</th>
                                    <th>عدد المشاهدات - Views Count</th>
                                    <th class="text-center">الإجراءات - Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center py-4" style="color: #6b7280;">
                                        <i class="bi bi-briefcase" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-2 mb-0">لا توجد إعلانات في قسم الوظائف</p>
                                        <small>No ads in Jobs section</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Electronics Tab -->
        <div class="tab-pane fade" id="electronics" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-tv me-2"></i>
                        إعلانات الإلكترونيات
                        <small class="ms-2">Electronics Ads</small>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">صورة الإعلان - Ad Image</th>
                                    <th>عنوان الإعلان - Ad Title</th>
                                    <th>اسم المعلن - Advertiser Name</th>
                                    <th>تكلفة الإعلان - Ad Cost</th>
                                    <th>نوع الباقة - Package Type</th>
                                    <th>تاريخ الإنشاء - Created Date</th>
                                    <th>عدد المشاهدات - Views Count</th>
                                    <th class="text-center">الإجراءات - Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center py-4" style="color: #6b7280;">
                                        <i class="bi bi-tv" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-2 mb-0">لا توجد إعلانات في قسم الإلكترونيات</p>
                                        <small>No ads in Electronics section</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Real Estate Tab -->
        <div class="tab-pane fade" id="real-estate" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-building me-2"></i>
                        إعلانات العقارات
                        <small class="ms-2">Real Estate Ads</small>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">صورة الإعلان - Ad Image</th>
                                    <th>عنوان الإعلان - Ad Title</th>
                                    <th>اسم المعلن - Advertiser Name</th>
                                    <th>تكلفة الإعلان - Ad Cost</th>
                                    <th>نوع الباقة - Package Type</th>
                                    <th>تاريخ الإنشاء - Created Date</th>
                                    <th>عدد المشاهدات - Views Count</th>
                                    <th class="text-center">الإجراءات - Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center py-4" style="color: #6b7280;">
                                        <i class="bi bi-building" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-2 mb-0">لا توجد إعلانات في قسم العقارات</p>
                                        <small>No ads in Real Estate section</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Car Sale Tab -->
        <div class="tab-pane fade" id="car-sale" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-car-front-fill me-2"></i>
                        إعلانات بيع السيارات
                        <small class="ms-2">Car Sale Ads</small>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">صورة الإعلان - Ad Image</th>
                                    <th>عنوان الإعلان - Ad Title</th>
                                    <th>اسم المعلن - Advertiser Name</th>
                                    <th>تكلفة الإعلان - Ad Cost</th>
                                    <th>نوع الباقة - Package Type</th>
                                    <th>تاريخ الإنشاء - Created Date</th>
                                    <th>عدد المشاهدات - Views Count</th>
                                    <th class="text-center">الإجراءات - Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center py-4" style="color: #6b7280;">
                                        <i class="bi bi-car-front-fill" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-2 mb-0">لا توجد إعلانات في قسم بيع السيارات</p>
                                        <small>No ads in Car Sale section</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Services Tab -->
        <div class="tab-pane fade" id="other-services" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-gear me-2"></i>
                        إعلانات الخدمات الأخرى
                        <small class="ms-2">Other Services Ads</small>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">صورة الإعلان - Ad Image</th>
                                    <th>عنوان الإعلان - Ad Title</th>
                                    <th>اسم المعلن - Advertiser Name</th>
                                    <th>تكلفة الإعلان - Ad Cost</th>
                                    <th>نوع الباقة - Package Type</th>
                                    <th>تاريخ الإنشاء - Created Date</th>
                                    <th>عدد المشاهدات - Views Count</th>
                                    <th class="text-center">الإجراءات - Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center py-4" style="color: #6b7280;">
                                        <i class="bi bi-gear" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-2 mb-0">لا توجد إعلانات في قسم الخدمات الأخرى</p>
                                        <small>No ads in Other Services section</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sorted Ads Tab -->
        <div class="tab-pane fade" id="sorted-ads" role="tabpanel">
            <!-- Filter Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-sort-numeric-down me-2 text-primary"></i>
                        خيارات الترتيب
                        <small class="text-muted">Sort Options</small>
                    </h5>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                ترتيب حسب المشاهدات
                                <small class="text-muted">Sort by Views</small>
                            </label>
                            <select id="viewsSortFilter" class="form-select">
                                <option value="desc">الأعلى مشاهدة <small>Highest Views</small></option>
                                <option value="asc">الأقل مشاهدة <small>Lowest Views</small></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                فلتر حسب الباقة
                                <small class="text-muted">Filter by Package</small>
                            </label>
                            <select id="packageFilter" class="form-select">
                                <option value="">جميع الباقات <small>All Packages</small></option>
                                <option value="مميز">مميز <small>Featured</small></option>
                                <option value="بريميوم">بريميوم <small>Premium</small></option>
                                <option value="عادي">عادي <small>Standard</small></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sorted Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-sort-numeric-down me-2"></i>
                        الإعلانات مرتبة حسب المشاهدات
                        <small class="ms-2">Ads Sorted by Views</small>
                    </h5>
                </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="sortedAdsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">صورة الإعلان - Ad Image</th>
                                            <th>عنوان الإعلان - Ad Title</th>
                                            <th>اسم المعلن - Advertiser Name</th>
                                            <th>تكلفة الإعلان - Ad Cost</th>
                                            <th>القسم - Category</th>
                                            <th>نوع الباقة - Package Type</th>
                                            <th>تاريخ الإنشاء - Created Date</th>
                                            <th>تاريخ الانتهاء - Expiry Date</th>
                                            <th>عدد المشاهدات - Views Count</th>
                                            <th class="text-center">الإجراءات - Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sortedTableBody">
                                        <!-- Data will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<style>
:root {
    --primary-blue: #3490dc;
}

.page-title {
    color: var(--primary-blue);
    font-weight: 600;
}

.btn-group .btn {
    border-radius: 8px;
    padding: 12px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
    text-align: center;
    line-height: 1.2;
}

.btn-group .btn.btn-primary {
    background: var(--primary-blue);
    border-color: var(--primary-blue);
    color: white;
}

.btn-group .btn.btn-outline-primary {
    background-color: #f8f9fa;
    color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.btn-group .btn.btn-outline-primary:hover {
    background-color: var(--primary-blue);
    color: white;
    border-color: var(--primary-blue);
}

.card {
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    border-bottom: none;
}

.ad-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.ad-image:hover {
    border-color: var(--primary-blue);
    transform: scale(1.1);
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid var(--primary-blue);
    font-weight: 600;
    color: var(--primary-blue);
    text-align: center;
    vertical-align: middle;
    padding: 15px 10px;
}

.table td {
    vertical-align: middle;
    padding: 15px 10px;
}

.table tbody tr:hover {
    background-color: rgba(52, 144, 220, 0.05);
    transform: scale(1.01);
    transition: all 0.3s ease;
}

.btn-group .btn {
    margin: 0 2px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.badge {
    font-size: 0.8rem;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
}

.card {
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.25);
}

.form-label {
    color: var(--primary-blue);
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    .nav-pills .nav-link {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}

.sortable-header {
    transition: all 0.3s ease;
    user-select: none;
}

.sortable-header:hover {
    background-color: rgba(52, 144, 220, 0.1);
    color: var(--primary-blue) !important;
}

.sortable-header:active {
    transform: scale(0.98);
}

.sort-controls {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    margin-bottom: 0;
}

.sort-controls .form-select {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    font-size: 0.9rem;
}

.sort-controls .form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.25);
}

/* Animated Buttons Styles */
.animated-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.animated-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    transform: translateY(0);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-radius: 8px !important;
    padding: 12px 20px;
    font-weight: 600;
}

.animated-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    scale: 1.05;
}

.animated-btn:active {
    transform: translateY(-1px);
    transition: all 0.1s ease;
}

.animated-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.animated-btn:hover::before {
    left: 100%;
}

/* Pulse animation for active button */
    .animated-btn.btn-primary,
    .animated-btn.btn-success,
    .animated-btn.btn-danger {
        animation: pulse 2s infinite;
    }

@keyframes pulse {
    0% {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    50% {
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }
    100% {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
}

/* Specific hover effects for each button type */
.animated-btn.btn-outline-primary:hover {
    background: linear-gradient(45deg, #0d6efd, #0b5ed7);
    border-color: #0b5ed7;
    color: white;
}

.animated-btn.btn-outline-success:hover {
    background: linear-gradient(45deg, #198754, #157347);
    border-color: #157347;
    color: white;
}

.animated-btn.btn-outline-danger:hover {
    background: linear-gradient(45deg, #dc3545, #b02a37);
    border-color: #b02a37;
    color: white;
}

/* Icon animation */
    .animated-btn i {
        transition: transform 0.3s ease;
    }
    
    .animated-btn:hover i {
        transform: scale(1.2) rotate(5deg);
    }
    
    /* Search Box Styles */
    .bg-gradient-light {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
        position: relative;
        overflow: hidden;
    }
    
    .bg-gradient-light::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    
    .search-container {
        position: relative;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .search-box-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: white;
        border-radius: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .search-box-wrapper:hover {
        box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .search-box-wrapper:focus-within {
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
        transform: translateY(-3px);
    }
    
    .search-icon {
        position: absolute;
        left: 20px;
        z-index: 2;
        color: #6c757d;
        font-size: 18px;
        transition: all 0.3s ease;
    }
    
    .search-box-wrapper:focus-within .search-icon {
        color: #0d6efd;
        transform: scale(1.1);
    }
    
    .search-input {
        border: none;
        outline: none;
        padding: 18px 60px 18px 55px;
        font-size: 16px;
        border-radius: 25px;
        background: transparent;
        width: 100%;
        transition: all 0.3s ease;
        box-shadow: none !important;
    }
    
    .search-input::placeholder {
        color: #adb5bd;
        font-style: italic;
        transition: all 0.3s ease;
    }
    
    .search-input:focus::placeholder {
        color: #dee2e6;
        transform: translateX(5px);
    }
    
    .search-clear {
        position: absolute;
        right: 20px;
        z-index: 2;
        color: #6c757d;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0.7;
    }
    
    .search-clear:hover {
        color: #dc3545;
        transform: scale(1.2);
        opacity: 1;
    }
    
    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        z-index: 1000;
        margin-top: 5px;
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #e9ecef;
    }
    
    .search-suggestion-item {
        padding: 12px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f8f9fa;
        display: flex;
        align-items: center;
    }
    
    .search-suggestion-item:hover {
        background: linear-gradient(90deg, #f8f9fa, #e9ecef);
        transform: translateX(5px);
    }
    
    .search-suggestion-item:last-child {
        border-bottom: none;
    }
    
    .search-suggestion-icon {
        margin-right: 10px;
        color: #6c757d;
        font-size: 14px;
    }
    
    .search-highlight {
        background: linear-gradient(120deg, #fff3cd, #ffeaa7);
        padding: 2px 4px;
        border-radius: 3px;
        font-weight: 600;
    }
    
    /* Search Animation */
    .search-loading {
        position: relative;
    }
    
    .search-loading::after {
        content: '';
        position: absolute;
        top: 50%;
        right: 20px;
        width: 20px;
        height: 20px;
        border: 2px solid #e9ecef;
        border-top: 2px solid #0d6efd;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        transform: translateY(-50%);
    }
    
    @keyframes spin {
        0% { transform: translateY(-50%) rotate(0deg); }
        100% { transform: translateY(-50%) rotate(360deg); }
    }
    
    /* No Results Message */
    .no-results {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    
    .no-results i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }
    
    /* Search Results Counter */
    .search-results-counter {
        background: linear-gradient(45deg, #0d6efd, #0b5ed7);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
        margin: 10px 0;
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
    }
</style>

<script>
// Sample data for demonstration
const adsData = [
    {
        id: 1,
        image: 'https://via.placeholder.com/60x60/3490dc/ffffff?text=AD',
        title: 'سيارة BMW للبيع',
        titleEn: 'BMW Car for Sale',
        advertiser: 'أحمد محمد',
        advertiserEn: 'Ahmed Mohamed',
        cost: '500 ريال',
        costEn: '500 SAR',
        category: 'سيارات',
        categoryEn: 'Cars',
        package: 'مميز',
        packageEn: 'Featured',
        created: '2024/01/15',
        createdEn: '15 Jan 2024',
        expiry: '2024/02/15',
        expiryEn: '15 Feb 2024',
        views: 1250
    },
    {
        id: 2,
        image: 'https://via.placeholder.com/60x60/3490dc/ffffff?text=AD',
        title: 'شقة للإيجار',
        titleEn: 'Apartment for Rent',
        advertiser: 'فاطمة علي',
        advertiserEn: 'Fatima Ali',
        cost: '300 ريال',
        costEn: '300 SAR',
        category: 'عقارات',
        categoryEn: 'Real Estate',
        package: 'بريميوم',
        packageEn: 'Premium',
        created: '2024/01/20',
        createdEn: '20 Jan 2024',
        expiry: '2024/02/20',
        expiryEn: '20 Feb 2024',
        views: 890
    },
    {
        id: 3,
        image: 'https://via.placeholder.com/60x60/3490dc/ffffff?text=AD',
        title: 'جهاز لابتوب للبيع',
        titleEn: 'Laptop for Sale',
        advertiser: 'محمد سعد',
        advertiserEn: 'Mohamed Saad',
        cost: '150 ريال',
        costEn: '150 SAR',
        category: 'إلكترونيات',
        categoryEn: 'Electronics',
        package: 'عادي',
        packageEn: 'Standard',
        created: '2024/01/25',
        createdEn: '25 Jan 2024',
        expiry: '2024/02/25',
        expiryEn: '25 Feb 2024',
        views: 650
    }
];

// Function to get package badge class
function getPackageBadgeClass(packageType) {
    switch(packageType) {
        case 'مميز': return 'bg-warning text-dark';
        case 'بريميوم': return 'bg-primary';
        case 'عادي': return 'bg-secondary';
        default: return 'bg-secondary';
    }
}

// Function to render table (generic for both tables)
function renderTable(data, tableBodyId) {
    const tbody = document.getElementById(tableBodyId);
    tbody.innerHTML = '';
    
    data.forEach(ad => {
        const row = `
            <tr class="fade-in">
                <td class="text-center">
                    <img src="${ad.image}" alt="Ad Image" class="rounded ad-image">
                </td>
                <td>
                    <div class="fw-bold text-primary">${ad.title}</div>
                    <small class="text-muted">${ad.titleEn}</small>
                </td>
                <td>
                    <div class="fw-semibold">${ad.advertiser}</div>
                    <small class="text-muted">${ad.advertiserEn}</small>
                </td>
                <td>
                    <span class="badge bg-success">${ad.cost}</span>
                    <small class="d-block text-muted">${ad.costEn}</small>
                </td>
                <td>
                    <span class="badge bg-info">${ad.category}</span>
                    <small class="d-block text-muted">${ad.categoryEn}</small>
                </td>
                <td>
                    <span class="badge ${getPackageBadgeClass(ad.package)}">${ad.package}</span>
                    <small class="d-block text-muted">${ad.packageEn}</small>
                </td>
                <td>
                    <div>${ad.created}</div>
                    <small class="text-muted">${ad.createdEn}</small>
                </td>
                <td>
                    <div>${ad.expiry}</div>
                    <small class="text-muted">${ad.expiryEn}</small>
                </td>
                <td>
                    <span class="badge bg-primary fs-6">${ad.views.toLocaleString()}</span>
                    <small class="d-block text-muted">Views</small>
                </td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-info" title="عرض التفاصيل - View Details" onclick="viewAdDetails(${ad.id})">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning" title="تعديل - Edit" onclick="editAd(${ad.id})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" title="تحديث - Refresh" onclick="refreshAd(${ad.id})">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" title="حذف - Delete" onclick="deleteAd(${ad.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Function to render sorted table (backward compatibility)
function renderSortedTable(data) {
    renderTable(data, 'sortedTableBody');
}

// Function to render all ads table
function renderAllAdsTable(data) {
    renderTable(data, 'allAdsTableBody');
}

// Function to filter and sort data for sorted ads tab
function filterAndSortData() {
    let filteredData = [...adsData];
    
    // Apply filters
    const packageFilter = document.getElementById('packageFilter').value;
    
    if (packageFilter) {
        filteredData = filteredData.filter(ad => ad.package === packageFilter);
    }
    
    // Apply sorting
    const sortOrder = document.getElementById('viewsSortFilter').value;
    filteredData.sort((a, b) => {
        return sortOrder === 'desc' ? b.views - a.views : a.views - b.views;
    });
    
    renderSortedTable(filteredData);
}

// Function to filter and sort data for all ads tab
function filterAndSortAllAds(sortOrder = 'desc', packageFilter = '') {
    let filteredData = [...adsData];
    
    // Apply filters
    if (packageFilter) {
        filteredData = filteredData.filter(ad => ad.package === packageFilter);
    }
    
    // Apply sorting
    filteredData.sort((a, b) => {
        return sortOrder === 'desc' ? b.views - a.views : a.views - b.views;
    });
    
    renderAllAdsTable(filteredData);
}

// Function to sort all ads table by clicking header
let allAdsSortOrder = 'desc';
function sortAllAdsTable(column) {
    if (column === 'views') {
        // Toggle sort order
        allAdsSortOrder = allAdsSortOrder === 'desc' ? 'asc' : 'desc';
        
        // Update the icon
        const icon = document.querySelector('.sortable-header i');
        icon.className = allAdsSortOrder === 'desc' ? 'bi bi-arrow-down ms-1 text-primary' : 'bi bi-arrow-up ms-1 text-primary';
        
        // Apply the sort
        filterAndSortAllAds(allAdsSortOrder);
    }
}

// New functions for control buttons
function showAllData() {
    // Update button states
    updateButtonStates('showAllDataBtn');
    
    // Get current search term
    const searchInput = document.getElementById('searchInput');
    const searchTerm = searchInput.value.trim().toLowerCase();
    
    if (searchTerm === '') {
        filteredData = [...originalData];
    } else {
        // Apply search to original data
        filteredData = originalData.filter(ad => {
            return (
                ad.title.toLowerCase().includes(searchTerm) ||
                ad.advertiser.toLowerCase().includes(searchTerm) ||
                ad.category.toLowerCase().includes(searchTerm) ||
                ad.package.toLowerCase().includes(searchTerm) ||
                ad.cost.toString().includes(searchTerm) ||
                ad.views.toString().includes(searchTerm)
            );
        });
    }
    
    renderTable(filteredData, 'allAdsTableBody');
    updateSearchCounter(filteredData.length, originalData.length);
    
    // Update sort icon
    const icon = document.querySelector('.sortable-header i');
    icon.className = 'bi bi-arrow-down-up ms-1 text-primary';
    allAdsSortOrder = 'desc';
}

function sortByHighestViews() {
    // Update button states
    updateButtonStates('sortHighestViewsBtn');
    
    // Get current search term
    const searchInput = document.getElementById('searchInput');
    const searchTerm = searchInput.value.trim().toLowerCase();
    
    let dataToSort;
    if (searchTerm === '') {
        dataToSort = [...originalData];
    } else {
        // Apply search first, then sort
        dataToSort = originalData.filter(ad => {
            return (
                ad.title.toLowerCase().includes(searchTerm) ||
                ad.advertiser.toLowerCase().includes(searchTerm) ||
                ad.category.toLowerCase().includes(searchTerm) ||
                ad.package.toLowerCase().includes(searchTerm) ||
                ad.cost.toString().includes(searchTerm) ||
                ad.views.toString().includes(searchTerm)
            );
        });
    }
    
    filteredData = dataToSort.sort((a, b) => b.views - a.views);
    renderTable(filteredData, 'allAdsTableBody');
    updateSearchCounter(filteredData.length, originalData.length);
    
    // Update sort icon
    const icon = document.querySelector('.sortable-header i');
    icon.className = 'bi bi-arrow-down ms-1 text-primary';
    allAdsSortOrder = 'desc';
}

function sortByLowestViews() {
    // Update button states
    updateButtonStates('sortLowestViewsBtn');
    
    // Get current search term
    const searchInput = document.getElementById('searchInput');
    const searchTerm = searchInput.value.trim().toLowerCase();
    
    let dataToSort;
    if (searchTerm === '') {
        dataToSort = [...originalData];
    } else {
        // Apply search first, then sort
        dataToSort = originalData.filter(ad => {
            return (
                ad.title.toLowerCase().includes(searchTerm) ||
                ad.advertiser.toLowerCase().includes(searchTerm) ||
                ad.category.toLowerCase().includes(searchTerm) ||
                ad.package.toLowerCase().includes(searchTerm) ||
                ad.cost.toString().includes(searchTerm) ||
                ad.views.toString().includes(searchTerm)
            );
        });
    }
    
    filteredData = dataToSort.sort((a, b) => a.views - b.views);
    renderTable(filteredData, 'allAdsTableBody');
    updateSearchCounter(filteredData.length, originalData.length);
    
    // Update sort icon
    const icon = document.querySelector('.sortable-header i');
    icon.className = 'bi bi-arrow-up ms-1 text-primary';
    allAdsSortOrder = 'asc';
}

// Function to update button states
function updateButtonStates(activeButtonId) {
    // Reset all buttons to outline style with animated-btn class
    document.getElementById('showAllDataBtn').className = 'btn btn-outline-primary animated-btn';
    document.getElementById('sortHighestViewsBtn').className = 'btn btn-outline-success animated-btn';
    document.getElementById('sortLowestViewsBtn').className = 'btn btn-outline-danger animated-btn';
    
    // Set active button to solid style with animated-btn class
    const activeButton = document.getElementById(activeButtonId);
    if (activeButtonId === 'showAllDataBtn') {
        activeButton.className = 'btn btn-primary animated-btn';
    } else if (activeButtonId === 'sortHighestViewsBtn') {
        activeButton.className = 'btn btn-success animated-btn';
    } else if (activeButtonId === 'sortLowestViewsBtn') {
        activeButton.className = 'btn btn-danger animated-btn';
    }
}

// Search functionality
let searchTimeout;
let originalData = [...adsData];
let filteredData = [...adsData];

function searchAds() {
    const searchInput = document.getElementById('searchInput');
    const searchClear = document.getElementById('searchClear');
    const searchTerm = searchInput.value.trim().toLowerCase();
    
    // Show/hide clear button
    if (searchTerm.length > 0) {
        searchClear.style.display = 'block';
    } else {
        searchClear.style.display = 'none';
    }
    
    // Add loading animation
    searchInput.parentElement.classList.add('search-loading');
    
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    // Set new timeout for search
    searchTimeout = setTimeout(() => {
        performSearch(searchTerm);
        searchInput.parentElement.classList.remove('search-loading');
    }, 300);
}

function performSearch(searchTerm) {
    if (searchTerm === '') {
        filteredData = [...originalData];
        renderTable(filteredData, 'allAdsTableBody');
        updateSearchCounter(filteredData.length, originalData.length);
        hideSuggestions();
        return;
    }
    
    // Filter data based on search term
    filteredData = originalData.filter(ad => {
        return (
            ad.title.toLowerCase().includes(searchTerm) ||
            ad.advertiser.toLowerCase().includes(searchTerm) ||
            ad.category.toLowerCase().includes(searchTerm) ||
            ad.package.toLowerCase().includes(searchTerm) ||
            ad.cost.toString().includes(searchTerm) ||
            ad.views.toString().includes(searchTerm)
        );
    });
    
    // Render filtered results
    renderTable(filteredData, 'allAdsTableBody');
    updateSearchCounter(filteredData.length, originalData.length);
    
    // Show suggestions if no exact matches
    if (filteredData.length === 0) {
        showNoResultsMessage();
    } else {
        hideSuggestions();
    }
}

function clearSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchClear = document.getElementById('searchClear');
    
    searchInput.value = '';
    searchClear.style.display = 'none';
    filteredData = [...originalData];
    renderTable(filteredData, 'allAdsTableBody');
    updateSearchCounter(filteredData.length, originalData.length);
    hideSuggestions();
    searchInput.focus();
}

function updateSearchCounter(filtered, total) {
    // Remove existing counter
    const existingCounter = document.querySelector('.search-results-counter');
    if (existingCounter) {
        existingCounter.remove();
    }
    
    // Add new counter if search is active
    const searchInput = document.getElementById('searchInput');
    if (searchInput.value.trim() !== '') {
        const counter = document.createElement('div');
        counter.className = 'search-results-counter';
        counter.innerHTML = `
            <i class="bi bi-search me-1"></i>
            ${filtered} من ${total} إعلان
            <small class="ms-1">${filtered} of ${total} ads</small>
        `;
        
        const tableContainer = document.querySelector('.table-responsive');
        tableContainer.parentNode.insertBefore(counter, tableContainer);
    }
}

function showNoResultsMessage() {
    const tableBody = document.getElementById('allAdsTableBody');
    tableBody.innerHTML = `
        <tr>
            <td colspan="10" class="no-results">
                <i class="bi bi-search"></i>
                <h5>لا توجد نتائج</h5>
                <p class="mb-0">لم يتم العثور على إعلانات تطابق بحثك</p>
                <small class="text-muted">No ads found matching your search</small>
            </td>
        </tr>
    `;
}

function hideSuggestions() {
    const suggestions = document.getElementById('searchSuggestions');
    suggestions.style.display = 'none';
}

// Enhanced search with suggestions
function showSearchSuggestions(searchTerm) {
    const suggestions = document.getElementById('searchSuggestions');
    const categories = [...new Set(originalData.map(ad => ad.category))];
    const packageTypes = [...new Set(originalData.map(ad => ad.package))];
    
    let suggestionHTML = '';
    
    // Category suggestions
    categories.forEach(category => {
        if (category.toLowerCase().includes(searchTerm.toLowerCase())) {
            suggestionHTML += `
                <div class="search-suggestion-item" onclick="selectSuggestion('${category}')">
                    <i class="bi bi-tag search-suggestion-icon"></i>
                    <span>${category}</span>
                </div>
            `;
        }
    });
    
    // Package type suggestions
    packageTypes.forEach(packageType => {
        if (packageType.toLowerCase().includes(searchTerm.toLowerCase())) {
            suggestionHTML += `
                <div class="search-suggestion-item" onclick="selectSuggestion('${packageType}')">
                    <i class="bi bi-box search-suggestion-icon"></i>
                    <span>${packageType}</span>
                </div>
            `;
        }
    });
    
    if (suggestionHTML) {
        suggestions.innerHTML = suggestionHTML;
        suggestions.style.display = 'block';
    } else {
        suggestions.style.display = 'none';
    }
}

function selectSuggestion(suggestion) {
    const searchInput = document.getElementById('searchInput');
    searchInput.value = suggestion;
    searchAds();
    hideSuggestions();
}

// Event listeners for filters
document.addEventListener('DOMContentLoaded', function() {
    // Initialize both tables with default data
    filterAndSortAllAds('desc');
    filterAndSortData();
    
    // Add event listeners for sorted ads tab
    document.getElementById('viewsSortFilter').addEventListener('change', filterAndSortData);
    document.getElementById('packageFilter').addEventListener('change', filterAndSortData);
    
    // Initialize button states
    updateButtonStates('showAllDataBtn');
});

// Action functions
function viewAdDetails(adId) {
    alert(`عرض تفاصيل الإعلان رقم ${adId} - View details for ad #${adId}`);
}

function editAd(adId) {
    if (confirm(`هل تريد تعديل الإعلان رقم ${adId}؟ - Do you want to edit ad #${adId}?`)) {
        alert(`تم فتح صفحة التعديل للإعلان رقم ${adId} - Edit page opened for ad #${adId}`);
    }
}

function refreshAd(adId) {
    if (confirm(`هل تريد تحديث الإعلان رقم ${adId}؟ - Do you want to refresh ad #${adId}?`)) {
        // Add loading effect
        const table = document.getElementById('sortedAdsTable');
        table.classList.add('loading');
        
        setTimeout(() => {
            table.classList.remove('loading');
            alert(`تم تحديث الإعلان رقم ${adId} بنجاح - Ad #${adId} refreshed successfully`);
            filterAndSortData(); // Refresh the table
        }, 1000);
    }
}

function deleteAd(adId) {
    if (confirm(`هل أنت متأكد من حذف الإعلان رقم ${adId}؟ - Are you sure you want to delete ad #${adId}?`)) {
        // Find and remove the ad from data
        const index = adsData.findIndex(ad => ad.id === adId);
        if (index > -1) {
            adsData.splice(index, 1);
            filterAndSortData(); // Refresh the table
            alert(`تم حذف الإعلان رقم ${adId} بنجاح - Ad #${adId} deleted successfully`);
        }
    }
}

// Show alert function
function showAlert(message, type = 'success') {
    const alertContainer = document.getElementById('alertContainer');
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    alertContainer.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endsection