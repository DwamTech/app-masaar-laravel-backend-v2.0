@extends('layouts.dashboard')

@section('title', 'إدارة الحسابات')

@section('styles')
{{-- أنماط مخصصة لتحسين تصميم الصفحة --}}
<style>
    :root {
        --primary-orange: #FC8700;
        --primary-gray: #6c757d;
        --light-gray: #f8f9fa;
        --white: #ffffff;
        --shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --shadow-lg: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .page-header {
        background: linear-gradient(135deg, var(--primary-orange) 0%, #e67600 100%);
        color: var(--white);
        padding: 2rem 0;
        margin: -2rem -2rem 2rem -2rem;
        border-radius: 0 0 24px 24px;
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>') repeat;
        pointer-events: none;
    }
    
    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 1;
    }
    
    .page-header .subtitle {
        opacity: 0.9;
        font-size: 1.1rem;
        margin-top: 0.5rem;
        position: relative;
        z-index: 1;
    }
    
    .stats-cards {
        margin-top: -1rem;
        margin-bottom: 2rem;
        display: flex;
        gap: 1.5rem;
        justify-content: space-between;
    }
    
    .stats-cards .col-md-3 {
        flex: 1;
        max-width: none;
        padding: 0;
    }
    
    .stat-card {
        background: var(--white);
        border-radius: 20px;
        padding: 2rem 1.5rem;
        box-shadow: var(--shadow-lg);
        border: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-orange), #e67600);
        border-radius: 0 20px 20px 0;
    }
    
    .stat-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 12px 35px rgba(252, 135, 0, 0.2), 0 4px 15px rgba(252, 135, 0, 0.1);
    }
    
    .stat-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-orange), #e67600);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 2.2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 20px rgba(252, 135, 0, 0.3);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 12px 30px rgba(252, 135, 0, 0.4);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-orange);
        margin: 0;
    }
    
    .stat-label {
        color: var(--primary-gray);
        font-size: 0.9rem;
        margin: 0;
    }
    
    .main-card {
        background: var(--white);
        border-radius: 20px;
        box-shadow: var(--shadow);
        border: none;
        overflow: hidden;
    }
    
    .card-header {
        background: var(--white);
        border-bottom: 1px solid #e9ecef;
        padding: 2rem;
        border-radius: 20px 20px 0 0 !important;
    }
    
    .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .nav-pills {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 0.5rem;
    }
    
    .nav-pills .nav-link {
        border-radius: 8px;
        margin: 0 0.25rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        color: var(--primary-gray);
        transition: all 0.3s ease;
        border: none;
    }
    
    .nav-pills .nav-link:hover {
        background: rgba(252, 135, 0, 0.1);
        color: var(--primary-orange);
    }
    
    .nav-pills .nav-link.active {
        background: var(--white) !important;
        color: var(--primary-orange) !important;
        box-shadow: 0 4px 12px rgba(252, 135, 0, 0.5) !important;
        transform: translateY(-1px) !important;
        font-weight: 700 !important;
        border: 3px solid var(--primary-orange) !important;
        position: relative !important;
    }
    
    .table-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 8px 16px rgba(0, 0, 0, 0.06);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        animation: tableSlideIn 0.6s ease-out;
    }
    
    .table-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(252, 135, 0, 0.02), rgba(230, 118, 0, 0.02));
        pointer-events: none;
        z-index: 1;
    }
    
    .table {
        margin: 0;
        border-radius: 20px;
        position: relative;
        z-index: 2;
        background: transparent;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table thead th {
        background: linear-gradient(135deg, #FC8700 0%, #e67600 50%, #d66500 100%);
        color: var(--white);
        border: none;
        font-weight: 700;
        padding: 1.5rem 1.25rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    
    .table thead th::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        animation: shimmerHeader 3s infinite;
    }
    
    .table thead th:first-child {
        border-radius: 20px 0 0 0;
    }
    
    .table thead th:last-child {
        border-radius: 0 20px 0 0;
    }
    
    .table tbody td {
        padding: 1.25rem;
        border: none;
        border-bottom: 1px solid rgba(241, 243, 244, 0.6);
        vertical-align: middle;
        background: rgba(255, 255, 255, 0.8);
        position: relative;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .table tbody tr {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    
    .table tbody tr::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 0;
        background: linear-gradient(135deg, var(--primary-orange), #e67600);
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
    }
    
    .table tbody tr:hover {
        background: linear-gradient(135deg, rgba(252, 135, 0, 0.08), rgba(230, 118, 0, 0.05));
        transform: translateY(-2px) scale(1.005);
        box-shadow: 0 8px 25px rgba(252, 135, 0, 0.15), 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .table tbody tr:hover::before {
        width: 4px;
    }
    
    .table tbody tr:hover td {
        background: transparent;
        color: #333;
        font-weight: 500;
    }
    
    .table tbody tr:last-child td:first-child {
        border-radius: 0 0 0 20px;
    }
    
    .table tbody tr:last-child td:last-child {
        border-radius: 0 0 20px 0;
    }
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .btn-primary {
        background: var(--primary-orange);
        border-color: var(--primary-orange);
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: #e67600;
        border-color: #e67600;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(252, 135, 0, 0.3);
    }
    
    .btn-outline-primary {
        color: var(--primary-orange);
        border-color: var(--primary-orange);
        border-radius: 8px;
        font-weight: 500;
    }
    
    .btn-outline-primary:hover {
        background: var(--primary-orange);
        border-color: var(--primary-orange);
    }
    
    .btn-icon {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 6px;
    }
    
    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
    }
    
    .modal-header {
        background: linear-gradient(135deg, var(--primary-orange), #e67600);
        color: var(--white);
        border-radius: 20px 20px 0 0;
        border: none;
        padding: 1.5rem 2rem;
    }
    
    .modal-header .btn-close {
        filter: invert(1);
    }
    
    .modal-body {
        padding: 2rem;
    }
    
    .modal-footer {
        border-top: 1px solid #dee2e6;
        background-color: #f8f9fa;
    }
    
    .form-control {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 0.2rem rgba(252, 135, 0, 0.25);
    }
    
    .form-label {
        font-weight: 600;
        color: var(--primary-gray);
        margin-bottom: 0.5rem;
    }
    
    .badge {
        border-radius: 6px;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
    }
    
    .text-primary {
        color: var(--primary-orange) !important;
    }
    
    .search-box {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .search-box input {
        padding-right: 3rem;
        border-radius: 12px;
        border: 2px solid #e9ecef;
    }
    
    .search-box .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-gray);
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--primary-gray);
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    /* الرسوم المتحركة المتقدمة */
    @keyframes tableSlideIn {
        0% {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes shimmerHeader {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }
    
    @keyframes rowSlideIn {
        0% {
            opacity: 0;
            transform: translateX(-20px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes glowPulse {
        0%, 100% {
            box-shadow: 0 0 5px rgba(252, 135, 0, 0.3);
        }
        50% {
            box-shadow: 0 0 20px rgba(252, 135, 0, 0.6), 0 0 30px rgba(252, 135, 0, 0.4);
        }
    }
    
    /* تحسينات إضافية للجدول */
    .table tbody tr {
        animation: rowSlideIn 0.3s ease-out;
        animation-fill-mode: both;
    }
    
    .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
    .table tbody tr:nth-child(2) { animation-delay: 0.15s; }
    .table tbody tr:nth-child(3) { animation-delay: 0.2s; }
    .table tbody tr:nth-child(4) { animation-delay: 0.25s; }
    .table tbody tr:nth-child(5) { animation-delay: 0.3s; }
    
    .table:hover {
        animation: glowPulse 2s infinite;
    }
    
    /* تأثيرات إضافية للخلايا */
    .table tbody td {
        border-left: 3px solid transparent;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .table tbody tr:hover td:first-child {
        border-left-color: var(--primary-orange);
        padding-left: 1.5rem;
    }
    
    /* تحسين الاستجابة */
    @media (max-width: 992px) {
        .stats-cards {
            flex-direction: column;
            gap: 1rem;
        }
        
        .stats-cards .col-md-3 {
            width: 100%;
        }
        
        .stat-card {
            flex-direction: row;
            text-align: right;
            padding: 1.5rem;
        }
        
        .stat-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
            margin-bottom: 0;
            margin-left: 1rem;
            flex-shrink: 0;
        }
        
        .stat-content {
            flex: 1;
        }
    }
    
    @media (max-width: 768px) {
        .table-container {
            border-radius: 15px;
            margin: 0 10px;
        }
        
        .table thead th {
            padding: 1rem 0.75rem;
            font-size: 0.8rem;
        }
        
        .table tbody td {
            padding: 1rem 0.75rem;
        }
        
        .stats-cards {
            margin: 0 -0.5rem;
        }
        
        .stat-card {
            margin: 0 0.5rem 1rem 0.5rem;
            padding: 1.25rem;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            font-size: 1.8rem;
        }
        
        .stat-number {
            font-size: 1.75rem;
        }
    }
    
    /* تصميم حديث ومبدع لنافذة التفاصيل */
    .modern-details-modal .modal-dialog {
        max-width: 95vw;
    }

    .modern-modal-content {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #ffffff 100%);
        border-radius: 25px;
        box-shadow: 
            0 25px 80px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(252, 135, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        border: none;
        overflow: hidden;
        backdrop-filter: blur(10px);
        position: relative;
    }

    .modern-modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #FC8700, #ff9500, #FC8700);
        background-size: 200% 100%;
        animation: gradientShift 3s ease-in-out infinite;
    }

    .modern-modal-header {
        background: linear-gradient(135deg, #FC8700 0%, #ff9500 30%, #FC8700 70%, #e67600 100%);
        color: white;
        border: none;
        padding: 25px 35px;
        position: relative;
        overflow: hidden;
    }

    .modern-modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shimmerFlow 4s infinite;
    }

    .modern-modal-title {
        font-size: 1.6rem;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        z-index: 2;
    }

    .title-icon-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .title-icon {
        font-size: 2rem;
        animation: iconPulse 2s ease-in-out infinite;
        position: relative;
        z-index: 2;
    }

    .icon-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 50px;
        height: 50px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, transparent 70%);
        border-radius: 50%;
        animation: glowPulse 2s ease-in-out infinite;
    }

    .title-text {
        background: linear-gradient(45deg, #ffffff, #f0f0f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .title-decoration {
        flex: 1;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
        border-radius: 1px;
    }

    .modern-close-btn {
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        position: relative;
        z-index: 3;
    }

    .modern-close-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: rotate(90deg) scale(1.15);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .modern-close-btn i {
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .modern-modal-body {
        padding: 35px;
        background: linear-gradient(135deg, #fafbfc 0%, #ffffff 50%, #f8f9fa 100%);
        position: relative;
    }
    
    /* أنماط التفاصيل الحديثة */
    .details-container {
        display: grid;
        gap: 20px;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
    
    .details-item {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(252, 135, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .details-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #FC8700, #ff9500);
        border-radius: 0 15px 15px 0;
    }
    
    .details-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(252, 135, 0, 0.15);
    }
    
    .details-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }
    
    .details-label i {
        color: #FC8700;
        font-size: 1.1rem;
    }
    
    .details-content {
        margin-right: 25px;
    }
    
    .details-value {
        color: #555;
        font-weight: 500;
        line-height: 1.5;
    }
    
    .details-empty {
        color: #999;
        font-style: italic;
        opacity: 0.7;
    }
    
    .details-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .badge-success {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.2);
    }
    
    .badge-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }
    
    .badge-warning {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }
    
    .badge-info {
        background: rgba(252, 135, 0, 0.1);
        color: #FC8700;
        border: 1px solid rgba(252, 135, 0, 0.2);
    }
    
    .details-image-container {
        position: relative;
        display: inline-block;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .details-image-container:hover {
        transform: scale(1.08);
        box-shadow: 0 15px 35px rgba(252, 135, 0, 0.3);
    }
    
    .details-image:hover {
        border-color: #FC8700;
        transform: scale(1.02);
    }
    
    .details-image {
        max-width: 150px;
        max-height: 100px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        object-fit: cover;
        border: 2px solid rgba(252, 135, 0, 0.2);
    }
    
    .details-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
        border-radius: 12px;
    }
    
    .details-image-container:hover .details-image-overlay {
        opacity: 1;
    }
    
    .details-image-overlay i {
        color: white;
        font-size: 1.8rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }
    
    /* تحسين نافذة عرض الصورة الكاملة */
    .img-modal .modal-dialog {
        max-width: 90vw;
        max-height: 90vh;
    }
    
    .img-modal .modal-content {
        background: transparent;
        border: none;
        box-shadow: none;
    }
    
    .img-modal .modal-body {
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .img-modal img {
        max-width: 100%;
        max-height: 85vh;
        object-fit: contain;
        border-radius: 15px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    
    /* تحسينات الجدول الرئيسي */
    .modern-accounts-table {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .modern-accounts-table thead th {
        background: linear-gradient(135deg, #FC8700 0%, #ff9500 100%);
        color: white;
        font-weight: 600;
        padding: 18px 15px;
        border: none;
        font-size: 0.95rem;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .modern-accounts-table thead th i {
        opacity: 0.9;
        font-size: 1.1rem;
    }
    
    .modern-accounts-table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.9rem;
    }
    
    .modern-accounts-table tbody tr {
        transition: all 0.3s ease;
        background: white;
    }
    
    .modern-accounts-table tbody tr:hover {
        background: linear-gradient(135deg, rgba(252, 135, 0, 0.05) 0%, rgba(255, 149, 0, 0.03) 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(252, 135, 0, 0.15);
    }
    
    .name-column {
        min-width: 250px;
    }
    
    .phone-column {
        min-width: 140px;
    }
    
    .type-column {
        min-width: 150px;
    }
    
    .location-column {
        min-width: 120px;
    }
    
    .actions-column {
        min-width: 120px;
    }
    
    /* تحسين عرض البيانات في الخلايا */
    .user-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 8px 0;
    }
    
    .user-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .user-email {
        color: #6c757d;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .user-phone {
        font-weight: 500;
        color: #495057;
        direction: ltr;
        text-align: right;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .user-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 15px;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 500;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
    }
    
    .user-location {
        font-weight: 500;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-group .btn {
        margin: 0 2px;
        transition: all 0.3s ease;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .details-nested {
        margin-right: 15px;
        border-right: 2px solid rgba(252, 135, 0, 0.2);
        padding-right: 15px;
    }
    
    .nested-item {
        margin-bottom: 15px;
        background: rgba(252, 135, 0, 0.03);
        border-radius: 10px;
        padding: 15px;
        border: 1px solid rgba(252, 135, 0, 0.1);
    }
    
    /* الرسوم المتحركة الجديدة */
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    @keyframes shimmerFlow {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    @keyframes iconPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    @keyframes glowPulse {
         0%, 100% { opacity: 0.4; transform: translate(-50%, -50%) scale(1); }
         50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.2); }
     }
     
     /* التصميم المتجاوب للشاشات الصغيرة */
     @media (max-width: 768px) {
         .modern-details-modal .modal-dialog {
             max-width: 98vw;
             margin: 10px;
         }
         
         .modern-modal-header {
             padding: 20px 25px;
         }
         
         .modern-modal-title {
             font-size: 1.3rem;
             gap: 15px;
         }
         
         .title-icon {
             font-size: 1.6rem;
         }
         
         .modern-modal-body {
             padding: 25px 20px;
         }
         
         .details-container {
             grid-template-columns: 1fr;
             gap: 15px;
         }
         
         .details-item {
             padding: 15px;
         }
         
         .details-label {
             font-size: 0.9rem;
         }
         
         .details-image {
             max-width: 100px;
             max-height: 70px;
         }
     }
     
     @media (max-width: 480px) {
         .modern-modal-header {
             padding: 15px 20px;
         }
         
         .modern-modal-title {
             font-size: 1.1rem;
             gap: 10px;
         }
         
         .title-icon {
             font-size: 1.4rem;
         }
         
         .modern-close-btn {
             width: 35px;
             height: 35px;
         }
         
         .modern-close-btn i {
             font-size: 1rem;
         }
     }
     
     /* أنماط الأقسام المتخصصة الجديدة */
     .details-section {
         margin-bottom: 25px;
         background: #ffffff;
         border-radius: 12px;
         padding: 20px;
         box-shadow: 0 2px 10px rgba(0,0,0,0.05);
         border: 1px solid #e9ecef;
         transition: all 0.3s ease;
     }
     
     .details-section:hover {
         box-shadow: 0 4px 20px rgba(0,0,0,0.08);
         transform: translateY(-2px);
     }
     
     .details-section.specialized-section {
         border-left: 4px solid #FC8700;
         background: linear-gradient(135deg, #ffffff 0%, #fff8f0 100%);
     }
     
     .section-title {
         color: #2c3e50;
         font-size: 16px;
         font-weight: 600;
         margin-bottom: 15px;
         padding-bottom: 10px;
         border-bottom: 2px solid #e9ecef;
         display: flex;
         align-items: center;
         gap: 8px;
     }
     
     .section-title i {
         color: #FC8700;
         font-size: 18px;
         animation: iconPulse 2s infinite;
     }
     
     /* شبكة البيانات النصية */
     .text-data-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: 15px;
         margin-bottom: 20px;
     }
     
     /* قسم الصور المحسن */
     .images-section {
         margin-top: 20px;
         padding-top: 20px;
         border-top: 1px solid #e9ecef;
     }
     
     .images-title {
         color: #495057;
         font-size: 14px;
         font-weight: 600;
         margin-bottom: 15px;
         display: flex;
         align-items: center;
         gap: 6px;
     }
     
     .images-title i {
         color: #28a745;
         animation: iconPulse 2s infinite;
     }
     
     .images-grid {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
         gap: 20px;
     }
     
     .image-card {
         background: #ffffff;
         border-radius: 12px;
         padding: 15px;
         box-shadow: 0 2px 8px rgba(0,0,0,0.08);
         border: 1px solid #e9ecef;
         transition: all 0.3s ease;
         text-align: center;
     }
     
     .image-card:hover {
         transform: translateY(-3px);
         box-shadow: 0 6px 20px rgba(252, 135, 0, 0.15);
         border-color: #FC8700;
     }
     
     .image-label {
         font-size: 12px;
         font-weight: 600;
         color: #6c757d;
         margin-bottom: 10px;
         text-align: center;
         background: #f8f9fa;
         padding: 5px 10px;
         border-radius: 6px;
         display: inline-block;
     }
     
     /* تحسينات للقيم النقدية */
     .details-value.money {
         color: #28a745;
         font-weight: 600;
         display: flex;
         align-items: center;
         gap: 5px;
         background: #f8fff9;
         padding: 5px 10px;
         border-radius: 6px;
         border: 1px solid #d4edda;
     }
     
     .details-value.money i {
         color: #ffc107;
         animation: iconPulse 2s infinite;
     }
     
     /* تحسينات للقيم المنطقية */
     .details-value .badge {
         font-size: 11px;
         padding: 6px 12px;
         border-radius: 20px;
         font-weight: 500;
         text-transform: uppercase;
         letter-spacing: 0.5px;
     }
     
     .details-value .badge.bg-success {
         background: linear-gradient(135deg, #28a745, #20c997) !important;
         box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
     }
     
     .details-value .badge.bg-danger {
         background: linear-gradient(135deg, #dc3545, #e74c3c) !important;
         box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
     }
     
     /* تحسينات متجاوبة للأقسام الجديدة */
     @media (max-width: 768px) {
         .text-data-grid {
             grid-template-columns: 1fr;
         }
         
         .images-grid {
             grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
             gap: 15px;
         }
         
         .details-section {
             padding: 15px;
             margin-bottom: 20px;
         }
         
         .section-title {
             font-size: 14px;
         }
         
         .image-card {
             padding: 10px;
         }
     }
     
     @media (max-width: 480px) {
         .text-data-grid {
             gap: 10px;
         }
         
         .images-grid {
             grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
             gap: 10px;
         }
         
         .details-section {
             padding: 12px;
             margin-bottom: 15px;
         }
         
         .section-title {
             font-size: 13px;
             gap: 6px;
         }
         
         .image-label {
             font-size: 11px;
             padding: 4px 8px;
         }
         }
         
         .modern-modal-body {
             padding: 20px 15px;
         }
         
         .details-item {
             padding: 12px;
         }
         
         .details-label {
             font-size: 0.85rem;
             gap: 8px;
         }
         
         .details-content {
             margin-right: 15px;
         }
         
         .details-image {
             max-width: 80px;
             max-height: 60px;
         }
         
         /* تحسينات modal تفاصيل الحساب */
         #detailsModal .modal-dialog {
             max-width: 900px;
         }
         
         #detailsModal .modal-body {
             max-height: 70vh;
             overflow-y: auto;
             padding: 1.5rem;
         }
         
         #detailsModal .border {
             border: 1px solid #e9ecef !important;
             transition: all 0.3s ease;
         }
         
         #detailsModal .border:hover {
             border-color: var(--primary-orange) !important;
             box-shadow: 0 2px 8px rgba(252, 135, 0, 0.1);
         }
         
         #detailsModal .form-label {
             color: #495057;
             font-size: 0.875rem;
             margin-bottom: 0.5rem;
         }
         
         #detailsModal .text-info {
             color: var(--primary-orange) !important;
         }
         
         #detailsModal .text-secondary {
             color: #6c757d !important;
         }
         
         #detailsModal .bg-light {
             background-color: #f8f9fa !important;
         }
         
         #detailsModal .rounded {
             border-radius: 0.5rem !important;
         }
         
         #detailsModal .details-image {
             max-width: 120px;
             max-height: 90px;
             border-radius: 0.375rem;
             cursor: pointer;
             transition: transform 0.3s ease;
         }
         
         #detailsModal .details-image:hover {
             transform: scale(1.05);
         }
     
     /* أنماط نافذة إضافة حساب جديد */
    .add-account-modal {
        background: linear-gradient(135deg, #ffffff 0%, #fff8f0 100%);
        border-radius: 18px;
        box-shadow: 0 15px 45px rgba(252, 135, 0, 0.18);
        border: 1px solid rgba(252, 135, 0, 0.2);
    }
    .type-selection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 16px;
        padding: 4px;
    }
    .type-card {
        position: relative;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        padding: 18px 16px;
        background: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 8px;
        min-height: 160px;
        isolation: isolate;
    }
    .type-card::before {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        top: -40px;
        left: -40px;
        background: radial-gradient(circle at center, rgba(252, 135, 0, 0.12), transparent 60%);
        transform: rotate(12deg);
        z-index: -1;
    }
    .type-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(252, 135, 0, 0.08), transparent 60%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .type-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(252, 135, 0, 0.18);
    }
    .type-card:hover::after {
        opacity: 1;
    }
    .type-card.active {
        border-color: #FC8700;
        box-shadow: 0 0 0 3px rgba(252, 135, 0, 0.14) inset, 0 8px 22px rgba(252, 135, 0, 0.2);
    }
    .type-card-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #FC8700 0%, #ff9500 100%);
        color: #fff;
        box-shadow: 0 6px 16px rgba(252, 135, 0, 0.3);
        border: 2px solid #fff;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        font-size: 1.4rem;
        margin-bottom: 10px;
    }
    .type-card:hover .type-card-icon {
        transform: translateY(-2px) scale(1.06);
        box-shadow: 0 10px 22px rgba(252, 135, 0, 0.35);
    }
    .type-card.active .type-card-icon {
        transform: translateY(-1px) scale(1.03);
        box-shadow: 0 10px 26px rgba(252, 135, 0, 0.4);
    }
    .type-card-title {
        font-weight: 800;
        color: #2c3e50;
        font-size: 1rem;
        letter-spacing: 0.2px;
    }
    .type-card-hint {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 4px;
    }
    .selection-status {
        font-size: 0.9rem;
        color: #6c757d;
    }
</style>
@endsection

@push('styles')
<style>
  /* أنماط نافذة إضافة الحساب فقط - مقصورة داخل #addUserModal */
  #addUserModal .add-account-modal {
    background: linear-gradient(135deg, #ffffff 0%, #fff8f0 100%);
    border-radius: 18px;
    box-shadow: 0 15px 45px rgba(252, 135, 0, 0.18);
    border: 1px solid rgba(252, 135, 0, 0.2);
  }
  #addUserModal .type-selection-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 16px;
    padding: 4px;
  }
  #addUserModal .type-card {
    position: relative;
    border: 1px solid #e9ecef;
    border-radius: 14px;
    padding: 18px 16px;
    background: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 8px;
    min-height: 160px;
    isolation: isolate;
  }
  #addUserModal .type-card::before {
    content: '';
    position: absolute;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    top: -40px;
    left: -40px;
    background: radial-gradient(circle at center, rgba(252, 135, 0, 0.12), transparent 60%);
    transform: rotate(12deg);
    z-index: -1;
  }
  #addUserModal .type-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(252, 135, 0, 0.08), transparent 60%);
    opacity: 0;
    transition: opacity 0.3s ease;
  }
  #addUserModal .type-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 28px rgba(252, 135, 0, 0.18);
  }
  #addUserModal .type-card:hover::after {
    opacity: 1;
  }
  #addUserModal .type-card.active {
    border-color: #FC8700;
    box-shadow: 0 0 0 3px rgba(252, 135, 0, 0.14) inset, 0 8px 22px rgba(252, 135, 0, 0.2);
  }
  #addUserModal .type-card-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #FC8700 0%, #ff9500 100%);
    color: #fff;
    box-shadow: 0 6px 16px rgba(252, 135, 0, 0.3);
    border: 2px solid #fff;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    font-size: 1.4rem;
    margin-bottom: 10px;
  }
  #addUserModal .type-card:hover .type-card-icon {
    transform: translateY(-2px) scale(1.06);
    box-shadow: 0 10px 22px rgba(252, 135, 0, 0.35);
  }
  #addUserModal .type-card.active .type-card-icon {
    transform: translateY(-1px) scale(1.03);
    box-shadow: 0 10px 26px rgba(252, 135, 0, 0.4);
  }
  #addUserModal .type-card-title {
    font-weight: 800;
    color: #2c3e50;
    font-size: 1rem;
    letter-spacing: 0.2px;
  }
  #addUserModal .type-card-hint {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 4px;
  }
  #addUserModal .selection-status {
    font-size: 0.9rem;
    color: #6c757d;
  }
