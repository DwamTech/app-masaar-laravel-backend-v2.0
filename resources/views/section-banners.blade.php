@extends('layouts.dashboard')

@section('title', 'تعيين لافتات كل قسم - Section Banners')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="text-primary mb-1">تعيين لافتات كل قسم</h2>
                    <p class="text-muted mb-0">Section Banners Management</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="#">إدارة التطبيق</a></li>
                        <li class="breadcrumb-item active" aria-current="page">تعيين لافتات كل قسم</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="row g-4">
        <!-- Car Sale -->
        <div class="col-lg-6 col-xl-6">
            <div class="category-card" data-category="car-sale">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="category-title">
                        <h4>بيع السيارات</h4>
                        <span>Car Sale</span>
                    </div>
                </div>
                <div class="banner-section">
                    <div class="language-tabs">
                        <button class="lang-tab active" data-lang="ar">عربي</button>
                        <button class="lang-tab" data-lang="en">English</button>
                    </div>
                    <div class="banner-content" data-lang="ar">
                        <div class="banner-preview empty" id="car-sale-ar-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر للشاشة الرئيسية</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> إضافة صورة
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> إضافة نص
                            </button>
                        </div>
                        <div class="banner-preview empty" id="car-sale-ar-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر في تفاصيل المنتج</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> إضافة صورة التفاصيل
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> إضافة نص التفاصيل
                            </button>
                        </div>
                    </div>
                    <div class="banner-content" data-lang="en" style="display: none;">
                        <div class="banner-preview empty" id="car-sale-en-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for main screen</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> Add Image
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> Add Text
                            </button>
                        </div>
                        <div class="banner-preview empty" id="car-sale-en-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for product details</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> Add Detail Image
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> Add Detail Text
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Real Estate -->
        <div class="col-lg-6 col-xl-6">
            <div class="category-card" data-category="real-estate">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="category-title">
                        <h4>العقارات</h4>
                        <span>Real Estate</span>
                    </div>
                </div>
                <div class="banner-section">
                    <div class="language-tabs">
                        <button class="lang-tab active" data-lang="ar">عربي</button>
                        <button class="lang-tab" data-lang="en">English</button>
                    </div>
                    <div class="banner-content" data-lang="ar">
                        <div class="banner-preview empty" id="real-estate-ar-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر للشاشة الرئيسية</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> إضافة صورة
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> إضافة نص
                            </button>
                        </div>
                        <div class="banner-preview empty" id="real-estate-ar-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر في تفاصيل المنتج</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> إضافة صورة التفاصيل
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> إضافة نص التفاصيل
                            </button>
                        </div>
                    </div>
                    <div class="banner-content" data-lang="en" style="display: none;">
                        <div class="banner-preview empty" id="real-estate-en-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for main screen</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> Add Image
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> Add Text
                            </button>
                        </div>
                        <div class="banner-preview empty" id="real-estate-en-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for product details</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> Add Detail Image
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> Add Detail Text
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Electronics & Home Appliances -->
        <div class="col-lg-6 col-xl-6">
            <div class="category-card" data-category="electronics">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-tv"></i>
                    </div>
                    <div class="category-title">
                        <h4>الإلكترونيات والأجهزة المنزلية</h4>
                        <span>Electronics & Home Appliances</span>
                    </div>
                </div>
                <div class="banner-section">
                    <div class="language-tabs">
                        <button class="lang-tab active" data-lang="ar">عربي</button>
                        <button class="lang-tab" data-lang="en">English</button>
                    </div>
                    <div class="banner-content" data-lang="ar">
                        <div class="banner-preview empty" id="electronics-ar-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر للشاشة الرئيسية</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> إضافة صورة
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> إضافة نص
                            </button>
                        </div>
                        <div class="banner-preview empty" id="electronics-ar-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر في تفاصيل المنتج</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> إضافة صورة التفاصيل
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> إضافة نص التفاصيل
                            </button>
                        </div>
                    </div>
                    <div class="banner-content" data-lang="en" style="display: none;">
                        <div class="banner-preview empty" id="electronics-en-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for main screen</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> Add Image
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> Add Text
                            </button>
                        </div>
                        <div class="banner-preview empty" id="electronics-en-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for product details</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> Add Detail Image
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> Add Detail Text
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jobs -->
        <div class="col-lg-6 col-xl-6">
            <div class="category-card" data-category="jobs">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="category-title">
                        <h4>الوظائف</h4>
                        <span>Jobs</span>
                    </div>
                </div>
                <div class="banner-section">
                    <div class="language-tabs">
                        <button class="lang-tab active" data-lang="ar">عربي</button>
                        <button class="lang-tab" data-lang="en">English</button>
                    </div>
                    <div class="banner-content" data-lang="ar">
                        <div class="banner-preview empty" id="jobs-ar-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر للشاشة الرئيسية</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> إضافة صورة
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> إضافة نص
                            </button>
                        </div>
                        <div class="banner-preview empty" id="jobs-ar-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر في تفاصيل المنتج</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> إضافة صورة التفاصيل
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> إضافة نص التفاصيل
                            </button>
                        </div>
                    </div>
                    <div class="banner-content" data-lang="en" style="display: none;">
                        <div class="banner-preview empty" id="jobs-en-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for main screen</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> Add Image
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> Add Text
                            </button>
                        </div>
                        <div class="banner-preview empty" id="jobs-en-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for product details</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> Add Detail Image
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> Add Detail Text
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Car Rent -->
        <div class="col-lg-6 col-xl-6">
            <div class="category-card" data-category="car-rent">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="category-title">
                        <h4>تأجير السيارات</h4>
                        <span>Car Rent</span>
                    </div>
                </div>
                <div class="banner-section">
                    <div class="language-tabs">
                        <button class="lang-tab active" data-lang="ar">عربي</button>
                        <button class="lang-tab" data-lang="en">English</button>
                    </div>
                    <div class="banner-content" data-lang="ar">
                        <div class="banner-preview empty" id="car-rent-ar-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر للشاشة الرئيسية</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> إضافة صورة
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> إضافة نص
                            </button>
                        </div>
                        <div class="banner-preview empty" id="car-rent-ar-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر في تفاصيل المنتج</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> إضافة صورة التفاصيل
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> إضافة نص التفاصيل
                            </button>
                        </div>
                    </div>
                    <div class="banner-content" data-lang="en" style="display: none;">
                        <div class="banner-preview empty" id="car-rent-en-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for main screen</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> Add Image
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> Add Text
                            </button>
                        </div>
                        <div class="banner-preview empty" id="car-rent-en-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for product details</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> Add Detail Image
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> Add Detail Text
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Car Services -->
        <div class="col-lg-6 col-xl-6">
            <div class="category-card" data-category="car-services">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <div class="category-title">
                        <h4>خدمات السيارات</h4>
                        <span>Car Services</span>
                    </div>
                </div>
                <div class="banner-section">
                    <div class="language-tabs">
                        <button class="lang-tab active" data-lang="ar">عربي</button>
                        <button class="lang-tab" data-lang="en">English</button>
                    </div>
                    <div class="banner-content" data-lang="ar">
                        <div class="banner-preview empty" id="car-services-ar-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر للشاشة الرئيسية</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> إضافة صورة
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> إضافة نص
                            </button>
                        </div>
                        <div class="banner-preview empty" id="car-services-ar-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر في تفاصيل المنتج</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> إضافة صورة التفاصيل
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> إضافة نص التفاصيل
                            </button>
                        </div>
                    </div>
                    <div class="banner-content" data-lang="en" style="display: none;">
                        <div class="banner-preview empty" id="car-services-en-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for main screen</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> Add Image
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> Add Text
                            </button>
                        </div>
                        <div class="banner-preview empty" id="car-services-en-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for product details</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> Add Detail Image
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> Add Detail Text
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Restaurants -->
        <div class="col-lg-6 col-xl-6">
            <div class="category-card" data-category="restaurants">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="category-title">
                        <h4>المطاعم</h4>
                        <span>Restaurants</span>
                    </div>
                </div>
                <div class="banner-section">
                    <div class="language-tabs">
                        <button class="lang-tab active" data-lang="ar">عربي</button>
                        <button class="lang-tab" data-lang="en">English</button>
                    </div>
                    <div class="banner-content" data-lang="ar">
                        <div class="banner-preview empty" id="restaurants-ar-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر للشاشة الرئيسية</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> إضافة صورة
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> إضافة نص
                            </button>
                        </div>
                        <div class="banner-preview empty" id="restaurants-ar-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر في تفاصيل المنتج</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> إضافة صورة التفاصيل
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> إضافة نص التفاصيل
                            </button>
                        </div>
                    </div>
                    <div class="banner-content" data-lang="en" style="display: none;">
                        <div class="banner-preview empty" id="restaurants-en-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for main screen</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> Add Image
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> Add Text
                            </button>
                        </div>
                        <div class="banner-preview empty" id="restaurants-en-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for product details</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> Add Detail Image
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> Add Detail Text
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Services -->
        <div class="col-lg-6 col-xl-6">
            <div class="category-card" data-category="other-services">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="category-title">
                        <h4>خدمات أخرى</h4>
                        <span>Other Services</span>
                    </div>
                </div>
                <div class="banner-section">
                    <div class="language-tabs">
                        <button class="lang-tab active" data-lang="ar">عربي</button>
                        <button class="lang-tab" data-lang="en">English</button>
                    </div>
                    <div class="banner-content" data-lang="ar">
                        <div class="banner-preview empty" id="other-services-ar-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر للشاشة الرئيسية</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> إضافة صورة
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> إضافة نص
                            </button>
                        </div>
                        <div class="banner-preview empty" id="other-services-ar-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>انقر لإضافة صورة بانر في تفاصيل المنتج</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> إضافة صورة التفاصيل
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> إضافة نص التفاصيل
                            </button>
                        </div>
                    </div>
                    <div class="banner-content" data-lang="en" style="display: none;">
                        <div class="banner-preview empty" id="other-services-en-preview">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for main screen</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-image">
                                <i class="fas fa-plus"></i> Add Image
                            </button>
                            <button class="btn-control add-text" data-action="add-text">
                                <i class="fas fa-font"></i> Add Text
                            </button>
                        </div>
                        <div class="banner-preview empty" id="other-services-en-detail-preview" style="margin-top: 15px;">
                            <div class="empty-banner">
                                <i class="fas fa-image"></i>
                                <p>Click to add banner image for product details</p>
                            </div>
                        </div>
                        <div class="banner-controls">
                            <button class="btn-control add-image" data-action="add-detail-image">
                                <i class="fas fa-plus"></i> Add Detail Image
                            </button>
                            <button class="btn-control add-text" data-action="add-detail-text">
                                <i class="fas fa-font"></i> Add Detail Text
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Image Upload Modal -->
<div class="modal fade" id="imageUploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">رفع صورة البانر - Upload Banner Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="upload-area" id="uploadArea">
                    <div class="upload-content">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h4>اسحب الصورة هنا أو انقر للاختيار</h4>
                        <p>Drag image here or click to select</p>
                        <input type="file" id="imageInput" accept="image/*" style="display: none;">
                    </div>
                </div>
                <div class="image-preview" id="imagePreview" style="display: none;">
                    <img id="previewImg" src="" alt="Preview">
                    <button class="btn-remove" id="removeImage">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء - Cancel</button>
                <button type="button" class="btn btn-primary" id="saveImage">حفظ - Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Text Editor Modal -->
