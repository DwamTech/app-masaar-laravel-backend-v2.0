@extends('layouts.dashboard')

@section('title', 'تعيين أفضل المعلنين - Assign Top Advertisers')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #2c3e50 0%, #0056b3 100%);
        border-radius: 25px;
        padding: 3rem;
        margin-bottom: 2.5rem;
        color: white;
        text-align: center;
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
        0%, 100% { transform: rotate(0deg); }
        50% { transform: rotate(180deg); }
    }
    
    .advertiser-form {
        background: linear-gradient(145deg, #ffffff 0%, #f8f9ff 100%);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        margin-bottom: 2.5rem;
        border: 1px solid rgba(102, 126, 234, 0.1);
        position: relative;
        transition: all 0.3s ease;
    }
    
    .advertiser-form:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 80px rgba(0,0,0,0.12);
    }
    
    .form-row {
        display: flex;
        gap: 1.5rem;
        align-items: end;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .form-group {
        flex: 1;
        min-width: 200px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.8rem;
        font-weight: 700;
        color: #2c3e50;
        font-size: 1.1rem;
        position: relative;
    }
    
    .label-bilingual {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .label-arabic {
        color: #0056b3;
    }
    
    .label-english {
        color: #2c3e50;
        font-size: 0.9rem;
        font-weight: 500;
        opacity: 0.8;
    }
    
    .form-control {
        width: 100%;
        padding: 1rem 1.2rem;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }
    
    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 0.3rem rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
        background: white;
    }
    
    .logo-upload {
        position: relative;
        width: 100px;
        height: 100px;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .logo-upload:hover {
        border-color: #0056b3;
        background: #e3f2fd;
    }
    
    .logo-upload input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    
    .logo-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
    }
    
    .upload-icon {
        font-size: 2rem;
        color: #6c757d;
    }
    
    .toggle-btn {
        background: linear-gradient(135deg, #10ac84 0%, #00d2d3 100%);
        border: none;
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.4s ease;
        min-width: 160px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(16, 172, 132, 0.3);
    }
    
    .toggle-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .toggle-btn:hover::before {
        left: 100%;
    }
    
    .toggle-btn:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 35px rgba(16, 172, 132, 0.4);
    }
    
    .toggle-btn.disabled {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
    }
    
    .toggle-btn.disabled:hover {
        box-shadow: 0 12px 35px rgba(255, 107, 107, 0.4);
    }
    
    .section-select {
        min-width: 200px;
    }
    
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 1rem;
        }
        
        .form-group {
            min-width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="page-title mb-1">
                                <i class="bi bi-star-fill me-2" style="color: #ffd700;"></i>
                                تعيين أفضل المعلنين <small class="text-muted">- Assign Top Advertisers</small>
                            </h2>
                            <p class="text-muted mb-0">إدارة وتعيين أفضل المعلنين في الأقسام المختلفة <small>- Manage and assign top advertisers in different sections</small></p>
                        </div>
                    </div>
                </div>
            </div>

<!-- Sections Overview -->
<div class="advertiser-form">
    <h3 class="mb-4" style="color: #0056b3; display: flex; align-items: center; gap: 1rem;">
        <i class="bi bi-grid-3x3-gap-fill"></i>
        <span>
            عدد المعلنين في جميع الأقسام
            <small class="d-block" style="color: #764ba2; font-size: 0.8rem; font-weight: 500;">Number of advertisers in all sections</small>
        </span>
    </h3>
    
    <div class="sections-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- الصف الأول -->
        <div class="section-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(102, 126, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(102, 126, 234, 0.3)'">
            <i class="bi bi-shop" style="font-size: 2.5rem; margin-bottom: 1rem; display: block;"></i>
            <h5 style="margin-bottom: 0.5rem;">المطاعم</h5>
            <small style="opacity: 0.9; display: block; margin-bottom: 1rem;">Restaurants</small>
            <div style="background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 10px; font-weight: 700; font-size: 1.2rem;">15</div>
        </div>
        
        <div class="section-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 1.5rem; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(240, 147, 251, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(240, 147, 251, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(240, 147, 251, 0.3)'">
            <i class="bi bi-tools" style="font-size: 2.5rem; margin-bottom: 1rem; display: block;"></i>
            <h5 style="margin-bottom: 0.5rem;">خدمات السيارات</h5>
            <small style="opacity: 0.9; display: block; margin-bottom: 1rem;">Car Services</small>
            <div style="background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 10px; font-weight: 700; font-size: 1.2rem;">8</div>
        </div>
        
        <div class="section-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 1.5rem; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(79, 172, 254, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(79, 172, 254, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(79, 172, 254, 0.3)'">
            <i class="bi bi-car-front" style="font-size: 2.5rem; margin-bottom: 1rem; display: block;"></i>
            <h5 style="margin-bottom: 0.5rem;">تأجير السيارات</h5>
            <small style="opacity: 0.9; display: block; margin-bottom: 1rem;">Car Rent</small>
            <div style="background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 10px; font-weight: 700; font-size: 1.2rem;">12</div>
        </div>
        
        <div class="section-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; padding: 1.5rem; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(250, 112, 154, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(250, 112, 154, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(250, 112, 154, 0.3)'">
            <i class="bi bi-briefcase" style="font-size: 2.5rem; margin-bottom: 1rem; display: block;"></i>
            <h5 style="margin-bottom: 0.5rem;">الوظائف</h5>
            <small style="opacity: 0.9; display: block; margin-bottom: 1rem;">Jobs</small>
            <div style="background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 10px; font-weight: 700; font-size: 1.2rem;">23</div>
        </div>
        
        <!-- الصف الثاني -->
        <div class="section-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #2c3e50; padding: 1.5rem; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(168, 237, 234, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(168, 237, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(168, 237, 234, 0.3)'">
            <i class="bi bi-tv" style="font-size: 2.5rem; margin-bottom: 1rem; display: block;"></i>
            <h5 style="margin-bottom: 0.5rem;">الإلكترونيات والمنزل</h5>
            <small style="opacity: 0.8; display: block; margin-bottom: 1rem;">Electronics & Home</small>
            <div style="background: rgba(44, 62, 80, 0.1); padding: 0.5rem 1rem; border-radius: 10px; font-weight: 700; font-size: 1.2rem;">18</div>
        </div>
        
        <div class="section-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #2c3e50; padding: 1.5rem; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(255, 236, 210, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(255, 236, 210, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(255, 236, 210, 0.3)'">
            <i class="bi bi-building" style="font-size: 2.5rem; margin-bottom: 1rem; display: block;"></i>
            <h5 style="margin-bottom: 0.5rem;">العقارات</h5>
            <small style="opacity: 0.8; display: block; margin-bottom: 1rem;">Real Estate</small>
            <div style="background: rgba(44, 62, 80, 0.1); padding: 0.5rem 1rem; border-radius: 10px; font-weight: 700; font-size: 1.2rem;">9</div>
        </div>
        
        <div class="section-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #2c3e50; padding: 1.5rem; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(255, 154, 158, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(255, 154, 158, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(255, 154, 158, 0.3)'">
            <i class="bi bi-car-front-fill" style="font-size: 2.5rem; margin-bottom: 1rem; display: block;"></i>
            <h5 style="margin-bottom: 0.5rem;">بيع السيارات</h5>
            <small style="opacity: 0.8; display: block; margin-bottom: 1rem;">Car Sale</small>
            <div style="background: rgba(44, 62, 80, 0.1); padding: 0.5rem 1rem; border-radius: 10px; font-weight: 700; font-size: 1.2rem;">14</div>
        </div>
        
        <div class="section-card" style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%); color: #2c3e50; padding: 1.5rem; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(161, 196, 253, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(161, 196, 253, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(161, 196, 253, 0.3)'">
            <i class="bi bi-gear" style="font-size: 2.5rem; margin-bottom: 1rem; display: block;"></i>
            <h5 style="margin-bottom: 0.5rem;">خدمات أخرى</h5>
            <small style="opacity: 0.8; display: block; margin-bottom: 1rem;">Other Services</small>
            <div style="background: rgba(44, 62, 80, 0.1); padding: 0.5rem 1rem; border-radius: 10px; font-weight: 700; font-size: 1.2rem;">7</div>
        </div>
    </div>
    
    <style>
        @media (max-width: 768px) {
            .sections-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 1rem !important;
            }
        }
        
        @media (max-width: 480px) {
            .sections-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</div>

<!-- قائمة المعلنين الحاليين -->
<div class="advertiser-form">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 style="color: #0056b3; display: flex; align-items: center; gap: 1rem; margin: 0;">
            <i class="bi bi-people-fill"></i>
            <span>
               أفضل المعلنين علي مستوي الاقسام
                <small class="d-block" style="color: #2c3e50; font-size: 0.8rem; font-weight: 500;">Top Advertisers by Section</small>
            </span>
        </h3>
        <button class="btn btn-primary" id="addAdvertiserBtn" data-bs-toggle="modal" data-bs-target="#addAdvertiserModal" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px; padding: 0.8rem 1.5rem; font-weight: 600;">
            <i class="bi bi-plus-circle me-2"></i>
            إضافة معلن
            <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Add Advertiser</small>
        </button>
    </div>
    
    <!-- Tabs للأقسام -->
    <ul class="nav nav-tabs mb-4" id="sectionTabs" role="tablist" style="border: none; background: #f8f9fa; border-radius: 15px; padding: 0.5rem;">
        <li class="nav-item" role="presentation" style="flex: 1;">
            <button class="nav-link active" id="restaurants-tab" data-bs-toggle="tab" data-bs-target="#restaurants" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                <i class="bi bi-shop"></i>
                المطاعم
                <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Restaurants</small>
            </button>
        </li>
        <li class="nav-item" role="presentation" style="flex: 1;">
            <button class="nav-link" id="car-services-tab" data-bs-toggle="tab" data-bs-target="#car-services" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                <i class="bi bi-tools"></i>
                خدمات السيارات
                <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Car Services</small>
            </button>
        </li>
        <li class="nav-item" role="presentation" style="flex: 1;">
            <button class="nav-link" id="car-rent-tab" data-bs-toggle="tab" data-bs-target="#car-rent" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                <i class="bi bi-car-front"></i>
                تأجير السيارات
                <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Car Rent</small>
            </button>
        </li>
        <li class="nav-item" role="presentation" style="flex: 1;">
            <button class="nav-link" id="jobs-tab" data-bs-toggle="tab" data-bs-target="#jobs" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                <i class="bi bi-briefcase"></i>
                الوظائف
                <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Jobs</small>
            </button>
        </li>
        <li class="nav-item" role="presentation" style="flex: 1;">
            <button class="nav-link" id="electronics-tab" data-bs-toggle="tab" data-bs-target="#electronics" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                <i class="bi bi-tv"></i>
                الإلكترونيات
                <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Electronics</small>
            </button>
        </li>
        <li class="nav-item" role="presentation" style="flex: 1;">
            <button class="nav-link" id="real-estate-tab" data-bs-toggle="tab" data-bs-target="#real-estate" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                <i class="bi bi-building"></i>
                العقارات
                <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Real Estate</small>
            </button>
        </li>
        <li class="nav-item" role="presentation" style="flex: 1;">
            <button class="nav-link" id="car-sale-tab" data-bs-toggle="tab" data-bs-target="#car-sale" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                <i class="bi bi-car-front-fill"></i>
                بيع السيارات
                <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Car Sale</small>
            </button>
        </li>
        <li class="nav-item" role="presentation" style="flex: 1;">
            <button class="nav-link" id="other-services-tab" data-bs-toggle="tab" data-bs-target="#other-services" type="button" role="tab" style="border: none; border-radius: 12px; padding: 1rem; font-weight: 600; color: #6b7280; transition: all 0.3s ease; width: 100%; text-align: center;">
                <i class="bi bi-gear"></i>
                خدمات أخرى
                <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Other Services</small>
            </button>
        </li>
    </ul>
    
    <!-- Tab Content -->
    <div class="tab-content" id="sectionTabContent">
        <!-- المطاعم -->
        <div class="tab-pane fade show active" id="restaurants" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th style="border: none; padding: 1rem; font-weight: 700;">اسم المعلن <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Advertiser Name</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">رقم التليفون <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Phone Number</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإيميل <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Email</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الحالة <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Status</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإجراءات <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Actions</small></th>
                        </tr>
                    </thead>
                    <tbody id="restaurantsTable">
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: #6b7280;">
                                <i class="bi bi-shop" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2 mb-0">لا توجد معلنين في قسم المطاعم</p>
                                <small>No advertisers in restaurants section</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- خدمات السيارات -->
        <div class="tab-pane fade" id="car-services" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th style="border: none; padding: 1rem; font-weight: 700;">اسم المعلن <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Advertiser Name</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">رقم التليفون <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Phone Number</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإيميل <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Email</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الحالة <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Status</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإجراءات <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Actions</small></th>
                        </tr>
                    </thead>
                    <tbody id="carServicesTable">
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: #6b7280;">
                                <i class="bi bi-tools" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2 mb-0">لا توجد معلنين في قسم خدمات السيارات</p>
                                <small>No advertisers in car services section</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- تأجير السيارات -->
        <div class="tab-pane fade" id="car-rent" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th style="border: none; padding: 1rem; font-weight: 700;">اسم المعلن <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Advertiser Name</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">رقم التليفون <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Phone Number</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإيميل <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Email</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الحالة <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Status</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإجراءات <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Actions</small></th>
                        </tr>
                    </thead>
                    <tbody id="carRentTable">
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: #6b7280;">
                                <i class="bi bi-car-front" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2 mb-0">لا توجد معلنين في قسم تأجير السيارات</p>
                                <small>No advertisers in car rent section</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- الوظائف -->
        <div class="tab-pane fade" id="jobs" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th style="border: none; padding: 1rem; font-weight: 700;">اسم المعلن <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Advertiser Name</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">رقم التليفون <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Phone Number</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإيميل <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Email</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الحالة <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Status</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإجراءات <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Actions</small></th>
                        </tr>
                    </thead>
                    <tbody id="jobsTable">
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: #6b7280;">
                                <i class="bi bi-briefcase" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2 mb-0">لا توجد معلنين في قسم الوظائف</p>
                                <small>No advertisers in jobs section</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- الإلكترونيات والمنزل -->
        <div class="tab-pane fade" id="electronics" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th style="border: none; padding: 1rem; font-weight: 700;">اسم المعلن <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Advertiser Name</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">رقم التليفون <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Phone Number</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإيميل <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Email</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الحالة <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Status</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإجراءات <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Actions</small></th>
                        </tr>
                    </thead>
                    <tbody id="electronicsTable">
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: #6b7280;">
                                <i class="bi bi-tv" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2 mb-0">لا توجد معلنين في قسم الإلكترونيات والمنزل</p>
                                <small>No advertisers in electronics & home section</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- العقارات -->
        <div class="tab-pane fade" id="real-estate" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th style="border: none; padding: 1rem; font-weight: 700;">اسم المعلن <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Advertiser Name</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">رقم التليفون <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Phone Number</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإيميل <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Email</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الحالة <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Status</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإجراءات <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Actions</small></th>
                        </tr>
                    </thead>
                    <tbody id="realEstateTable">
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: #6b7280;">
                                <i class="bi bi-building" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2 mb-0">لا توجد معلنين في قسم العقارات</p>
                                <small>No advertisers in real estate section</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- بيع السيارات -->
        <div class="tab-pane fade" id="car-sale" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th style="border: none; padding: 1rem; font-weight: 700;">اسم المعلن <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Advertiser Name</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">رقم التليفون <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Phone Number</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإيميل <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Email</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الحالة <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Status</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإجراءات <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Actions</small></th>
                        </tr>
                    </thead>
                    <tbody id="carSaleTable">
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: #6b7280;">
                                <i class="bi bi-car-front-fill" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2 mb-0">لا توجد معلنين في قسم بيع السيارات</p>
                                <small>No advertisers in car sale section</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- خدمات أخرى -->
        <div class="tab-pane fade" id="other-services" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th style="border: none; padding: 1rem; font-weight: 700;">اسم المعلن <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Advertiser Name</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">رقم التليفون <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Phone Number</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإيميل <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Email</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الحالة <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Status</small></th>
                            <th style="border: none; padding: 1rem; font-weight: 700;">الإجراءات <small class="d-block" style="font-size: 0.8rem; opacity: 0.9;">Actions</small></th>
                        </tr>
                    </thead>
                    <tbody id="otherServicesTable">
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: #6b7280;">
                                <i class="bi bi-gear" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2 mb-0">لا توجد معلنين في قسم الخدمات الأخرى</p>
                                <small>No advertisers in other services section</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal لإضافة معلن -->
<div class="modal fade" id="addAdvertiserModal" tabindex="-1" aria-labelledby="addAdvertiserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: none; border-radius: 20px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; padding: 1.5rem 2rem;">
                <h5 class="modal-title" id="addAdvertiserModalLabel" style="font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="bi bi-person-plus"></i>
                    اختيار معلن
                    <small style="font-size: 0.8rem; opacity: 0.9;">Select Advertiser</small>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div class="mb-3">
                    <input type="text" class="form-control" id="searchUsers" placeholder="البحث في المستخدمين... - Search users..." style="border-radius: 12px; padding: 1rem; border: 2px solid #e9ecef;">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th style="padding: 1rem; font-weight: 600;">اسم المعلن <small class="d-block text-muted" style="font-size: 0.8rem;">Advertiser Name</small></th>
                                <th style="padding: 1rem; font-weight: 600;">رقم التليفون <small class="d-block text-muted" style="font-size: 0.8rem;">Phone Number</small></th>
                                <th style="padding: 1rem; font-weight: 600;">الإيميل <small class="d-block text-muted" style="font-size: 0.8rem;">Email</small></th>
                                <th style="padding: 1rem; font-weight: 600;">القسم <small class="d-block text-muted" style="font-size: 0.8rem;">Section</small></th>
                                <th style="padding: 1rem; font-weight: 600;">التفعيل <small class="d-block text-muted" style="font-size: 0.8rem;">Activation</small></th>
                                <th style="padding: 1rem; font-weight: 600;">اختيار <small class="d-block text-muted" style="font-size: 0.8rem;">Select</small></th>
                            </tr>
                        </thead>
                        <tbody id="usersTable">
                            <!-- بيانات وهمية للمستخدمين -->
                            <tr>
                                <td style="padding: 1rem;">أحمد محمد علي</td>
                                <td style="padding: 1rem;">01234567890</td>
                                <td style="padding: 1rem;">ahmed@example.com</td>
                                <td style="padding: 1rem;">
                                    <select class="form-select form-select-sm" style="border-radius: 8px;">
                                        <option value="restaurants">المطاعم - Restaurants</option>
                                        <option value="car-services">خدمات السيارات - Car Services</option>
                                        <option value="car-rent">تأجير السيارات - Car Rent</option>
                                        <option value="jobs">الوظائف - Jobs</option>
                                        <option value="electronics">الإلكترونيات والمنزل - Electronics & Home</option>
                                        <option value="real-estate">العقارات - Real Estate</option>
                                        <option value="car-sale">بيع السيارات - Car Sale</option>
                                        <option value="other-services">خدمات أخرى - Other Services</option>
                                    </select>
                                </td>
                                <td style="padding: 1rem;">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="activate1" checked>
                                        <label class="form-check-label" for="activate1">مفعل</label>
                                    </div>
                                </td>
                                <td style="padding: 1rem;">
                                    <button class="btn btn-primary btn-sm select-user" data-name="أحمد محمد علي" data-phone="01234567890" data-email="ahmed@example.com" style="border-radius: 8px;">
                                        اختيار
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 1rem;">فاطمة أحمد</td>
                                <td style="padding: 1rem;">01098765432</td>
                                <td style="padding: 1rem;">fatma@example.com</td>
                                <td style="padding: 1rem;">
                                    <select class="form-select form-select-sm" style="border-radius: 8px;">
                                        <option value="restaurants">المطاعم - Restaurants</option>
                                        <option value="car-services" selected>خدمات السيارات - Car Services</option>
                                        <option value="car-rent">تأجير السيارات - Car Rent</option>
                                        <option value="jobs">الوظائف - Jobs</option>
                                        <option value="electronics">الإلكترونيات والمنزل - Electronics & Home</option>
                                        <option value="real-estate">العقارات - Real Estate</option>
                                        <option value="car-sale">بيع السيارات - Car Sale</option>
                                        <option value="other-services">خدمات أخرى - Other Services</option>
                                    </select>
                                </td>
                                <td style="padding: 1rem;">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="activate2">
                                        <label class="form-check-label" for="activate2">معطل</label>
                                    </div>
                                </td>
                                <td style="padding: 1rem;">
                                    <button class="btn btn-primary btn-sm select-user" data-name="فاطمة أحمد" data-phone="01098765432" data-email="fatma@example.com" style="border-radius: 8px;">
                                        اختيار
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 1rem;">محمد عبدالله</td>
                                <td style="padding: 1rem;">01555123456</td>
                                <td style="padding: 1rem;">mohamed@example.com</td>
                                <td style="padding: 1rem;">
                                    <select class="form-select form-select-sm" style="border-radius: 8px;">
                                        <option value="restaurants">المطاعم - Restaurants</option>
                                        <option value="car-services">خدمات السيارات - Car Services</option>
                                        <option value="car-rent" selected>تأجير السيارات - Car Rent</option>
                                        <option value="jobs">الوظائف - Jobs</option>
                                        <option value="electronics">الإلكترونيات والمنزل - Electronics & Home</option>
                                        <option value="real-estate">العقارات - Real Estate</option>
                                        <option value="car-sale">بيع السيارات - Car Sale</option>
                                        <option value="other-services">خدمات أخرى - Other Services</option>
                                    </select>
                                </td>
                                <td style="padding: 1rem;">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="activate3" checked>
                                        <label class="form-check-label" for="activate3">مفعل</label>
                                    </div>
                                </td>
                                <td style="padding: 1rem;">
                                    <button class="btn btn-primary btn-sm select-user" data-name="محمد عبدالله" data-phone="01555123456" data-email="mohamed@example.com" style="border-radius: 8px;">
                                        اختيار
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// بيانات وهمية للإحصائيات
const sectionStats = {
    'restaurants': 15,
    'car-services': 8,
    'car-rent': 12,
    'jobs': 23,
    'electronics': 18,
    'real-estate': 9,
    'car-sale': 14,
    'other-services': 7
};

// تفعيل الـ tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

// تحديث الإحصائيات
function updateStats() {
    // هنا يمكن إضافة كود لتحديث الإحصائيات من الخادم
    console.log('تحديث الإحصائيات...');
}

// تحديث الإحصائيات كل 30 ثانية
setInterval(updateStats, 30000);

// تفعيل التابات مع تأثيرات بصرية
document.addEventListener('DOMContentLoaded', function() {
    // إضافة تأثيرات للتابات
    const tabButtons = document.querySelectorAll('#sectionTabs .nav-link');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function(e) {
            // إزالة الكلاس النشط من جميع التابات
            tabButtons.forEach(btn => {
                btn.style.background = 'transparent';
                btn.style.color = '#6b7280';
            });
            
            // إضافة التأثير للتاب النشط
            e.target.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            e.target.style.color = 'white';
        });
    });
    
    // تفعيل التاب الأول
    const firstTab = document.querySelector('#restaurants-tab');
    if (firstTab) {
        firstTab.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
        firstTab.style.color = 'white';
    }
});