</style>
@endpush

@section('content')
<div id="accountsApp">
    <!-- Page Header -->
    <div class="page-header text-center">
        <h1><i class="bi bi-people me-3"></i>إدارة الحسابات</h1>
        <p class="subtitle">إدارة وتتبع جميع حسابات المستخدمين في النظام</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row stats-cards">
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-content">
                    <h3 class="stat-number" id="totalAccounts">0</h3>
                    <p class="stat-label">إجمالي الحسابات</p>
                </div>
                <!-- <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div> -->
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-content">
                    <h3 class="stat-number" id="activeAccounts">0</h3>
                    <p class="stat-label">الحسابات النشطة</p>
                </div>
                <!-- <div class="stat-icon">
                    <i class="bi bi-person-check"></i>
                </div> -->
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-content">
                    <h3 class="stat-number" id="inactiveAccounts">0</h3>
                    <p class="stat-label">الحسابات غير النشطة</p>
                </div>
                <!-- <div class="stat-icon">
                    <i class="bi bi-person-x"></i>
                </div> -->
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-content">
                    <h3 class="stat-number" id="newAccounts">0</h3>
                    <p class="stat-label">حسابات جديدة اليوم</p>
                </div>
                <!-- <div class="stat-icon">
                    <i class="bi bi-person-plus"></i>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Search Box -->
    <div class="search-box">
        <input type="text" class="form-control" id="searchInput" placeholder="البحث في الحسابات...">
        <i class="bi bi-search search-icon"></i>
    </div>

    {{-- رأس الصفحة والفلترة --}}
    <div class="main-card shadow-sm mb-4">
        <div class="card-header card-header-flex bg-white py-3">
            <h2 class="h4 mb-0 text-primary">
                <i class="bi bi-people-fill me-2"></i>إدارة الحسابات
            </h2>
            
        </div>
        <div class="card-body">
            <ul class="nav nav-pills" id="userTypeTabs">
                {{-- سيتم تعبئة أزرار الفلترة هنا عبر JavaScript --}}
            </ul>
        </div>
    </div>

    {{-- جدول عرض الحسابات --}}
    <div class="main-card">
        <div class="card-body p-0">
            <div id="accountsTable">
                {{-- سيتم تعبئة الجدول هنا عبر JavaScript --}}
            </div>
            <div class="empty-state d-none" id="emptyState">
                <i class="bi bi-inbox"></i>
                <h5>لا توجد حسابات</h5>
                <p>لم يتم العثور على أي حسابات تطابق معايير البحث</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal عرض التفاصيل (تصميم حديث ومبدع) -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modern-details-modal">
        <div class="modal-content modern-modal-content">
            <div class="modal-header modern-modal-header">
                <h5 class="modal-title modern-modal-title">
                    <div class="title-icon-wrapper">
                        <i class="bi bi-person-badge-fill title-icon"></i>
                        <div class="icon-glow"></div>
                    </div>
                    <span class="title-text">تفاصيل الحساب</span>
                    <div class="title-decoration"></div>
                </h5>
                <button type="button" class="btn-close modern-close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body modern-modal-body" id="detailsContent">
                {{-- سيتم عرض تفاصيل الحساب هنا بشكل بسيط ومنظم --}}
            </div>
        </div>
    </div>