<div class="modal fade" id="textEditorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تحرير نص البانر - Edit Banner Text</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">النص - Text</label>
                    <textarea class="form-control" id="bannerText" rows="4" placeholder="أدخل نص البانر هنا - Enter banner text here"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">لون النص - Text Color</label>
                            <input type="color" class="form-control" id="textColor" value="#ffffff">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">لون الخلفية - Background Color</label>
                            <input type="color" class="form-control" id="backgroundColor" value="#3490dc">
                        </div>
                    </div>
                </div>
                <div class="text-preview" id="textPreview">
                    <div class="preview-banner">
                        <span id="previewText">نص تجريبي - Sample Text</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء - Cancel</button>
                <button type="button" class="btn btn-primary" id="saveText">حفظ - Save</button>
            </div>
        </div>
    </div>
</div>


<style>
:root {
    --primary-blue: #3490dc;
    --secondary-blue: #2779bd;
    --success-green: #38c172;
    --warning-orange: #ffed4e;
    --danger-red: #e3342f;
    --dark-gray: #2d3748;
    --light-gray: #f7fafc;
    --border-color: #e2e8f0;
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    padding: 1.25rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e0e6ed;
    padding: 0.75rem;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.25);
}

.form-check-input:checked {
    background-color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}

.btn-outline-primary {
    border-color: var(--primary-blue);
    color: var(--primary-blue);
    border-radius: 6px;
}

