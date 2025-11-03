@extends('layouts.dashboard')

@section('title', 'إدارة المستخدمين - Users Management')

@section('content')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .users-header {
        background: linear-gradient(135deg, #2c3e50 0%, #73C6E6 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    /* .users-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    } */
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        border: none;
    }
    
    .filter-btn {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #dee2e6;
        color: #495057;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 0.25rem;
    }
    
    .filter-btn:hover {
        background: linear-gradient(135deg, #73C6E6 0%, #2c3e50 100%);
        color: white;
        border-color: #73C6E6;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(115, 198, 230, 0.3);
    }
    
    .filter-btn.active {
        background: linear-gradient(135deg, #2c3e50 0%, #73C6E6 100%);
        color: white;
        border-color: #138496 ;
        box-shadow: 0 5px 15px rgba(44, 62, 80, 0.3);
    }
    
    .search-container {
        position: relative;
        margin-bottom: 1.5rem;
        margin-top: 3.5rem;
        max-width: 500px;
    }
    
    .smart-search {
        background: linear-gradient(145deg, #ffffff 0%, #f8f9ff 100%);
        border: 2px solid #e3e8f0;
        border-radius: 25px;
        padding: 1rem 1.5rem 1.2rem 3.5rem;
        font-size: 1rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        font-weight: 500;
    }
    
    .smart-search:hover {
        border-color: #c7d2fe;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }
    
    .smart-search:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.3rem rgba(102, 126, 234, 0.15), 0 8px 25px rgba(0, 0, 0, 0.12);
        outline: none;
        transform: translateY(-2px);
        background: linear-gradient(145deg, #ffffff 0%, #f0f4ff 100%);
    }
    
    .smart-search::placeholder {
        color: #8b9dc3;
        font-weight: 400;
        transition: all 0.3s ease;
    }
    
    .smart-search:focus::placeholder {
        color: #a5b4d4;
        transform: translateX(5px);
    }
    
    .search-icon {
        position: absolute;
        right: 90%;
        top: 50%;
        transform: translateY(-50%);
        color: #8b9dc3;
        font-size: 1.3rem;
        transition: all 0.3s ease;
    }
    
    .search-container:hover .search-icon {
        color: #667eea;
        transform: translateY(-50%) scale(1.1);
    }
    
    .smart-search:focus + .search-icon {
        color: #667eea;
        transform: translateY(-50%) scale(1.1);
    }
    
    .users-table {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border: none;
    }
    
    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed;
        width: 100%;
    }
    
    .table thead {
        background: linear-gradient(135deg, #2c3e50 0%, #73C6E6 100%);
    }
    
    .table thead th {
        background: transparent;
        color: white;
        border: none;
        padding: 0.8rem 0.5rem;
        font-weight: 600;
        text-align: center;
        position: relative;
        font-size: 0.9rem;
        white-space: nowrap;
    }
    
    .table tbody td {
        padding: 0.6rem 0.4rem;
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fa;
        text-align: center;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.2s ease;
    }
    
    .action-btn {
        padding: 0.4rem 0.6rem;
        border-radius: 6px;
        border: none;
        font-size: 0.75rem;
        font-weight: 500;
        margin: 0.1rem;
        transition: all 0.3s ease;
        cursor: pointer;
        min-width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-view {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }
    
    .btn-view:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(23, 162, 184, 0.3);
    }
    
    .btn-packages {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }
    
    .btn-packages:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(23, 162, 184, 0.3);
    }
    .btn-best-advertiser {
        background: linear-gradient(135deg, #ffc107 0%, #dc3545 100%);
        color: #fff;
    }
    .btn-best-advertiser:hover {
        background: linear-gradient(135deg, #ffc107 0%, #bd2130 100%);;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
    }
    
    .btn-block {
        background: linear-gradient(135deg, #ffc107 0%, #dc3545 100%);
        color: #fff;
    }
    
    .btn-block:hover {
        background: linear-gradient(135deg, #ffc107 0%, #bd2130 100%);;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
    }
    
    .btn-unblock {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .btn-unblock:hover {
        background: linear-gradient(135deg, #20c997 0%, #1e7e34 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }
    
    .btn-delete:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #73C6E6 0%, #2c3e50 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin: 0 auto;
    }
    
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }
    
    .status-advertiser {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .status-visitor {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }
    
    /* Packages Modal Styles */
    .packages-modal .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    
    .packages-modal .modal-header {
        background: linear-gradient(135deg, #2c3e50 0%, #73C6E6 100%);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 1.5rem 2rem;
        border: none;
    }
    
    .packages-modal .modal-title {
        font-weight: 700;
        font-size: 1.5rem;
    }
    
    .packages-modal .btn-close {
        /* background: white; */
        border-radius: 50%;
        opacity: 1;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .packages-table {
        margin-top: 1rem;
    }
    
    .packages-table .table thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #495057;
        border: none;
        padding: 1rem;
        font-weight: 600;
    }
    
    .package-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .package-card:hover {
        border-color: #73C6E6;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(115, 198, 230, 0.2);
    }
    
    .package-featured {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #212529;
    }
    
    .package-premium {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .package-premium-star {
        background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
        color: white;
    }
    
    .pagination {
        justify-content: center;
        margin-top: 2rem;
        padding-left: 20px;
    }
    
    .page-link {
        border: none;
        padding: 0.75rem 1rem;
        margin: 0 0.25rem;
        border-radius: 8px;
        color: #495057;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        background: linear-gradient(135deg, #73C6E6 0%, #2c3e50 100%);
        color: white;
        transform: translateY(-2px);
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #2c3e50 0%, #73C6E6 100%);
        color: white;
        box-shadow: 0 5px 15px rgba(44, 62, 80, 0.3);
    }
    .text-muted{
        padding-right: 20px;
    }
    
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1.5rem;
        }
        
        .users-header {
            padding: 2.5rem 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .users-header .row {
            flex-direction: column;
        }
        
        .users-header .col-md-4 {
            margin-top: 2rem;
        }
        
        .users-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            line-height: 1.3;
        }
        
        .users-header p {
            font-size: 1.2rem;
            line-height: 1.6;
        }
        
        .filter-card {
            padding: 2rem 1.5rem;
            margin-bottom: 2rem;
            border-radius: 15px;
        }
        
        .filter-card .row {
            flex-direction: column;
            gap: 2rem;
        }
        
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
        }
        
        .filter-btn {
            flex: 1;
            min-width: 150px;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
        }
        
        .search-container {
            max-width: 100%;
            margin-top: 1rem;
        }
        
        .smart-search {
            padding: 1.5rem 1.8rem 1.5rem 4rem;
            font-size: 1.2rem;
            height: auto;
            min-height: 55px;
            border-radius: 12px;
        }
        
        /* Hide table on mobile and show cards instead */
        .users-table .table {
            display: none;
        }
        
        .mobile-user-cards {
            display: block;
        }
        
        .user-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            border: 1px solid #e9ecef;
        }
        
        .user-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f8f9fa;
        }
        
        .user-card-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #73C6E6 0%, #2c3e50 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-left: 1.2rem;
            font-size: 1.4rem;
        }
        
        .user-card-info h5 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1.4;
        }
        
        .user-card-status {
            margin-top: 0.5rem;
        }
        
        .user-card-details {
            margin-bottom: 1.5rem;
        }
        
        .user-detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .user-detail-row:last-child {
            border-bottom: none;
        }
        
        .user-detail-label {
            font-weight: 700;
            color: #495057;
            font-size: 1.05rem;
        }
        
        .user-detail-value {
            font-size: 1.05rem;
            color: #2c3e50;
            text-align: left;
            font-weight: 500;
        }
        
        .user-card-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            justify-content: center;
            margin-top: 1rem;
        }
        
        .user-card-actions .action-btn {
            flex: 1;
            min-width: 52px;
            height: 48px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 3px 6px rgba(0,0,0,0.12);
            margin: 3px;
        }
        
        .user-card-actions .btn-view {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .user-card-actions .btn-view:hover {
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .user-card-actions .btn-packages {
            background: linear-gradient(135deg, #6f42c1 0%, #8e44ad 100%);
            color: white;
        }
        
        .user-card-actions .btn-packages:hover {
            background: linear-gradient(135deg, #5a2d91 0%, #7d3c98 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .user-card-actions .btn-best-advertiser:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .user-card-actions .btn-block {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
            color: white;
        }
        
        .user-card-actions .btn-block:hover {
            background: linear-gradient(135deg, #c82333 0%, #d62c1a 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .user-card-actions .btn-unblock {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .user-card-actions .btn-unblock:hover {
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .user-card-actions .btn-delete {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
        }
        
        .user-card-actions .btn-delete:hover {
            background: linear-gradient(135deg, #545b62 0%, #4e555b 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        /* Touch-friendly action buttons for mobile */
        .user-card-actions .action-btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .pagination {
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        
        .page-link {
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
        }
    }
    
    @media (min-width: 769px) {
        .mobile-user-cards {
            display: none;
        }
    }
    
    /* Tablet Responsive Styles */
    @media (max-width: 992px) and (min-width: 769px) {
        .users-header {
            padding: 1.8rem;
        }
        
        .users-header h1 {
            font-size: 2rem;
        }
        
        .filter-card {
            padding: 1.25rem;
        }
        
        .table thead th {
            padding: 0.7rem 0.4rem;
            font-size: 0.85rem;
        }
        
        .table tbody td {
            padding: 0.5rem 0.3rem;
            font-size: 0.8rem;
        }
        
        .action-btn {
            padding: 0.35rem 0.5rem;
            font-size: 0.7rem;
            min-width: 30px;
            height: 30px;
        }
    }
    
    /* Small mobile devices */
    @media (max-width: 480px) {
        .users-header {
            padding: 1rem;
        }
        
        .users-header h1 {
            font-size: 1.5rem;
        }
        
        .users-header .badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
        
        .filter-btn {
            min-width: 100px;
            padding: 0.5rem 0.8rem;
            font-size: 0.85rem;
        }
        
        .user-card {
            padding: 1rem;
        }
        
        .user-card-avatar {
            width: 45px;
            height: 45px;
            font-size: 1rem;
        }
        
        .user-card-info h5 {
            font-size: 1rem;
        }
        
        .user-card-actions .action-btn {
            min-width: 40px;
            height: 36px;
            font-size: 0.8rem;
        }
        
        /* Pagination improvements for mobile */
        .pagination {
            margin-top: 2.5rem;
            padding: 0 1rem;
        }
        
        .page-link {
            padding: 1rem 1.2rem;
            font-size: 1.1rem;
            margin: 0 0.3rem;
            border-radius: 10px;
            min-width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .text-muted {
            padding: 0 1.5rem;
            font-size: 1.1rem;
            margin-top: 1.5rem;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="users-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2 fw-bold">
                    <i class="bi bi-people-fill me-3"></i>
                    إدارة المستخدمين
                </h1>
                <p class="mb-0 fs-5 opacity-90">إدارة وتتبع جميع المستخدمين والمعلنين في النظام</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex align-items-center justify-content-end gap-3">
                    <span class="badge bg-light text-dark fs-6 px-3 py-2">
                        <i class="bi bi-people me-1"></i>
                        1,234 مستخدم
                    </span>
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="bi bi-megaphone me-1"></i>
                        567 معلن
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filter-card">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-3 fw-bold text-primary">
                    <i class="bi bi-funnel me-2"></i>
                    فلاتر البحث
                </h5>
                <div class="btn-group" role="group">
                    <button type="button" class="filter-btn active" data-filter="all">
                        <i class="bi bi-people me-1"></i>
                        الكل
                    </button>
                    <button type="button" class="filter-btn" data-filter="advertisers">
                        <i class="bi bi-megaphone me-1"></i>
                        المعلنين
                    </button>
                    <button type="button" class="filter-btn" data-filter="visitors">
                        <i class="bi bi-eye me-1"></i>
                        الزوار
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <!-- <h5 class="mb-3 fw-bold text-primary">
                    <i class="bi bi-search me-2"></i>
                    البحث الذكي
                </h5> -->
                <div class="search-container">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="smart-search" placeholder="ابحث بالاسم، الإيميل، رقم الهاتف أو أي معلومة أخرى...">
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="users-table">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 16%;">الاسم</th>
                        <th style="width: 14%;">رقم التليفون</th>
                        <th style="width: 20%;">الإيميل</th>
                        <th style="width: 8%;">عدد الإعلانات</th>
                        <th style="width: 12%;">سجل من خلال</th>
                        <th style="width: 10%;">الحالة</th>
                        <th style="width: 20%;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <tr data-user-type="advertiser" data-user-blocked="false">
                        <td>
                            <strong style="font-size: 0.85rem;">أحمد محمد علي</strong>
                        </td>
                        <td style="font-size: 0.8rem;">+971 50 123 4567</td>
                        <td style="font-size: 0.8rem;">ahmed.ali@email.com</td>
                        <td>
                            <span class="badge bg-primary" style="font-size: 0.7rem;">15</span>
                        </td>
                        <td style="font-size: 0.8rem;">
                            
                            GGL-2024-7891
                        </td>
                        <td>
                            <span class="status-badge status-advertiser">معلن</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('أحمد محمد علي')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('أحمد محمد علي')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'أحمد محمد علي', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('أحمد محمد علي')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="visitor" data-user-blocked="false">
                        <td>
                            <strong>سارة محمود حسن</strong>
                        </td>
                        <td>+971 55 987 6543</td>
                        <td>sara.hassan@email.com</td>
                        <td>
                            <span class="badge bg-secondary">0</span>
                        </td>
                        <td>
                           
                            FB-2024-3456
                        </td>
                        <td>
                            <span class="status-badge status-visitor">زائر</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('سارة محمود حسن')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('سارة محمود حسن')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'سارة محمود حسن', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('سارة محمود حسن')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="advertiser" data-user-blocked="false">
                        <td>
                            <strong>محمد عبدالله أحمد</strong>
                        </td>
                        <td>+971 52 456 7890</td>
                        <td>mohammed.ahmed@email.com</td>
                        <td>
                            <span class="badge bg-primary">8</span>
                        </td>
                        <td>
                            
                            EML-2024-9876
                        </td>
                        <td>
                            <span class="status-badge status-advertiser">معلن</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('محمد عبدالله أحمد')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('محمد عبدالله أحمد')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'محمد عبدالله أحمد', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('محمد عبدالله أحمد')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="visitor" data-user-blocked="true">
                        <td>
                            <strong>فاطمة طارق سالم</strong>
                        </td>
                        <td>+971 56 321 0987</td>
                        <td>fatima.salem@email.com</td>
                        <td>
                            <span class="badge bg-secondary">0</span>
                        </td>
                        <td>
                            
                            PHN-2024-5432
                        </td>
                        <td>
                            <span class="status-badge status-visitor">زائر محظور</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('فاطمة طارق سالم')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('فاطمة طارق سالم')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-unblock" onclick="toggleUserBlock(this, 'فاطمة طارق سالم', true)" title="فك الحظر">
                                <i class="bi bi-check-circle"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('فاطمة طارق سالم')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="advertiser" data-user-blocked="false">
                        <td>
                            <strong>خالد يوسف محمد</strong>
                        </td>
                        <td>+971 54 789 0123</td>
                        <td>khalid.youssef@email.com</td>
                        <td>
                            <span class="badge bg-primary">23</span>
                        </td>
                        <td>
                            
                            GGL-2024-9123
                        </td>
                        <td>
                            <span class="status-badge status-advertiser">معلن</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('خالد يوسف محمد')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('خالد يوسف محمد')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'خالد يوسف محمد', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('خالد يوسف محمد')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="visitor" data-user-blocked="false">
                        <td>
                            <strong>نورا عبدالرحمن</strong>
                        </td>
                        <td>+971 50 111 2222</td>
                        <td>nora.abdulrahman@email.com</td>
                        <td>
                            <span class="badge bg-secondary">0</span>
                        </td>
                        <td>
                            
                            EML-2024-1357
                        </td>
                        <td>
                            <span class="status-badge status-visitor">زائر</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('نورا عبدالرحمن')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('نورا عبدالرحمن')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'نورا عبدالرحمن', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('نورا عبدالرحمن')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="advertiser" data-user-blocked="false">
                        <td>
                            <strong>عبدالله سالم أحمد</strong>
                        </td>
                        <td>+971 55 333 4444</td>
                        <td>abdullah.salem@email.com</td>
                        <td>
                            <span class="badge bg-primary">12</span>
                        </td>
                        <td>
                           
                            FB-2024-7890
                        </td>
                        <td>
                            <span class="status-badge status-advertiser">معلن</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('عبدالله سالم أحمد')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('عبدالله سالم أحمد')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'عبدالله سالم أحمد', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('عبدالله سالم أحمد')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="visitor" data-user-blocked="false">
                        <td>
                            <strong>ليلى حسن محمد</strong>
                        </td>
                        <td>+971 52 555 6666</td>
                        <td>layla.hassan@email.com</td>
                        <td>
                            <span class="badge bg-secondary">0</span>
                        </td>
                        <td>
                            
                            PHN-2024-8024
                        </td>
                        <td>
                            <span class="status-badge status-visitor">زائر</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('ليلى حسن محمد')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('ليلى حسن محمد')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'ليلى حسن محمد', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('ليلى حسن محمد')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="advertiser" data-user-blocked="false">
                        <td>
                            <strong>يوسف طارق علي</strong>
                        </td>
                        <td>+971 56 777 8888</td>
                        <td>youssef.tarek@email.com</td>
                        <td>
                            <span class="badge bg-primary">19</span>
                        </td>
                        <td>
                            
                            GGL-2024-4567
                        </td>
                        <td>
                            <span class="status-badge status-advertiser">معلن</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('يوسف طارق علي')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('يوسف طارق علي')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'يوسف طارق علي', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('يوسف طارق علي')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="visitor" data-user-blocked="true">
                        <td>
                            <strong>مريم عادل حسن</strong>
                        </td>
                        <td>+971 54 999 0000</td>
                        <td>mariam.adel@email.com</td>
                        <td>
                            <span class="badge bg-secondary">0</span>
                        </td>
                        <td>
                            
                            EML-2024-6420
                        </td>
                        <td>
                            <span class="status-badge status-visitor">زائر محظور</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('مريم عادل حسن')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('مريم عادل حسن')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-unblock" onclick="toggleUserBlock(this, 'مريم عادل حسن', true)" title="فك الحظر">
                                <i class="bi bi-check-circle"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('مريم عادل حسن')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="advertiser" data-user-blocked="false">
                        <td>
                            <strong>حسام محمود سالم</strong>
                        </td>
                        <td>+971 50 123 9999</td>
                        <td>hussam.mahmoud@email.com</td>
                        <td>
                            <span class="badge bg-primary">31</span>
                        </td>
                        <td>
                           
                            FB-2024-8901
                        </td>
                        <td>
                            <span class="status-badge status-advertiser">معلن</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('حسام محمود سالم')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('حسام محمود سالم')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'حسام محمود سالم', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('حسام محمود سالم')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="visitor" data-user-blocked="false">
                        <td>
                            <strong>رانيا أحمد علي</strong>
                        </td>
                        <td>+971 55 456 1111</td>
                        <td>rania.ahmed@email.com</td>
                        <td>
                            <span class="badge bg-secondary">0</span>
                        </td>
                        <td>
                            
                            PHN-2024-3691
                        </td>
                        <td>
                            <span class="status-badge status-visitor">زائر</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('رانيا أحمد علي')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('رانيا أحمد علي')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'رانيا أحمد علي', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('رانيا أحمد علي')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="advertiser" data-user-blocked="false">
                        <td>
                            <strong>عمر سعد محمد</strong>
                        </td>
                        <td>+971 52 789 2222</td>
                        <td>omar.saad@email.com</td>
                        <td>
                            <span class="badge bg-primary">7</span>
                        </td>
                        <td>
                            
                            GGL-2024-2345
                        </td>
                        <td>
                            <span class="status-badge status-advertiser">معلن</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('عمر سعد محمد')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('عمر سعد محمد')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'عمر سعد محمد', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('عمر سعد محمد')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="visitor" data-user-blocked="false">
                        <td>
                            <strong>دينا خالد يوسف</strong>
                        </td>
                        <td>+971 56 321 3333</td>
                        <td>dina.khalid@email.com</td>
                        <td>
                            <span class="badge bg-secondary">0</span>
                        </td>
                        <td>
                            
                            EML-2024-7531
                        </td>
                        <td>
                            <span class="status-badge status-visitor">زائر</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('دينا خالد يوسف')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('دينا خالد يوسف')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'دينا خالد يوسف', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('دينا خالد يوسف')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="advertiser" data-user-blocked="false">
                        <td>
                            <strong>طارق عبدالله حسن</strong>
                        </td>
                        <td>+971 54 654 4444</td>
                        <td>tarek.abdullah@email.com</td>
                        <td>
                            <span class="badge bg-primary">26</span>
                        </td>
                        <td>
                           
                            FB-2024-6789
                        </td>
                        <td>
                            <span class="status-badge status-advertiser">معلن</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('طارق عبدالله حسن')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('طارق عبدالله حسن')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'طارق عبدالله حسن', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('طارق عبدالله حسن')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="visitor" data-user-blocked="false">
                        <td>
                            <strong>هند محمد سالم</strong>
                        </td>
                        <td>+971 50 987 5555</td>
                        <td>hind.mohammed@email.com</td>
                        <td>
                            <span class="badge bg-secondary">0</span>
                        </td>
                        <td>
                            
                            PHN-2024-9147
                        </td>
                        <td>
                            <span class="status-badge status-visitor">زائر</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('هند محمد سالم')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('هند محمد سالم')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'هند محمد سالم', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('هند محمد سالم')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="advertiser" data-user-blocked="false">
                        <td>
                            <strong>سامي أحمد علي</strong>
                        </td>
                        <td>+971 55 123 6666</td>
                        <td>sami.ahmed@email.com</td>
                        <td>
                            <span class="badge bg-primary">14</span>
                        </td>
                        <td>
                            
                            GGL-2024-5678
                        </td>
                        <td>
                            <span class="status-badge status-advertiser">معلن</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('سامي أحمد علي')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('سامي أحمد علي')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'سامي أحمد علي', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('سامي أحمد علي')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-user-type="visitor" data-user-blocked="false">
                        <td>
                            <strong>ندى حسن محمد</strong>
                        </td>
                        <td>+971 52 456 7777</td>
                        <td>nada.hassan@email.com</td>
                        <td>
                            <span class="badge bg-secondary">0</span>
                        </td>
                        <td>
                            
                            EML-2024-2468
                        </td>
                        <td>
                            <span class="status-badge status-visitor">زائر</span>
                        </td>
                        <td>
                            <button class="action-btn btn-view" onclick="viewUserData('ندى حسن محمد')" title="عرض البيانات">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                                <i class="bi bi-box"></i>
                            </button>
                            <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('ندى حسن محمد')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                                <i class="bi bi-star-fill"></i>
                            </button>
                            <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'ندى حسن محمد', false)" title="حظر المستخدم">
                                <i class="bi bi-ban"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteUser('ندى حسن محمد')" title="حذف المستخدم">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile User Cards (Hidden on Desktop) -->
        <div class="mobile-user-cards">
            <!-- User Card 1 -->
            <div class="user-card" data-user-type="advertiser" data-user-blocked="false">
                <div class="user-card-header">
                    <div class="user-card-avatar">أ</div>
                    <div class="user-card-info">
                        <h5>أحمد محمد علي</h5>
                        <div class="user-card-status">
                            <span class="status-badge status-advertiser">معلن</span>
                        </div>
                    </div>
                </div>
                <div class="user-card-details">
                    <div class="user-detail-row">
                        <span class="user-detail-label">رقم التليفون:</span>
                        <span class="user-detail-value">+971 50 123 4567</span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">الإيميل:</span>
                        <span class="user-detail-value">ahmed.ali@email.com</span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">عدد الإعلانات:</span>
                        <span class="user-detail-value"><span class="badge bg-primary">15</span></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">سجل من خلال:</span>
                        <span class="user-detail-value">GGL-2024-7891</span>
                    </div>
                </div>
                <div class="user-card-actions">
                    <button class="action-btn btn-view" onclick="viewUserData('أحمد محمد علي')" title="عرض البيانات">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                        <i class="bi bi-box"></i>
                    </button>
                    <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('أحمد محمد علي')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                        <i class="bi bi-star-fill"></i>
                    </button>
                    <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'أحمد محمد علي', false)" title="حظر المستخدم">
                        <i class="bi bi-ban"></i>
                    </button>
                    <button class="action-btn btn-delete" onclick="deleteUser('أحمد محمد علي')" title="حذف المستخدم">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

            <!-- User Card 2 -->
            <div class="user-card" data-user-type="visitor" data-user-blocked="false">
                <div class="user-card-header">
                    <div class="user-card-avatar">س</div>
                    <div class="user-card-info">
                        <h5>سارة محمود حسن</h5>
                        <div class="user-card-status">
                            <span class="status-badge status-visitor">زائر</span>
                        </div>
                    </div>
                </div>
                <div class="user-card-details">
                    <div class="user-detail-row">
                        <span class="user-detail-label">رقم التليفون:</span>
                        <span class="user-detail-value">+971 55 987 6543</span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">الإيميل:</span>
                        <span class="user-detail-value">sara.hassan@email.com</span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">عدد الإعلانات:</span>
                        <span class="user-detail-value"><span class="badge bg-secondary">0</span></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">سجل من خلال:</span>
                        <span class="user-detail-value">FB-2024-3456</span>
                    </div>
                </div>
                <div class="user-card-actions">
                    <button class="action-btn btn-view" onclick="viewUserData('سارة محمود حسن')" title="عرض البيانات">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                        <i class="bi bi-box"></i>
                    </button>
                    <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('سارة محمود حسن')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                        <i class="bi bi-star-fill"></i>
                    </button>
                    <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'سارة محمود حسن', false)" title="حظر المستخدم">
                        <i class="bi bi-ban"></i>
                    </button>
                    <button class="action-btn btn-delete" onclick="deleteUser('سارة محمود حسن')" title="حذف المستخدم">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

            <!-- User Card 3 -->
            <div class="user-card" data-user-type="advertiser" data-user-blocked="false">
                <div class="user-card-header">
                    <div class="user-card-avatar">م</div>
                    <div class="user-card-info">
                        <h5>محمد عبدالله أحمد</h5>
                        <div class="user-card-status">
                            <span class="status-badge status-advertiser">معلن</span>
                        </div>
                    </div>
                </div>
                <div class="user-card-details">
                    <div class="user-detail-row">
                        <span class="user-detail-label">رقم التليفون:</span>
                        <span class="user-detail-value">+971 52 456 7890</span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">الإيميل:</span>
                        <span class="user-detail-value">mohammed.ahmed@email.com</span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">عدد الإعلانات:</span>
                        <span class="user-detail-value"><span class="badge bg-primary">8</span></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">سجل من خلال:</span>
                        <span class="user-detail-value">EML-2024-9876</span>
                    </div>
                </div>
                <div class="user-card-actions">
                    <button class="action-btn btn-view" onclick="viewUserData('محمد عبدالله أحمد')" title="عرض البيانات">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                        <i class="bi bi-box"></i>
                    </button>
                    <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('محمد عبدالله أحمد')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                        <i class="bi bi-star-fill"></i>
                    </button>
                    <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'محمد عبدالله أحمد', false)" title="حظر المستخدم">
                        <i class="bi bi-ban"></i>
                    </button>
                    <button class="action-btn btn-delete" onclick="deleteUser('محمد عبدالله أحمد')" title="حذف المستخدم">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

            <!-- User Card 4 -->
            <div class="user-card" data-user-type="visitor" data-user-blocked="true">
                <div class="user-card-header">
                    <div class="user-card-avatar">ف</div>
                    <div class="user-card-info">
                        <h5>فاطمة طارق سالم</h5>
                        <div class="user-card-status">
                            <span class="status-badge status-visitor">زائر</span>
                            <span class="badge bg-danger ms-2" style="font-size: 0.7rem;">محظور</span>
                        </div>
                    </div>
                </div>
                <div class="user-card-details">
                    <div class="user-detail-row">
                        <span class="user-detail-label">رقم التليفون:</span>
                        <span class="user-detail-value">+971 56 321 9876</span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">الإيميل:</span>
                        <span class="user-detail-value">fatima.tarek@email.com</span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">عدد الإعلانات:</span>
                        <span class="user-detail-value"><span class="badge bg-secondary">0</span></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">سجل من خلال:</span>
                        <span class="user-detail-value">PHN-2024-5432</span>
                    </div>
                </div>
                <div class="user-card-actions">
                    <button class="action-btn btn-view" onclick="viewUserData('فاطمة طارق سالم')" title="عرض البيانات">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                        <i class="bi bi-box"></i>
                    </button>
                    <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('فاطمة طارق سالم')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                        <i class="bi bi-star-fill"></i>
                    </button>
                    <button class="action-btn btn-unblock" onclick="toggleUserBlock(this, 'فاطمة طارق سالم', true)" title="فك الحظر">
                        <i class="bi bi-check-circle"></i>
                    </button>
                    <button class="action-btn btn-delete" onclick="deleteUser('فاطمة طارق سالم')" title="حذف المستخدم">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

            <!-- User Card 5 -->
            <div class="user-card" data-user-type="advertiser" data-user-blocked="false">
                <div class="user-card-header">
                    <div class="user-card-avatar">خ</div>
                    <div class="user-card-info">
                        <h5>خالد يوسف محمد</h5>
                        <div class="user-card-status">
                            <span class="status-badge status-advertiser">معلن</span>
                        </div>
                    </div>
                </div>
                <div class="user-card-details">
                    <div class="user-detail-row">
                        <span class="user-detail-label">رقم التليفون:</span>
                        <span class="user-detail-value">+971 54 789 0123</span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">الإيميل:</span>
                        <span class="user-detail-value">khalid.youssef@email.com</span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">عدد الإعلانات:</span>
                        <span class="user-detail-value"><span class="badge bg-primary">23</span></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">سجل من خلال:</span>
                        <span class="user-detail-value">GGL-2024-9123</span>
                    </div>
                </div>
                <div class="user-card-actions">
                    <button class="action-btn btn-view" onclick="viewUserData('خالد يوسف محمد')" title="عرض البيانات">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="action-btn btn-packages" data-bs-toggle="modal" data-bs-target="#packagesModal" title="الباقات">
                        <i class="bi bi-box"></i>
                    </button>
                    <button class="action-btn btn-best-advertiser" onclick="openBestAdvertiserModal('خالد يوسف محمد')" title="تعيين أفضل المعلنين" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                        <i class="bi bi-star-fill"></i>
                    </button>
                    <button class="action-btn btn-block" onclick="toggleUserBlock(this, 'خالد يوسف محمد', false)" title="حظر المستخدم">
                        <i class="bi bi-ban"></i>
                    </button>
                    <button class="action-btn btn-delete" onclick="deleteUser('خالد يوسف محمد')" title="حذف المستخدم">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="pagination-info">
                <span class="text-muted">عرض <span id="currentStart">1</span> - <span id="currentEnd">5</span> من <span id="totalUsers">20</span> مستخدم</span>
            </div>
            <nav aria-label="صفحات المستخدمين">
                <ul class="pagination" id="paginationControls">
                    <li class="page-item" id="prevPage">
                        <a class="page-link" href="#" onclick="changePage('prev')">
                            <i class="bi bi-chevron-right me-1"></i>
                            السابق
                        </a>
                    </li>
                    <li class="page-item active" id="page1">
                        <a class="page-link" href="#" onclick="changePage(1)">1</a>
                    </li>
                    <li class="page-item" id="page2">
                        <a class="page-link" href="#" onclick="changePage(2)">2</a>
                    </li>
                    <li class="page-item" id="page3">
                        <a class="page-link" href="#" onclick="changePage(3)">3</a>
                    </li>
                    <li class="page-item" id="page4">
                        <a class="page-link" href="#" onclick="changePage(4)">4</a>
                    </li>
                    <li class="page-item" id="nextPage">
                        <a class="page-link" href="#" onclick="changePage('next')">
                            التالي
                            <i class="bi bi-chevron-left ms-1"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Packages Modal -->
<div class="modal fade packages-modal" id="packagesModal" tabindex="-1" aria-labelledby="packagesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="packagesModalLabel">
                    <i class="bi bi-box-seam me-2"></i>
                    إدارة الباقات
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق">
                    <i class="bi bi-x-lg text-dark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">جدول الباقات</h6>
                    <button class="btn btn-primary btn-sm" onclick="addPackageRow()">
                        <i class="bi bi-plus-circle me-1"></i>
                        إضافة باقة
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table packages-table" id="packagesTable">
                        <thead>
                            <tr>
                                <th>نوع الباقة</th>
                                <th>عدد الإعلانات</th>
                                <th>السعر (درهم)</th>
                                <th>مدة الصلاحية (يوم)</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="packagesTableBody">
                            <tr>
                                <td>
                                    <input type="text" class="form-control" value="مميز" placeholder="نوع الباقة">
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="5" placeholder="عدد الإعلانات">
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="50" placeholder="السعر">
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="30" placeholder="مدة الصلاحية">
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="removePackageRow(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" value="بريميوم" placeholder="نوع الباقة">
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="15" placeholder="عدد الإعلانات">
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="120" placeholder="السعر">
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="60" placeholder="مدة الصلاحية">
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="removePackageRow(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" value="بريميوم ستار" placeholder="نوع الباقة">
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="50" placeholder="عدد الإعلانات">
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="300" placeholder="السعر">
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="90" placeholder="مدة الصلاحية">
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="removePackageRow(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-end mt-3">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" onclick="savePackages()">حفظ التغييرات</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Best Advertiser Modal -->
<div class="modal fade" id="bestAdvertiserModal" tabindex="-1" aria-labelledby="bestAdvertiserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;">
                <h5 class="modal-title" id="bestAdvertiserModalLabel">
                    <i class="bi bi-star-fill me-2"></i>
                    تعيين أفضل المعلنين - <span id="bestAdvertiserUserName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-grid-3x3-gap me-2"></i>
                        اختر الأقسام التي سيظهر فيها كأفضل معلن:
                    </h6>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-3 border rounded" style="background: #f8f9fa;">
                            <label class="form-check-label" for="carSales">
                                <i class="bi bi-car-front me-2 text-primary"></i>
                                <strong>بيع السيارات</strong>
                                <small class="d-block text-muted">Car Sales</small>
                            </label>
                            <input class="form-check-input" type="checkbox" id="carSales" data-category="car-sales" style="transform: scale(1.2); margin-left: .5rem;">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-3 border rounded" style="background: #f8f9fa;">
                            <label class="form-check-label" for="carRent">
                                <i class="bi bi-key me-2 text-success"></i>
                                <strong>تأجير السيارات</strong>
                                <small class="d-block text-muted">Car Rent</small>
                            </label>
                            <input class="form-check-input" type="checkbox" id="carRent" data-category="car-rent" style="transform: scale(1.2); margin-left: .5rem;">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-3 border rounded" style="background: #f8f9fa;">
                            <label class="form-check-label" for="carServices">
                                <i class="bi bi-wrench me-2 text-warning"></i>
                                <strong>خدمات السيارات</strong>
                                <small class="d-block text-muted">Car Services</small>
                            </label>
                            <input class="form-check-input" type="checkbox" id="carServices" data-category="car-services" style="transform: scale(1.2); margin-left: .5rem;">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-3 border rounded" style="background: #f8f9fa;">
                            <label class="form-check-label" for="realEstate">
                                <i class="bi bi-house me-2 text-info"></i>
                                <strong>العقارات</strong>
                                <small class="d-block text-muted">Real Estate</small>
                            </label>
                            <input class="form-check-input" type="checkbox" id="realEstate" data-category="real-estate" style="transform: scale(1.2); margin-left: .5rem;">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-3 border rounded" style="background: #f8f9fa;">
                            <label class="form-check-label" for="electronics">
                                <i class="bi bi-phone me-2 text-danger"></i>
                                <strong>الإلكترونيات والأجهزة المنزلية</strong>
                                <small class="d-block text-muted">Electronics & Home Appliances</small>
                            </label>
                            <input class="form-check-input" type="checkbox" id="electronics" data-category="electronics" style="transform: scale(1.2); margin-left: .5rem;">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-3 border rounded" style="background: #f8f9fa;">
                            <label class="form-check-label" for="jobs">
                                <i class="bi bi-briefcase me-2 text-secondary"></i>
                                <strong>الوظائف</strong>
                                <small class="d-block text-muted">Jobs</small>
                            </label>
                            <input class="form-check-input" type="checkbox" id="jobs" data-category="jobs" style="transform: scale(1.2); margin-left: .5rem;">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-3 border rounded" style="background: #f8f9fa;">
                            <label class="form-check-label" for="restaurants">
                                <i class="bi bi-cup-hot me-2 text-dark"></i>
                                <strong>المطاعم</strong>
                                <small class="d-block text-muted">Restaurants</small>
                            </label>
                            <input class="form-check-input" type="checkbox" id="restaurants" data-category="restaurants" style="transform: scale(1.2); margin-left: .5rem;">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-3 border rounded" style="background: #f8f9fa;">
                            <label class="form-check-label" for="otherServices">
                                <i class="bi bi-gear me-2 text-primary"></i>
                                <strong>خدمات أخرى</strong>
                                <small class="d-block text-muted">Other Services</small>
                            </label>
                            <input class="form-check-input" type="checkbox" id="otherServices" data-category="other-services" style="transform: scale(1.2); margin-left: .5rem;">
                        </div>
                    </div>
                </div>
                
                <div class="text-end mt-4">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" onclick="saveBestAdvertiserSettings()" style="background: linear-gradient(135deg, #ffd700, #ffed4e); color: #333; border: none;">
                        <i class="bi bi-check-circle me-1"></i>
                        حفظ الإعدادات
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Pagination variables
let currentPage = 1;
const itemsPerPage = 15;
let allUsersData = [];
let filteredData = [];

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const searchInput = document.querySelector('.smart-search');
    const tableBody = document.getElementById('usersTableBody');
    
    // Store all users data from the table
    storeAllUsersData();
    
    // Initialize pagination
    updatePagination();

    // Filter by user type
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            filterTable(filter, searchInput.value);
        });
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
        filterTable(activeFilter, this.value);
    });

    function storeAllUsersData() {
        const rows = tableBody.querySelectorAll('tr');
        allUsersData = Array.from(rows).map(row => ({
            element: row.cloneNode(true),
            userType: row.getAttribute('data-user-type'),
            text: row.textContent.toLowerCase(),
            blocked: row.getAttribute('data-user-blocked') === 'true'
        }));
        filteredData = [...allUsersData];
    }

    function filterTable(filter, searchTerm) {
        // Reset to first page when filtering
        currentPage = 1;
        
        // Filter data based on criteria
        filteredData = allUsersData.filter(userData => {
            const matchesSearch = searchTerm === '' || userData.text.includes(searchTerm.toLowerCase());
            
            let matchesFilter = true;
            if (filter === 'advertisers') {
                matchesFilter = userData.userType === 'advertiser';
            } else if (filter === 'visitors') {
                matchesFilter = userData.userType === 'visitor';
            }
            
            return matchesSearch && matchesFilter;
        });
        
        // Update pagination and display
        updatePagination();
        displayCurrentPage();
    }

    function displayCurrentPage() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const pageData = filteredData.slice(startIndex, endIndex);
        
        // Clear table body
        tableBody.innerHTML = '';
        
        // Add rows for current page
        pageData.forEach(userData => {
            tableBody.appendChild(userData.element.cloneNode(true));
        });
        
        // Update pagination info
        const totalItems = filteredData.length;
        const currentStart = totalItems > 0 ? startIndex + 1 : 0;
        const currentEnd = Math.min(endIndex, totalItems);
        
        document.getElementById('currentStart').textContent = currentStart;
        document.getElementById('currentEnd').textContent = currentEnd;
        document.getElementById('totalUsers').textContent = totalItems;
    }

    function updatePagination() {
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        const paginationControls = document.getElementById('paginationControls');
        
        // Update page buttons visibility
        for (let i = 1; i <= 4; i++) {
            const pageBtn = document.getElementById(`page${i}`);
            if (i <= totalPages) {
                pageBtn.style.display = 'block';
                pageBtn.classList.toggle('active', i === currentPage);
            } else {
                pageBtn.style.display = 'none';
            }
        }
        
        // Update prev/next buttons
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        
        prevBtn.classList.toggle('disabled', currentPage === 1);
        nextBtn.classList.toggle('disabled', currentPage === totalPages || totalPages === 0);
        
        displayCurrentPage();
    }

    // Make functions global for onclick handlers
    window.changePage = function(page) {
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        
        if (page === 'prev' && currentPage > 1) {
            currentPage--;
        } else if (page === 'next' && currentPage < totalPages) {
            currentPage++;
        } else if (typeof page === 'number' && page >= 1 && page <= totalPages) {
            currentPage = page;
        }
        
        updatePagination();
    };
});

// View user data function
function viewUserData(userName) {
    Swal.fire({
        title: 'بيانات المستخدم',
        html: `
            <div class="text-end">
                <h6><i class="bi bi-person me-2"></i>الاسم: ${userName}</h6>
                <p><i class="bi bi-envelope me-2"></i>الإيميل: user@example.com</p>
                <p><i class="bi bi-phone me-2"></i>الهاتف: +971 50 123 4567</p>
                <p><i class="bi bi-calendar me-2"></i>تاريخ التسجيل: 2024-01-15</p>
                <p><i class="bi bi-geo-alt me-2"></i>الموقع: دبي، الإمارات</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'إغلاق',
        confirmButtonColor: '#2c3e50'
    });
}

// Toggle user block/unblock function
function toggleUserBlock(button, userName, isBlocked) {
    const action = isBlocked ? 'فك الحظر' : 'الحظر';
    const actionText = isBlocked ? 'فك حظر' : 'حظر';
    
    Swal.fire({
        title: `${action} المستخدم`,
        text: `هل أنت متأكد من ${actionText} المستخدم "${userName}"؟`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: isBlocked ? '#28a745' : '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `نعم، ${actionText}`,
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            // Update button appearance and functionality
            const row = button.closest('tr');
            const statusBadge = row.querySelector('.status-badge');
            
            if (isBlocked) {
                // Unblock user
                button.className = 'action-btn btn-block';
                button.innerHTML = '<i class="bi bi-ban"></i>';
                button.title = 'حظر المستخدم';
                button.onclick = () => toggleUserBlock(button, userName, false);
                row.setAttribute('data-user-blocked', 'false');
                statusBadge.textContent = statusBadge.textContent.replace(' محظور', '');
            } else {
                // Block user
                button.className = 'action-btn btn-unblock';
                button.innerHTML = '<i class="bi bi-check-circle"></i>';
                button.title = 'فك الحظر';
                button.onclick = () => toggleUserBlock(button, userName, true);
                row.setAttribute('data-user-blocked', 'true');
                statusBadge.textContent += ' محظور';
            }
            
            Swal.fire({
                title: 'تم بنجاح!',
                text: `تم ${actionText} المستخدم "${userName}" بنجاح.`,
                icon: 'success',
                confirmButtonColor: '#28a745'
            });
        }
    });
}

// Delete user function
function deleteUser(userName) {
    Swal.fire({
        title: 'حذف المستخدم',
        text: `هل أنت متأكد من حذف المستخدم "${userName}"؟ لا يمكن التراجع عن هذا الإجراء!`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you would typically send a request to delete the user
            // For now, we'll just remove the row from the table
            const buttons = document.querySelectorAll('.btn-delete');
            buttons.forEach(btn => {
                if (btn.onclick && btn.onclick.toString().includes(userName)) {
                    btn.closest('tr').remove();
                }
            });
            
            Swal.fire({
                title: 'تم الحذف!',
                text: `تم حذف المستخدم "${userName}" بنجاح.`,
                icon: 'success',
                confirmButtonColor: '#28a745'
            });
        }
    });
}

// Packages management functions
function addPackageRow() {
    const tableBody = document.getElementById('packagesTableBody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>
            <input type="text" class="form-control" placeholder="نوع الباقة">
        </td>
        <td>
            <input type="number" class="form-control" placeholder="عدد الإعلانات">
        </td>
        <td>
            <input type="number" class="form-control" placeholder="السعر">
        </td>
        <td>
            <input type="number" class="form-control" placeholder="مدة الصلاحية">
        </td>
        <td>
            <button class="btn btn-danger btn-sm" onclick="removePackageRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tableBody.appendChild(newRow);
}

function removePackageRow(button) {
    Swal.fire({
        title: 'حذف الباقة',
        text: 'هل أنت متأكد من حذف هذه الباقة؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('tr').remove();
            Swal.fire({
                title: 'تم الحذف!',
                text: 'تم حذف الباقة بنجاح.',
                icon: 'success',
                confirmButtonColor: '#28a745'
            });
        }
    });
}

function savePackages() {
    const rows = document.querySelectorAll('#packagesTableBody tr');
    const packages = [];
    
    rows.forEach(row => {
        const inputs = row.querySelectorAll('input');
        if (inputs.length >= 4) {
            packages.push({
                type: inputs[0].value,
                ads_count: inputs[1].value,
                price: inputs[2].value,
                validity: inputs[3].value
            });
        }
    });
    
    // Here you would typically send the packages data to the server
    console.log('Packages to save:', packages);
    
    Swal.fire({
        title: 'تم الحفظ!',
        text: 'تم حفظ تغييرات الباقات بنجاح.',
        icon: 'success',
        confirmButtonColor: '#28a745'
    }).then(() => {
        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('packagesModal'));
        modal.hide();
    });
}

// Best Advertiser Modal Functions
// تخزين إعدادات كل مستخدم بشكل منفصل
let userBestAdvertiserSettings = {};

function openBestAdvertiserModal(userName, userId = null) {
    document.getElementById('bestAdvertiserUserName').textContent = userName;
    
    // إذا لم يتم تمرير userId، استخدم userName كمعرف مؤقت
    const userKey = userId || userName;
    
    // تحميل الإعدادات المحفوظة للمستخدم إن وجدت
    loadUserBestAdvertiserSettings(userKey);
    
    const modal = new bootstrap.Modal(document.getElementById('bestAdvertiserModal'));
    modal.show();
}

function loadUserBestAdvertiserSettings(userKey) {
    // إعادة تعيين جميع الـ switches
    const switches = document.querySelectorAll('#bestAdvertiserModal .form-check-input');
    switches.forEach(switchEl => {
        switchEl.checked = false;
    });
    
    // تحميل الإعدادات المحفوظة للمستخدم
    if (userBestAdvertiserSettings[userKey]) {
        const userCategories = userBestAdvertiserSettings[userKey];
        switches.forEach(switchEl => {
            const category = switchEl.getAttribute('data-category');
            if (userCategories.includes(category)) {
                switchEl.checked = true;
            }
        });
    }
}

function saveBestAdvertiserSettings() {
    const userName = document.getElementById('bestAdvertiserUserName').textContent;
    const userKey = userName; // يمكن استخدام user ID بدلاً من الاسم في التطبيق الحقيقي
    const categories = [];
    
    // Get all category switches
    const switches = document.querySelectorAll('#bestAdvertiserModal .form-check-input');
    switches.forEach(switchEl => {
        if (switchEl.checked) {
            categories.push(switchEl.getAttribute('data-category'));
        }
    });
    
    // حفظ الإعدادات للمستخدم المحدد
    userBestAdvertiserSettings[userKey] = categories;
    
    // Here you would typically send the data to the server
    console.log('Best advertiser settings for', userName, ':', categories);
    console.log('All user settings:', userBestAdvertiserSettings);
    
    // تحديث عرض الأزرار في الجدول لإظهار الحالة الحالية
    updateBestAdvertiserButtonDisplay(userKey, categories);
    
    Swal.fire({
        title: 'تم الحفظ!',
        text: `تم حفظ إعدادات أفضل المعلنين للمستخدم ${userName} بنجاح.`,
        icon: 'success',
        confirmButtonColor: '#28a745'
    }).then(() => {
        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('bestAdvertiserModal'));
        modal.hide();
    });
}

function updateBestAdvertiserButtonDisplay(userKey, categories) {
    // البحث عن جميع أزرار المستخدم في الجدول وتحديث عرضها
    const userButtons = document.querySelectorAll(`[onclick*="${userKey}"]`);
    userButtons.forEach(button => {
        if (button.classList.contains('btn-best-advertiser')) {
            // تحديث لون الزر بناءً على عدد الأقسام المفعلة
            if (categories.length > 0) {
                button.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                button.title = `أفضل معلن في ${categories.length} قسم/أقسام: ${categories.join(', ')}`;
            } else {
                button.style.background = 'linear-gradient(135deg, #6c757d 0%, #495057 100%)';
                button.title = 'لم يتم تعيينه كأفضل معلن في أي قسم';
            }
        }
    });
}

// دالة لعرض إعدادات جميع المستخدمين (للاختبار)
function showAllUserSettings() {
    console.log('إعدادات جميع المستخدمين:', userBestAdvertiserSettings);
    return userBestAdvertiserSettings;
}
</script>

@endsection