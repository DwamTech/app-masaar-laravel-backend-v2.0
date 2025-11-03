@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="page-title mb-1">
                        <i class="bi bi-gear-fill me-2"></i>
                        متغيرات النظام - System Variables
                    </h2>
                    <p class="text-muted mb-0">إدارة إعدادات ومتغيرات النظام الأساسية - Manage system settings and variables</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success" onclick="saveAllSettings()">
                        <i class="bi bi-check-lg me-1"></i>
                        حفظ جميع التغييرات - Save All Changes
                    </button>
                </div>
            </div>

            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-4" id="systemTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="true">
                        <i class="bi bi-grid-3x3-gap me-2"></i>
                        أقسام الإعلانات - Categories
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms" type="button" role="tab" aria-controls="terms" aria-selected="false">
                        <i class="bi bi-file-text me-2"></i>
                        الشروط والأحكام - Terms & Conditions
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="systemTabsContent">
                <!-- Categories Tab -->
                <div class="tab-pane fade show active" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                    <!-- Categories Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-grid-3x3-gap me-2"></i>
                        أقسام الإعلانات - Advertisement Categories
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0" id="categoriesContainer">
                        <!-- Car Sales -->
                        <div class="col-lg-6 col-xl-4">
                            <div class="category-card border-end border-bottom p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="category-icon">
                                        <i class="bi bi-car-front-fill text-primary fs-2"></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editCategory('car_sales')"><i class="bi bi-pencil me-2"></i>تعديل</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="publishCategory('car_sales')"><i class="bi bi-eye me-2"></i>نشر</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteCategory('car_sales')"><i class="bi bi-trash me-2"></i>حذف</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <h6 class="category-title mb-3">بيع السيارات - Car Sales</h6>
                                <div class="category-stats">
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد الإعلانات - Ads Count</label>
                                        <input type="number" class="form-control form-control-sm" value="150" data-category="car_sales" data-field="ads_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">أقصى عدد مجاني - Max Free Ads</label>
                                        <input type="number" class="form-control form-control-sm" value="3" data-category="car_sales" data-field="max_free_ads">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد أفضل المعلنين - Top Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="85" data-category="car_sales" data-field="advertisers_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد المعلنين في خانة الظهور اليومي - Daily Display Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="12" data-category="car_sales" data-field="daily_display_advertisers">
                                    </div>
                                    <div class="stat-item">
                                        <label class="form-label text-muted small">سعر الظهور اليومي - Daily Display Price</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" value="25" data-category="car_sales" data-field="daily_price">
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Real Estate -->
                        <div class="col-lg-6 col-xl-4">
                            <div class="category-card border-end border-bottom p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="category-icon">
                                        <i class="bi bi-house-fill text-success fs-2"></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editCategory('real_estate')"><i class="bi bi-pencil me-2"></i>تعديل</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="publishCategory('real_estate')"><i class="bi bi-eye me-2"></i>نشر</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteCategory('real_estate')"><i class="bi bi-trash me-2"></i>حذف</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <h6 class="category-title mb-3">العقارات - Real Estate</h6>
                                <div class="category-stats">
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد الإعلانات - Ads Count</label>
                                        <input type="number" class="form-control form-control-sm" value="200" data-category="real_estate" data-field="ads_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">أقصى عدد مجاني - Max Free Ads</label>
                                        <input type="number" class="form-control form-control-sm" value="2" data-category="real_estate" data-field="max_free_ads">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد أفضل المعلنين - Top Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="120" data-category="real_estate" data-field="advertisers_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد المعلنين في خانة الظهور اليومي - Daily Display Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="15" data-category="real_estate" data-field="daily_display_advertisers">
                                    </div>
                                    <div class="stat-item">
                                        <label class="form-label text-muted small">سعر الظهور اليومي - Daily Display Price</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" value="30" data-category="real_estate" data-field="daily_price">
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Electronics & Home Appliances -->
                        <div class="col-lg-6 col-xl-4">
                            <div class="category-card border-end border-bottom p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="category-icon">
                                        <i class="bi bi-laptop text-info fs-2"></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editCategory('electronics')"><i class="bi bi-pencil me-2"></i>تعديل</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="publishCategory('electronics')"><i class="bi bi-eye me-2"></i>نشر</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteCategory('electronics')"><i class="bi bi-trash me-2"></i>حذف</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <h6 class="category-title mb-3">إلكترونيات ومنزلية - Electronics & Home</h6>
                                <div class="category-stats">
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد الإعلانات - Ads Count</label>
                                        <input type="number" class="form-control form-control-sm" value="300" data-category="electronics" data-field="ads_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">أقصى عدد مجاني - Max Free Ads</label>
                                        <input type="number" class="form-control form-control-sm" value="5" data-category="electronics" data-field="max_free_ads">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد أفضل المعلنين - Top Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="180" data-category="electronics" data-field="advertisers_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد المعلنين في خانة الظهور اليومي - Daily Display Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="20" data-category="electronics" data-field="daily_display_advertisers">
                                    </div>
                                    <div class="stat-item">
                                        <label class="form-label text-muted small">سعر الظهور اليومي - Daily Display Price</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" value="15" data-category="electronics" data-field="daily_price">
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Jobs -->
                        <div class="col-lg-6 col-xl-4">
                            <div class="category-card border-end border-bottom p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="category-icon">
                                        <i class="bi bi-briefcase-fill text-warning fs-2"></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editCategory('jobs')"><i class="bi bi-pencil me-2"></i>تعديل</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="publishCategory('jobs')"><i class="bi bi-eye me-2"></i>نشر</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteCategory('jobs')"><i class="bi bi-trash me-2"></i>حذف</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <h6 class="category-title mb-3">الوظائف - Jobs</h6>
                                <div class="category-stats">
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد الإعلانات - Ads Count</label>
                                        <input type="number" class="form-control form-control-sm" value="75" data-category="jobs" data-field="ads_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">أقصى عدد مجاني - Max Free Ads</label>
                                        <input type="number" class="form-control form-control-sm" value="2" data-category="jobs" data-field="max_free_ads">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد أفضل المعلنين - Top Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="45" data-category="jobs" data-field="advertisers_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد المعلنين في خانة الظهور اليومي - Daily Display Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="8" data-category="jobs" data-field="daily_display_advertisers">
                                    </div>
                                    <div class="stat-item">
                                        <label class="form-label text-muted small">سعر الظهور اليومي - Daily Display Price</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" value="20" data-category="jobs" data-field="daily_price">
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Car Rent -->
                        <div class="col-lg-6 col-xl-4">
                            <div class="category-card border-end border-bottom p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="category-icon">
                                        <i class="bi bi-key-fill text-danger fs-2"></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editCategory('car_rent')"><i class="bi bi-pencil me-2"></i>تعديل</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="publishCategory('car_rent')"><i class="bi bi-eye me-2"></i>نشر</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteCategory('car_rent')"><i class="bi bi-trash me-2"></i>حذف</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <h6 class="category-title mb-3">تأجير السيارات - Car Rent</h6>
                                <div class="category-stats">
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد الإعلانات - Ads Count</label>
                                        <input type="number" class="form-control form-control-sm" value="60" data-category="car_rent" data-field="ads_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">أقصى عدد مجاني - Max Free Ads</label>
                                        <input type="number" class="form-control form-control-sm" value="1" data-category="car_rent" data-field="max_free_ads">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد أفضل المعلنين - Top Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="35" data-category="car_rent" data-field="advertisers_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد المعلنين في خانة الظهور اليومي - Daily Display Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="6" data-category="car_rent" data-field="daily_display_advertisers">
                                    </div>
                                    <div class="stat-item">
                                        <label class="form-label text-muted small">سعر الظهور اليومي - Daily Display Price</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" value="35" data-category="car_rent" data-field="daily_price">
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Car Services -->
                        <div class="col-lg-6 col-xl-4">
                            <div class="category-card border-end border-bottom p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="category-icon">
                                        <i class="bi bi-tools text-secondary fs-2"></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editCategory('car_services')"><i class="bi bi-pencil me-2"></i>تعديل</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="publishCategory('car_services')"><i class="bi bi-eye me-2"></i>نشر</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteCategory('car_services')"><i class="bi bi-trash me-2"></i>حذف</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <h6 class="category-title mb-3">خدمات السيارات - Car Services</h6>
                                <div class="category-stats">
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد الإعلانات - Ads Count</label>
                                        <input type="number" class="form-control form-control-sm" value="90" data-category="car_services" data-field="ads_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">أقصى عدد مجاني - Max Free Ads</label>
                                        <input type="number" class="form-control form-control-sm" value="3" data-category="car_services" data-field="max_free_ads">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد أفضل المعلنين - Top Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="55" data-category="car_services" data-field="advertisers_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد المعلنين في خانة الظهور اليومي - Daily Display Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="10" data-category="car_services" data-field="daily_display_advertisers">
                                    </div>
                                    <div class="stat-item">
                                        <label class="form-label text-muted small">سعر الظهور اليومي - Daily Display Price</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" value="18" data-category="car_services" data-field="daily_price">
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Restaurants -->
                        <div class="col-lg-6 col-xl-4">
                            <div class="category-card border-end border-bottom p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="category-icon">
                                        <i class="bi bi-cup-hot-fill text-orange fs-2"></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editCategory('restaurants')"><i class="bi bi-pencil me-2"></i>تعديل</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="publishCategory('restaurants')"><i class="bi bi-eye me-2"></i>نشر</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteCategory('restaurants')"><i class="bi bi-trash me-2"></i>حذف</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <h6 class="category-title mb-3">المطاعم - Restaurants</h6>
                                <div class="category-stats">
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد الإعلانات - Ads Count</label>
                                        <input type="number" class="form-control form-control-sm" value="120" data-category="restaurants" data-field="ads_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">أقصى عدد مجاني - Max Free Ads</label>
                                        <input type="number" class="form-control form-control-sm" value="2" data-category="restaurants" data-field="max_free_ads">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد أفضل المعلنين - Top Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="70" data-category="restaurants" data-field="advertisers_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد المعلنين في خانة الظهور اليومي - Daily Display Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="14" data-category="restaurants" data-field="daily_display_advertisers">
                                    </div>
                                    <div class="stat-item">
                                        <label class="form-label text-muted small">سعر الظهور اليومي - Daily Display Price</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" value="22" data-category="restaurants" data-field="daily_price">
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Other Services -->
                        <div class="col-lg-6 col-xl-4">
                            <div class="category-card border-bottom p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="category-icon">
                                        <i class="bi bi-three-dots text-dark fs-2"></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editCategory('other_services')"><i class="bi bi-pencil me-2"></i>تعديل</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="publishCategory('other_services')"><i class="bi bi-eye me-2"></i>نشر</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteCategory('other_services')"><i class="bi bi-trash me-2"></i>حذف</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <h6 class="category-title mb-3">خدمات أخرى - Other Services</h6>
                                <div class="category-stats">
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد الإعلانات - Ads Count</label>
                                        <input type="number" class="form-control form-control-sm" value="180" data-category="other_services" data-field="ads_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">أقصى عدد مجاني - Max Free Ads</label>
                                        <input type="number" class="form-control form-control-sm" value="4" data-category="other_services" data-field="max_free_ads">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد أفضل المعلنين - Top Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="95" data-category="other_services" data-field="advertisers_count">
                                    </div>
                                    <div class="stat-item mb-2">
                                        <label class="form-label text-muted small">عدد المعلنين في خانة الظهور اليومي - Daily Display Advertisers Count</label>
                                        <input type="number" class="form-control form-control-sm" value="18" data-category="other_services" data-field="daily_display_advertisers">
                                    </div>
                                    <div class="stat-item">
                                        <label class="form-label text-muted small">سعر الظهور اليومي - Daily Display Price</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" value="12" data-category="other_services" data-field="daily_price">
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Price Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-currency-dollar me-2"></i>
                        سعر الاشتراك اليومي - Daily Subscription Price
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-2">سعر الاشتراك في الظهور اليومي - Daily Display Subscription Price</h6>
                            <p class="text-muted mb-0">
                                السعر الأساسي للاشتراك في خدمة الظهور اليومي للإعلانات<br>
                                <small>Base price for daily display subscription service for advertisements</small>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="number" class="form-control form-control-lg text-center fw-bold" 
                                       id="dailySubscriptionPrice" value="50" min="1" step="0.5">
                                <span class="input-group-text fs-5">ريال سعودي</span>
                            </div>
                            <div class="text-center mt-2">
                                <button class="btn btn-danger btn-sm" onclick="updateSubscriptionPrice()">
                                    <i class="bi bi-check-lg me-1"></i>
                                    تحديث السعر - Update Price
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Packages Section -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-gradient-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-box-seam me-2"></i>
                إعدادات الباقات - Packages Settings
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="border-0 px-4 py-3">
                                <strong>البيانات - Data</strong>
                            </th>
                            <th class="border-0 px-4 py-3 text-center">
                                <strong>مميزة - Featured</strong>
                            </th>
                            <th class="border-0 px-4 py-3 text-center">
                                <strong>بريميوم - Premium</strong>
                            </th>
                            <th class="border-0 px-4 py-3 text-center">
                                <strong>بريميوم ستار - Premium Star</strong>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-bottom">
                            <td class="px-4 py-3">
                                <strong class="text-dark">السعر - Price</strong>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="input-group">
                                    <input type="number" class="form-control text-center" id="featured_price" value="50" min="0" step="0.01">
                                    <span class="input-group-text">درهم</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="input-group">
                                    <input type="number" class="form-control text-center" id="premium_price" value="100" min="0" step="0.01">
                                    <span class="input-group-text">درهم</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="input-group">
                                    <input type="number" class="form-control text-center" id="premium_star_price" value="200" min="0" step="0.01">
                                    <span class="input-group-text">درهم</span>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-bottom">
                                <td class="px-4 py-3">
                                    <strong class="text-dark">مدة الصلاحية (أيام) - Validity (Days)</strong>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number" class="form-control text-center" id="featured_days" value="30" min="1">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number" class="form-control text-center" id="premium_days" value="60" min="1">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number" class="form-control text-center" id="premium_star_days" value="90" min="1">
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-center">
                <button type="button" class="btn btn-primary btn-lg px-5" onclick="savePackagesPrices()">
                    <i class="bi bi-save me-2"></i>
                    حفظ أسعار الباقات - Save Package Prices
                </button>
            </div>
        </div>
    </div>


                </div>
                <!-- End Categories Tab -->

                <!-- Terms & Conditions Tab -->
                <div class="tab-pane fade" id="terms" role="tabpanel" aria-labelledby="terms-tab">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-file-text me-2"></i>
                                الشروط والأحكام - Terms & Conditions
                            </h5>
                            <button type="button" class="btn btn-light btn-sm rounded-pill px-3" onclick="addNewTerm()">
                                <i class="bi bi-plus-lg me-1"></i>
                                إضافة - Add
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <div id="termsContainer" class="terms-container">
                                <!-- Terms will be dynamically added here -->
                            </div>
                            
                            <!-- Action Button -->
                            <div class="d-flex justify-content-center mt-5">
                                <button type="button" class="btn btn-primary btn-lg rounded-pill px-5 shadow" onclick="saveAllTerms()">
                                    <i class="bi bi-check-lg me-2"></i>
                                    حفظ الشروط - Save Terms
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Terms & Conditions Tab -->
            </div>
            <!-- End Tab Content -->
        </div>
    </div>