.btn-outline-primary:hover {
    background-color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.table th {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
    border: none;
    font-weight: 500;
    padding: 1rem 0.75rem;
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background-color: rgba(52, 144, 220, 0.05);
}

.banner-preview {
    border-radius: 8px;
    overflow: hidden;
}

.badge {
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-weight: 500;
}

.breadcrumb {
    background: rgba(52, 144, 220, 0.1);
    border-radius: 8px;
    padding: 0.75rem 1rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    color: var(--primary-blue);
}

.breadcrumb-item a {
    color: var(--primary-blue);
    text-decoration: none;
}

.text-primary {
    color: var(--primary-blue) !important;
}

.bg-primary {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)) !important;
}

.bg-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268) !important;
}

.bg-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}

.bg-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14) !important;
}

.bg-info {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
}

.display-4 {
    font-size: 2.5rem;
}

/* Category Cards Styles */
.category-card {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    border: 1px solid var(--border-color);
    position: relative;
}

.category-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-blue), var(--secondary-blue));
}

.category-header {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 1px solid var(--border-color);
}

.category-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: var(--shadow-md);
}

.category-title h4 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark-gray);
    line-height: 1.3;
}

.category-title span {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}

.banner-section {
    padding: 1.5rem;
}

.language-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    background: var(--light-gray);
    padding: 0.25rem;
    border-radius: 8px;
}