// وظيفة البحث في المستخدمين
document.getElementById('searchUsers').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tr');
    
    rows.forEach(row => {
        const name = row.cells[0]?.textContent.toLowerCase() || '';
        const phone = row.cells[1]?.textContent.toLowerCase() || '';
        const email = row.cells[2]?.textContent.toLowerCase() || '';
        
        if (name.includes(searchTerm) || phone.includes(searchTerm) || email.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// وظيفة اختيار المستخدم وإضافته للجدول
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('select-user')) {
        const name = e.target.getAttribute('data-name');
        const phone = e.target.getAttribute('data-phone');
        const email = e.target.getAttribute('data-email');
        
        // الحصول على القسم المحدد
        const row = e.target.closest('tr');
        const sectionSelect = row.querySelector('select');
        const selectedSection = sectionSelect.value;
        const sectionText = sectionSelect.options[sectionSelect.selectedIndex].text;
        
        // الحصول على حالة التفعيل
        const activationCheckbox = row.querySelector('input[type="checkbox"]');
        const isActive = activationCheckbox.checked;
        
        // إضافة المستخدم للجدول المناسب
        addUserToSection(name, phone, email, selectedSection, sectionText, isActive);
        
        // إغلاق النافذة المنبثقة
        const modal = bootstrap.Modal.getInstance(document.getElementById('addAdvertiserModal'));
        modal.hide();
        
        // عرض رسالة نجاح
        showSuccessMessage('تم إضافة المعلن بنجاح!');
    }
});

