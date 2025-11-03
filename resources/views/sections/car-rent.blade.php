@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Enhanced Header -->
            <div class="page-header fade-in">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">
                            <i class="bi bi-car-front me-3"></i>
                            إدارة مواصفات تأجير السيارات
                        </h1>
                        <p class="page-subtitle mb-0">
                            <i class="bi bi-gear me-2"></i>
                            Car Services Specifications Management
                        </p>
                    </div>
                    <div class="d-flex gap-3 align-items-center">
                        <div class="stats-badge">
                            <i class="bi bi-list-check me-1"></i>
                            <span id="totalSpecs">0</span> مواصفة
                        </div>
                        <button id="saveAllBtn" class="btn save-all-btn">
                            <i class="bi bi-cloud-upload me-2"></i>
                            حفظ جميع القوائم
                            <span class="save-text">Save All</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer" class="slide-up"></div>

            <!-- Loading Overlay -->
            <div id="loadingOverlay" class="loading-overlay">
                <div class="text-center">
                    <div class="loading-spinner"></div>
                    <p class="mt-3 text-muted">جاري التحميل...</p>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div id="deleteModal" class="delete-modal">
                <div class="delete-modal-content">
                    <div class="delete-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h3>تأكيد الحذف</h3>
                    <p id="deleteMessage">هل أنت متأكد من حذف هذا العنصر؟</p>
                    <div class="delete-modal-buttons">
                        <button id="confirmDelete" class="delete-confirm-btn">
                            <i class="bi bi-trash me-2"></i>
                            حذف
                        </button>
                        <button id="cancelDelete" class="delete-cancel-btn">
                            <i class="bi bi-x-circle me-2"></i>
                            إلغاء
                        </button>
                    </div>
                </div>
            </div>

            <!-- Specs Grid -->
            <div class="row" id="specsGrid">
                <!-- سيتم ملء هذه البطاقات ديناميكياً بناءً على البيانات -->
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    /* ========== Professional Design Improvements ========== */
    :root {
        --primary-gradient: linear-gradient(135deg, #4a90e2 0%, #2c5aa0 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --danger-gradient: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        --warning-gradient: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
        --card-shadow-hover: 0 20px 40px rgba(0,0,0,0.15);
        --border-radius: 12px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .container-fluid {
        /* background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); */
        min-height: 100vh;
        padding: 2rem 1rem;
    }

    /* Header Styling */
    .page-header {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .page-title {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        font-weight: 400;
    }

    /* Save Button */
    .save-all-btn {
        background: var(--success-gradient);
        border: none;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 600;
        color: white;
        box-shadow: 0 8px 25px rgba(17, 153, 142, 0.3);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .save-all-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(17, 153, 142, 0.4);
        color: white;
    }

    .save-all-btn:active {
        transform: translateY(0);
    }

    .save-all-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .save-all-btn:hover::before {
        left: 100%;
    }

    /* Spec Cards */
    .spec-card {
        min-height: 400px;
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        background: white;
        overflow: hidden;
        position: relative;
    }

    .spec-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }

    .spec-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow-hover);
    }

    .spec-card .card-header {
        background: linear-gradient(135deg, #f8f9ff 0%, #e3e8ff 100%);
        border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        padding: 1.5rem;
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .spec-title {
        font-weight: 700;
        color: #2d3748;
        font-size: 1.25rem;
        margin-bottom: 0.25rem;
    }

    .spec-field-name {
        color: #4a90e2;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Toggle Switch */
    .form-check-input:checked {
        background: var(--success-gradient);
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
    }

    .form-check-input {
        width: 3rem;
        height: 1.5rem;
        border-radius: 50px;
        transition: var(--transition);
    }

    /* Input Group */
    .input-group-enhanced {
        margin-bottom: 1.5rem;
        border-radius: 50px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .input-group-enhanced .form-control {
        border: 2px solid #e2e8f0;
        border-right: none;
        padding: 12px 20px;
        font-size: 0.95rem;
        transition: var(--transition);
        background: #f8fafc;
    }

    .input-group-enhanced .form-control:focus {
        border-color: #4a90e2;
        box-shadow: none;
        background: white;
    }

    .add-item-btn {
        background: var(--primary-gradient);
        border: none;
        padding: 12px 20px;
        color: white;
        font-weight: 600;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .add-item-btn:hover {
        transform: scale(1.05);
        color: white;
    }

    .add-item-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s, height 0.3s;
    }

    .add-item-btn:active::after {
        width: 300px;
        height: 300px;
    }

    /* Options List */
    .options-list {
        max-height: 200px;
        overflow-y: auto;
        padding: 0 1rem;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e0 #f7fafc;
    }

    .options-list::-webkit-scrollbar {
        width: 6px;
    }

    .options-list::-webkit-scrollbar-track {
        background: #f7fafc;
        border-radius: 3px;
    }

    .options-list::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }

    .options-list::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }

    /* Option Items */
    .option-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        margin-bottom: 8px;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .option-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--primary-gradient);
        transform: scaleY(0);
        transition: transform 0.3s;
    }

    .option-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-color: #4a90e2;
    }

    .option-item:hover::before {
        transform: scaleY(1);
    }

    .option-item span {
        font-weight: 500;
        color: #2d3748;
    }

    .remove-btn {
        border: none;
        background: transparent;
        color: #e53e3e;
        padding: 4px 8px;
        border-radius: 50%;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .remove-btn:hover {
        background: #fed7d7;
        color: #c53030;
        transform: scale(1.1);
    }

    /* Card Footer */
    .card-footer {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 1.5rem;
    }

    .spec-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .clear-btn {
        background: linear-gradient(135deg, #E24B55 0%, #E24B53 100%);
        border: none;
        border-radius: 25px;
        padding: 8px 16px;
        color: white;
        font-size: 0.875rem;
        font-weight: 500;
        transition: var(--transition);
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.2);
    }

    .clear-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.3);
        color: white;
    }

    /* Alert Enhancements */
    .alert {
        border: none;
        border-radius: var(--border-radius);
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(17, 153, 142, 0.1) 0%, rgba(56, 239, 125, 0.1) 100%);
        border-left: 4px solid #11998e;
        color: #0f5132;
    }

    .alert-warning {
        background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(245, 87, 108, 0.1) 100%);
        border-left: 4px solid #f093fb;
        color: #664d03;
    }

    .alert-danger {
        background: linear-gradient(135deg, rgba(255, 65, 108, 0.1) 0%, rgba(255, 75, 43, 0.1) 100%);
        border-left: 4px solid #ff416c;
        color: #721c24;
    }

    .alert-info {
        background: linear-gradient(135deg, rgba(74, 144, 226, 0.1) 0%, rgba(44, 90, 160, 0.1) 100%);
        border-left: 4px solid #4a90e2;
        color: #055160;
    }

    /* Loading States */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
    }

    .loading-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #e2e8f0;
        border-top: 4px solid #4a90e2;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Enhanced Animations and Interactions */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(-100%); }
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    @keyframes ripple {
        0% { transform: scale(0); opacity: 1; }
        100% { transform: scale(4); opacity: 0; }
    }

    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    .slide-up {
        animation: slideUp 0.4s ease-out;
    }

    .pulse {
        animation: pulse 0.6s ease-in-out;
    }

    /* Button Click Effects */
    .add-item-btn.clicked::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: translate(-50%, -50%);
        animation: ripple 0.6s linear;
    }

    /* Loading States */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid var(--primary-light);
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-text {
        margin-top: 20px;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* Enhanced Input States */
    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* Hover Effects Enhancement */
    .spec-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-hover);
    }

    .option-item:hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
        transform: translateX(5px);
    }

    .add-item-btn:hover {
        background: linear-gradient(135deg, var(--success) 0%, #20c997 100%);
        transform: scale(1.05);
    }

    .clear-btn:hover {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        transform: scale(1.02);
    }

    .save-btn:hover {
        background: linear-gradient(135deg, var(--primary) 0%, #0056b3 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
    }

    /* Twist Animation for Delete Effects */
    @keyframes twistOut {
        0% { 
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }
        50% { 
            transform: scale(0.8) rotate(180deg);
            opacity: 0.5;
        }
        100% { 
            transform: scale(0) rotate(360deg);
            opacity: 0;
        }
    }

    @keyframes twistIn {
        0% { 
            transform: scale(0) rotate(-360deg);
            opacity: 0;
        }
        50% { 
            transform: scale(0.8) rotate(-180deg);
            opacity: 0.5;
        }
        100% { 
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }
    }

    .twist-out {
        animation: twistOut 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
    }

    .twist-in {
        animation: twistIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
    }

    /* Enhanced Delete Confirmation Modal */
    .delete-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
    }

    .delete-modal.show {
        opacity: 1;
        visibility: visible;
    }

    .delete-modal-content {
        background: white;
        border-radius: 15px;
        padding: 30px;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        transform: scale(0.8);
        transition: transform 0.3s ease;
    }

    .delete-modal.show .delete-modal-content {
        transform: scale(1);
    }

    .delete-icon {
        font-size: 3rem;
        color: #ff416c;
        margin-bottom: 20px;
        animation: pulse 2s infinite;
    }

    .delete-modal h3 {
        color: #2d3748;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .delete-modal p {
        color: #6c757d;
        margin-bottom: 25px;
        line-height: 1.5;
    }

    .delete-modal-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
    }

    .delete-confirm-btn {
        background: var(--danger-gradient);
        border: none;
        border-radius: 25px;
        padding: 12px 25px;
        color: white;
        font-weight: 600;
        transition: var(--transition);
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
    }

    .delete-confirm-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.4);
        color: white;
    }

    .delete-cancel-btn {
        background: #6c757d;
        border: none;
        border-radius: 25px;
        padding: 12px 25px;
        color: white;
        font-weight: 600;
        transition: var(--transition);
    }

    .delete-cancel-btn:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }
            .container-fluid {
                padding: 20px 15px;
            }
            
            .spec-card {
                margin-bottom: 25px;
            }
        

        @media (max-width: 992px) {
            .page-header {
                padding: 25px 20px;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .stats-badge {
                margin-top: 15px;
            }
            
            .col-lg-6 {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 15px 10px;
            }

            .page-header {
                padding: 20px 15px;
                text-align: center;
                background: linear-gradient(135deg, var(--primary) 0%, #0056b3 100%);
                color: white;
                border-radius: 15px;
                margin-bottom: 25px;
            }

            .page-title {
                font-size: 1.5rem;
                margin-bottom: 10px;
            }

            .page-subtitle {
                font-size: 0.9rem;
                opacity: 0.9;
            }

            .stats-badge {
                margin-top: 15px;
                background: rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(10px);
            }

            .stats-badge .badge {
                background: rgba(255, 255, 255, 0.3);
                color: white;
                font-weight: 600;
            }

            .spec-card {
                margin-bottom: 20px;
                border-radius: 15px;
                overflow: hidden;
            }

            .spec-actions {
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }

            .items-count {
                order: -1;
                text-align: center;
                padding: 8px 12px;
                background: var(--primary-light);
                border-radius: 20px;
                font-size: 0.9rem;
            }

            .clear-btn {
                width: 100%;
                justify-content: center;
            }

            .input-group-enhanced {
                flex-direction: column;
                gap: 10px;
            }

            .input-group-enhanced .form-control {
                border-radius: 10px;
                padding: 12px 15px;
                font-size: 1rem;
            }

            .add-item-btn {
                border-radius: 10px !important;
                padding: 12px;
                width: 100%;
                font-weight: 600;
            }

            .option-item {
                padding: 15px;
                margin-bottom: 8px;
                border-radius: 10px;
                font-size: 0.95rem;
            }

            .option-content {
                flex: 1;
            }

            .remove-btn {
                padding: 8px;
                min-width: 36px;
                height: 36px;
            }

            .save-btn {
                position: fixed;
                bottom: 20px;
                right: 15px;
                left: 15px;
                z-index: 1000;
                border-radius: 25px;
                padding: 15px;
                font-size: 1.1rem;
                font-weight: 600;
                box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
            }

            /* Hide loading overlay backdrop on mobile for better performance */
            .loading-overlay {
                backdrop-filter: blur(3px);
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding: 10px 8px;
            }

            .page-header {
                padding: 15px 12px;
                margin-bottom: 20px;
            }

            .page-title {
                font-size: 1.3rem;
                line-height: 1.3;
            }

            .page-subtitle {
                font-size: 0.8rem;
                margin-top: 5px;
            }

            .spec-title {
                font-size: 1rem;
                line-height: 1.4;
            }

            .spec-field-name {
                font-size: 0.75rem;
            }

            .option-item {
                font-size: 0.9rem;
                padding: 12px;
            }

            .option-text {
                line-height: 1.4;
            }

            .form-control {
                font-size: 16px; /* Prevent zoom on iOS */
            }

            .card-header, .card-footer {
                padding: 12px 15px;
            }

            .card-body {
                padding: 15px;
            }

            .save-btn {
                bottom: 15px;
                right: 10px;
                left: 10px;
                padding: 12px;
                font-size: 1rem;
            }

            /* Optimize animations for mobile */
            .spec-card:hover {
                transform: none;
            }

            .option-item:hover {
                transform: none;
                background: var(--primary-light);
            }
        }

        @media (max-width: 400px) {
            .page-title {
                font-size: 1.2rem;
            }

            .spec-card {
                margin-bottom: 15px;
            }

            .option-item {
                padding: 10px;
                font-size: 0.85rem;
            }

            .input-group-enhanced .form-control {
                padding: 10px 12px;
            }

            .add-item-btn {
                padding: 10px;
            }
        }

        /* Landscape orientation optimizations */
        @media (max-height: 500px) and (orientation: landscape) {
            .page-header {
                padding: 10px 15px;
                margin-bottom: 15px;
            }

            .page-title {
                font-size: 1.2rem;
                margin-bottom: 5px;
            }

            .spec-card {
                margin-bottom: 15px;
            }

            .save-btn {
                position: relative;
                margin-top: 20px;
                border-radius: 10px;
            }
        }

        /* High DPI displays */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .spec-card {
                border-width: 0.5px;
            }

            .option-item {
                border-width: 0.5px;
            }
        }

        /* Reduced motion preferences */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }

            .spec-card:hover {
                transform: none;
            }

            .option-item:hover {
                transform: none;
            }
        }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        :root {
            --bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --border-color: #404040;
        }

        body {
            background: var(--bg-primary);
            color: var(--text-primary);
        }

        .spec-card {
            background: var(--bg-secondary);
            border-color: var(--border-color);
        }

        .card-header, .card-footer {
            background: var(--bg-secondary) !important;
            border-color: var(--border-color);
        }

        .form-control {
            background: var(--bg-primary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .option-item {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--border-color);
        }
    }

    /* Stats Badge */
     .stats-badge {
         background: linear-gradient(135deg, #E24B55 0%, #E24B53 100%);
         color: white;
         padding: 8px 16px;
         border-radius: 25px;
         font-size: 0.9rem;
         font-weight: 600;
         box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
         display: flex;
         align-items: center;
     }

     /* Save Button Text */
     .save-text {
         font-size: 0.85rem;
         opacity: 0.9;
         margin-left: 5px;
     }
</style>

@section('scripts')
<script>
// =============================
// Guard & Helpers
// =============================
function getAuthHeaders() {
    const token = localStorage.getItem('token');
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };
    if (token) headers['Authorization'] = `Bearer ${token}`;
    return headers;
}