.lang-tab {
    flex: 1;
    padding: 0.5rem 1rem;
    border: none;
    background: transparent;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.875rem;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.lang-tab.active {
    background: white;
    color: var(--primary-blue);
    box-shadow: var(--shadow-sm);
}

.lang-tab:hover:not(.active) {
    background: rgba(52, 144, 220, 0.1);
    color: var(--primary-blue);
}

.banner-content {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.banner-preview {
    min-height: 120px;
    border: 2px dashed var(--border-color);
    border-radius: 12px;
    margin-bottom: 1rem;
    position: relative;
    overflow: hidden;
    background: var(--light-gray);
    transition: all 0.3s ease;
    cursor: pointer;
}

.banner-preview:hover {
    border-color: var(--primary-blue);
    background: rgba(52, 144, 220, 0.05);
    transform: scale(1.02);
}

.banner-preview.empty:hover {
    border-color: var(--primary-blue);
    background: rgba(52, 144, 220, 0.1);
}

.banner-preview.empty:hover .empty-banner {
    color: var(--primary-blue);
}

.banner-preview.empty:hover .empty-banner::after {
    content: '\f0c7';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--primary-blue);
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.1); }
}

.empty-banner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 120px;
    color: #94a3b8;
    text-align: center;
}

.empty-banner i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.6;
}

.empty-banner p {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 500;
}