// وظيفة إضافة المستخدم للقسم المناسب
function addUserToSection(name, phone, email, section, sectionText, isActive) {
    let tableId;
    
    // تحديد الجدول المناسب
    switch(section) {
        case 'restaurants':
            tableId = 'restaurantsTable';
            break;
        case 'car-services':
            tableId = 'carServicesTable';
            break;
        case 'car-rent':
            tableId = 'carRentTable';
            break;
        case 'jobs':
            tableId = 'jobsTable';
            break;
        case 'electronics':
            tableId = 'electronicsTable';
            break;
        case 'real-estate':
            tableId = 'realEstateTable';
            break;
        case 'car-sale':
            tableId = 'carSaleTable';
            break;
        case 'other-services':
            tableId = 'otherServicesTable';
            break;
        default:
            tableId = 'restaurantsTable';
    }
    
    const table = document.getElementById(tableId);
    
    // إزالة رسالة "لا توجد معلنين" إذا كانت موجودة
    const emptyRow = table.querySelector('tr td[colspan="5"]');
    if (emptyRow) {
        emptyRow.parentElement.remove();
    }
    
    // إنشاء صف جديد
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td style="padding: 1rem;">${name}</td>
        <td style="padding: 1rem;">${phone}</td>
        <td style="padding: 1rem;">${email}</td>
        <td style="padding: 1rem;">
            <span class="badge ${isActive ? 'bg-success' : 'bg-danger'}" style="padding: 0.5rem 1rem; border-radius: 10px; font-weight: 600;">
                ${isActive ? 'مفعل' : 'معطل'}
                <small class="d-block" style="font-size: 0.7rem; opacity: 0.9;">${isActive ? 'Active' : 'Inactive'}</small>
            </span>
        </td>
        <td style="padding: 1rem;">
            <button class="btn btn-sm ${isActive ? 'btn-success' : 'btn-warning'} me-2 toggle-status" title="${isActive ? 'تعطيل - Deactivate' : 'تفعيل - Activate'}" style="border-radius: 8px; padding: 0.5rem 0.8rem; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                ${isActive ? 'تعطيل' : 'تفعيل'}
            </button>
            <button class="btn btn-sm btn-danger delete-user" title="حذف - Delete" style="border-radius: 8px; padding: 0.5rem 0.8rem; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    table.appendChild(newRow);
}