function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alertContainer');
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    alertContainer.prepend(alertDiv);
    setTimeout(() => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alertDiv);
        if (bsAlert) bsAlert.close();
    }, 5000);
}

// =============================
// Specs UI Logic
// =============================
const ADMIN_SPECS_ENDPOINT = '/api/admin/car-sales-ad-specs';
const BULK_UPDATE_ENDPOINT = '/api/admin/car-sales-ad-specs/bulk-update';

// المتوقع من المستخدم: قوائم لهذه الحقول
const REQUIRED_FIELDS = [
    'emirate', 'car_type', 'trans_type', 'fuel_type', 'color', 'interior_color', 'seats_no'
];

let specsMap = new Map(); // field_name -> spec object

function renderSpecsGrid(specs) {
    const grid = document.getElementById('specsGrid');
    grid.innerHTML = '';

    // حافظ على ترتيب sort_order القادم من السيرفر
    specs.forEach(spec => {
        specsMap.set(spec.field_name, spec);
        grid.appendChild(createSpecCard(spec));
    });

    // إن كانت هناك حقول مطلوبة غير موجودة في السيرفر، أظهر بطاقات فارغة لها (لن تُحفظ إذا لم تكن موجودة)
    REQUIRED_FIELDS.forEach(field => {
        if (!specsMap.has(field)) {
            const placeholder = {
                field_name: field,
                display_name: getArabicDisplayName(field),
                options: [],
                is_active: true,
                sort_order: 999
            };
            grid.appendChild(createSpecCard(placeholder, true));
        }
    });
}