</div>

<!-- Modal عرض الصورة -->
<div class="modal fade" id="imgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <img id="imgModalSrc" src="" style="max-width:98vw;max-height:85vh;display:block;margin:auto; border-radius: 8px;">
    </div>
</div>

<!-- Modal تأكيد الحذف (مع أيقونة تحذير) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-danger w-100">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>تحذير!
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p>هل أنت متأكد من رغبتك في حذف هذا الحساب بشكل نهائي؟</p>
                <p class="fw-bold fs-5" id="deleteUserName"></p>
                <p class="text-muted small">لا يمكن التراجع عن هذا الإجراء.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
                    <i class="bi bi-trash-fill me-2"></i>حذف نهائي
                </button>
                <button type="button" class="btn btn-secondary px-4 ms-2" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal التعديل الديناميكي (مع أيقونة حفظ) -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="editUserForm" autocomplete="off">
        <div class="modal-header">
          <h5 class="modal-title">
              <i class="bi bi-pencil-square me-2"></i>تعديل بيانات الحساب
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="editFormContent"></div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">
              <i class="bi bi-check-circle-fill me-2"></i>حفظ التعديلات
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        </div>
      </form>
    </div>
  </div>
</div><!-- Modal إضافة حساب جديد -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content add-account-modal">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>إنشاء حساب جديد</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="addUserSelectionStep">
          <div class="mb-3 text-muted small">
            اختر نوع الحساب لبدء الإعداد. سنجهز النموذج لكل نوع لاحقًا.
          </div>
          <div class="type-selection-grid">
          <div class="type-card" data-type="normal" onclick="selectNewAccountType('normal')">
            <div class="type-card-icon"><i class="bi bi-person"></i></div>
            <div class="type-card-title">مستخدم عادي</div>
            <div class="type-card-hint">حساب قياسي للمستخدمين</div>
          </div>
          <div class="type-card" data-type="restaurant" onclick="selectNewAccountType('restaurant')">
            <div class="type-card-icon"><i class="bi bi-shop"></i></div>
            <div class="type-card-title">مطعم</div>
            <div class="type-card-hint">ملف مطعم شامل</div>
          </div>
          <div class="type-card" data-type="real_estate_office" onclick="selectNewAccountType('real_estate_office')">
            <div class="type-card-icon"><i class="bi bi-building"></i></div>
            <div class="type-card-title">مكتب عقارات</div>
            <div class="type-card-hint">كيان تجاري للعقارات</div>
          </div>
          <div class="type-card" data-type="real_estate_individual" onclick="selectNewAccountType('real_estate_individual')">
            <div class="type-card-icon"><i class="bi bi-person-badge-fill"></i></div>
            <div class="type-card-title">سمسار</div>
            <div class="type-card-hint">وسيط عقاري فردي</div>
          </div>
          <div class="type-card" data-type="driver" onclick="selectNewAccountType('driver')">
            <div class="type-card-icon"><i class="bi bi-steering-wheel"></i></div>
            <div class="type-card-title">سائق</div>
            <div class="type-card-hint">سائق مرتبط بمكتب</div>
          </div>
          <div class="type-card" data-type="car_rental_office" onclick="selectNewAccountType('car_rental_office')">
            <div class="type-card-icon"><i class="bi bi-car-front"></i></div>
            <div class="type-card-title">مكتب تأجير سيارات</div>
            <div class="type-card-hint">تأجير سيارات وإدارة السائقين</div>
          </div>
          </div>
        </div>
        <div id="addUserFormStep" class="d-none">
          <form id="addNormalUserForm" autocomplete="off">
            @include('accounts.partials.normal-user-form')
          </form>
          <form id="addRealEstateOfficeForm" class="d-none" autocomplete="off">
            @include('accounts.partials.real-estate-office-form')
          </form>
          <form id="addRealEstateIndividualForm" class="d-none" autocomplete="off">
            @include('accounts.partials.real-estate-individual-form')
          </form>
          <form id="addDriverForm" class="d-none" autocomplete="off">
            @include('accounts.partials.driver-form')
          </form>
          <form id="addCarRentalOfficeForm" class="d-none" autocomplete="off">
            @include('accounts.partials.car-rental-office-form')
          </form>
          <form id="addRestaurantForm" class="d-none" autocomplete="off">
            @include('accounts.partials.restaurant-form')
          </form>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <div class="d-flex align-items-center gap-2">
          <span class="selection-status" id="addTypeStatus">لم يتم اختيار نوع بعد</span>
        </div>
        <div>
          <button type="button" class="btn btn-outline-secondary d-none" id="addUserBackBtn" onclick="backToTypeSelection()">
            <i class="bi bi-arrow-right-circle me-1"></i>رجوع
          </button>
          <button type="button" class="btn btn-primary" id="addUserProceedBtn" onclick="proceedCreateUser()" disabled>
            <i class="bi bi-arrow-right-circle me-1"></i>استمرار
          </button>
          <button type="button" class="btn btn-success d-none" id="addUserSubmitBtn" onclick="submitNormalUserForm()" disabled>
            <i class="bi bi-check-circle me-1"></i>إضافة
          </button>
          <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">إلغاء</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// =================== الجزء الخاص بالـ JavaScript ===================

// تعريف المتغيرات العامة
let users = [];
let userTypes = [];
let currentType = null;
let pollingInterval = null;
let detailsUserId = null;
let userToDelete = null;
const IMAGE_BASE = 'http://msar.app/storage/uploads/images/';

const userTypeFields = {
  'restaurant': [
    {name: 'name', label: 'اسم المستخدم', type: 'text'}, {name: 'email', label: 'البريد الإلكتروني', type: 'email'}, {name: 'phone', label: 'رقم الهاتف', type: 'text'}, {name: 'governorate', label: 'المحافظة', type: 'text'}, {name: 'restaurant_detail.restaurant_name', label: 'اسم المطعم', type: 'text'}, {name: 'restaurant_detail.logo_image', label: 'شعار المطعم', type: 'image'}, {name: 'restaurant_detail.profile_image', label: 'صورة الملف الشخصي', type: 'image'}, {name: 'restaurant_detail.owner_id_front_image', label: 'بطاقة المالك - أمامية', type: 'image'}, {name: 'restaurant_detail.owner_id_back_image', label: 'بطاقة المالك - خلفية', type: 'image'}, {name: 'restaurant_detail.license_front_image', label: 'رخصة المطعم - أمامية', type: 'image'}, {name: 'restaurant_detail.license_back_image', label: 'رخصة المطعم - خلفية', type: 'image'}, {name: 'restaurant_detail.commercial_register_front_image', label: 'السجل التجاري - أمامية', type: 'image'}, {name: 'restaurant_detail.commercial_register_back_image', label: 'السجل التجاري - خلفية', type: 'image'}, {name: 'restaurant_detail.vat_included', label: 'خاضع للقيمة المضافة', type: 'select', options: {'0':'لا', '1':'نعم'}}, {name: 'restaurant_detail.vat_image_front', label: 'شهادة ضريبية - أمامية', type: 'image'}, {name: 'restaurant_detail.vat_image_back', label: 'شهادة ضريبية - خلفية', type: 'image'},
  ], 'car_rental_office': [
    {name: 'name', label: 'اسم المكتب', type: 'text'}, {name: 'email', label: 'البريد الإلكتروني', type: 'email'}, {name: 'phone', label: 'رقم الهاتف', type: 'text'}, {name: 'governorate', label: 'المحافظة', type: 'text'}, {name: 'car_rental.office_detail.office_name', label: 'اسم المكتب', type: 'text'}, {name: 'car_rental.office_detail.logo_image', label: 'شعار المكتب', type: 'image'}, {name: 'car_rental.office_detail.commercial_register_front_image', label: 'سجل تجاري أمامية', type: 'image'}, {name: 'car_rental.office_detail.commercial_register_back_image', label: 'سجل تجاري خلفية', type: 'image'}, {name: 'car_rental.office_detail.payment_methods', label: 'طرق الدفع', type: 'text'}, {name: 'car_rental.office_detail.rental_options', label: 'خيارات التأجير', type: 'text'}, {name: 'car_rental.office_detail.cost_per_km', label: 'التكلفة لكل كم', type: 'text'}, {name: 'car_rental.office_detail.daily_driver_cost', label: 'تكلفة السائق اليومية', type: 'text'}, {name: 'car_rental.office_detail.max_km_per_day', label: 'أقصى كم/يوم', type: 'text'}, {name: 'car_rental.office_detail.is_available_for_delivery', label: 'يدعم التوصيل؟', type: 'select', options: {'0':'لا', '1':'نعم'}}, {name: 'car_rental.office_detail.is_available_for_rent', label: 'يدعم التأجير؟', type: 'select', options: {'0':'لا', '1':'نعم'}},
  ], 'driver': [
    {name: 'name', label: 'اسم السائق', type: 'text'}, {name: 'email', label: 'البريد الإلكتروني', type: 'email'}, {name: 'phone', label: 'رقم الهاتف', type: 'text'}, {name: 'governorate', label: 'المحافظة', type: 'text'}, {name: 'car_rental.driver_detail.profile_image', label: 'صورة شخصية', type: 'image'}, {name: 'car_rental.driver_detail.license_image', label: 'صورة رخصة القيادة', type: 'image'},
  ], 'real_estate_office': [
    {name: 'name', label: 'اسم المكتب', type: 'text'}, {name: 'email', label: 'البريد الإلكتروني', type: 'email'}, {name: 'phone', label: 'رقم الهاتف', type: 'text'}, {name: 'governorate', label: 'المحافظة', type: 'text'}, {name: 'real_estate.office_detail.office_name', label: 'اسم المكتب التفصيلي', type: 'text'}, {name: 'real_estate.office_detail.logo_image', label: 'شعار المكتب', type: 'image'}, {name: 'real_estate.office_detail.office_address', label: 'عنوان المكتب', type: 'text'}, {name: 'real_estate.office_detail.office_phone', label: 'هاتف المكتب', type: 'text'}, {name: 'real_estate.office_detail.office_image', label: 'صورة المكتب', type: 'image'}, {name: 'real_estate.office_detail.owner_id_front_image', label: 'بطاقة المالك - أمامية', type: 'image'}, {name: 'real_estate.office_detail.owner_id_back_image', label: 'بطاقة المالك - خلفية', type: 'image'}, {name: 'real_estate.office_detail.commercial_register_front_image', label: 'سجل تجاري أمامية', type: 'image'}, {name: 'real_estate.office_detail.commercial_register_back_image', label: 'سجل تجاري خلفية', type: 'image'}, {name: 'real_estate.office_detail.tax_enabled', label: 'خاضع للضريبة', type: 'select', options: {'0':'لا', '1':'نعم'}},
  ], 'real_estate_individual': [
    {name: 'name', label: 'اسم السمسار', type: 'text'}, {name: 'email', label: 'البريد الإلكتروني', type: 'email'}, {name: 'phone', label: 'رقم الهاتف', type: 'text'}, {name: 'governorate', label: 'المحافظة', type: 'text'}, {name: 'real_estate.individual_detail.profile_image', label: 'صورة شخصية', type: 'image'}, {name: 'real_estate.individual_detail.agent_name', label: 'اسم السمسار التفصيلي', type: 'text'}, {name: 'real_estate.individual_detail.agent_id_front_image', label: 'بطاقة السمسار أمامية', type: 'image'}, {name: 'real_estate.individual_detail.agent_id_back_image', label: 'بطاقة السمسار خلفية', type: 'image'}, {name: 'real_estate.individual_detail.tax_card_front_image', label: 'بطاقة ضريبية أمامية', type: 'image'}, {name: 'real_estate.individual_detail.tax_card_back_image', label: 'بطاقة ضريبية خلفية', type: 'image'},
  ], 'normal': [
    {name: 'name', label: 'اسم المستخدم', type: 'text'}, {name: 'email', label: 'البريد الإلكتروني', type: 'email'}, {name: 'phone', label: 'رقم الهاتف', type: 'text'}, {name: 'governorate', label: 'المحافظة', type: 'text'},
  ]
};

