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

    .header-actions {
        display: flex;
        gap: .5rem;
        align-items: center;
    }

    .send-notification-btn {
        background: var(--white);
        color: var(--primary-color);
        border: none;
        padding: .6rem .9rem;
        border-radius: 10px;
        box-shadow: var(--shadow-md);
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        cursor: pointer;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .send-notification-btn:hover { transform: translateY(-1px); box-shadow: var(--shadow-lg); }
    .send-notification-btn i { font-size: 1rem; }

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

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.35);
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal {
        width: min(900px, 96vw);
        background: var(--white);
        border-radius: 16px;
        box-shadow: var(--shadow-xl);
        overflow: hidden;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem;
        background: var(--light-gray);
        border-bottom: 1px solid var(--border-color);
    }
    .modal-title { display: flex; align-items: center; gap: .5rem; font-weight: 600; }
    .modal-close { background: transparent; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.25rem; }
    .modal-close:hover { color: var(--primary-color); }
    .modal-body { padding: 1rem 1.25rem; }

    .eligible-search-bar { position: relative; display: flex; gap: .5rem; margin-bottom: .75rem; }
    .eligible-search-bar input { flex: 1; padding: .6rem .75rem; border-radius: 10px; border: 1px solid var(--border-color); }
    .eligible-search-bar button { border: none; background: transparent; color: var(--text-muted); cursor: pointer; }
    .eligible-info { display: flex; align-items: center; gap: .5rem; color: var(--text-muted); margin-bottom: .75rem; }
    .eligible-table-wrapper { position: relative; }
    .eligible-table { width: 100%; border-collapse: collapse; }
    .eligible-table th, .eligible-table td { text-align: right; padding: .5rem .6rem; border-bottom: 1px solid var(--border-color); }
    .eligible-table th { background: var(--light-gray); font-weight: 600; }
    .eligible-loading, .eligible-empty, .eligible-warning { display: flex; align-items: center; gap: .5rem; color: var(--text-muted); padding: .5rem 0; }
    .select-user-btn { background: var(--primary-color); color: var(--white); border: none; padding: .4rem .6rem; border-radius: 8px; cursor: pointer; }

    .compose-target { display: flex; align-items: center; gap: .5rem; margin-bottom: .75rem; }
    .compose-form label { display: block; margin-top: .5rem; margin-bottom: .25rem; font-weight: 600; }
    .compose-form input, .compose-form textarea { width: 100%; padding: .6rem .75rem; border-radius: 10px; border: 1px solid var(--border-color); }
    .compose-actions { display: flex; justify-content: space-between; gap: .5rem; margin-top: .75rem; }
    .primary-btn { background: var(--primary-color); color: var(--white); border: none; padding: .6rem .9rem; border-radius: 10px; cursor: pointer; }
    .secondary-btn { background: var(--light-gray); color: var(--text-color); border: none; padding: .6rem .9rem; border-radius: 10px; cursor: pointer; }
    .compose-error { color: #b91c1c; background: #fee2e2; border: 1px solid #fca5a5; padding: .5rem .75rem; border-radius: 10px; }

    .result-summary { display: flex; align-items: center; gap: .5rem; font-weight: 600; margin-bottom: .5rem; }
    .result-panels { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
    .panel { border: 1px solid var(--border-color); border-radius: 10px; overflow: hidden; }
    .panel-title { background: var(--light-gray); padding: .5rem .75rem; font-weight: 600; }
    .panel-body { padding: .5rem .75rem; }
    .result-raw pre { background: #0f172a; color: #e5e7eb; padding: .75rem; border-radius: 10px; overflow: auto; max-height: 260px; }


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