function createSpecCard(spec, isPlaceholder = false) {
    const col = document.createElement('div');
    col.className = 'col-lg-6 col-xl-4 mb-4 fade-in';

    const card = document.createElement('div');
    card.className = 'card border-0 shadow-sm spec-card';
    card.dataset.field = spec.field_name;

    const header = document.createElement('div');
    header.className = 'card-header bg-white';
    header.innerHTML = `
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h5 class="spec-title mb-0">
                    <i class="bi bi-${getFieldIcon(spec.field_name)} me-2"></i>
                    ${spec.display_name || spec.field_name}
                </h5>
                <small class="spec-field-name">${spec.field_name}</small>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" ${spec.is_active ? 'checked' : ''} data-role="active-toggle">
            </div>
        </div>
    `;

    const body = document.createElement('div');
    body.className = 'card-body';

    const inputGroup = document.createElement('div');
    inputGroup.className = 'input-group input-group-enhanced';
    inputGroup.innerHTML = `
        <input type="text" class="form-control" placeholder="أضف عنصرًا جديدًا - Add item" data-role="new-item-input">
        <button class="btn add-item-btn" type="button" data-role="add-item-btn">
            <i class="bi bi-plus-lg"></i>
        </button>
    `;

    const list = document.createElement('div');
    list.className = 'options-list';

    (spec.options || []).forEach((opt, idx) => {
        list.appendChild(createOptionItem(opt));
    });

    body.appendChild(inputGroup);
    body.appendChild(list);

    const footer = document.createElement('div');
    footer.className = 'card-footer bg-white';
    footer.innerHTML = `
        <div class="spec-actions">
            <button class="btn clear-btn" type="button" data-role="clear-items">
                <i class="bi bi-trash me-1"></i>
                مسح الكل
            </button>
            <div class="items-count">
                <i class="bi bi-collection me-1"></i>
                <span class="count">${(spec.options || []).length}</span> عنصر
            </div>
            ${isPlaceholder && spec.field_name !== 'seats_no' && spec.field_name !== 'steering_side' ? '<span class="text-muted small"><i class="bi bi-info-circle me-1"></i>غير محفوظ</span>' : ''}
        </div>
    `;

    // Enhanced Events with animations
    const addBtn = inputGroup.querySelector('[data-role="add-item-btn"]');
    const newItemInput = inputGroup.querySelector('[data-role="new-item-input"]');
    
    addBtn.addEventListener('click', () => {
        const value = newItemInput.value.trim();
        if (!value) {
            showAlert('يرجى إدخال قيمة صحيحة', 'warning');
            newItemInput.focus();
            newItemInput.classList.add('is-invalid');
            setTimeout(() => newItemInput.classList.remove('is-invalid'), 2000);
            return;
        }
        // التحقق من عدم التكرار
        const existingItems = Array.from(list.querySelectorAll('.option-item span')).map(s => s.textContent.trim());
        if (existingItems.includes(value)) {
            showAlert('هذا العنصر موجود بالفعل', 'warning');
            newItemInput.focus();
            newItemInput.classList.add('is-invalid');
            setTimeout(() => newItemInput.classList.remove('is-invalid'), 2000);
            return;
        }
        const newItem = createOptionItem(value);
        newItem.classList.add('slide-up');
        list.appendChild(newItem);
        newItemInput.value = '';
        updateItemsCount(footer);
        showAlert('تم إضافة العنصر بنجاح', 'success');
        
        // Add ripple effect
        addBtn.classList.add('clicked');
        setTimeout(() => addBtn.classList.remove('clicked'), 300);
    });

    // إضافة عنصر عند الضغط على Enter
    newItemInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            addBtn.click();
        }
    });

    footer.querySelector('[data-role="clear-items"]').addEventListener('click', () => {
        if (list.children.length === 0) {
            showAlert('لا توجد عناصر للمسح', 'info');
            return;
        }
        showDeleteModal(
            'هل أنت متأكد من مسح جميع العناصر؟',
            () => {
                // Animate items out with twist
                Array.from(list.children).forEach((item, index) => {
                    setTimeout(() => {
                        removeItemWithTwist(item, null);
                    }, index * 100);
                });
                
                setTimeout(() => {
                    updateItemsCount(footer);
                    showAlert('تم مسح جميع العناصر', 'success');
                    autoSaveSpecs(); // Auto-save after clearing
                }, list.children.length * 100 + 600);
            }
        );
    });

    card.appendChild(header);
    card.appendChild(body);
    card.appendChild(footer);

    col.appendChild(card);
    return col;
}