async function fetchUsers(auto = false) {
    const token = localStorage.getItem('token');
    if (!token) {
        alert("يرجى تسجيل الدخول أولًا.");
        window.location.href = '/login';
        return;
    }
    try {
        const res = await fetch('/api/users', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' } });
        if (!res.ok) { console.error("API responded with status:", res.status); return; }
        const data = await res.json();
        users = Array.isArray(data.users) ? data.users : [];
        userTypes = [...new Set(users.map(u => u.user_type))].filter(type => type !== 'admin');
        if (!currentType || !userTypes.includes(currentType)) { currentType = userTypes[0] || ''; }
        renderTabs();
        renderTable();
        if (detailsUserId && users.some(u => u.id === detailsUserId)) {
            document.getElementById('detailsContent').innerHTML = renderUserDetails(users.find(u => u.id === detailsUserId));
        } else if (detailsUserId) {
            detailsUserId = null;
            const modal = bootstrap.Modal.getInstance(document.getElementById('detailsModal'));
            if (modal) modal.hide();
        }
        if (!auto) console.log("Accounts refreshed");
    } catch (err) { console.error("Error fetching users:", err); }
}

function startPolling() {
    // تم إيقاف إعادة التحميل التلقائي
    // if (pollingInterval) clearInterval(pollingInterval);
    // pollingInterval = setInterval(() => fetchUsers(true), 10000);
    console.log("Auto-refresh disabled. Use manual refresh button instead.");
}

function renderTabs() {
    const tabs = userTypes.map(type => `
        <li class="nav-item">
            <button class="nav-link${type === currentType ? ' active' : ''}" onclick="changeType('${type}')">
                ${getTypeLabel(type)}
            </button>
        </li>
    `).join('');
    document.getElementById('userTypeTabs').innerHTML = tabs;
}

function changeType(type) {
    currentType = type;
    fetchUsers();
}

/**
 * [دالة محسّنة] - عرض جدول المستخدمين بتصميم جديد وأيقونات
 */
function renderTable() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    if (searchTerm) {
        filterUsers(searchTerm);
    } else {
        const filtered = users.filter(u => u.user_type === currentType && u.user_type !== 'admin');
        renderFilteredTable(filtered);
    }
    updateStatistics();
}

/**
 * [دالة محسّنة] - عرض أزرار القبول والرفض بأيقونات وألوان
 */
function renderApproveBtn(user) {
    if (typeof user.is_approved === 'undefined') return '';
    if (user.is_approved == 0) {
        return `<button class="btn btn-sm btn-outline-success btn-icon" title="قبول الحساب" onclick="approveUser(${user.id}, 1)">
                    <i class="bi bi-check-circle"></i>
                </button>`;
    } else {
        return `<button class="btn btn-sm btn-outline-warning btn-icon" title="إلغاء الموافقة" onclick="approveUser(${user.id}, 0)">
                    <i class="bi bi-x-circle"></i>
                </button>`;
    }
}


async function approveUser(id, val) {
    const token = localStorage.getItem('token');
    if (!token) { alert("يرجى تسجيل الدخول أولًا."); window.location.href = '/login'; return; }
    let res = await fetch(`/api/users/${id}`, {
        method: 'PUT',
        headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ is_approved: val })
    });
    if (res.ok) { console.log(`User ${id} is_approved updated to ${val}`); await fetchUsers(); } 
    else { alert('حدث خطأ أثناء التحديث! (status: ' + res.status + ')'); }
}

function showDetails(id) {
    detailsUserId = id;
    const user = users.find(u => u.id === id);
    if (!user) return;
    document.getElementById('detailsContent').innerHTML = renderUserDetails(user);
    new bootstrap.Modal(document.getElementById('detailsModal')).show();
}

function renderUserDetails(user) {
    // دوال مساعدة للحصول على الأيقونات والتسميات
    function getFieldIcon(key) {
        const iconMap = {
            'name': 'bi-person-fill',
            'email': 'bi-envelope-fill',
            'phone': 'bi-telephone-fill',
            'governorate': 'bi-geo-alt-fill',
            'id': 'bi-hash',
            'user_type': 'bi-tags-fill',
            'is_approved': 'bi-check-circle-fill',
            'created_at': 'bi-calendar-plus-fill',
            'updated_at': 'bi-calendar-check-fill',
            'restaurant_name': 'bi-shop',
            'office_name': 'bi-building',
            'agent_name': 'bi-person-badge',
            'logo_image': 'bi-image',
            'profile_image': 'bi-person-circle',
            'license': 'bi-card-text',
            'commercial_register': 'bi-file-earmark-text',
            'vat': 'bi-receipt',
            'tax': 'bi-calculator',
            'cost': 'bi-currency-dollar',
            'payment': 'bi-credit-card',
            'delivery': 'bi-truck',
            'address': 'bi-house-door'
        };
        
        for (let pattern in iconMap) {
            if (key.toLowerCase().includes(pattern)) {
                return iconMap[pattern];
            }
        }
        
        if (key.includes('image')) return 'bi-image-fill';
        if (key.includes('detail')) return 'bi-info-circle-fill';
        return 'bi-dot';
    }
    
    function getFieldLabel(key) {
        const labelMap = {
            'name': 'الاسم',
            'email': 'البريد الإلكتروني',
            'phone': 'رقم الهاتف',
            'governorate': 'المحافظة',
            'id': 'رقم التعريف',
            'user_type': 'نوع الحساب',
            'is_approved': 'حالة الموافقة',
            'created_at': 'تاريخ الإنشاء',
            'updated_at': 'تاريخ التحديث',
            'restaurant_detail': 'تفاصيل المطعم',
            'car_rental': 'تفاصيل تأجير السيارات',
            'real_estate': 'تفاصيل العقارات',
            'restaurant_name': 'اسم المطعم',
            'office_detail': 'تفاصيل المكتب',
            'driver_detail': 'تفاصيل السائق',
            'individual_detail': 'تفاصيل السمسار',
            'logo_image': 'شعار',
            'profile_image': 'صورة شخصية',
            'owner_id_front_image': 'بطاقة المالك - أمامية',
            'owner_id_back_image': 'بطاقة المالك - خلفية',
            'license_front_image': 'رخصة - أمامية',
            'license_back_image': 'رخصة - خلفية',
            'commercial_register_front_image': 'سجل تجاري - أمامية',
            'commercial_register_back_image': 'سجل تجاري - خلفية',
            'vat_included': 'خاضع للقيمة المضافة',
            'vat_image_front': 'شهادة ضريبية - أمامية',
            'vat_image_back': 'شهادة ضريبية - خلفية',
            'payment_methods': 'طرق الدفع',
            'rental_options': 'خيارات التأجير',
            'cost_per_km': 'التكلفة لكل كم',
            'daily_driver_cost': 'تكلفة السائق اليومية',
            'max_km_per_day': 'أقصى كم/يوم',
            'is_available_for_delivery': 'يدعم التوصيل',
            'is_available_for_rent': 'يدعم التأجير',
            'office_address': 'عنوان المكتب',
            'office_phone': 'هاتف المكتب',
            'office_image': 'صورة المكتب',
            'tax_enabled': 'خاضع للضريبة',
            'agent_id_front_image': 'بطاقة السمسار - أمامية',
            'agent_id_back_image': 'بطاقة السمسار - خلفية',
            'tax_card_front_image': 'بطاقة ضريبية - أمامية',
            'tax_card_back_image': 'بطاقة ضريبية - خلفية',
            'license_image': 'صورة رخصة القيادة'
        };
        return labelMap[key] || key.replace(/_/g, ' ');
    }
    
    function renderValue(value, key) {
        if (value === null || value === undefined || value === '') {
            return '<span class="text-muted fst-italic">غير محدد</span>';
        }
        
        // معالجة التواريخ - عرض اليوم والشهر والسنة فقط
        if (key === 'created_at' || key === 'updated_at' || key.includes('date') || key.includes('_at')) {
            try {
                const date = new Date(value);
                if (!isNaN(date.getTime())) {
                    return `<span class="text-primary">
                                <i class="bi bi-calendar3 me-1"></i>
                                ${date.toLocaleDateString('ar-EG', { 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric' 
                                })}
                            </span>`;
                }
            } catch (e) {
                // في حالة فشل تحويل التاريخ، عرض القيمة كما هي
            }
        }
        
        // معالجة القيم الرقمية (1/0) وتحويلها إلى true/false
        if (typeof value === 'number' && (value === 1 || value === 0)) {
            return value === 1 ? 
                '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>true</span>' : 
                '<span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>false</span>';
        }
        
        if (typeof value === 'boolean') {
            return value ? 
                '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>true</span>' : 
                '<span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>false</span>';
        }
        
        if (key === 'user_type') {
            return `<span class="badge bg-primary">
                        <i class="bi bi-tags"></i>
                        ${getTypeLabel(value)}
                    </span>`;
        }
        
        if (key === 'is_approved') {
            const approved = parseInt(value);
            return approved === 1 ? 
                '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>true</span>' : 
                '<span class="badge bg-warning"><i class="bi bi-clock me-1"></i>false</span>';
        }
        
        // معالجة القيم المنطقية للحقول المتخصصة
        if (key === 'vat_included' || key === 'is_available_for_delivery' || key === 'is_available_for_rent' || key === 'tax_enabled') {
            const boolValue = parseInt(value) === 1;
            return boolValue ? 
                '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>true</span>' : 
                '<span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>false</span>';
        }
        
        // معالجة المصفوفات (cuisine_types, working_hours, payment_methods, rental_options)
        if (Array.isArray(value)) {
            if (key === 'cuisine_types') {
                return value.map(type => 
                    `<span class="badge bg-info me-1 mb-1">
                        <i class="bi bi-tag-fill me-1"></i>${type}
                    </span>`
                ).join('');
            }
            
            if (key === 'payment_methods') {
                const methodLabels = {
                    'cash': 'نقدي',
                    'card': 'بطاقة ائتمان',
                    'online': 'دفع إلكتروني',
                    'bank_transfer': 'تحويل بنكي'
                };
                return value.map(method => 
                    `<span class="badge bg-success me-1 mb-1">
                        <i class="bi bi-credit-card me-1"></i>${methodLabels[method] || method}
                    </span>`
                ).join('');
            }
            
            if (key === 'rental_options') {
                const optionLabels = {
                    'daily': 'يومي',
                    'weekly': 'أسبوعي',
                    'monthly': 'شهري',
                    'hourly': 'بالساعة'
                };
                return value.map(option => 
                    `<span class="badge bg-warning me-1 mb-1">
                        <i class="bi bi-calendar-check me-1"></i>${optionLabels[option] || option}
                    </span>`
                ).join('');
            }
            
            // معالجة عامة للمصفوفات الأخرى
            return value.map(item => 
                `<span class="badge bg-secondary me-1 mb-1">${item}</span>`
            ).join('');
        }
        
        // معالجة working_hours كـ object
        if (key === 'working_hours' && typeof value === 'object' && value !== null) {
            const dayLabels = {
                'mon': 'الاثنين',
                'tue': 'الثلاثاء', 
                'wed': 'الأربعاء',
                'thu': 'الخميس',
                'fri': 'الجمعة',
                'sat': 'السبت',
                'sun': 'الأحد'
            };
            
            let html = '<div class="working-hours-container">';
            for (let day in value) {
                if (value[day] && Array.isArray(value[day]) && value[day].length >= 2) {
                    html += `<div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                <span class="fw-bold text-primary">${dayLabels[day] || day}:</span>
                                <span class="badge bg-success">
                                    <i class="bi bi-clock me-1"></i>
                                    ${value[day][0]} - ${value[day][1]}
                                </span>
                            </div>`;
                }
            }
            html += '</div>';
            return html;
        }
        
        // معالجة القيم النقدية - إزالة رمز الدولار واستخدام الجنيه فقط
        if (key.includes('cost') || key.includes('price')) {
            return `<span class="fw-bold text-success">
                        ${value} جنيه
                    </span>`;
        }
        
        const isImage = typeof value === 'string' && value && /\.(jpg|jpeg|png|gif|webp)(\?.*)?$/i.test(value);
        if (isImage) {
            const src = value.startsWith('http') ? value : value;
            return `<div class="text-center">
                        <img src="${src}" class="img-thumbnail" style="max-width: 80px; max-height: 80px; cursor: pointer;" 
                             onclick="openImgFull('${src}')" alt="صورة">
                        <div class="small text-muted mt-1">اضغط للتكبير</div>
                    </div>`;
        }
        
        if (typeof value === 'object') {
            return renderNestedObject(value);
        }
        
        return `<span class="text-dark">${value}</span>`;
    }
    
    function renderNestedObject(obj) {
        let html = '<div class="details-nested">';
        for (let key in obj) {
            if (obj.hasOwnProperty(key) && obj[key] !== null && obj[key] !== undefined && obj[key] !== '') {
                html += `<div class="details-item nested-item">
                            <div class="details-label">
                                <i class="bi ${getFieldIcon(key)}"></i>
                                <span>${getFieldLabel(key)}</span>
                            </div>
                            <div class="details-content">
                                ${renderValue(obj[key], key)}
                            </div>
                        </div>`;
            }
        }
        html += '</div>';
        return html;
    }
    
    // البناء الرئيسي للتفاصيل بشكل بسيط ومنظم
    let html = '<div class="container-fluid">';
    
    // قسم المعلومات الأساسية
    const basicFields = ['name', 'email', 'phone', 'governorate', 'user_type', 'is_approved'];
    html += '<div class="mb-4">';
    html += '<h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-circle me-2"></i>المعلومات الأساسية</h6>';
    html += '<div class="row g-3">';
    
    basicFields.forEach(key => {
        if (user.hasOwnProperty(key) && user[key] !== null && user[key] !== undefined && user[key] !== '') {
            html += `<div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <label class="form-label fw-bold mb-2">
                                <i class="bi ${getFieldIcon(key)} me-1 text-secondary"></i>
                                ${getFieldLabel(key)}
                            </label>
                            <div class="mt-1">
                                ${renderValue(user[key], key)}
                            </div>
                        </div>
                    </div>`;
        }
    });
    html += '</div></div>';
    
    // قسم التفاصيل المتخصصة
    if (user.restaurant_detail) {
        html += renderSpecializedSection('restaurant_detail', user.restaurant_detail, 'تفاصيل المطعم', 'bi-shop');
    }
    
    if (user.car_rental) {
        if (user.car_rental.office_detail) {
            html += renderSpecializedSection('office_detail', user.car_rental.office_detail, 'تفاصيل مكتب تأجير السيارات', 'bi-building');
        }
        if (user.car_rental.driver_detail) {
            html += renderSpecializedSection('driver_detail', user.car_rental.driver_detail, 'تفاصيل السائق', 'bi-person-badge');
        }
    }
    
    if (user.real_estate) {
        if (user.real_estate.office_detail) {
            html += renderSpecializedSection('office_detail', user.real_estate.office_detail, 'تفاصيل مكتب العقارات', 'bi-building');
        }
        if (user.real_estate.individual_detail) {
            html += renderSpecializedSection('individual_detail', user.real_estate.individual_detail, 'تفاصيل السمسار', 'bi-person-badge');
        }
    }
    
    // قسم معلومات النظام
    const systemFields = ['id', 'created_at', 'updated_at'];
    html += '<div class="mb-4">';
    html += '<h6 class="fw-bold text-secondary mb-3"><i class="bi bi-gear me-2"></i>معلومات النظام</h6>';
    html += '<div class="row g-3">';
    
    systemFields.forEach(key => {
        if (user.hasOwnProperty(key) && user[key] !== null && user[key] !== undefined && user[key] !== '') {
            html += `<div class="col-md-6">
                        <div class="border rounded p-3 h-100 bg-light">
                            <label class="form-label fw-bold mb-2">
                                <i class="bi ${getFieldIcon(key)} me-1 text-secondary"></i>
                                ${getFieldLabel(key)}
                            </label>
                            <div class="mt-1">
                                ${renderValue(user[key], key)}
                            </div>
                        </div>
                    </div>`;
        }
    });
    html += '</div></div>';
    
    html += '</div>';
    
    function renderSpecializedSection(sectionKey, sectionData, title, icon) {
        let sectionHtml = `<div class="mb-4">
                            <h6 class="fw-bold text-info mb-3"><i class="bi ${icon} me-2"></i>${title}</h6>`;
        
        // تجميع البيانات النصية والصور
        const textData = [];
        const images = [];
        
        for (let key in sectionData) {
            if (sectionData.hasOwnProperty(key) && sectionData[key] !== null && 
                sectionData[key] !== undefined && sectionData[key] !== '') {
                if (key.includes('image')) {
                    images.push({key, value: sectionData[key]});
                } else {
                    textData.push({key, value: sectionData[key]});
                }
            }
        }
        
        // عرض البيانات النصية
        if (textData.length > 0) {
            sectionHtml += '<div class="row g-3 mb-3">';
            textData.forEach(item => {
                sectionHtml += `<div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <label class="form-label fw-bold mb-2">
                                        <i class="bi ${getFieldIcon(item.key)} me-1 text-secondary"></i>
                                        ${getFieldLabel(item.key)}
                                    </label>
                                    <div class="mt-1">
                                        ${renderValue(item.value, item.key)}
                                    </div>
                                </div>
                            </div>`;
            });
            sectionHtml += '</div>';
        }
        
        // عرض الصور إذا وجدت
        if (images.length > 0) {
            sectionHtml += '<div class="mt-3">';
            sectionHtml += '<h6 class="fw-bold text-muted mb-3"><i class="bi bi-images me-2"></i>الصور والمرفقات</h6>';
            sectionHtml += '<div class="row g-3">';
            images.forEach(item => {
                sectionHtml += `<div class="col-md-4 col-lg-3">
                                <div class="border rounded p-3 text-center">
                                    <label class="form-label fw-bold mb-2 d-block">${getFieldLabel(item.key)}</label>
                                    <div class="mt-1">
                                        ${renderValue(item.value, item.key)}
                                    </div>
                                </div>
                            </div>`;
            });
            sectionHtml += '</div></div>';
        }
        
        sectionHtml += '</div>';
        return sectionHtml;
    }
    
    return html;
}