// وظيفة تبديل حالة التفعيل
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('toggle-status')) {
        const button = e.target;
        const row = button.closest('tr');
        const statusBadge = row.querySelector('.badge');
        
        // تبديل الحالة
        if (statusBadge.classList.contains('bg-success')) {
            // تعطيل
            statusBadge.classList.remove('bg-success');
            statusBadge.classList.add('bg-danger');
            statusBadge.innerHTML = 'معطل<small class="d-block" style="font-size: 0.7rem; opacity: 0.9;">Inactive</small>';
            
            button.classList.remove('btn-success');
            button.classList.add('btn-warning');
            button.textContent = 'تفعيل';
            button.title = 'تفعيل - Activate';
        } else {
            // تفعيل
            statusBadge.classList.remove('bg-danger');
            statusBadge.classList.add('bg-success');
            statusBadge.innerHTML = 'مفعل<small class="d-block" style="font-size: 0.7rem; opacity: 0.9;">Active</small>';
            
            button.classList.remove('btn-warning');
            button.classList.add('btn-success');
            button.textContent = 'تعطيل';
            button.title = 'تعطيل - Deactivate';
        }
        
        showSuccessMessage('تم تحديث حالة المعلن بنجاح!');
    }
});

// وظيفة حذف المستخدم
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bi-trash') || e.target.classList.contains('delete-user')) {
        const button = e.target.classList.contains('bi-trash') ? e.target.parentElement : e.target;
        const row = button.closest('tr');
        const table = row.closest('tbody');
        
        if (confirm('هل أنت متأكد من حذف هذا المعلن؟\nAre you sure you want to delete this advertiser?')) {
            row.remove();
            
            // إذا لم تعد هناك صفوف، أضف رسالة "لا توجد معلنين"
            if (table.children.length === 0) {
                const emptyRow = document.createElement('tr');
                const tableId = table.id;
                let icon, message;
                
                switch(tableId) {
                    case 'restaurantsTable':
                        icon = 'bi-shop';
                        message = 'لا توجد معلنين في قسم المطاعم';
                        break;
                    case 'carServicesTable':
                        icon = 'bi-tools';
                        message = 'لا توجد معلنين في قسم خدمات السيارات';
                        break;
                    case 'carRentTable':
                        icon = 'bi-car-front';
                        message = 'لا توجد معلنين في قسم تأجير السيارات';
                        break;
                    case 'jobsTable':
                        icon = 'bi-briefcase';
                        message = 'لا توجد معلنين في قسم الوظائف';
                        break;
                    case 'electronicsTable':
                        icon = 'bi-tv';
                        message = 'لا توجد معلنين في قسم الإلكترونيات والمنزل';
                        break;
                    case 'realEstateTable':
                        icon = 'bi-building';
                        message = 'لا توجد معلنين في قسم العقارات';
                        break;
                    case 'carSaleTable':
                        icon = 'bi-car-front-fill';
                        message = 'لا توجد معلنين في قسم بيع السيارات';
                        break;
                    case 'otherServicesTable':
                        icon = 'bi-gear';
                        message = 'لا توجد معلنين في قسم الخدمات الأخرى';
                        break;
                    default:
                        icon = 'bi-shop';
                        message = 'لا توجد معلنين';
                }
                
                emptyRow.innerHTML = `
                    <td colspan="5" class="text-center py-4" style="color: #6b7280;">
                        <i class="bi ${icon}" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="mt-2 mb-0">${message}</p>
                        <small>No advertisers in this section</small>
                    </td>
                `;
                
                table.appendChild(emptyRow);
            }
            
            showSuccessMessage('تم حذف المعلن بنجاح!');
        }
    }
});