function createOptionItem(value) {
    const item = document.createElement('div');
    item.className = 'option-item';
    item.innerHTML = `
        <div class="option-content">
            <i class="bi bi-check-circle-fill option-icon"></i>
            <span class="option-text">${value}</span>
        </div>
        <button class="btn remove-btn" type="button" data-role="remove-item">
            <i class="bi bi-x-lg"></i>
        </button>
    `;

    // Enhanced remove event with twist animation
    item.querySelector('[data-role="remove-item"]').addEventListener('click', () => {
        showDeleteModal(
            `هل تريد حذف "${value}"؟`,
            () => {
                removeItemWithTwist(item, () => {
                    // Update count if parent footer exists
                    const card = item.closest('.spec-card');
                    if (card) {
                        const footer = card.querySelector('.card-footer');
                        if (footer) updateItemsCount(footer);
                    }
                    autoSaveSpecs(); // Auto-save after deletion
                });
            }
        );
    });

    return item;
}

// Helper function to get field icons
function getFieldIcon(fieldName) {
    const iconMap = {
        'engine': 'gear-fill',
        'transmission': 'arrow-repeat',
        'fuel_type': 'fuel-pump',
        'color': 'palette-fill',
        'brand': 'award-fill',
        'model': 'car-front-fill',
        'year': 'calendar-fill',
        'mileage': 'speedometer2',
        'condition': 'shield-check',
        'features': 'star-fill',
        'safety': 'shield-fill-check',
        'interior': 'house-fill',
        'exterior': 'sun-fill',
        'technology': 'cpu-fill',
        'performance': 'lightning-fill',
        'comfort': 'heart-fill',
        'maintenance': 'tools',
        'warranty': 'patch-check-fill',
        'price': 'currency-dollar',
        'location': 'geo-alt-fill',
        'seats_no': 'person-fill',
        'steering_side': 'steering-wheel'
    };
    
    // Find matching icon or use default
    const key = Object.keys(iconMap).find(k => fieldName.toLowerCase().includes(k));
    return iconMap[key] || 'gear-fill';
}