.banner-controls {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-control {
    flex: 1;
    min-width: 120px;
    padding: 0.75rem 1rem;
    border: 2px solid var(--border-color);
    background: white;
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.875rem;
    color: var(--dark-gray);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    position: relative;
    overflow: hidden;
}

.btn-control:hover {
    border-color: var(--primary-blue);
    color: var(--primary-blue);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-control.add-image:hover {
    background: linear-gradient(135deg, rgba(52, 144, 220, 0.1), rgba(39, 121, 189, 0.1));
}

.btn-control.add-text:hover {
    background: linear-gradient(135deg, rgba(56, 193, 114, 0.1), rgba(32, 201, 151, 0.1));
    border-color: var(--success-green);
    color: var(--success-green);
}

.btn-control.delete {
    border-color: var(--danger-red);
    color: var(--danger-red);
}

.btn-control.delete:hover {
    background: linear-gradient(135deg, rgba(227, 52, 47, 0.1), rgba(220, 38, 38, 0.1));
    transform: translateY(-2px);
}



/* Ripple Effect */
.btn-control::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(52, 144, 220, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-control:active::before {
    width: 300px;
    height: 300px;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 16px;
    box-shadow: var(--shadow-xl);
}

.modal-header {
    border-bottom: 1px solid var(--border-color);
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.modal-title {
    font-weight: 600;
    color: var(--dark-gray);
}

.modal-body {
    padding: 1.5rem;
}

.upload-area {
    border: 3px dashed var(--border-color);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    background: var(--light-gray);
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: var(--primary-blue);
    background: rgba(52, 144, 220, 0.05);
}

.upload-area.dragover {
    border-color: var(--primary-blue);
    background: rgba(52, 144, 220, 0.1);
    transform: scale(1.02);
}

.upload-content i {
    font-size: 3rem;
    color: var(--primary-blue);
    margin-bottom: 1rem;
}

.upload-content h4 {
    margin-bottom: 0.5rem;
    color: var(--dark-gray);
    font-weight: 600;
}

.upload-content p {
    color: #64748b;
    margin: 0;
}

.image-preview {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-md);
}

.image-preview img {
    width: 100%;
    height: auto;
    display: block;
}

.btn-remove {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    background: rgba(227, 52, 47, 0.9);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.btn-remove:hover {
    background: var(--danger-red);
    transform: scale(1.1);
}

.text-preview {
    margin-top: 1rem;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.preview-banner {
    padding: 2rem;
    text-align: center;
    background: var(--primary-blue);
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

/* Responsive Design */
@media (max-width: 768px) {
    .category-header {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .category-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .banner-controls {
        flex-direction: column;
    }
    
    .btn-control {
        min-width: auto;
    }
}

/* Loading Animation */
.loading {
    position: relative;
    overflow: hidden;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Success States */
.banner-preview.has-image {
    border-style: solid;
    border-color: var(--success-green);
    background: rgba(56, 193, 114, 0.05);
}

.banner-preview.has-text {
    border-style: solid;
    border-color: var(--primary-blue);
    background: rgba(52, 144, 220, 0.05);
}

.banner-preview.text-mode {
    border-style: solid;
    border-color: var(--success-green);
    background: rgba(56, 193, 114, 0.05);
}

.text-editor-container {
    transition: all 0.3s ease;
}

.text-editor-container:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-control.save-text {
    border-color: var(--success-green);
    color: var(--success-green);
}

.btn-control.save-text:hover {
    background: linear-gradient(135deg, rgba(56, 193, 114, 0.1), rgba(32, 201, 151, 0.1));
    transform: translateY(-2px);
}

.btn-control.edit-text {
    border-color: #f59e0b;
    color: #f59e0b;
}

.btn-control.edit-text:hover {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
    transform: translateY(-2px);
}

/* Animations */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.category-card {
    animation: slideInUp 0.6s ease forwards;
}

.category-card:nth-child(1) { animation-delay: 0.1s; }
.category-card:nth-child(2) { animation-delay: 0.2s; }
.category-card:nth-child(3) { animation-delay: 0.3s; }
.category-card:nth-child(4) { animation-delay: 0.4s; }
.category-card:nth-child(5) { animation-delay: 0.5s; }
.category-card:nth-child(6) { animation-delay: 0.6s; }
.category-card:nth-child(7) { animation-delay: 0.7s; }
.category-card:nth-child(8) { animation-delay: 0.8s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Language tab switching
    document.querySelectorAll('.lang-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const card = this.closest('.category-card');
            const lang = this.dataset.lang;
            
            // Update active tab
            card.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Show/hide content
            card.querySelectorAll('.banner-content').forEach(content => {
                if (content.dataset.lang === lang) {
                    content.style.display = 'block';
                } else {
                    content.style.display = 'none';
                }
            });
        });
    });
    
    // Banner control buttons
    let currentCategory = '';
    let currentLang = '';
    
    document.querySelectorAll('.btn-control').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.category-card');
            const content = this.closest('.banner-content');
            currentCategory = card.dataset.category;
            currentLang = content.dataset.lang;
            
            const action = this.dataset.action;
            
            if (action === 'add-image') {
                openImageModal();
            } else if (action === 'add-text') {
                switchToTextMode();
            } else if (action === 'delete') {
                deleteBanner();
            } else if (action === 'switch-to-image') {
                switchToImageMode();
            } else if (action === 'switch-to-text') {
                switchToTextMode();
            }
        });
    });
    
    // Image upload modal
    function openImageModal() {
        const modal = new bootstrap.Modal(document.getElementById('imageUploadModal'));
        modal.show();
        
        // Reset modal
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('uploadArea').style.display = 'block';
        document.getElementById('imageInput').value = '';
    }
    
    // Text editor modal
    function openTextModal() {
        const modal = new bootstrap.Modal(document.getElementById('textEditorModal'));
        modal.show();
        
        // Reset modal
        document.getElementById('bannerText').value = '';
        document.getElementById('textColor').value = '#ffffff';
        document.getElementById('backgroundColor').value = '#3490dc';
        updateTextPreview();
    }
    
    // File upload handling
    const uploadArea = document.getElementById('uploadArea');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    // Create hidden file input for direct image upload
    const hiddenFileInput = document.createElement('input');
    hiddenFileInput.type = 'file';
    hiddenFileInput.accept = 'image/*';
    hiddenFileInput.style.display = 'none';
    document.body.appendChild(hiddenFileInput);
    
    // Handle clicks on banner preview areas to open file picker
    document.addEventListener('click', function(e) {
        if (e.target.closest('.banner-preview') && e.target.closest('.empty-banner')) {
            const card = e.target.closest('.category-card');
            const content = e.target.closest('.banner-content');
            currentCategory = card.dataset.category;
            currentLang = content.dataset.lang;
            
            hiddenFileInput.onchange = function(event) {
                if (event.target.files.length > 0) {
                    handleDirectImageUpload(event.target.files[0]);
                }
            };
            hiddenFileInput.click();
        }
    });
    
    uploadArea.addEventListener('click', () => imageInput.click());
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleImageFile(files[0]);
        }
    });
    
    imageInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleImageFile(e.target.files[0]);
        }
    });
    
    // Handle direct image upload from banner preview click
    function handleDirectImageUpload(file) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                updateBannerPreview('image', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    }
    
    function handleImageFile(file) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                uploadArea.style.display = 'none';
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Remove image
    document.getElementById('removeImage').addEventListener('click', () => {
        imagePreview.style.display = 'none';
        uploadArea.style.display = 'block';
        imageInput.value = '';
    });
    
    // Save image
    document.getElementById('saveImage').addEventListener('click', () => {
        if (previewImg.src) {
            updateBannerPreview('image', previewImg.src);
            bootstrap.Modal.getInstance(document.getElementById('imageUploadModal')).hide();
        }
    });
    
    // Text preview updates
    const bannerText = document.getElementById('bannerText');
    const textColor = document.getElementById('textColor');
    const backgroundColor = document.getElementById('backgroundColor');
    const previewText = document.getElementById('previewText');
    const previewBanner = document.querySelector('.preview-banner');
    
    [bannerText, textColor, backgroundColor].forEach(input => {
        input.addEventListener('input', updateTextPreview);
    });
    
    function updateTextPreview() {
        previewText.textContent = bannerText.value || 'نص تجريبي - Sample Text';
        previewText.style.color = textColor.value;
        previewBanner.style.backgroundColor = backgroundColor.value;
    }
    
    // Save text
    document.getElementById('saveText').addEventListener('click', () => {
        if (bannerText.value.trim()) {
            updateBannerPreview('text', {
                text: bannerText.value,
                textColor: textColor.value,
                backgroundColor: backgroundColor.value
            });
            bootstrap.Modal.getInstance(document.getElementById('textEditorModal')).hide();
        }
    });
    

    
    // Update banner preview
    function updateBannerPreview(type, data) {
        const previewId = `${currentCategory}-${currentLang}-preview`;
        const preview = document.getElementById(previewId);
        const controls = preview.nextElementSibling;
        
        if (type === 'image') {
             preview.innerHTML = `<img src="${data}" alt="Banner" style="width: 100%; height: 120px; object-fit: cover;">`;
             preview.classList.remove('empty', 'text-mode');
             preview.classList.add('has-image');
         } else if (type === 'text') {
            preview.innerHTML = `
                <div style="
                    background: ${data.backgroundColor};
                    color: ${data.textColor};
                    padding: 2rem;
                    text-align: center;
                    font-weight: 600;
                    height: 120px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">${data.text}</div>
            `;
             preview.classList.remove('empty', 'text-mode');
             preview.classList.add('has-text');
        } else if (type === 'text-editor') {
            showTextEditor(data);
            return;
        }
        
        // Update controls
        updateControls(controls, type);
    }
    
    // Show inline text editor
    function showTextEditor(data = {}) {
        const previewId = `${currentCategory}-${currentLang}-preview`;
        const preview = document.getElementById(previewId);
        const controls = preview.nextElementSibling;
        const isArabic = currentLang === 'ar';
        
        const currentText = data.text || '';
        const currentBgColor = data.backgroundColor || '#3490dc';
        const currentTextColor = data.textColor || '#ffffff';
        
        preview.innerHTML = `
            <div class="text-editor-container" style="
                background: ${currentBgColor};
                color: ${currentTextColor};
                padding: 1rem;
                height: 120px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                position: relative;
                border-radius: 12px;
            ">
                <textarea 
                    id="inline-text-editor" 
                    style="
                        background: transparent;
                        border: 2px dashed rgba(255,255,255,0.5);
                        color: inherit;
                        font-weight: 600;
                        font-size: 1rem;
                        text-align: center;
                        resize: none;
                        width: 100%;
                        height: 60px;
                        border-radius: 8px;
                        padding: 10px;
                        outline: none;
                    "
                    placeholder="${isArabic ? 'اكتب النص هنا...' : 'Type text here...'}"
                >${currentText}</textarea>
                
                <div style="
                    display: flex;
                    gap: 0.5rem;
                    justify-content: center;
                    margin-top: 0.5rem;
                ">
                    <input type="color" id="text-color-picker" value="${currentTextColor}" 
                           style="width: 30px; height: 30px; border: none; border-radius: 50%; cursor: pointer;" 
                           title="${isArabic ? 'لون النص' : 'Text Color'}">
                    <input type="color" id="bg-color-picker" value="${currentBgColor}" 
                           style="width: 30px; height: 30px; border: none; border-radius: 50%; cursor: pointer;" 
                           title="${isArabic ? 'لون الخلفية' : 'Background Color'}">
                </div>
            </div>
        `;
        
        preview.classList.remove('empty', 'has-image', 'has-text');
        preview.classList.add('text-mode');
        
        // Update controls for text editor mode
        controls.innerHTML = `
            <button class="btn-control save-text" data-action="save-text">
                <i class="fas fa-check"></i> ${isArabic ? 'حفظ النص' : 'Save Text'}
            </button>
            <button class="btn-control edit-text" data-action="edit-text">
                <i class="fas fa-edit"></i> ${isArabic ? 'تعديل النص' : 'Edit Text'}
            </button>
            <button class="btn-control add-image" data-action="switch-to-image">
                <i class="fas fa-image"></i> ${isArabic ? 'إضافة صورة' : 'Add Image'}
            </button>
            <button class="btn-control delete" data-action="delete">
                <i class="fas fa-trash"></i> ${isArabic ? 'حذف' : 'Delete'}
            </button>
        `;
        
        // Setup text editor functionality
        setupTextEditor();
        
        // Re-attach event listeners
        attachControlListeners(controls);
    }
    
    // Setup text editor functionality
    function setupTextEditor() {
        const textarea = document.getElementById('inline-text-editor');
        const textColorPicker = document.getElementById('text-color-picker');
        const bgColorPicker = document.getElementById('bg-color-picker');
        const container = textarea.closest('.text-editor-container');
        
        if (!textarea || !textColorPicker || !bgColorPicker) return;
        
        // Focus on textarea
        textarea.focus();
        
        // Update colors in real-time
        textColorPicker.addEventListener('input', function() {
            container.style.color = this.value;
            textarea.style.color = this.value;
        });
        
        bgColorPicker.addEventListener('input', function() {
            container.style.background = this.value;
        });
        
        // Save on Enter key (Ctrl+Enter for new line)
        textarea.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.ctrlKey && !e.shiftKey) {
                e.preventDefault();
                saveTextFromEditor();
            }
        });
    }
    
    // Save text from inline editor
    function saveTextFromEditor() {
        const textarea = document.getElementById('inline-text-editor');
        const textColorPicker = document.getElementById('text-color-picker');
        const bgColorPicker = document.getElementById('bg-color-picker');
        
        if (!textarea || !textarea.value.trim()) {
            alert(currentLang === 'ar' ? 'يرجى إدخال نص' : 'Please enter text');
            return;
        }
        
        updateBannerPreview('text', {
            text: textarea.value,
            textColor: textColorPicker.value,
            backgroundColor: bgColorPicker.value
        });
    }
    
    // Update control buttons
    function updateControls(controls, type) {
        const isArabic = currentLang === 'ar';
        
        if (type === 'image') {
            controls.innerHTML = `
                <button class="btn-control add-image" data-action="add-image">
                    <i class="fas fa-plus"></i> ${isArabic ? 'تغيير الصورة' : 'Change Image'}
                </button>
                <button class="btn-control add-text" data-action="switch-to-text">
                    <i class="fas fa-font"></i> ${isArabic ? 'إضافة نص' : 'Add Text'}
                </button>
                <button class="btn-control delete" data-action="delete">
                    <i class="fas fa-trash"></i> ${isArabic ? 'حذف الصورة' : 'Delete Image'}
                </button>
            `;
        } else if (type === 'text') {
            controls.innerHTML = `
                <button class="btn-control add-text" data-action="add-text">
                    <i class="fas fa-font"></i> ${isArabic ? 'تغيير النص' : 'Change Text'}
                </button>
                <button class="btn-control edit-text" data-action="edit-text">
                    <i class="fas fa-edit"></i> ${isArabic ? 'تعديل النص' : 'Edit Text'}
                </button>
                <button class="btn-control add-image" data-action="switch-to-image">
                    <i class="fas fa-image"></i> ${isArabic ? 'إضافة صورة' : 'Add Image'}
                </button>
                <button class="btn-control delete" data-action="delete">
                    <i class="fas fa-trash"></i> ${isArabic ? 'حذف النص' : 'Delete Text'}
                </button>
            `;
        }
        
        // Re-attach event listeners
        attachControlListeners(controls);
    }
    
    // Attach event listeners to control buttons
    function attachControlListeners(controls) {
        controls.querySelectorAll('.btn-control').forEach(btn => {
            btn.addEventListener('click', function() {
                const card = this.closest('.category-card');
                const content = this.closest('.banner-content');
                currentCategory = card.dataset.category;
                currentLang = content.dataset.lang;
                
                const action = this.dataset.action;
                
                if (action === 'delete') {
                    deleteBanner();
                } else if (action === 'add-image') {
                    hiddenFileInput.onchange = function(event) {
                        if (event.target.files.length > 0) {
                            handleDirectImageUpload(event.target.files[0]);
                        }
                    };
                    hiddenFileInput.click();
                } else if (action === 'add-text') {
                    openTextModal();
                } else if (action === 'switch-to-image') {
                    switchToImageMode();
                } else if (action === 'switch-to-text') {
                    switchToTextMode();
                } else if (action === 'edit-text') {
                    editCurrentText();
                } else if (action === 'save-text') {
                    saveTextFromEditor();
                }
            });
        });
    }
    
    // Switch to image mode
    function switchToImageMode() {
        const previewId = `${currentCategory}-${currentLang}-preview`;
        const preview = document.getElementById(previewId);
        const controls = preview.nextElementSibling;
        const isArabic = currentLang === 'ar';
        
        preview.innerHTML = `
            <div class="empty-banner">
                <i class="fas fa-image"></i>
                <p>${isArabic ? 'انقر لإضافة صورة بانر' : 'Click to add banner image'}</p>
            </div>
        `;
        preview.classList.remove('has-image', 'has-text', 'text-mode');
        preview.classList.add('empty');
        
        controls.innerHTML = `
            <button class="btn-control add-image" data-action="add-image">
                <i class="fas fa-plus"></i> ${isArabic ? 'إضافة صورة' : 'Add Image'}
            </button>
            <button class="btn-control add-text" data-action="switch-to-text">
                <i class="fas fa-font"></i> ${isArabic ? 'إضافة نص' : 'Add Text'}
            </button>
        `;
        
        attachControlListeners(controls);
    }
    
    // Switch to text mode
    function switchToTextMode() {
        updateBannerPreview('text-editor', {});
    }
    
    // Edit current text
    function editCurrentText() {
        const previewId = `${currentCategory}-${currentLang}-preview`;
        const preview = document.getElementById(previewId);
        const textDiv = preview.querySelector('div');
        
        if (textDiv) {
            const currentText = textDiv.textContent || '';
            const currentBgColor = textDiv.style.backgroundColor || '#3490dc';
            const currentTextColor = textDiv.style.color || '#ffffff';
            
            updateBannerPreview('text-editor', {
                text: currentText,
                backgroundColor: currentBgColor,
                textColor: currentTextColor
            });
        } else {
            updateBannerPreview('text-editor', {});
        }
    }
    
    // Delete banner
    function deleteBanner() {
        const previewId = `${currentCategory}-${currentLang}-preview`;
        const preview = document.getElementById(previewId);
        const controls = preview.nextElementSibling;
        const isArabic = currentLang === 'ar';
        
        preview.innerHTML = `
            <div class="empty-banner">
                <i class="fas fa-image"></i>
                <p>${isArabic ? 'انقر لإضافة صورة بانر' : 'Click to add banner image'}</p>
            </div>
        `;
        preview.classList.remove('has-image', 'has-text');
        preview.classList.add('empty');
        
        controls.innerHTML = `
            <button class="btn-control add-image" data-action="add-image">
                <i class="fas fa-plus"></i> ${isArabic ? 'إضافة صورة' : 'Add Image'}
            </button>
            <button class="btn-control add-text" data-action="switch-to-text">
                <i class="fas fa-font"></i> ${isArabic ? 'إضافة نص' : 'Add Text'}
            </button>
        `;
        
        // Re-attach event listeners
        attachControlListeners(controls);
    }
    
    // Ripple effect for buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-control')) {
            const button = e.target;
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            button.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        }
    });
});
</script>
@endsection