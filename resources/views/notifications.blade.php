@extends('layouts.dashboard')

@section('title', 'الإشعارات')

@push('styles')
{{-- ========== قسم الأنماط (CSS) الخاص بالصفحة ========== --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-color: #f97316; /* orange-500 */
        --primary-light: #fb923c; /* orange-400 */
        --primary-dark: #c2410c;  /* orange-700 */
        --success-color: #f97316; /* use orange for success */
        --warning-color: #f97316; /* use orange for warning */
        --danger-color: #000000;  /* black */
        --info-color: #6b7280;    /* gray-500 */
        --light-gray: #f3f4f6;    /* gray-100 */
        --medium-gray: #e5e7eb;   /* gray-200 */
        --border-color: #d1d5db;  /* gray-300 */
        --text-primary: #000000;  /* black */
        --text-secondary: #6b7280;/* gray-500 */
        --text-muted: #9ca3af;    /* gray-400 */
        --white: #ffffff;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    * {
        font-family: 'Cairo', sans-serif;
    }

    .notifications-page {
        /* background: linear-gradient(135deg, var(--white) 0%, var(--light-gray) 100%); */
        min-height: 100vh;
        padding: .6rem;
    
    }

    .notifications-container {
        max-width: 1400px;
        margin: 0 auto;
        background: var(--white);
        border-radius: 20px;
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    /* Header Section */
    .notifications-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        padding: 2rem;
        color: var(--white);
        position: relative;
        overflow: hidden;
    }

    .notifications-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .header-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-title h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header-title .title-icon {
        font-size: 2.5rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    #permission-button {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: var(--white);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    #permission-button:hover {
        background: rgba(255,255,255,0.3);
        border-color: rgba(255,255,255,0.5);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    /* Filters Section */
    .filters-section {
        padding: 1rem;
        background: var(--light-gray);
        border-bottom: 1px solid var(--border-color);
    }

    .filters-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .filter-tabs {
        display: flex;
        background: var(--white);
        border-radius: 50px;
        /* padding: 0.2rem; */
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .filter-tab {
        padding: 0.7rem 1rem;
        border: none;
        background: transparent;
        color: var(--text-secondary);
        font-weight: 500;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-tab.active {
        background: var(--primary-color);
        color: var(--white);
        box-shadow: var(--shadow-md);
        transform: translateY(-1px);
    }

    .filter-tab:not(.active):hover {
        background: var(--medium-gray);
        color: var(--text-primary);
    }

    .filter-count {
        background: var(--primary-color);
        color: var(--white);
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 50px;
        min-width: 1.5rem;
        text-align: center;
        font-weight: 600;
    }

    .filter-tab.active .filter-count {
        background: rgba(255,255,255,0.2);
    }

    .search-container {
        position: relative;
        min-width: 360px;
        display: flex;
        align-items: center;
        direction: rtl;
        margin-top: 1.5rem;
    }
    
    .search-input {
        width: 100%;
        padding: 0.75rem 3rem 0.75rem 2.5rem; /* أيقونة البحث يمين وزر المسح يسار */
        border: 2px solid var(--border-color);
        border-radius: 50px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--white);
        text-align: right;
    }
    
    .search-container:focus-within .search-input {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
    }
    
    .search-input::placeholder {
        color: var(--text-muted);
    }
    
    .search-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 1.1rem;
        pointer-events: none;
    }
    
    .search-clear {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 1rem;
        padding: 0;
        display: none; /* يظهر عند وجود نص */
    }
    
    .search-clear:hover {
        color: var(--primary-color);
    }
    /* Notifications Body */
    .notifications-body {
        max-height: 58vh;
        overflow-y: auto;
        padding: 1rem;
    }

    .notifications-body::-webkit-scrollbar {
        width: 8px;
    }

    .notifications-body::-webkit-scrollbar-track {
        background: var(--light-gray);
        border-radius: 4px;
    }

    .notifications-body::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 4px;
    }

    .notifications-body::-webkit-scrollbar-thumb:hover {
        background: var(--primary-dark);
    }

    .notification-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 1rem;
    }

    /* Ticket Style Notifications */
    .notification-ticket {
        background: var(--white);
        border-radius: 16px;
        padding: 1rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .notification-ticket::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-color);
        transition: width 0.3s ease;
    }

    .notification-ticket:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .notification-ticket:hover::before {
        width: 8px;
    }

    .notification-ticket.unread {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-color: var(--info-color);
    }

    .notification-ticket.unread::before {
        background: var(--info-color);
    }

    .ticket-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .ticket-icon-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ticket-icon {
        width: 30px;
        height: 30px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: var(--white);
        background: var(--primary-color);
        box-shadow: var(--shadow-md);
    }

    .ticket-icon.order { background: var(--success-color); }
    .ticket-icon.appointment { background: var(--warning-color); }
    .ticket-icon.chat { background: var(--info-color); }
    .ticket-icon.system { background: var(--danger-color); }

    .ticket-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ticket-time {
        color: var(--text-muted);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .ticket-status {
        padding: 0.2rem 0.5rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .ticket-status.unread {
        background: var(--info-color);
        color: var(--white);
    }

    .ticket-status.read {
        background: var(--medium-gray);
        color: var(--text-secondary);
    }

    .ticket-content {
        margin-bottom: .5rem;
    }

    .ticket-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.3rem;
        line-height: 1.3;
    }

    .ticket-message {
        color: var(--text-secondary);
        line-height: 1.3;
        margin: 0;
        font-size: 0.9rem;
    }

    .ticket-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: .5rem;
        border-top: 1px solid var(--border-color);
    }

    .ticket-type-badge {
        padding: 0.2rem 0.5rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .type-order { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
    .type-appointment { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
    .type-chat { background: rgba(6, 182, 212, 0.1); color: var(--info-color); }
    .type-system { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }

    .read-toggle-btn {
        background: none;
        border: 2px solid var(--border-color);
        color: var(--text-secondary);
        padding: 0.5rem .5rem;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 400;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .read-toggle-btn:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-1px);
    }

    .read-toggle-btn.mark-unread {
        border-color: var(--info-color);
        color: var(--info-color);
    }

    .read-toggle-btn.mark-read {
        border-color: var(--success-color);
        color: var(--success-color);
    }

    /* Empty and Loading States */
    #empty-state, #loading-state {
        padding: 4rem 2rem;
        text-align: center;
        color: var(--text-muted);
    }

    #empty-state .empty-icon, #loading-state .loading-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--text-muted);
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }

    #loading-state .loading-icon {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .empty-message, .loading-message {
        font-size: 1.125rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .empty-description, .loading-description {
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .notifications-page {
            padding: 1rem 0.5rem;
        }

        .notifications-header {
            padding: 1rem;
        }

        .header-title h1 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .filters-container {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-tabs {
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .filter-tab {
            width: 100%;
            justify-content: center;
            padding: 0.75rem 1rem;
        }

        .search-container {
            min-width: auto;
            margin-top: 1rem;
        }

        .search-input {
            font-size: 16px; /* منع التكبير في iOS */
        }

        .notifications-body {
            padding: 0.5rem;
        }

        .notification-ticket {
            margin-bottom: 0.75rem;
            padding: 1rem;
        }

        .ticket-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .ticket-icon-wrapper {
            align-self: flex-start;
        }

        .ticket-meta {
            width: 100%;
            justify-content: space-between;
        }

        .ticket-content {
            margin: 1rem 0;
        }

        .ticket-title {
            font-size: 1rem;
            line-height: 1.4;
        }

        .ticket-message {
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .ticket-actions {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }

        .read-toggle-btn {
            width: 100%;
            justify-content: center;
            padding: 0.75rem;
            font-size: 0.875rem;
        }

        .ticket-type-badge {
            align-self: flex-start;
            margin-bottom: 0.5rem;
        }
    }

    @media (max-width: 480px) {
        .notifications-container {
            padding: 0.5rem;
        }

        .notifications-header {
            padding: 0.75rem;
        }

        .notifications-header h1 {
            font-size: 1.25rem;
        }

        .filter-tab {
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
        }

        .filter-count {
            min-width: 1.25rem;
            height: 1.25rem;
            font-size: 0.75rem;
        }

        .notification-ticket {
            padding: 0.75rem;
            border-radius: 8px;
        }

        .ticket-icon {
            width: 2.5rem;
            height: 2.5rem;
        }

        .ticket-icon i {
            font-size: 1rem;
        }

        .ticket-time,
        .ticket-status {
            font-size: 0.75rem;
        }

        .ticket-title {
            font-size: 0.9rem;
        }

        .ticket-message {
            font-size: 0.8rem;
        }

        .read-toggle-btn {
            padding: 0.625rem;
            font-size: 0.8rem;
        }

        .read-toggle-btn i {
            font-size: 0.875rem;
        }
    }

    /* تحسينات للأجهزة اللوحية */
    @media (min-width: 769px) and (max-width: 1024px) {
        .notifications-container {
            max-width: 90%;
        }

        .filter-tabs {
            justify-content: center;
            gap: 1rem;
        }

        .notification-ticket {
            max-width: none;
        }

        .ticket-actions {
            justify-content: space-between;
        }
    }

    /* تحسينات للشاشات الكبيرة */
    @media (min-width: 1200px) {
        .notifications-container {
            max-width: 1000px;
        }

        .notification-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1rem;
        }

        .notification-ticket {
            margin-bottom: 0;
        }
    }

    /* تحسينات إضافية للتفاعل باللمس */
    @media (hover: none) and (pointer: coarse) {
        .filter-tab,
        .read-toggle-btn {
            min-height: 44px; /* الحد الأدنى لحجم اللمس */
        }

        .notification-ticket {
            cursor: default;
        }

        .notification-ticket:active {
            transform: scale(0.98);
        }
    }

    /* تحسينات للوضع الأفقي على الهواتف */
    @media (max-width: 768px) and (orientation: landscape) {
        .filter-tabs {
            flex-direction: row;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .filter-tab {
            flex-shrink: 0;
            min-width: 120px;
        }

        .ticket-header {
            flex-direction: row;
            align-items: center;
        }

        .ticket-actions {
            flex-direction: row;
            align-items: center;
        }
    }

    /* تحسينات للطباعة */
    @media print {
        .notifications-header,
        .filter-tabs,
        .search-container,
        .read-toggle-btn {
            display: none !important;
        }

        .notification-ticket {
            break-inside: avoid;
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            margin-bottom: 1rem;
        }

        .ticket-content {
            color: #000 !important;
        }
    }

    /* Animation Classes */
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .slide-in {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>
@endpush

@section('content')
{{-- ========== قسم HTML الخاص بالصفحة ========== --}}
<div class="notifications-page">
    <div class="notifications-container">
        <!-- Header Section -->
        <div class="notifications-header">
            <div class="header-content">
                <div class="header-title">
                    <i class="bi bi-bell-fill title-icon"></i>
                    <h1>مركز الإشعارات</h1>
                </div>
                <button id="permission-button" style="display: none;">
                    <i class="bi bi-bell-slash"></i>
                    تفعيل إشعارات المتصفح
                </button>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <div class="filters-container">
                <div class="filter-tabs">
                    <button class="filter-tab active" data-filter="all">
                        <i class="bi bi-list-ul"></i>
                        الكل
                        <span class="filter-count" id="count-all">0</span>
                    </button>
                    <button class="filter-tab" data-filter="unread">
                        <i class="bi bi-envelope"></i>
                        غير مقروء
                        <span class="filter-count" id="count-unread">0</span>
                    </button>
                    <button class="filter-tab" data-filter="read">
                        <i class="bi bi-envelope-open"></i>
                        مقروء
                        <span class="filter-count" id="count-read">0</span>
                    </button>
                </div>

                <div class="search-container">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="search-input" id="search-input" placeholder="البحث في الإشعارات...">
                    <button type="button" class="search-clear" id="search-clear" aria-label="مسح البحث">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Notifications Body -->
        <div class="notifications-body">
            <ul class="notification-list" id="notifications-list">
                {{-- سيتم ملء الإشعارات هنا عبر JavaScript --}}
            </ul>
            
            <div id="loading-state">
                <div class="loading-icon">
                    <i class="bi bi-arrow-clockwise"></i>
                </div>
                <div class="loading-message">جاري تحميل الإشعارات...</div>
                <div class="loading-description">يرجى الانتظار قليلاً</div>
            </div>
            
            <div id="empty-state" style="display: none;">
                <div class="empty-icon">
                    <i class="bi bi-bell-slash"></i>
                </div>
                <div class="empty-message">لا توجد إشعارات</div>
                <div class="empty-description">ستظهر إشعاراتك الجديدة هنا</div>
            </div>
        </div>
    </div>
</div>

<audio id="notificationSound" src="{{ asset('sounds/notification.mp3') }}" preload="auto"></audio>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log("🔔 [Notification Page] DOM fully loaded. Starting script...");
    console.log("🔍 [Debug] Current URL:", window.location.href);
    console.log("🔍 [Debug] User Agent:", navigator.userAgent);

    // --- [1] إعدادات وتحققات أساسية ---
    const API_TOKEN = localStorage.getItem('token');
    if (!API_TOKEN) {
        console.warn("⚠️ WARNING: API Token not found in localStorage. Available keys:", Object.keys(localStorage));
        console.warn("⚠️ WARNING: Page will load but API calls may fail without authentication");
        // لا نعيد التوجيه للسماح بعرض التصميم
    } else {
        console.log("✅ Token found. Length:", API_TOKEN.length, "First 10 chars:", API_TOKEN.substring(0, 10) + "...");
    }

    if (typeof Echo === 'undefined') {
        console.warn('⚠️ Laravel Echo غير متاح. سنستمر بدون إشعارات فورية.');
    } else {
        console.log("✅ Echo is defined. Type:", typeof Echo);
        console.log("✅ Echo options:", Echo.options || 'No options available');
    }

    let loggedInUser = null;
    try {
        const userDataRaw = localStorage.getItem('user');
        console.log("🔍 [Debug] Raw user data from localStorage:", userDataRaw);
        
        loggedInUser = JSON.parse(userDataRaw);
        if (!loggedInUser || !loggedInUser.id) {
            throw new Error("User data is invalid or missing ID. Data: " + JSON.stringify(loggedInUser));
        }
    } catch (e) {
        console.error("❌ CRITICAL: Failed to parse user data from localStorage.", e);
        console.error("❌ Available localStorage keys:", Object.keys(localStorage));
        return;
    }
    console.log(`✅ Logged in user found: ID ${loggedInUser.id}, Name: ${loggedInUser.name || 'N/A'}, Email: ${loggedInUser.email || 'N/A'}`);

    // --- [2] عناصر الصفحة ---
    const list = document.getElementById('notifications-list');
    const loadingState = document.getElementById('loading-state');
    const emptyState = document.getElementById('empty-state');
    const permissionBtn = document.getElementById('permission-button');
    const notificationSound = document.getElementById('notificationSound');
    const searchInput = document.getElementById('search-input');
    const filterTabs = document.querySelectorAll('.filter-tab');
    
    // متغيرات الحالة
    let allNotifications = [];
    let currentFilter = 'all';
    let searchQuery = '';
    
    // التحقق من وجود العناصر المطلوبة
    console.log("🔍 [Debug] DOM Elements check:");
    console.log("  - notifications-list:", list ? "✅ Found" : "❌ Missing");
    console.log("  - loading-state:", loadingState ? "✅ Found" : "❌ Missing");
    console.log("  - empty-state:", emptyState ? "✅ Found" : "❌ Missing");
    console.log("  - permission-button:", permissionBtn ? "✅ Found" : "❌ Missing");
    console.log("  - notificationSound:", notificationSound ? "✅ Found" : "❌ Missing");
    console.log("  - search-input:", searchInput ? "✅ Found" : "❌ Missing");
    console.log("  - filter-tabs:", filterTabs.length, "tabs found");
    
    if (!list || !loadingState || !emptyState) {
        console.error("❌ CRITICAL: Required DOM elements are missing. Cannot proceed.");
        return;
    }
    
    // --- [3] منطق إشعارات المتصفح ---
    function handleBrowserNotifications() {
        console.log("🚦 [Browser Notifications] Checking permissions...");
        if (!("Notification" in window)) {
            console.log("This browser does not support desktop notification");
            return;
        }

        if (Notification.permission === 'default') {
            permissionBtn.style.display = 'block';
            permissionBtn.onclick = () => {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        permissionBtn.style.display = 'none';
                        showDesktopNotification('رائع!', 'تم تفعيل إشعارات المتصفح بنجاح.');
                    }
                });
            };
        } else if (Notification.permission === 'granted') {
            console.log("Notification permission already granted.");
        }
    }

    function showDesktopNotification(title, body) {
        if (Notification.permission === 'granted') {
            new Notification(title, { body: body, icon: "{{ asset('images/logo.png') }}" });
        }
    }

    // --- [4] دوال مساعدة ---
    function timeAgo(date) {
        const seconds = Math.floor((new Date() - new Date(date)) / 1000);
        let interval = seconds / 31536000;
        if (interval > 1) return `منذ ${Math.floor(interval)} سنة`;
        interval = seconds / 2592000;
        if (interval > 1) return `منذ ${Math.floor(interval)} شهر`;
        interval = seconds / 86400;
        if (interval > 1) return `منذ ${Math.floor(interval)} يوم`;
        interval = seconds / 3600;
        if (interval > 1) return `منذ ${Math.floor(interval)} ساعة`;
        interval = seconds / 60;
        if (interval > 1) return `منذ ${Math.floor(interval)} دقيقة`;
        return `منذ ثوانٍ قليلة`;
    }

    function getNotificationIcon(type) {
        if (type.includes('order')) return { icon: 'bi-cart-check-fill', class: 'order' };
        if (type.includes('appointment')) return { icon: 'bi-calendar2-check-fill', class: 'appointment' };
        if (type.includes('chat')) return { icon: 'bi-chat-dots-fill', class: 'chat' };
        if (type.includes('system')) return { icon: 'bi-gear-fill', class: 'system' };
        return { icon: 'bi-info-circle-fill', class: 'system' };
    }

    function getNotificationTypeClass(type) {
        if (type.includes('order')) return 'type-order';
        if (type.includes('appointment')) return 'type-appointment';
        if (type.includes('chat')) return 'type-chat';
        return 'type-system';
    }

    function getNotificationTypeLabel(type) {
        if (type.includes('order')) return 'طلب';
        if (type.includes('appointment')) return 'موعد';
        if (type.includes('chat')) return 'رسالة';
        return 'نظام';
    }
    
    function createNotificationTicket(notification) {
        const ticket = document.createElement('li');
        ticket.className = `notification-ticket fade-in ${!notification.is_read ? 'unread' : ''}`;
        ticket.dataset.id = notification.id;
        ticket.dataset.type = notification.type;
        ticket.dataset.isRead = notification.is_read ? 'true' : 'false';
        
        const iconInfo = getNotificationIcon(notification.type);
        const typeClass = getNotificationTypeClass(notification.type);
        const typeLabel = getNotificationTypeLabel(notification.type);
        
        // دعم الرابط: إذا توفر notification.link نخزنه على العنصر
        if (notification.link) {
            ticket.dataset.link = notification.link;
        }

        ticket.innerHTML = `
            <div class="ticket-header">
                <div class="ticket-icon-wrapper">
                    <div class="ticket-icon ${iconInfo.class}">
                        <i class="bi ${iconInfo.icon}"></i>
                    </div>
                </div>
                <div class="ticket-meta">
                    <span class="ticket-time">${timeAgo(notification.created_at)}</span>
                    <span class="ticket-status ${notification.is_read ? 'read' : 'unread'}">
                        ${notification.is_read ? 'مقروء' : 'جديد'}
                    </span>
                </div>
            </div>
            
            <div class="ticket-content">
                <h3 class="ticket-title">${notification.title}</h3>
                <p class="ticket-message">${notification.message}</p>
            </div>
            
            <div class="ticket-actions">
                <span class="ticket-type-badge ${typeClass}">${typeLabel}</span>
                <button class="read-toggle-btn ${notification.is_read ? 'mark-unread' : 'mark-read'}" 
                        onclick="toggleReadStatus(${notification.id}, ${!notification.is_read})">
                    <i class="bi ${notification.is_read ? 'bi-envelope' : 'bi-envelope-open'}"></i>
                    ${notification.is_read ? 'تحديد كغير مقروء' : 'تحديد كمقروء'}
                </button>
            </div>
        `;
    
        return ticket;
    }

    // --- [5] دوال الفلترة والبحث ---
    function updateCounts() {
        const total = allNotifications.length;
        const unread = allNotifications.filter(n => !n.is_read).length;
        const read = total - unread;
        
        document.getElementById('count-all').textContent = total;
        document.getElementById('count-unread').textContent = unread;
        document.getElementById('count-read').textContent = read;
    }

    function filterNotifications() {
        let filtered = allNotifications;
        
        // تطبيق فلتر الحالة
        if (currentFilter === 'unread') {
            filtered = filtered.filter(n => !n.is_read);
        } else if (currentFilter === 'read') {
            filtered = filtered.filter(n => n.is_read);
        }
        
        // تطبيق البحث
        if (searchQuery.trim()) {
            const query = searchQuery.toLowerCase().trim();
            filtered = filtered.filter(n => 
                n.title.toLowerCase().includes(query) || 
                n.message.toLowerCase().includes(query) ||
                n.type.toLowerCase().includes(query)
            );
        }
        
        return filtered;
    }

    function renderNotifications() {
        const filtered = filterNotifications();
        
        list.innerHTML = '';
        
        if (filtered.length === 0) {
            if (allNotifications.length === 0) {
                emptyState.style.display = 'block';
                emptyState.querySelector('.empty-message').textContent = 'لا توجد إشعارات';
                emptyState.querySelector('.empty-description').textContent = 'ستظهر إشعاراتك الجديدة هنا';
            } else {
                emptyState.style.display = 'block';
                emptyState.querySelector('.empty-message').textContent = 'لا توجد نتائج';
                emptyState.querySelector('.empty-description').textContent = 'جرب تغيير الفلتر أو البحث';
            }
        } else {
            emptyState.style.display = 'none';
            filtered.forEach((notification, index) => {
                const ticket = createNotificationTicket(notification);
                ticket.style.animationDelay = `${index * 0.1}s`;
                list.appendChild(ticket);
            });
        }
        
        updateCounts();
    }

    // --- [6] Event Listeners للفلاتر والبحث ---
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // إزالة الفئة النشطة من جميع التبويبات
            filterTabs.forEach(t => t.classList.remove('active'));
            // إضافة الفئة النشطة للتبويب المحدد
            this.classList.add('active');
            
            currentFilter = this.dataset.filter;
            renderNotifications();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchQuery = this.value;
            renderNotifications();
        });
    }

    // --- [7] دالة تبديل حالة القراءة ---
    window.toggleReadStatus = async function(notificationId, markAsRead) {
        console.log(`🔄 [Toggle] Toggling read status for notification ${notificationId} to ${markAsRead ? 'read' : 'unread'}`);
        
        try {
            if (markAsRead) {
                await markAsReadAPI(notificationId);
            } else {
                // يمكن إضافة API لتحديد كغير مقروء إذا كان متوفراً
                console.log("⚠️ [Toggle] Mark as unread API not implemented yet");
            }
            
            // تحديث البيانات المحلية
            const notification = allNotifications.find(n => n.id == notificationId);
            if (notification) {
                notification.is_read = markAsRead;
                const targetFilter = markAsRead ? 'read' : 'unread';
                // ضبط التاب النشط وفق الحالة الجديدة
                filterTabs.forEach(t => {
                    if (t.dataset.filter === targetFilter) {
                        t.classList.add('active');
                    } else {
                        t.classList.remove('active');
                    }
                });
                currentFilter = targetFilter;
                renderNotifications();
            }
            
        } catch (error) {
            console.error('💥 [Toggle] Failed to toggle read status:', error);
        }
    };
    
    // --- [8] دوال الاتصال بالـ API ---
    async function fetchNotifications() {
        console.log("📡 [API] Fetching notifications from server...");
        loadingState.style.display = 'block';
        emptyState.style.display = 'none';
        
        // إذا لم يكن هناك توكن، عرض بيانات تجريبية
        if (!API_TOKEN) {
            console.log("⚠️ [API] No token available, showing demo data");
            setTimeout(() => {
                allNotifications = [
                    {
                        id: 1,
                        title: 'إشعار تجريبي 1',
                        message: 'هذا إشعار تجريبي لاختبار التصميم',
                        type: 'info',
                        is_read: false,
                        created_at: new Date().toISOString(),
                        data: { link: '#' }
                    },
                    {
                        id: 2,
                        title: 'إشعار تجريبي 2',
                        message: 'إشعار آخر لاختبار الفلاتر',
                        type: 'success',
                        is_read: true,
                        created_at: new Date().toISOString(),
                        data: { link: '#' }
                    },
                    {
                        id: 3,
                        title: 'إشعار تجريبي 3',
                        message: 'إشعار ثالث لاختبار التصميم الجديد',
                        type: 'warning',
                        is_read: false,
                        created_at: new Date().toISOString(),
                        data: { link: '#' }
                    }
                ];
                loadingState.style.display = 'none';
                renderNotifications();
            }, 1000);
            return;
        }
        
        try {
            const response = await fetch('/api/notifications', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${API_TOKEN}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            console.log("📡 [API] Response status:", response.status);
            console.log("📡 [API] Response headers:", Object.fromEntries(response.headers.entries()));
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log("📡 [API] Response data:", data);
            
            if (data && data.status === true && Array.isArray(data.notifications)) {
                allNotifications = data.notifications;
                console.log(`✅ [API] Successfully loaded ${allNotifications.length} notifications`);
                renderNotifications();
            } else {
                throw new Error('Invalid response format: ' + JSON.stringify(data));
            }
            
        } catch (error) {
            console.error('💥 [API] Failed to fetch notifications:', error);
            emptyState.style.display = 'block';
            emptyState.querySelector('.empty-message').textContent = 'خطأ في تحميل الإشعارات';
            emptyState.querySelector('.empty-description').textContent = 'يرجى المحاولة مرة أخرى';
        } finally {
            loadingState.style.display = 'none';
        }
    }

    async function markAsReadAPI(notificationId) {
        console.log(`📡 [API] Marking notification ${notificationId} as read...`);
        
        // إذا لم يكن هناك توكن، محاكاة النجاح
        if (!API_TOKEN) {
            console.log("⚠️ [API] No token available, simulating success");
            return true;
        }
        
        try {
            const response = await fetch(`/api/notifications/${notificationId}/read`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${API_TOKEN}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            console.log("📡 [API] Mark as read response status:", response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log("📡 [API] Mark as read response:", data);
            
            if (data && data.status === true) {
                console.log(`✅ [API] Successfully marked notification ${notificationId} as read`);
                return true;
            } else {
                throw new Error('Failed to mark as read: ' + (data.message || JSON.stringify(data)));
            }
            
        } catch (error) {
            console.error(`💥 [API] Failed to mark notification ${notificationId} as read:`, error);
            throw error;
        }
    }

    // --- [9] إعداد Laravel Echo للإشعارات المباشرة ---
    function setupEcho() {
        console.log("🔊 [Echo] Setting up Laravel Echo for real-time notifications...");
        
        // إذا لم يكن هناك توكن، تخطي إعداد Echo
        if (!API_TOKEN) {
            console.log("⚠️ [Echo] No token available, skipping Echo setup");
            return;
        }
        
        // إذا لم يكن Echo متاحاً، لا نوقف الصفحة
        if (typeof Echo === 'undefined') {
            console.log("⚠️ [Echo] Echo not available, skipping real-time subscription");
            return;
        }
        
        try {
            Echo.private(`App.Models.User.${loggedInUser.id}`)
                .notification((notification) => {
                    console.log("🔔 [Echo] New notification received:", notification);
                    
                    // إضافة الإشعار الجديد إلى القائمة
                    allNotifications.unshift(notification);
                    renderNotifications();
                    
                    // تشغيل صوت الإشعار
                    if (notificationSound) {
                        notificationSound.play().catch(e => console.log("Could not play notification sound:", e));
                    }
                    
                    // إظهار إشعار المتصفح
                    showDesktopNotification(notification.title, notification.message);
                });
                
            console.log("✅ [Echo] Successfully subscribed to user notifications channel");
            
        } catch (error) {
            console.error("💥 [Echo] Failed to setup Echo:", error);
        }
    }

    // --- [10] معالجة النقر على الإشعارات ---
    list.addEventListener('click', async function(e) {
        // منع فتح الروابط عند الضغط على زر التبديل
        if (e.target.closest('.read-toggle-btn')) {
            e.stopPropagation();
            e.preventDefault();
            return;
        }
        const notificationItem = e.target.closest('.notification-ticket');
        if (!notificationItem) return;
        
        const notificationId = notificationItem.dataset.id;
        const notificationLink = notificationItem.dataset.link;
        
        console.log(`👆 [Click] Notification ${notificationId} clicked. Link: ${notificationLink || 'None'}`);
        
        // تحديد الإشعار كمقروء إذا لم يكن كذلك
        if (notificationItem.classList.contains('unread')) {
            try {
                await markAsReadAPI(notificationId);
                // تحديث البيانات المحلية
                const notification = allNotifications.find(n => n.id == notificationId);
                if (notification) {
                    notification.is_read = true;
                    renderNotifications();
                }
            } catch (error) {
                console.error('Failed to mark notification as read on click:', error);
            }
        }
        
        // التنقل إلى الرابط إذا كان متوفراً
        if (notificationLink) {
            console.log(`🔗 [Navigation] Navigating to: ${notificationLink}`);
            
            try {
                if (notificationLink.startsWith('app://')) {
                    console.log("🔗 [Navigation] Processing app:// protocol link");
                    const chatMatch = notificationLink.match(/^app:\/\/admin\/chats\/(\d+)/);
                    if (chatMatch) {
                        const userId = chatMatch[1];
                        const webLink = `/chat?user_id=${userId}`;
                        console.log("🔗 [Navigation] Converted chat link to:", webLink);
                        window.location.href = webLink;
                    } else {
                        window.location.href = notificationLink;
                    }
                } else {
                    window.location.href = notificationLink;
                }
            } catch (navigationError) {
                console.error("🔗 [Navigation] Navigation failed:", navigationError);
            }
        }
    });
    
    // --- [11] تهيئة الصفحة ---
    function initializePage() {
        console.log("🚀 [Init] Initializing notifications page...");
        
        handleBrowserNotifications();
        setupEcho();
        fetchNotifications();
        
        console.log("✅ [Init] Notifications page initialized successfully");
    }

    // بدء تشغيل الصفحة
    initializePage();
});
</script>
@endpush