// Helper function to get Arabic display names
function getArabicDisplayName(fieldName) {
    const arabicNames = {
        'emirate': 'الإمارة - Emirate',
        'car_type': 'نوع السيارة - Car Type',
        'trans_type': 'نوع ناقل الحركة - Transmission',
        'fuel_type': 'نوع الوقود - Fuel Type',
        'color': 'اللون الخارجي - Exterior Color',
        'interior_color': 'لون الداخلية - Interior Color',
        'seats_no': 'عدد المقاعد - Number of Seats'
    };
    
    return arabicNames[fieldName] || fieldName.replace(/_/g, ' ').toUpperCase();
}

// Helper function to update items count
function updateItemsCount(footer) {
    const countSpan = footer.querySelector('.items-count .count');
    const card = footer.closest('.spec-card');
    if (countSpan && card) {
        const itemsCount = card.querySelectorAll('.option-item').length;
        countSpan.textContent = itemsCount;
        
        // Add pulse animation to count
        countSpan.parentElement.classList.add('pulse');
        setTimeout(() => countSpan.parentElement.classList.remove('pulse'), 600);
    }
}

function collectPayloadFromUI() {
    // نجمع المواصفات من واجهة المستخدم للبنود الموجودة بالسيرفر فقط
    const cards = document.querySelectorAll('.spec-card');
    const specifications = [];
    cards.forEach(card => {
        const field = card.dataset.field;
        const spec = specsMap.get(field);
        if (!spec) return; // نتجاهل البطاقات غير الموجودة بالسيرفر

        const active = card.querySelector('[data-role="active-toggle"]').checked;
        const options = Array.from(card.querySelectorAll('.option-item span')).map(s => s.textContent.trim()).filter(Boolean);

        // نضمن عدم تكرار "other" لأن السيرفر يضيفها دائماً في النهاية
        const sanitizedOptions = options.filter(o => o.toLowerCase() !== 'other');

        specifications.push({
            field_name: field,
            display_name: spec.display_name || field,
            options: sanitizedOptions,
            is_active: active,
            sort_order: spec.sort_order ?? 0,
        });
    });
    return { specifications };
}