function openImgFull(src) {
    openImageModal(src, 'صورة المستخدم');
}

function getTypeLabel(type) {
    const labels = {
        'restaurant': 'مطعم', 'car_rental_office': 'مكتب تأجير سيارات',
        'real_estate_office': 'مكتب عقارات', 'real_estate_individual': 'سمسار',
        'driver': 'سائق', 'normal': 'مستخدم عادي'
    };
    return labels[type] || type;
}

function showDeleteModal(id) {
    const user = users.find(u => u.id === id);
    userToDelete = id;
    document.getElementById('deleteUserName').innerText = user ? user.name : '';
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

document.getElementById('confirmDeleteBtn').onclick = async function() {
    if (!userToDelete) return;
    const token = localStorage.getItem('token');
    if (!token) { alert("يرجى تسجيل الدخول أولًا."); window.location.href = '/login'; return; }
    const res = await fetch(`/api/users/${userToDelete}`, {
        method: 'DELETE',
        headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
    });
    if (res.ok) {
        console.log(`User ${userToDelete} deleted successfully`);
        userToDelete = null;
        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
        await fetchUsers();
    } else { alert('فشل الحذف! (status: ' + res.status + ')'); }
}

// ================ قسم تعديل المستخدم (بدون تغيير) ===================
function showEditModal(id) {
    const user = users.find(u => u.id === id);
    if (!user) return;
    const type = user.user_type;
    const fields = userTypeFields[type] || [];
    let html = '';
    fields.forEach(field => { html += buildFormField(field, user); });
    document.getElementById('editFormContent').innerHTML = html;
    document.getElementById('editUserForm').onsubmit = e => handleEditSubmit(e, user.id, type, fields, user);
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
function buildFormField(field, user) {
    let val = getNestedValue(user, field.name) || '';
    if (field.type === 'text' || field.type === 'email') {
        return `<div class="mb-3"><label class="form-label">${field.label}</label><input name="${field.name}" type="${field.type}" class="form-control" value="${val}"></div>`;
    }
    if (field.type === 'select') {
        let opts = Object.entries(field.options).map(([v, l]) => `<option value="${v}"${val==v?' selected':''}>${l}</option>`).join('');
        return `<div class="mb-3"><label class="form-label">${field.label}</label><select class="form-select" name="${field.name}">${opts}</select></div>`;
    }
    if (field.type === 'image') {
        return `<div class="mb-3"><label class="form-label">${field.label}</label><br><img src="${val ? val : ''}" style="max-width:80px;max-height:60px;${val?'':'display:none;'}" id="img_preview_${field.name}" class="rounded border mb-2"><input type="file" class="form-control" onchange="uploadImage(event, '${field.name}')"><input type="hidden" name="${field.name}" value="${val}"></div>`;
    }
    return '';
}
function getNestedValue(obj, path) {
    return path.split('.').reduce((o, i) => o && o[i], obj);
}
async function uploadImage(event, fieldName) {
    const file = event.target.files[0];
    if (!file) return;
    const form = event.target.closest('form');
    const token = localStorage.getItem('token');
    if (!token) { alert("يرجى تسجيل الدخول أولًا."); window.location.href = '/login'; return; }
    let formData = new FormData();
    formData.append('files[]', file);
    const res = await fetch('/api/upload', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' },
        body: formData
    });
    const data = await res.json();
    if (data.status && data.files && data.files[0]) {
        const hidden = form ? form.querySelector(`[name="${fieldName}"]`) : document.querySelector(`[name="${fieldName}"]`);
        if (hidden) hidden.value = data.files[0];
        const imgEl = form ? form.querySelector(`#img_preview_${fieldName}`) : document.getElementById(`img_preview_${fieldName}`);
        if (imgEl) {
          imgEl.src = data.files[0];
          imgEl.style.display = 'inline-block';
        }
        const wrapper = form ? form.querySelector(`#preview_wrapper_${fieldName}`) : document.getElementById(`preview_wrapper_${fieldName}`);
        if (wrapper) wrapper.style.display = 'inline-block';
        // إعادة تقييم التحقق للنماذج ذات الصلة
        if (typeof validateRealEstateOfficeForm === 'function') validateRealEstateOfficeForm();
        if (typeof validateRealEstateIndividualForm === 'function') validateRealEstateIndividualForm();
        if (typeof validateDriverForm === 'function') validateDriverForm();
        if (typeof validateCarRentalOfficeForm === 'function') validateCarRentalOfficeForm();
        if (typeof validateRestaurantForm === 'function') validateRestaurantForm();
  } else { alert('فشل رفع الصورة'); }
}

function removeImage(fieldName) {
  try {
    const visibleForm = document.querySelector('#addUserFormStep form:not(.d-none)');
    const form = visibleForm || document.getElementById('addCarRentalOfficeForm');
    const hidden = form ? form.querySelector(`[name="${fieldName}"]`) : document.querySelector(`[name="${fieldName}"]`);
    if (hidden) hidden.value = '';
    const imgEl = form ? form.querySelector(`#img_preview_${fieldName}`) : document.getElementById(`img_preview_${fieldName}`);
    if (imgEl) { imgEl.src = ''; imgEl.style.display = 'none'; }
    const wrapper = form ? form.querySelector(`#preview_wrapper_${fieldName}`) : document.getElementById(`preview_wrapper_${fieldName}`);
    if (wrapper) wrapper.style.display = 'none';
    const fileInput = form ? form.querySelector(`input[type="file"][data-field="${fieldName}"]`) : document.querySelector(`input[type="file"][data-field="${fieldName}"]`);
    if (fileInput) fileInput.value = '';
    // إعادة تقييم زر الإرسال في نموذج مكتب العقارات إن وجد
    if (typeof validateRealEstateOfficeForm === 'function') validateRealEstateOfficeForm();
    // إعادة تقييم زر الإرسال في نموذج السمسار إن وجد
    if (typeof validateRealEstateIndividualForm === 'function') validateRealEstateIndividualForm();
    // إعادة تقييم زر الإرسال في نموذج السائق إن وجد
    if (typeof validateDriverForm === 'function') validateDriverForm();
    if (typeof validateCarRentalOfficeForm === 'function') validateCarRentalOfficeForm();
    if (typeof validateRestaurantForm === 'function') validateRestaurantForm();
  } catch (e) { console.warn('removeImage error for', fieldName, e); }
}
async function handleEditSubmit(e, id) {
    e.preventDefault();
    const token = localStorage.getItem('token');
    if (!token) { alert("يرجى تسجيل الدخول أولًا."); window.location.href = '/login'; return; }
    const form = e.target;
    const formData = new FormData(form);
    let data = {};
    for (let [key, val] of formData.entries()) {
        if (key.includes('.')) {
            const parts = key.split('.');
            if (!data[parts[0]]) data[parts[0]] = {};
            data[parts[0]][parts[1]] = val;
        } else {
            data[key] = val;
        }
    }
    const res = await fetch(`/api/users/${id}`, {
        method: 'PUT',
        headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(data)
    });
    if (res.ok) {
        await fetchUsers();
        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    } else {
        const errorData = await res.json();
        alert('فشل التعديل: ' + (errorData.message || res.statusText));
    }
}

// بدء التشغيل
document.addEventListener('DOMContentLoaded', () => {
    fetchUsers();
    startPolling();
    setupSearch();
    console.log("Accounts management app initialized.");
});

// إعداد البحث
function setupSearch() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            filterUsers(searchTerm);
        });
    }
}

// فلترة المستخدمين حسب النص
function filterUsers(searchTerm) {
    const filtered = users.filter(u => u.user_type === currentType && u.user_type !== 'admin');
    let searchFiltered = filtered;
    
    if (searchTerm) {
        searchFiltered = filtered.filter(user => 
            user.name?.toLowerCase().includes(searchTerm) ||
            user.email?.toLowerCase().includes(searchTerm) ||
            user.phone?.toLowerCase().includes(searchTerm)
        );
    }
    
    renderFilteredTable(searchFiltered);
}