// وظيفة عرض رسالة النجاح
function showSuccessMessage(message) {
    // إنشاء عنصر التنبيه
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    // إزالة التنبيه تلقائياً بعد 3 ثوان
    setTimeout(() => {
        if (alert.parentElement) {
            alert.remove();
        }
    }, 3000);
}

// وظائف للتعامل مع الجدول
function toggleAdvertiserStatus(button, currentStatus) {
    const row = button.closest('tr');
    const statusBadge = row.querySelector('.badge');
    const actionButton = button;
    
    if (currentStatus === 'active') {
        // تغيير إلى معطل
        statusBadge.className = 'badge bg-danger';
        statusBadge.innerHTML = 'معطل<small class="d-block" style="font-size: 0.7rem; opacity: 0.9;">Inactive</small>';
        actionButton.className = 'btn btn-sm btn-warning me-2';
        actionButton.innerHTML = 'تفعيل';
        actionButton.setAttribute('onclick', 'toggleAdvertiserStatus(this, "inactive")');
        actionButton.title = 'تفعيل - Activate';
    } else {
        // تغيير إلى مفعل
        statusBadge.className = 'badge bg-success';
        statusBadge.innerHTML = 'مفعل<small class="d-block" style="font-size: 0.7rem; opacity: 0.9;">Active</small>';
        actionButton.className = 'btn btn-sm btn-success me-2';
        actionButton.innerHTML = 'تعطيل';
        actionButton.setAttribute('onclick', 'toggleAdvertiserStatus(this, "active")');
        actionButton.title = 'تعطيل - Deactivate';
    }
}

function deleteAdvertiser(button) {
    if (confirm('هل أنت متأكد من حذف هذا المعلن؟\nAre you sure you want to delete this advertiser?')) {
        const row = button.closest('tr');
        row.remove();
        
        // التحقق من وجود صفوف أخرى
        const tbody = document.getElementById('advertisersTable');
        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-5" style="background: linear-gradient(145deg, #f8f9ff 0%, #ffffff 100%);">
                        <i class="bi bi-inbox fs-1 d-block mb-3" style="color: #667eea; opacity: 0.6;"></i>
                        <h5 style="color: #0056b3; margin-bottom: 0.5rem;">لا توجد معلنون مضافون بعد</h5>
                        <small style="color: #2c3e50; opacity: 0.8;">No advertisers added yet</small>
                    </td>
                </tr>
            `;
        }
    }
}
</script>

        </div>
    </div>
</div>
@endsection