// Enhanced loading functions with better UX
function showLoadingOverlay(message = 'جاري التحميل...') {
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay show';
    overlay.innerHTML = `
        <div class="text-center">
            <div class="loading-spinner"></div>
            <div class="loading-text">${message}</div>
        </div>
    `;
    document.body.appendChild(overlay);
    return overlay;
}

function hideLoadingOverlay(overlay) {
    if (overlay && overlay.parentNode) {
        overlay.classList.remove('show');
        setTimeout(() => overlay.remove(), 300);
    }
}

// Enhanced loadAdminSpecs with better loading states
async function loadAdminSpecs() {
    const overlay = showLoadingOverlay('جاري تحميل المواصفات...');
    
    try {
        const res = await fetch(ADMIN_SPECS_ENDPOINT, { headers: getAuthHeaders() });
        if (res.status === 401) {
            hideLoadingOverlay(overlay);
            showAlert('يرجى تسجيل الدخول أولاً', 'warning');
            window.location.href = '/login';
            return;
        }
        if (!res.ok) throw new Error('فشل في جلب المواصفات');
        const data = await res.json();
        const specs = Array.isArray(data?.data) ? data.data : [];
        hideLoadingOverlay(overlay);
        renderSpecsGrid(specs);
        updateStatsCounter(specs.length);
        showAlert('تم تحميل المواصفات بنجاح', 'success');
    } catch (err) {
        hideLoadingOverlay(overlay);
        console.error(err);
        showAlert('حدث خطأ أثناء التحميل: ' + err.message + ' - سيتم عرض بيانات وهمية للمعاينة', 'warning');
        // عرض بيانات وهمية للمعاينة
        loadDummyData();
    }
}