// عرض الجدول المفلتر
function renderFilteredTable(filteredUsers) {
    let rows = filteredUsers.map(u => `
        <tr>
            <td class="text-center">
                <span class="badge bg-primary">${u.id}</span>
            </td>
            <td>
                <div class="user-info">
                    <div class="user-name">
                        <i class="bi bi-person-fill me-2 text-primary"></i>${u.name}
                    </div>
                    <div class="user-email">
                        <i class="bi bi-envelope me-2"></i>${u.email}
                    </div>
                </div>
            </td>
            <td>
                <div class="user-phone">
                    <i class="bi bi-telephone-fill me-2 text-success"></i>
                    ${u.phone || '<span class="text-muted">غير محدد</span>'}
                </div>
            </td>
            <td>
                <span class="user-type-badge">
                    <i class="bi bi-tag-fill"></i>
                    ${getTypeLabel(u.user_type)}
                </span>
            </td>
            <td>
                <div class="user-location">
                    <i class="bi bi-geo-alt-fill me-2 text-warning"></i>
                    ${u.governorate || '<span class="text-muted">غير محدد</span>'}
                </div>
            </td>
            <td class="text-center">
                <div class="btn-group" role="group">
                    ${renderApproveBtn(u)}
                    <button class="btn btn-sm btn-outline-secondary btn-icon" title="عرض التفاصيل" onclick="showDetails(${u.id})">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-primary btn-icon" title="تعديل" onclick="showEditModal(${u.id})">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-icon" title="حذف" onclick="showDeleteModal(${u.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    if(!rows) {
        rows = `<tr><td colspan="6" class="text-center p-4">لا توجد نتائج تطابق البحث.</td></tr>`;
        document.getElementById('emptyState').classList.remove('d-none');
    } else {
        document.getElementById('emptyState').classList.add('d-none');
    }

    document.getElementById('accountsTable').innerHTML = `
        <div class="table-responsive">
            <div class="d-flex justify-content-between align-items-center mb-3 px-3 pt-3">
                <h5 class="mb-0">قائمة الحسابات</h5>
                <div class=\"d-flex gap-2\">
    <button class=\"btn btn-primary btn-sm\" onclick=\"openAddUserModal()\" title=\"إضافة حساب جديد\">
        <i class=\"bi bi-plus-circle me-1\"></i>اضاف
    </button>
    <button class=\"btn btn-outline-primary btn-sm\" onclick=\"fetchUsers(false)\" title=\"تحديث البيانات\">
        <i class=\"bi bi-arrow-clockwise me-1\"></i>تحديث
    </button>
</div>
            </div>
            <table class="table table-hover align-middle modern-accounts-table">
                <thead class="table-light">
                    <tr>
                        <th class="text-center id-column"><i class="bi bi-hash me-2"></i>المعرف</th>
                        <th class="name-column"><i class="bi bi-person-circle me-2"></i>الاسم والبريد</th>
                        <th class="phone-column"><i class="bi bi-telephone me-2"></i>الهاتف</th>
                        <th class="type-column"><i class="bi bi-tag me-2"></i>النوع</th>
                        <th class="location-column"><i class="bi bi-geo-alt me-2"></i>المحافظة</th>
                        <th class="text-center actions-column"><i class="bi bi-gear me-2"></i>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    ${rows}
                </tbody>
            </table>
        </div>
    `;
}

// تحديث الإحصائيات
function updateStatistics() {
    const total = users.length;
    const active = users.filter(user => user.is_approved == 1).length;
    const inactive = users.filter(user => user.is_approved == 0).length;
    const today = new Date().toDateString();
    const newToday = users.filter(user => 
        new Date(user.created_at).toDateString() === today
    ).length;

    // تحديث العدادات مع تأثير العد
    animateCounter('totalAccounts', total);
    animateCounter('activeAccounts', active);
    animateCounter('inactiveAccounts', inactive);
    animateCounter('newAccounts', newToday);
}

// تأثير العد المتحرك
function animateCounter(elementId, targetValue) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const startValue = 0;
    const duration = 1000;
    const startTime = performance.now();
    
    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const currentValue = Math.floor(startValue + (targetValue - startValue) * progress);
        
        element.textContent = currentValue;
        
        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        }
    }
    
    requestAnimationFrame(updateCounter);
}

// دالة لفتح الصور بحجم أكبر
function openImageModal(imageSrc, imageAlt = '') {
    // إنشاء نافذة منبثقة لعرض الصورة
    const modalHtml = `
        <div class="modal fade img-modal" id="imageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 p-2">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <div class="modal-body">
                        <img src="${imageSrc}" alt="${imageAlt}" class="img-fluid" />
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // إزالة النافذة السابقة إن وجدت
    const existingModal = document.getElementById('imageModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // إضافة النافذة الجديدة
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // عرض النافذة
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
    
    // إزالة النافذة عند الإغلاق
    document.getElementById('imageModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}


// ===== إضافة حساب جديد: دوال النافذة =====
let selectedNewUserType = null;
function openAddUserModal() {
  selectedNewUserType = null;
  const modalEl = document.getElementById('addUserModal');
  const proceedBtn = document.getElementById('addUserProceedBtn');
  const backBtn = document.getElementById('addUserBackBtn');
  const submitBtn = document.getElementById('addUserSubmitBtn');
  const statusEl = document.getElementById('addTypeStatus');
  if (proceedBtn) { proceedBtn.disabled = true; proceedBtn.classList.remove('d-none'); }
  if (backBtn) backBtn.classList.add('d-none');
  if (submitBtn) { submitBtn.classList.add('d-none'); submitBtn.disabled = true; }
  if (statusEl) statusEl.textContent = 'لم يتم اختيار نوع بعد';
  if (modalEl) {
    const selectionStep = modalEl.querySelector('#addUserSelectionStep');
    const formStep = modalEl.querySelector('#addUserFormStep');
    if (selectionStep) selectionStep.classList.remove('d-none');
    if (formStep) formStep.classList.add('d-none');
    modalEl.querySelectorAll('.type-card').forEach(c => c.classList.remove('active'));
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
  }
}
function selectNewAccountType(type) {
  selectedNewUserType = type;
  const modalEl = document.getElementById('addUserModal');
  if (modalEl) {
    modalEl.querySelectorAll('.type-card').forEach(c => {
      c.classList.toggle('active', c.dataset.type === type);
    });
  }
  const proceedBtn = document.getElementById('addUserProceedBtn');
  const statusEl = document.getElementById('addTypeStatus');
  if (proceedBtn) proceedBtn.disabled = false;
  if (statusEl) statusEl.textContent = 'تم اختيار النوع: ' + (typeof getTypeLabel === 'function' ? getTypeLabel(type) : type);
}
function proceedCreateUser() {
  if (!selectedNewUserType) return;
  const modalEl = document.getElementById('addUserModal');
  if (!modalEl) return;
  const selectionStep = modalEl.querySelector('#addUserSelectionStep');
  const formStep = modalEl.querySelector('#addUserFormStep');
  const proceedBtn = document.getElementById('addUserProceedBtn');
  const backBtn = document.getElementById('addUserBackBtn');
  const submitBtn = document.getElementById('addUserSubmitBtn');
  const statusEl = document.getElementById('addTypeStatus');
  const normalForm = document.getElementById('addNormalUserForm');
  const reoForm = document.getElementById('addRealEstateOfficeForm');
  const reiForm = document.getElementById('addRealEstateIndividualForm');
  const driverForm = document.getElementById('addDriverForm');
  const carForm = document.getElementById('addCarRentalOfficeForm');
  const restaurantForm = document.getElementById('addRestaurantForm');
  if (selectedNewUserType === 'normal') {
    if (selectionStep) selectionStep.classList.add('d-none');
    if (formStep) formStep.classList.remove('d-none');
    if (proceedBtn) proceedBtn.classList.add('d-none');
    if (backBtn) backBtn.classList.remove('d-none');
    if (submitBtn) { submitBtn.classList.remove('d-none'); }
    if (statusEl) statusEl.textContent = 'نموذج: إضافة مستخدم عادي';
    if (reoForm) reoForm.classList.add('d-none');
    if (normalForm) normalForm.classList.remove('d-none');
    if (submitBtn) { submitBtn.removeAttribute('onclick'); submitBtn.onclick = submitNormalUserForm; }
    attachNormalUserFormListeners();
    validateNormalUserForm();
  } else {
    if (selectedNewUserType === 'real_estate_office') {
      if (selectionStep) selectionStep.classList.add('d-none');
      if (formStep) formStep.classList.remove('d-none');
      if (proceedBtn) proceedBtn.classList.add('d-none');
      if (backBtn) backBtn.classList.remove('d-none');
      if (submitBtn) { submitBtn.classList.remove('d-none'); }
      if (statusEl) statusEl.textContent = 'نموذج: إضافة مكتب عقارات';
      if (normalForm) normalForm.classList.add('d-none');
      if (reoForm) reoForm.classList.remove('d-none');
      if (submitBtn) { submitBtn.removeAttribute('onclick'); submitBtn.onclick = submitRealEstateOfficeForm; }
      attachRealEstateOfficeFormListeners();
      validateRealEstateOfficeForm();
    } else if (selectedNewUserType === 'real_estate_individual') {
      if (selectionStep) selectionStep.classList.add('d-none');
      if (formStep) formStep.classList.remove('d-none');
      if (proceedBtn) proceedBtn.classList.add('d-none');
      if (backBtn) backBtn.classList.remove('d-none');
      if (submitBtn) { submitBtn.classList.remove('d-none'); }
      if (statusEl) statusEl.textContent = 'نموذج: إضافة سمسار';
      if (normalForm) normalForm.classList.add('d-none');
      if (reoForm) reoForm.classList.add('d-none');
      if (reiForm) reiForm.classList.remove('d-none');
      if (submitBtn) { submitBtn.removeAttribute('onclick'); submitBtn.onclick = submitRealEstateIndividualForm; }
      attachRealEstateIndividualFormListeners();
      validateRealEstateIndividualForm();
    } else if (selectedNewUserType === 'driver') {
      if (selectionStep) selectionStep.classList.add('d-none');
      if (formStep) formStep.classList.remove('d-none');
      if (proceedBtn) proceedBtn.classList.add('d-none');
      if (backBtn) backBtn.classList.remove('d-none');
      if (submitBtn) { submitBtn.classList.remove('d-none'); }
      if (statusEl) statusEl.textContent = 'نموذج: إضافة سائق';
      if (normalForm) normalForm.classList.add('d-none');
      if (reoForm) reoForm.classList.add('d-none');
      if (reiForm) reiForm.classList.add('d-none');
      if (driverForm) driverForm.classList.remove('d-none');
      if (submitBtn) { submitBtn.removeAttribute('onclick'); submitBtn.onclick = submitDriverForm; }
      attachDriverFormListeners();
      validateDriverForm();
    } else if (selectedNewUserType === 'car_rental_office') {
      if (selectionStep) selectionStep.classList.add('d-none');
      if (formStep) formStep.classList.remove('d-none');
      if (proceedBtn) proceedBtn.classList.add('d-none');
      if (backBtn) backBtn.classList.remove('d-none');
      if (submitBtn) { submitBtn.classList.remove('d-none'); }
      if (statusEl) statusEl.textContent = 'نموذج: إضافة مكتب تأجير سيارات';
      if (normalForm) normalForm.classList.add('d-none');
      if (reoForm) reoForm.classList.add('d-none');
      if (reiForm) reiForm.classList.add('d-none');
      if (driverForm) driverForm.classList.add('d-none');
      if (carForm) carForm.classList.remove('d-none');
      if (submitBtn) { submitBtn.removeAttribute('onclick'); submitBtn.onclick = submitCarRentalOfficeForm; }
      attachCarRentalOfficeFormListeners();
      validateCarRentalOfficeForm();
    } else if (selectedNewUserType === 'restaurant') {
      if (selectionStep) selectionStep.classList.add('d-none');
      if (formStep) formStep.classList.remove('d-none');
      if (proceedBtn) proceedBtn.classList.add('d-none');
      if (backBtn) backBtn.classList.remove('d-none');
      if (submitBtn) { submitBtn.classList.remove('d-none'); }
      if (statusEl) statusEl.textContent = 'نموذج: إضافة مطعم';
      if (normalForm) normalForm.classList.add('d-none');
      if (reoForm) reoForm.classList.add('d-none');
      if (reiForm) reiForm.classList.add('d-none');
      if (driverForm) driverForm.classList.add('d-none');
      if (carForm) carForm.classList.add('d-none');
      if (restaurantForm) restaurantForm.classList.remove('d-none');
      if (submitBtn) { submitBtn.removeAttribute('onclick'); submitBtn.onclick = submitRestaurantForm; }
      attachRestaurantFormListeners();
      validateRestaurantForm();
    } else {
      try {
        if (typeof showToast === 'function') {
          showToast('هذا النوع غير مدعوم بعد. قريبًا.', 'warning');
        } else {
          alert('هذا النوع غير مدعوم بعد. قريبًا.');
        }
      } catch (e) { console.log('Selected type:', selectedNewUserType); }
    }
  }
}

function backToTypeSelection() {
  const modalEl = document.getElementById('addUserModal');
  if (!modalEl) return;
  const selectionStep = modalEl.querySelector('#addUserSelectionStep');
  const formStep = modalEl.querySelector('#addUserFormStep');
  const proceedBtn = document.getElementById('addUserProceedBtn');
  const backBtn = document.getElementById('addUserBackBtn');
  const submitBtn = document.getElementById('addUserSubmitBtn');
  const statusEl = document.getElementById('addTypeStatus');
  const normalForm = document.getElementById('addNormalUserForm');
  const reoForm = document.getElementById('addRealEstateOfficeForm');
  const reiForm = document.getElementById('addRealEstateIndividualForm');
  const driverForm = document.getElementById('addDriverForm');
  const carForm = document.getElementById('addCarRentalOfficeForm');
  const restaurantForm = document.getElementById('addRestaurantForm');
  if (formStep) formStep.classList.add('d-none');
  if (selectionStep) selectionStep.classList.remove('d-none');
  if (proceedBtn) { proceedBtn.classList.remove('d-none'); proceedBtn.disabled = !selectedNewUserType; }
  if (backBtn) backBtn.classList.add('d-none');
  if (submitBtn) { submitBtn.classList.add('d-none'); submitBtn.disabled = true; }
  if (statusEl) statusEl.textContent = selectedNewUserType ? ('تم اختيار النوع: ' + (typeof getTypeLabel === 'function' ? getTypeLabel(selectedNewUserType) : selectedNewUserType)) : 'لم يتم اختيار نوع بعد';
  if (normalForm) normalForm.classList.add('d-none');
  if (reoForm) reoForm.classList.add('d-none');
  if (reiForm) reiForm.classList.add('d-none');
  if (driverForm) driverForm.classList.add('d-none');
  if (carForm) carForm.classList.add('d-none');
  if (restaurantForm) restaurantForm.classList.add('d-none');
}

// ===== منطق التحقق وإرسال نموذج المستخدم العادي =====
function attachNormalUserFormListeners() {
  const form = document.getElementById('addNormalUserForm');
  if (!form) return;
  const inputs = form.querySelectorAll('input, select');
  inputs.forEach(el => {
    el.addEventListener('input', validateNormalUserForm);
    el.addEventListener('change', validateNormalUserForm);
  });
}

function validateNormalUserForm() {
  const form = document.getElementById('addNormalUserForm');
  const submitBtn = document.getElementById('addUserSubmitBtn');
  if (!form || !submitBtn) return;
  const name = form.querySelector('[name="name"]')?.value?.trim();
  const email = form.querySelector('[name="email"]')?.value?.trim();
  const password = form.querySelector('[name="password"]')?.value || '';
  const phone = form.querySelector('[name="phone"]')?.value?.trim();
  const governorate = form.querySelector('[name="governorate"]')?.value?.trim();
  const valid = !!(name && email && email.includes('@') && password.length >= 6 && phone && governorate);
  submitBtn.disabled = !valid;
}

async function submitNormalUserForm() {
  const form = document.getElementById('addNormalUserForm');
  if (!form) return;
  const token = localStorage.getItem('token');
  if (!token) { alert('يرجى تسجيل الدخول أولًا.'); window.location.href = '/login'; return; }

  const payload = {
    name: form.querySelector('[name="name"]').value.trim(),
    email: form.querySelector('[name="email"]').value.trim(),
    password: form.querySelector('[name="password"]').value,
    phone: form.querySelector('[name="phone"]').value.trim(),
    governorate: form.querySelector('[name="governorate"]').value.trim(),
    user_type: 'normal'
  };

  try {
    const res = await fetch('/api/users', {
      method: 'POST',
      headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(payload)
    });
    if (res.ok) {
      if (typeof showToast === 'function') { showToast('تم إضافة المستخدم بنجاح', 'success'); }
      else { alert('تم إضافة المستخدم بنجاح'); }
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
    console.error('Add user error:', err);
    alert('حدث خطأ غير متوقع أثناء الإضافة');
  }
}

// ===== منطق التحقق وإرسال نموذج مكتب العقارات =====
function attachRealEstateOfficeFormListeners() {
  const form = document.getElementById('addRealEstateOfficeForm');
  if (!form) return;
  const inputs = form.querySelectorAll('input, select, textarea');
  inputs.forEach(el => {
    el.addEventListener('input', validateRealEstateOfficeForm);
    el.addEventListener('change', validateRealEstateOfficeForm);
  });

  // فتح نافذة خرائط جوجل لاختيار عنوان المكتب
  const openBtn = form.querySelector('#openOfficeMapBtn');
  const addrInput = form.querySelector('#officeAddressInput');
  if (openBtn) openBtn.addEventListener('click', openOfficeMapPicker);
  if (addrInput) addrInput.addEventListener('click', openOfficeMapPicker);

  // ربط أحداث إغلاق نافذة الخريطة مرة واحدة فقط
  if (!window._reoMapEventsBound) {
    window._reoMapEventsBound = true;
    const closeBtn = document.getElementById('closeOfficeMap');
    const overlay = document.getElementById('officeMapPicker');
    if (closeBtn) closeBtn.addEventListener('click', closeOfficeMapPicker);
    if (overlay) overlay.addEventListener('click', (e) => {
      if (e.target && e.target.id === 'officeMapPicker') closeOfficeMapPicker();
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeOfficeMapPicker(); });
  }
}

function validateRealEstateOfficeForm() {
  const form = document.getElementById('addRealEstateOfficeForm');
  const submitBtn = document.getElementById('addUserSubmitBtn');
  if (!form || !submitBtn) return;
  const name = form.querySelector('[name="name"]')?.value?.trim();
  const email = form.querySelector('[name="email"]')?.value?.trim();
  const password = form.querySelector('[name="password"]')?.value || '';
  const phone = form.querySelector('[name="phone"]')?.value?.trim();
  const governorate = form.querySelector('[name="governorate"]')?.value?.trim();

  const office_name = form.querySelector('[name="office_name"]')?.value?.trim();
  const office_address = form.querySelector('[name="office_address"]')?.value?.trim();
  const office_phone = form.querySelector('[name="office_phone"]')?.value?.trim();

  const logo_image = form.querySelector('[name="logo_image"]')?.value?.trim();
  const owner_id_front_image = form.querySelector('[name="owner_id_front_image"]')?.value?.trim();
  const owner_id_back_image = form.querySelector('[name="owner_id_back_image"]')?.value?.trim();
  const office_image = form.querySelector('[name="office_image"]')?.value?.trim();
  const commercial_register_front_image = form.querySelector('[name="commercial_register_front_image"]')?.value?.trim();
  const commercial_register_back_image = form.querySelector('[name="commercial_register_back_image"]')?.value?.trim();

  const baseValid = !!(name && email && email.includes('@') && password.length >= 6 && phone && governorate);
  const officeValid = !!(office_name && office_address && office_phone);
  const imagesValid = !!(logo_image && owner_id_front_image && owner_id_back_image && office_image && commercial_register_front_image && commercial_register_back_image);
  submitBtn.disabled = !(baseValid && officeValid && imagesValid);
}

async function submitRealEstateOfficeForm() {
  const form = document.getElementById('addRealEstateOfficeForm');
  if (!form) return;
  const token = localStorage.getItem('token');
  if (!token) { alert('يرجى تسجيل الدخول أولًا.'); window.location.href = '/login'; return; }

  const payload = {
    name: form.querySelector('[name="name"]').value.trim(),
    email: form.querySelector('[name="email"]').value.trim(),
    password: form.querySelector('[name="password"]').value,
    phone: form.querySelector('[name="phone"]').value.trim(),
    governorate: form.querySelector('[name="governorate"]').value.trim(),
    user_type: 'real_estate_office',

    office_name: form.querySelector('[name="office_name"]').value.trim(),
    office_address: form.querySelector('[name="office_address"]').value.trim(),
    office_phone: form.querySelector('[name="office_phone"]').value.trim(),

    logo_image: form.querySelector('[name="logo_image"]').value.trim(),
    owner_id_front_image: form.querySelector('[name="owner_id_front_image"]').value.trim(),
    owner_id_back_image: form.querySelector('[name="owner_id_back_image"]').value.trim(),
    office_image: form.querySelector('[name="office_image"]').value.trim(),
    commercial_register_front_image: form.querySelector('[name="commercial_register_front_image"]').value.trim(),
    commercial_register_back_image: form.querySelector('[name="commercial_register_back_image"]').value.trim(),

    tax_enabled: !!form.querySelector('[name="tax_enabled"]')?.checked,
  };

  try {
    const res = await fetch('/api/users', {
      method: 'POST',
      headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(payload)
    });
    if (res.ok) {
      if (typeof showToast === 'function') { showToast('تم إضافة مكتب العقارات بنجاح', 'success'); }
      else { alert('تم إضافة مكتب العقارات بنجاح'); }
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
    console.error('Add real estate office error:', err);
    alert('حدث خطأ غير متوقع أثناء الإضافة');
  }
}

// ===== منطق التحقق وإرسال نموذج السمسار =====
function attachRealEstateIndividualFormListeners() {
  const form = document.getElementById('addRealEstateIndividualForm');
  if (!form) return;
  const inputs = form.querySelectorAll('input, select, textarea');
  inputs.forEach(el => {
    el.addEventListener('input', validateRealEstateIndividualForm);
    el.addEventListener('change', validateRealEstateIndividualForm);
  });

  // فتح نافذة خرائط جوجل لاختيار عنوان السمسار (اختياري)
  const openBtn = form.querySelector('#openIndividualMapBtn');
  const addrInput = form.querySelector('#individualAddressInput');
  if (openBtn) openBtn.addEventListener('click', openIndividualMapPicker);
  if (addrInput) addrInput.addEventListener('click', openIndividualMapPicker);

  // ربط أحداث إغلاق نافذة الخريطة مرة واحدة فقط
  if (!window._reiMapEventsBound) {
    window._reiMapEventsBound = true;
    const closeBtn = document.getElementById('closeIndividualMap');
    const overlay = document.getElementById('individualMapPicker');
    if (closeBtn) closeBtn.addEventListener('click', closeIndividualMapPicker);
    if (overlay) overlay.addEventListener('click', (e) => {
      if (e.target && e.target.id === 'individualMapPicker') closeIndividualMapPicker();
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeIndividualMapPicker(); });
  }
}

// ===== منطق التحقق وإرسال نموذج السائق =====
function attachDriverFormListeners() {
  const form = document.getElementById('addDriverForm');
  if (!form) return;
  const inputs = form.querySelectorAll('input, select');
  inputs.forEach(el => {
    el.addEventListener('input', validateDriverForm);
    el.addEventListener('change', validateDriverForm);
  });
}

function validateDriverForm() {
  const form = document.getElementById('addDriverForm');
  const submitBtn = document.getElementById('addUserSubmitBtn');
  if (!form || !submitBtn) return;
  const name = form.querySelector('[name="name"]')?.value?.trim();
  const email = form.querySelector('[name="email"]')?.value?.trim();
  const password = form.querySelector('[name="password"]')?.value || '';
  const phone = form.querySelector('[name="phone"]')?.value?.trim();
  const governorate = form.querySelector('[name="governorate"]')?.value?.trim();

  const baseValid = !!(name && email && email.includes('@') && password.length >= 6 && phone && governorate);
  // تبسيط التحقق: تفاصيل السائق اختيارية عند التسجيل الأولي حسب السيرفر
  submitBtn.disabled = !baseValid;
}

async function submitDriverForm() {
  const form = document.getElementById('addDriverForm');
  if (!form) return;
  const token = localStorage.getItem('token');
  if (!token) { alert('يرجى تسجيل الدخول أولًا.'); window.location.href = '/login'; return; }

  // جمع قيم طرق الدفع وخيارات التأجير من الشيك بوكس
  const payment_methods = [];
  const rental_options = [];
  form.querySelectorAll('#pm_cash, #pm_card, #pm_wallet').forEach(cb => { if (cb.checked) payment_methods.push(cb.value); });
  form.querySelectorAll('#ro_hourly, #ro_daily, #ro_monthly').forEach(cb => { if (cb.checked) rental_options.push(cb.value); });

  const payload = {
    name: form.querySelector('[name="name"]').value.trim(),
    email: form.querySelector('[name="email"]').value.trim(),
    password: form.querySelector('[name="password"]').value,
    phone: form.querySelector('[name="phone"]').value.trim(),
    governorate: form.querySelector('[name="governorate"]').value.trim(),
    user_type: 'driver',

    profile_image: form.querySelector('[name="profile_image"]').value.trim(),
    payment_methods,
    rental_options,
    cost_per_km: parseFloat(form.querySelector('[name="cost_per_km"]').value),
    daily_driver_cost: parseFloat(form.querySelector('[name="daily_driver_cost"]').value),
    max_km_per_day: parseInt(form.querySelector('[name="max_km_per_day"]').value, 10)
  };

  try {
    const res = await fetch('/api/users', {
      method: 'POST',
      headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(payload)
    });
    if (res.ok) {
      if (typeof showToast === 'function') { showToast('تم إضافة السائق بنجاح', 'success'); }
      else { alert('تم إضافة السائق بنجاح'); }
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
    console.error('Add driver error:', err);
    alert('حدث خطأ غير متوقع أثناء الإضافة');
  }
}

// ===== منطق التحقق وإرسال نموذج مكتب تأجير السيارات =====
function attachCarRentalOfficeFormListeners() {
  const form = document.getElementById('addCarRentalOfficeForm');
  if (!form) return;
  const inputs = form.querySelectorAll('input, select');
  inputs.forEach(el => {
    el.addEventListener('input', validateCarRentalOfficeForm);
    el.addEventListener('change', validateCarRentalOfficeForm);
  });
}

function validateCarRentalOfficeForm() {
  const form = document.getElementById('addCarRentalOfficeForm');
  const submitBtn = document.getElementById('addUserSubmitBtn');
  if (!form || !submitBtn) return;
  const name = form.querySelector('[name="name"]')?.value?.trim();
  const email = form.querySelector('[name="email"]')?.value?.trim();
  const password = form.querySelector('[name="password"]')?.value || '';
  const phone = form.querySelector('[name="phone"]')?.value?.trim();
  const governorate = form.querySelector('[name="governorate"]')?.value?.trim();

  const office_name = form.querySelector('[name="office_name"]')?.value?.trim();
  const logo_image = form.querySelector('[name="logo_image"]')?.value?.trim();
  const commercial_register_front_image = form.querySelector('[name="commercial_register_front_image"]')?.value?.trim();
  const commercial_register_back_image = form.querySelector('[name="commercial_register_back_image"]')?.value?.trim();

  const baseValid = !!(name && email && email.includes('@') && password.length >= 6 && phone && governorate);
  const officeValid = !!(office_name);
  // سنُفعّل الزر عند اكتمال البيانات الأساسية + اسم المكتب
  // المستندات ستُتحقق عند الإرسال لإظهار رسالة واضحة للمستخدم
  submitBtn.disabled = !(baseValid && officeValid);
}

async function submitCarRentalOfficeForm() {
  const form = document.getElementById('addCarRentalOfficeForm');
  if (!form) return;
  const token = localStorage.getItem('token');
  if (!token) { alert('يرجى تسجيل الدخول أولًا.'); window.location.href = '/login'; return; }

  // تحقق المستندات قبل الإرسال لإعطاء تغذية راجعة واضحة
  const logo_image = form.querySelector('[name="logo_image"]')?.value?.trim();
  const commercial_register_front_image = form.querySelector('[name="commercial_register_front_image"]')?.value?.trim();
  const commercial_register_back_image = form.querySelector('[name="commercial_register_back_image"]')?.value?.trim();
  const docsValid = !!(logo_image && commercial_register_front_image && commercial_register_back_image);
  if (!docsValid) {
    alert('من فضلك قم برفع المستندات المطلوبة (الشعار والسجل التجاري أمامي وخلفي).');
    return;
  }

  const payment_methods = [];
  const rental_options = [];
  form.querySelectorAll('#co_pm_cash, #co_pm_card, #co_pm_wallet').forEach(cb => { if (cb.checked) payment_methods.push(cb.value); });
  form.querySelectorAll('#co_ro_hourly, #co_ro_daily, #co_ro_monthly').forEach(cb => { if (cb.checked) rental_options.push(cb.value); });

  const payload = {
    name: form.querySelector('[name="name"]').value.trim(),
    email: form.querySelector('[name="email"]').value.trim(),
    password: form.querySelector('[name="password"]').value,
    phone: form.querySelector('[name="phone"]').value.trim(),
    governorate: form.querySelector('[name="governorate"]').value.trim(),
    user_type: 'car_rental_office',

    office_name: form.querySelector('[name="office_name"]').value.trim(),
    logo_image: form.querySelector('[name="logo_image"]').value.trim(),
    commercial_register_front_image: form.querySelector('[name="commercial_register_front_image"]').value.trim(),
    commercial_register_back_image: form.querySelector('[name="commercial_register_back_image"]').value.trim(),
    payment_methods,
    rental_options,
    cost_per_km: parseFloat(form.querySelector('[name="cost_per_km"]').value || '0'),
    daily_driver_cost: parseFloat(form.querySelector('[name="daily_driver_cost"]').value || '0'),
    max_km_per_day: parseInt(form.querySelector('[name="max_km_per_day"]').value || '0', 10),
    is_available_for_delivery: !!form.querySelector('[name="is_available_for_delivery"]')?.checked,
    is_available_for_rent: !!form.querySelector('[name="is_available_for_rent"]')?.checked
  };

  try {
    const res = await fetch('/api/users', {
      method: 'POST',
      headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(payload)
    });
    if (res.ok) {
      if (typeof showToast === 'function') { showToast('تم إضافة مكتب التأجير بنجاح', 'success'); }
      else { alert('تم إضافة مكتب التأجير بنجاح'); }
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
    console.error('Add car rental office error:', err);
    alert('حدث خطأ غير متوقع أثناء الإضافة');
  }
}

function validateRealEstateIndividualForm() {
  const form = document.getElementById('addRealEstateIndividualForm');
  const submitBtn = document.getElementById('addUserSubmitBtn');
  if (!form || !submitBtn) return;
  const name = form.querySelector('[name="name"]')?.value?.trim();
  const email = form.querySelector('[name="email"]')?.value?.trim();
  const password = form.querySelector('[name="password"]')?.value || '';
  const phone = form.querySelector('[name="phone"]')?.value?.trim();
  const governorate = form.querySelector('[name="governorate"]')?.value?.trim();

  const agent_name = form.querySelector('[name="agent_name"]')?.value?.trim();
  const profile_image = form.querySelector('[name="profile_image"]')?.value?.trim();
  const agent_id_front_image = form.querySelector('[name="agent_id_front_image"]')?.value?.trim();
  const agent_id_back_image = form.querySelector('[name="agent_id_back_image"]')?.value?.trim();
  const tax_card_front_image = form.querySelector('[name="tax_card_front_image"]')?.value?.trim();
  const tax_card_back_image = form.querySelector('[name="tax_card_back_image"]')?.value?.trim();

  const baseValid = !!(name && email && email.includes('@') && password.length >= 6 && phone && governorate);
  const agentValid = !!(agent_name);
  const imagesValid = !!(profile_image && agent_id_front_image && agent_id_back_image && tax_card_front_image && tax_card_back_image);
  submitBtn.disabled = !(baseValid && agentValid && imagesValid);
}

async function submitRealEstateIndividualForm() {
  const form = document.getElementById('addRealEstateIndividualForm');
  if (!form) return;
  const token = localStorage.getItem('token');
  if (!token) { alert('يرجى تسجيل الدخول أولًا.'); window.location.href = '/login'; return; }

  const payload = {
    name: form.querySelector('[name="name"]').value.trim(),
    email: form.querySelector('[name="email"]').value.trim(),
    password: form.querySelector('[name="password"]').value,
    phone: form.querySelector('[name="phone"]').value.trim(),
    governorate: form.querySelector('[name="governorate"]').value.trim(),
    user_type: 'real_estate_individual',

    agent_name: form.querySelector('[name="agent_name"]').value.trim(),
    profile_image: form.querySelector('[name="profile_image"]').value.trim(),
    agent_id_front_image: form.querySelector('[name="agent_id_front_image"]').value.trim(),
    agent_id_back_image: form.querySelector('[name="agent_id_back_image"]').value.trim(),
    tax_card_front_image: form.querySelector('[name="tax_card_front_image"]').value.trim(),
    tax_card_back_image: form.querySelector('[name="tax_card_back_image"]').value.trim(),

    // موقع اختياري
    agent_address: form.querySelector('[name="agent_address"]')?.value?.trim() || '',
    agent_lat: form.querySelector('[name="agent_lat"]')?.value?.trim() || '',
    agent_lng: form.querySelector('[name="agent_lng"]')?.value?.trim() || ''
  };

  try {
    const res = await fetch('/api/users', {
      method: 'POST',
      headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(payload)
    });
    if (res.ok) {
      if (typeof showToast === 'function') { showToast('تم إضافة السمسار بنجاح', 'success'); }
      else { alert('تم إضافة السمسار بنجاح'); }
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
    console.error('Add real estate individual error:', err);
    alert('حدث خطأ غير متوقع أثناء الإضافة');
  }
}

// ===== تكامل خرائط جوجل لاختيار عنوان مكتب العقارات =====
  const GOOGLE_MAPS_API_KEY = '{{ config('services.google.maps_api_key') }}';

function ensureGoogleMapsLoaded(callback) {
  try {
    if (window.google && window.google.maps && window.google.maps.places) { callback(); return; }
    const officeKeyNote = document.getElementById('officeMapsKeyNote');
    const individualKeyNote = document.getElementById('individualMapsKeyNote');
    if (!GOOGLE_MAPS_API_KEY) {
      if (officeKeyNote) officeKeyNote.style.display = 'block';
      if (individualKeyNote) individualKeyNote.style.display = 'block';
      return;
    }
    if (window._gmapsLoading) {
      window._gmapsCallbacks = window._gmapsCallbacks || [];
      window._gmapsCallbacks.push(callback);
      return;
    }
    window._gmapsLoading = true;
    window._gmapsCallbacks = [callback];
    const s = document.createElement('script');
    s.src = `https://maps.googleapis.com/maps/api/js?key=${GOOGLE_MAPS_API_KEY}&libraries=places`;
    s.async = true;
    s.onload = () => {
      window._gmapsLoading = false;
      const cbs = window._gmapsCallbacks || [];
      cbs.forEach(cb => { try { cb(); } catch (_) {} });
      window._gmapsCallbacks = [];
    };
    s.onerror = () => { window._gmapsLoading = false; if (keyNote) keyNote.style.display = 'block'; };
    document.head.appendChild(s);
  } catch (e) { console.error('ensureGoogleMapsLoaded error', e); }
}

function openOfficeMapPicker() {
  const overlay = document.getElementById('officeMapPicker');
  if (!overlay) return;
  overlay.style.display = 'flex';
  ensureGoogleMapsLoaded(initOfficeMap);
}

function closeOfficeMapPicker() {
  const overlay = document.getElementById('officeMapPicker');
  if (!overlay) return;
  overlay.style.display = 'none';
}

function initOfficeMap() {
  try {
    const mapEl = document.getElementById('officeMap');
    const searchInput = document.getElementById('officeMapSearch');
    const addressInput = document.getElementById('officeAddressInput');
    if (!mapEl || !searchInput || !addressInput || !(window.google && window.google.maps)) return;

    const center = { lat: 30.0444, lng: 31.2357 }; // القاهرة
    const map = new google.maps.Map(mapEl, { center, zoom: 12, mapTypeControl: false, streetViewControl: false });
    let marker = null;
    const placeMarker = (latLng) => {
      if (marker) marker.setMap(null);
      marker = new google.maps.Marker({ position: latLng, map });
      map.panTo(latLng);
    };

    if (google.maps.places) {
      const ac = new google.maps.places.Autocomplete(searchInput, {
        fields: ['geometry', 'formatted_address'],
        componentRestrictions: { country: ['eg'] }
      });
      ac.addListener('place_changed', () => {
        const place = ac.getPlace();
        if (!place || !place.geometry) return;
        const loc = place.geometry.location;
        const latLng = { lat: loc.lat(), lng: loc.lng() };
        placeMarker(latLng);
        addressInput.value = place.formatted_address || '';
      });
    }

    map.addListener('click', async (e) => {
      const latLng = { lat: e.latLng.lat(), lng: e.latLng.lng() };
      placeMarker(latLng);
      try {
        const geocoder = new google.maps.Geocoder();
        const result = await geocoder.geocode({ location: latLng });
        const formatted = result.results?.[0]?.formatted_address || '';
        if (formatted) addressInput.value = formatted;
      } catch (_) {}
    });
  } catch (err) { console.error('initOfficeMap error', err); }
}

// ===== تكامل خرائط جوجل لاختيار عنوان السمسار (اختياري) =====
function openIndividualMapPicker() {
  const overlay = document.getElementById('individualMapPicker');
  if (!overlay) return;
  overlay.style.display = 'flex';
  ensureGoogleMapsLoaded(initIndividualMap);
}

function closeIndividualMapPicker() {
  const overlay = document.getElementById('individualMapPicker');
  if (!overlay) return;
  overlay.style.display = 'none';
}

function initIndividualMap() {
  try {
    const mapEl = document.getElementById('individualMap');
    const searchInput = document.getElementById('individualMapSearch');
    const addressInput = document.getElementById('individualAddressInput');
    const latInput = document.querySelector('#addRealEstateIndividualForm [name="agent_lat"]');
    const lngInput = document.querySelector('#addRealEstateIndividualForm [name="agent_lng"]');
    if (!mapEl || !searchInput || !addressInput || !(window.google && window.google.maps)) return;

    const center = { lat: 30.0444, lng: 31.2357 }; // القاهرة
    const map = new google.maps.Map(mapEl, { center, zoom: 12, mapTypeControl: false, streetViewControl: false });
    let marker = null;
    const placeMarker = (latLng) => {
      if (marker) marker.setMap(null);
      marker = new google.maps.Marker({ position: latLng, map });
      map.panTo(latLng);
      if (latInput) latInput.value = String(latLng.lat);
      if (lngInput) lngInput.value = String(latLng.lng);
    };

    if (google.maps.places) {
      const ac = new google.maps.places.Autocomplete(searchInput, {
        fields: ['geometry', 'formatted_address'],
        componentRestrictions: { country: ['eg'] }
      });
      ac.addListener('place_changed', () => {
        const place = ac.getPlace();
        if (!place || !place.geometry) return;
        const loc = place.geometry.location;
        const latLng = { lat: loc.lat(), lng: loc.lng() };
        placeMarker(latLng);
        addressInput.value = place.formatted_address || '';
      });
    }

    map.addListener('click', async (e) => {
      const latLng = { lat: e.latLng.lat(), lng: e.latLng.lng() };
      placeMarker(latLng);
      try {
        const geocoder = new google.maps.Geocoder();
        const result = await geocoder.geocode({ location: latLng });
        const formatted = result.results?.[0]?.formatted_address || '';
        if (formatted) addressInput.value = formatted;
      } catch (_) {}
    });
  } catch (err) { console.error('initIndividualMap error', err); }
}
</script>
@endsection