</div>

<style>
.page-title {
    color: #2c3e50;
    font-weight: 600;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545 0%, #a71e2a 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.terms-container {
    min-height: 200px;
}

.term-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
    position: relative;
}

.term-item:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.term-item .delete-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.term-item .form-control {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.term-item .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.package-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.category-card {
    transition: all 0.3s ease;
    min-height: 280px;
}

.category-card:hover {
    background-color: rgba(0, 123, 255, 0.02);
    transform: translateY(-2px);
}

.category-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
}

.category-title {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
}

.stat-item label {
    font-size: 0.75rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.form-control-sm {
    font-size: 0.8rem;
}

.dropdown-toggle::after {
    display: none;
}

.text-orange {
    color: #fd7e14 !important;
}

.card {
    transition: all 0.3s ease;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .category-card {
        border-end: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Category management functions
function editCategory(categoryId) {
    showAlert(`تم فتح نافذة تعديل القسم: ${categoryId}`, 'info');
    // Here you would typically open a modal or navigate to edit page
}

function publishCategory(categoryId) {
    if (confirm('هل أنت متأكد من نشر هذا القسم؟')) {
        showAlert(`تم نشر القسم: ${categoryId} بنجاح`, 'success');
        // Send publish request to server
    }
}

function deleteCategory(categoryId) {
    if (confirm('هل أنت متأكد من حذف هذا القسم؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        showAlert(`تم حذف القسم: ${categoryId}`, 'danger');
        // Send delete request to server
        // Remove category card from DOM
    }
}

// Save all settings
function saveAllSettings() {
    const saveBtn = event.target;
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>جاري الحفظ...';
    saveBtn.disabled = true;
    
    // Collect all category data
    const categories = {};
    document.querySelectorAll('[data-category]').forEach(input => {
        const category = input.dataset.category;
        const field = input.dataset.field;
        
        if (!categories[category]) {
            categories[category] = {};
        }
        
        categories[category][field] = input.value;
    });
    
    // Collect terms and conditions data
    const termsContainer = document.getElementById('termsContainer');
    const termItems = termsContainer ? termsContainer.querySelectorAll('.term-item') : [];
    const termsData = [];
    
    termItems.forEach((item) => {
        const termId = item.getAttribute('data-term-id');
        const titleElement = document.getElementById(`term_title_${termId}`);
        const descriptionElement = document.getElementById(`term_description_${termId}`);
        
        if (titleElement && descriptionElement) {
            const title = titleElement.value.trim();
            const description = descriptionElement.value.trim();
            
            if (title && description) {
                termsData.push({
                    id: termId,
                    title: title,
                    description: description
                });
            }
        }
    });
    
    // Get subscription price
    const subscriptionPrice = document.getElementById('dailySubscriptionPrice').value;
    
    // Simulate API call
    setTimeout(() => {
        showAlert('تم حفظ جميع الإعدادات بنجاح! - All settings saved successfully!', 'success');
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
        
        console.log('Saved data:', {
            categories: categories,
            terms: termsData,
            subscriptionPrice: subscriptionPrice
        });
    }, 2000);
}

// Update subscription price
function updateSubscriptionPrice() {
    const price = document.getElementById('dailySubscriptionPrice').value;
    
    if (price && price > 0) {
        // Send update request to server
        showAlert(`تم تحديث سعر الاشتراك اليومي إلى ${price} ريال`, 'success');
    } else {
        showAlert('يرجى إدخال سعر صحيح', 'danger');
    }
}

// Package Management Functions
function editPackagePrice(packageType) {
    const input = document.getElementById(packageType + '_price');
    input.focus();
    input.select();
    showAlert('يمكنك الآن تعديل سعر الباقة', 'info');
}

function savePackagePrice(packageType) {
    const input = document.getElementById(packageType + '_price');
    const price = input.value;
    
    if (price && price > 0) {
        showAlert(`تم حفظ سعر باقة ${getPackageName(packageType)} بنجاح! السعر الجديد: ${price} ريال`, 'success');
        // هنا يمكن إضافة AJAX request لحفظ السعر في قاعدة البيانات
    } else {
        showAlert('يرجى إدخال سعر صحيح!', 'danger');
    }
}

function deletePackage(packageType) {
    if (confirm(`هل أنت متأكد من حذف باقة ${getPackageName(packageType)}؟\nهذا الإجراء لا يمكن التراجع عنه.`)) {
        showAlert(`تم حذف باقة ${getPackageName(packageType)} بنجاح!`, 'success');
        // هنا يمكن إضافة AJAX request لحذف الباقة من قاعدة البيانات
        // أو إخفاء الصف من الجدول
    }
}

function getPackageName(packageType) {
    const names = {
        'featured': 'المميزة',
        'premium': 'البريميوم',
        'premium_star': 'البريميوم ستار'
    };
    return names[packageType] || packageType;
}

// Show alert messages
function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    alertContainer.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

// Terms & Conditions Functions
let termCounter = 0;

// Initialize with default term on page load
document.addEventListener('DOMContentLoaded', function() {
    addNewTerm();
});

function addNewTerm() {
    termCounter++;
    const termsContainer = document.getElementById('termsContainer');
    
    // Remove empty state if exists
    const emptyState = termsContainer.querySelector('.empty-state');
    if (emptyState) {
        emptyState.remove();
    }
    
    const termItem = document.createElement('div');
    termItem.className = 'term-item';
    termItem.setAttribute('data-term-id', termCounter);
    
    termItem.innerHTML = `
        <button type="button" class="btn btn-danger btn-sm delete-btn" onclick="deleteTerm(${termCounter})" title="حذف الشرط">
            <i class="bi bi-trash"></i>
        </button>
        
        <div class="mb-3">
            <label class="form-label fw-bold text-primary">
                <i class="bi bi-card-heading me-2"></i>
                العنوان - Title
            </label>
            <input type="text" class="form-control" id="term_title_${termCounter}" placeholder="أدخل عنوان الشرط هنا..." value="${termCounter === 1 ? 'شروط وأحكام الاستخدام' : ''}">
        </div>
        
        <div class="mb-0">
            <label class="form-label fw-bold text-primary">
                <i class="bi bi-card-text me-2"></i>
                الوصف - Description
            </label>
            <textarea class="form-control" id="term_description_${termCounter}" rows="6" placeholder="أدخل وصف الشرط هنا...">${termCounter === 1 ? 'باستخدام هذا التطبيق، فإنك توافق على الالتزام بجميع الشروط والأحكام المذكورة أدناه. يرجى قراءة هذه الشروط بعناية قبل استخدام الخدمة.' : ''}</textarea>
        </div>
    `;
    
    termsContainer.appendChild(termItem);
    
    // Animate the new item
    setTimeout(() => {
        termItem.style.opacity = '0';
        termItem.style.transform = 'translateY(20px)';
        termItem.style.transition = 'all 0.3s ease';
        
        setTimeout(() => {
            termItem.style.opacity = '1';
            termItem.style.transform = 'translateY(0)';
        }, 10);
    }, 10);
}

function deleteTerm(termId) {
    const termItem = document.querySelector(`[data-term-id="${termId}"]`);
    if (termItem) {
        // Animate removal
        termItem.style.transform = 'translateX(100%)';
        termItem.style.opacity = '0';
        
        setTimeout(() => {
            termItem.remove();
            
            // Check if container is empty
            const termsContainer = document.getElementById('termsContainer');
            if (termsContainer.children.length === 0) {
                showEmptyState();
            }
        }, 300);
    }
}

function showEmptyState() {
    const termsContainer = document.getElementById('termsContainer');
    const emptyState = document.createElement('div');
    emptyState.className = 'empty-state';
    emptyState.innerHTML = `
        <i class="bi bi-file-text"></i>
        <h5>لا توجد شروط مضافة</h5>
        <p class="mb-0">اضغط على زر "إضافة" لإضافة شرط جديد</p>
    `;
    termsContainer.appendChild(emptyState);
}

function saveAllTerms() {
    const termsContainer = document.getElementById('termsContainer');
    const termItems = termsContainer.querySelectorAll('.term-item');
    
    if (termItems.length === 0) {
        showAlert('يرجى إضافة شرط واحد على الأقل قبل الحفظ', 'warning');
        return;
    }
    
    const terms = [];
    let hasEmptyFields = false;
    
    termItems.forEach((item, index) => {
        const termId = item.getAttribute('data-term-id');
        const title = document.getElementById(`term_title_${termId}`).value.trim();
        const description = document.getElementById(`term_description_${termId}`).value.trim();
        
        if (!title || !description) {
            hasEmptyFields = true;
            return;
        }
        
        terms.push({
            id: termId,
            title: title,
            description: description
        });
    });
    
    if (hasEmptyFields) {
        showAlert('يرجى ملء جميع حقول العنوان والوصف', 'warning');
        return;
    }
    
    const saveBtn = event.target;
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>جاري الحفظ...';
    saveBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        showAlert(`تم حفظ ونشر ${terms.length} شرط في التطبيق بنجاح!`, 'success');
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
        
        console.log('Saved terms:', terms);
}, 1500);
}

// Packages management functions
function savePackagesPrices() {
    const featuredPrice = document.getElementById('featured_price').value;
    const premiumPrice = document.getElementById('premium_price').value;
    const premiumStarPrice = document.getElementById('premium_star_price').value;
    
    if (!featuredPrice || !premiumPrice || !premiumStarPrice) {
        showAlert('يرجى ملء جميع حقول الأسعار', 'warning');
        return;
    }
    
    if (featuredPrice < 0 || premiumPrice < 0 || premiumStarPrice < 0) {
        showAlert('يجب أن تكون الأسعار أكبر من أو تساوي صفر', 'warning');
        return;
    }
    
    const saveBtn = event.target;
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>جاري الحفظ...';
    saveBtn.disabled = true;
    
    const packagesData = {
        featured_price: parseFloat(featuredPrice),
        premium_price: parseFloat(premiumPrice),
        premium_star_price: parseFloat(premiumStarPrice)
    };
    
    // Simulate API call
    setTimeout(() => {
        showAlert('تم حفظ أسعار الباقات بنجاح!', 'success');
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
        
        console.log('Saved packages prices:', packagesData);
    }, 1500);
}

// Auto-save functionality
document.addEventListener('change', function(e) {
    if (e.target.matches('[data-category]')) {
        // Auto-save individual field changes
        const category = e.target.dataset.category;
        const field = e.target.dataset.field;
        const value = e.target.value;
        
        // Here you could implement auto-save to server
        console.log(`Auto-saving: ${category}.${field} = ${value}`);
    }
    
    // Auto-save for terms and conditions
    if (e.target.matches('[id^="term_title_"], [id^="term_description_"]')) {
        console.log('Terms content changed:', e.target.id);
        // You could implement auto-save here too
    }
});
</script>
@endsection