function loadDummyData() {
    const dummySpecs = [
        {
            field_name: 'emirate',
            display_name: 'الإمارة - Emirate',
            options: ['أبوظبي', 'دبي', 'الشارقة', 'عجمان', 'أم القيوين', 'رأس الخيمة', 'الفجيرة'],
            is_active: true,
            sort_order: 1
        },
        {
            field_name: 'car_type',
            display_name: 'نوع السيارة - Car Type',
            options: ['سيدان', 'هاتشباك', 'SUV', 'كوبيه', 'كونفرتيبل', 'بيك أب', 'كروس أوفر'],
            is_active: true,
            sort_order: 2
        },
        {
            field_name: 'trans_type',
            display_name: 'نوع ناقل الحركة - Transmission',
            options: ['أوتوماتيك', 'مانيوال', 'CVT', 'تيبترونيك', 'هايبرد'],
            is_active: true,
            sort_order: 3
        },
        {
            field_name: 'fuel_type',
            display_name: 'نوع الوقود - Fuel Type',
            options: ['بنزين', 'ديزل', 'هايبرد', 'كهربائي', 'غاز طبيعي', 'بنزين + كهرباء'],
            is_active: true,
            sort_order: 4
        },
        {
            field_name: 'color',
            display_name: 'اللون الخارجي - Exterior Color',
            options: ['أبيض', 'أسود', 'فضي', 'رمادي', 'أحمر', 'أزرق', 'ذهبي', 'بني', 'أخضر', 'برتقالي'],
            is_active: true,
            sort_order: 5
        },
        {
            field_name: 'interior_color',
            display_name: 'لون الداخلية - Interior Color',
            options: ['أسود', 'بيج', 'رمادي', 'بني', 'أحمر', 'أبيض', 'كريمي', 'أزرق داكن'],
            is_active: true,
            sort_order: 6
        },
        {
            field_name: 'seats_no',
            display_name: 'عدد المقاعد - Number of Seats',
            options: ['2', '4', '5', '7', '8', '9+'],
            is_active: true,
            sort_order: 7
        }
    ];
    
    renderSpecsGrid(dummySpecs);
    updateStatsCounter(dummySpecs.length);
    showAlert('تم عرض بيانات وهمية للمعاينة - Dummy data loaded for preview', 'info');
}

// Enhanced saveAllSpecs with better UX and auto-save integration
async function saveAllSpecs() {
    const payload = collectPayloadFromUI();
    if (!payload.specifications.length) {
        showAlert('لا توجد عناصر للحفظ', 'warning');
        return;
    }
    
    const token = localStorage.getItem('token');
    if (!token) {
        showAlert('يجب تسجيل الدخول أولاً لحفظ التغييرات', 'warning');
        return;
    }
    
    const overlay = showLoadingOverlay('جاري حفظ المواصفات...');
    
    // Update save button state
    const saveBtn = document.getElementById('saveAllBtn');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>جاري الحفظ...';
    saveBtn.disabled = true;
    
    try {
        const res = await fetch(BULK_UPDATE_ENDPOINT, {
            method: 'POST',
            headers: getAuthHeaders(),
            body: JSON.stringify(payload)
        });
        if (res.status === 401) {
            hideLoadingOverlay(overlay);
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
            showAlert('يرجى تسجيل الدخول أولاً', 'warning');
            window.location.href = '/login';
            return;
        }
        const data = await res.json();
        if (!res.ok || data.success === false) throw new Error(data.message || 'فشل الحفظ');
        
        hideLoadingOverlay(overlay);
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
        
        showAlert('تم حفظ المواصفات بنجاح ✅', 'success');
        // Update localStorage backup
        localStorage.setItem('car_specs_backup', JSON.stringify(payload.specifications));
        // Auto-save is now active
        showAlert('تم تفعيل الحفظ التلقائي - سيتم حفظ التغييرات كل 30 ثانية', 'info');
        // إعادة التحميل لضمان التزام ترتيب وحقل other من السيرفر
        setTimeout(() => loadAdminSpecs(), 1000);
    } catch (err) {
        hideLoadingOverlay(overlay);
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
        
        console.error(err);
        showAlert('حدث خطأ أثناء الحفظ. تم حفظ البيانات محلياً كنسخة احتياطية.', 'danger');
        // Save to localStorage as backup
        localStorage.setItem('car_specs_backup', JSON.stringify(payload.specifications));
        // في حالة الخطأ، نعرض رسالة توضيحية
        setTimeout(() => {
            showAlert('تلميح: تأكد من صحة البيانات والاتصال بالإنترنت', 'info');
        }, 3000);
    }
}

// Enhanced stats counter with animation
function updateStatsCounter(count) {
    const counter = document.getElementById('totalSpecs');
    if (counter) {
        counter.style.transform = 'scale(1.2)';
        counter.textContent = count;
        setTimeout(() => {
            counter.style.transform = 'scale(1)';
        }, 200);
    }
}

// Enhanced delete functions with twist animations
        let deleteCallback = null;
        
        function showDeleteModal(message, callback) {
            const modal = document.getElementById('deleteModal');
            const messageEl = document.getElementById('deleteMessage');
            
            messageEl.textContent = message;
            deleteCallback = callback;
            
            modal.classList.add('show');
        }
        
        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('show');
            deleteCallback = null;
        }
        
        function removeItemWithTwist(element, callback) {
            element.classList.add('twist-out');
            
            setTimeout(() => {
                if (callback) callback();
                element.remove();
            }, 600);
        }
        
        // Modal event listeners
        document.getElementById('confirmDelete').addEventListener('click', () => {
            if (deleteCallback) {
                deleteCallback();
            }
            hideDeleteModal();
        });
        
        document.getElementById('cancelDelete').addEventListener('click', hideDeleteModal);
        
        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', (e) => {
            if (e.target.id === 'deleteModal') {
                hideDeleteModal();
            }
        });
function autoSaveSpecs() {
    const token = localStorage.getItem('token');
    if (!token) return;
    
    const allSpecs = [];
    document.querySelectorAll('.spec-card').forEach(card => {
        const fieldName = card.querySelector('.spec-field-name').textContent;
        const isActive = card.querySelector('.form-check-input').checked;
        const options = Array.from(card.querySelectorAll('.option-item span')).map(span => span.textContent);
        
        allSpecs.push({
            field_name: fieldName,
            is_active: isActive,
            options: options
        });
    });
    
    // Save to localStorage as backup
    localStorage.setItem('car_specs_backup', JSON.stringify(allSpecs));
    
    // Auto-save to server every 30 seconds
    fetch('/api/admin/car-sales-ad-specs', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({ specs: allSpecs })
    }).catch(error => {
        console.log('Auto-save failed, data saved locally');
    });
}

// Auto-save every 30 seconds
setInterval(autoSaveSpecs, 30000);

// Save on page unload
window.addEventListener('beforeunload', autoSaveSpecs);

document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('token');
    if (!token) {
        showAlert('غير مصرح: يرجى تسجيل الدخول - سيتم عرض بيانات وهمية للمعاينة', 'warning');
        // عرض بيانات وهمية حتى لو لم يكن هناك token
        loadDummyData();
        // إضافة رسالة تحذيرية
        setTimeout(() => {
            showAlert('تنبيه: لن تتمكن من حفظ التغييرات بدون تسجيل الدخول', 'info');
        }, 2000);
    } else {
        loadAdminSpecs();
    }
    document.getElementById('saveAllBtn').addEventListener('click', saveAllSpecs);
});
</script>
@endsection