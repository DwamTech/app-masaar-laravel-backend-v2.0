@extends('layouts.dashboard')

@section('title', 'الإشعارات')

@push('styles')
{{-- ========== قسم الأنماط (CSS) الخاص بالصفحة ========== --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    :root {
        --primary-color: #0d6efd;
        --light-gray: #f8f9fa;
        --border-color: #dee2e6;
        --text-muted: #6c757d;
        --white: #ffffff;
        --header-gradient: linear-gradient(180deg, rgba(13,110,253,0.12), rgba(13,110,253,0));
    }
    .notifications-container {
        max-width: 900px;
        margin: 2rem auto;
        background: var(--white);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    .notifications-header {
        padding: 1.25rem 1.5rem 0.75rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        background: var(--header-gradient);
    }
    .notifications-header h4 {
        margin: 0;
        color: #343a40;
    }
    #permission-button {
        background-color: transparent;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    #permission-button:hover {
        background-color: var(--primary-color);
        color: var(--white);
    }
    #permission-button i {
        margin-left: 0.5rem;
    }
    .notifications-toolbar {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-top: .75rem;
        flex-wrap: wrap;
    }
    .search-input {
        flex: 1;
        min-width: 240px;
        padding: .5rem .75rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background: var(--white);
    }
    .filter-pills {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
    }
    .filter-pill {
        padding: .4rem .75rem;
        border: 1px solid var(--border-color);
        border-radius: 999px;
        background: var(--light-gray);
        color: #343a40;
        cursor: pointer;
        user-select: none;
        font-size: .9rem;
    }
    .filter-pill.active {
        background: rgba(13,110,253,.12);
        border-color: var(--primary-color);
        color: #0b5ed7;
    }
    .mark-all-button {
        margin-right: .5rem;
        background-color: transparent;
        border: 1px solid var(--border-color);
        color: #343a40;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .mark-all-button:hover { background-color: var(--light-gray); }
    .notifications-body { max-height: 70vh; overflow-y: auto; }
    .notification-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .notification-item {
        display: flex;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #eef0f2;
        transition: background-color 0.2s ease;
        cursor: pointer;
    }
    .notification-item:last-child {
        border-bottom: none;
    }
    .notification-item:hover {
        background-color: #f1f3f5;
    }
    .notification-item.unread {
        background-color: #e7f1ff;
        font-weight: 500;
    }
    .notification-icon {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-left: 1.5rem;
    }
    .notification-content {
        flex-grow: 1;
    }
    .notification-content p {
        margin: 0;
        color: #495057;
        line-height: 1.6;
    }
    .notification-content span {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    #empty-state, #loading-state {
        padding: 4rem;
        text-align: center;
        color: var(--text-muted);
    }
    #empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
{{-- ========== قسم HTML الخاص بالصفحة ========== --}}
<div class="notifications-container">
    <div class="notifications-header">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="mb-0">الإشعارات</h4>
            <div class="d-flex align-items-center gap-2">
                <button id="mark-all-read" class="mark-all-button" type="button">
                    <i class="bi bi-check2-all"></i>
                    تعليم الكل كمقروء
                </button>
                <button id="permission-button" style="display: none;">
                    <i class="bi bi-bell-slash"></i>
                    تفعيل إشعارات المتصفح
                </button>
            </div>
        </div>
        <div class="notifications-toolbar">
            <input id="notifSearch" type="text" class="search-input" placeholder="ابحث بالعنوان أو المحتوى" />
            <div id="notifFilters" class="filter-pills">
                <span class="filter-pill active" data-type="all">الكل</span>
                <span class="filter-pill" data-type="order">أوامر</span>
                <span class="filter-pill" data-type="appointment">مواعيد</span>
                <span class="filter-pill" data-type="chat">محادثة</span>
                <span class="filter-pill" data-type="other">أخرى</span>
            </div>
        </div>
    </div>
    <div class="notifications-body">
        <ul class="notification-list" id="notifications-list">
            {{-- سيتم ملء الإشعارات هنا عبر JavaScript --}}
        </ul>
        <div id="loading-state">
            <p>جاري تحميل الإشعارات...</p>
        </div>
        <div id="empty-state" style="display: none;">
            <i class="bi bi-bell-slash"></i>
            <p>لا توجد لديك إشعارات بعد.</p>
        </div>
    </div>
</div>
<audio id="notificationSound" src="{{ asset('sounds/notification.mp3') }}" preload="auto"></audio>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log("🔔 [Notification Page] DOM fully loaded. Starting script...");

    // --- [1] إعدادات وتحققات أساسية ---
    const API_TOKEN = localStorage.getItem('token');
    if (!API_TOKEN) {
        console.error("❌ CRITICAL: API Token not found in localStorage. Redirecting to login.");
        window.location.href = '/login';
        return;
    }
    console.log("✅ Token found.");

    if (typeof Echo === 'undefined') {
        console.error('❌ CRITICAL: Laravel Echo is not defined! Make sure bootstrap.js (or app.js) is loaded before this script.');
        return;
    }
    console.log("✅ Echo is defined.");

    let loggedInUser = null;
    try {
        loggedInUser = JSON.parse(localStorage.getItem('user'));
        if (!loggedInUser || !loggedInUser.id) throw new Error("User data is invalid or missing ID.");
    } catch (e) {
        console.error("❌ CRITICAL: Failed to parse user data from localStorage.", e);
        return;
    }
    console.log(`✅ Logged in user found: ID ${loggedInUser.id}, Name: ${loggedInUser.name}`);

    // --- [2] عناصر الصفحة ---
    const list = document.getElementById('notifications-list');
    const loadingState = document.getElementById('loading-state');
    const emptyState = document.getElementById('empty-state');
    const permissionBtn = document.getElementById('permission-button');
    const notificationSound = document.getElementById('notificationSound');
    const searchInput = document.getElementById('notifSearch');
    const filtersWrap = document.getElementById('notifFilters');
    const markAllBtn = document.getElementById('mark-all-read');
    
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
    
    function createNotificationElement(notification) {
        const item = document.createElement('li');
        item.className = 'notification-item';
        item.dataset.id = notification.id;
        if (!notification.is_read) { item.classList.add('unread'); }
        let iconClass = 'bi-info-circle-fill';
        if (notification.type.includes('order')) iconClass = 'bi-cart-check-fill';
        if (notification.type.includes('appointment')) iconClass = 'bi-calendar2-check-fill';
        if (notification.type.includes('chat')) iconClass = 'bi-chat-dots-fill';
        const unreadDot = (!notification.is_read) ? '<span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#0d6efd;margin-right:.5rem;"></span>' : '';
        item.innerHTML = `
            <div class="notification-icon"><i class="bi ${iconClass}"></i></div>
            <div class="notification-content">
                <p><strong>${notification.title}</strong> ${unreadDot}<br>${notification.message}</p>
                <span>${timeAgo(notification.created_at)}</span>
            </div>`;

        // دعم الرابط: إذا توفر notification.link نخزنه على العنصر
        if (notification.link) {
            item.dataset.link = notification.link;
        }

        return item;
    }
    
    // --- [5] دوال الاتصال بالـ API ---
    let allNotifications = [];
    let activeType = 'all';
    let searchQuery = '';

    async function fetchNotifications() {
        console.log("⏳ [API] Attempting to fetch notifications from '/api/notifications'...");
        try {
            const response = await fetch('/api/notifications', {
                headers: { 'Authorization': `Bearer ${API_TOKEN}`, 'Accept': 'application/json' }
            });

            console.log(`📡 [API] Response received with Status Code: ${response.status}`);
            if (!response.ok) {
                const errorBody = await response.text();
                console.error(`❌ [API] Error fetching notifications. Status: ${response.status}`, {body: errorBody});
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            console.log("📦 [API] Successfully parsed JSON response:", result);

            loadingState.style.display = 'none';

            allNotifications = (result.notifications && Array.isArray(result.notifications)) ? result.notifications : [];
            applyFilters();
            if (allNotifications.length === 0) { emptyState.style.display = 'block'; }
        } catch (error) {
            console.error("💥 [Catch] An exception occurred during fetchNotifications:", error);
            loadingState.style.display = 'block';
            loadingState.innerHTML = '<p>حدث خطأ فادح أثناء تحميل الإشعارات. تحقق من الـ Console لمزيد من التفاصيل.</p>';
        }
    }

    async function markAsReadAPI(id) {
        console.log(`⏳ [API] Marking notification #${id} as read...`);
        try {
            await fetch(`/api/notifications/${id}/read`, {
                method: 'PUT',
                headers: { 'Authorization': `Bearer ${API_TOKEN}`, 'Accept': 'application/json' }
            });
            console.log(`✅ [API] Notification #${id} marked as read.`);
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
        }
    }

    // --- [6] Event Listeners ---
    list.addEventListener('click', function(e) {
        const item = e.target.closest('.notification-item');
        if (!item) return;
    
        // فتح الرابط إن توفر
        const link = item.dataset.link;
        if (link) {
            try {
                if (link.startsWith('app://')) {
                    // نحاول تحويله إلى رابط ويب مكافئ إذا كان يشير إلى محادثة الشات
                    // مثال: app://admin/chats/123 => /chat?user_id=123
                    const chatMatch = link.match(/^app:\/\/admin\/chats\/(\d+)/);
                    if (chatMatch) {
                        const userId = chatMatch[1];
                        window.location.href = `/chat?user_id=${userId}`;
                    } else {
                        // روابط app:// أخرى - نحاول فتحها كما هي (قد تعتمد على بروتوكول مخصص على الجهاز)
                        window.location.href = link;
                    }
                } else if (link.startsWith('/')) {
                    // رابط نسبي داخل الموقع
                    window.location.href = link;
                } else if (/^https?:\/\//i.test(link)) {
                    // رابط مطلق
                    window.location.href = link;
                }
            } catch (_) { /* ignore navigation errors */ }
        }
    
        if (item.classList.contains('unread')) {
            const notificationId = item.dataset.id;
            item.classList.remove('unread');
            markAsReadAPI(notificationId);
            const idx = allNotifications.findIndex(n => String(n.id) === String(notificationId));
            if (idx >= 0) allNotifications[idx].is_read = true;
        }
    });

    // البحث والفلاتر
    let searchDebounce;
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => { searchQuery = e.target.value; applyFilters(); }, 200);
    });
    filtersWrap.addEventListener('click', (e) => {
        const pill = e.target.closest('.filter-pill');
        if (!pill) return;
        [...filtersWrap.children].forEach(p => p.classList.remove('active'));
        pill.classList.add('active');
        activeType = pill.dataset.type || 'all';
        applyFilters();
    });

    function applyFilters() {
        const q = (searchQuery || '').trim().toLowerCase();
        const filtered = allNotifications.filter(n => {
            const t = (n.type || '').toLowerCase();
            const typeOk = activeType === 'all' ? true : (
                (activeType === 'order' && t.includes('order')) ||
                (activeType === 'appointment' && t.includes('appointment')) ||
                (activeType === 'chat' && (t.includes('chat') || t.includes('message')))
            ) || (activeType === 'other' && !(t.includes('order') || t.includes('appointment') || t.includes('chat') || t.includes('message')));
            if (!typeOk) return false;
            if (!q) return true;
            const text = `${(n.title||'').toLowerCase()} ${(n.message||'').toLowerCase()}`;
            return text.includes(q);
        });
        list.innerHTML = '';
        filtered.forEach(n => list.appendChild(createNotificationElement(n)));
        emptyState.style.display = filtered.length ? 'none' : 'block';
    }

    // تعليم الكل كمقروء
    markAllBtn.addEventListener('click', async () => {
        const unread = allNotifications.filter(n => !n.is_read);
        if (unread.length === 0) return;
        try {
            await Promise.all(unread.map(n => markAsReadAPI(n.id)));
            unread.forEach(n => n.is_read = true);
            applyFilters();
        } catch (err) {
            console.error('Failed to mark all as read:', err);
        }
    });
    
    // --- [7] إعداد Echo والاستماع للأحداث ---
    function setupEcho() {
        console.log("🎧 [Echo] Setting up real-time listener...");
        try {
            const channelName = `App.Models.User.${loggedInUser.id}`;
            Echo.private(channelName)
                .listen('.new-notification', (event) => {
                    console.log('⚡️ [Echo] New notification received via Echo:', event);
                    notificationSound.play().catch(e => console.warn("Could not play sound:", e));
                    showDesktopNotification(event.notification.title, event.notification.message);
                    emptyState.style.display = 'none';
                    allNotifications.unshift(event.notification);
                    applyFilters();
                });
            console.log(`✅ [Echo] Successfully listening for notifications on channel: ${channelName}`);
        } catch (e) {
            console.error("💥 [Echo] An exception occurred during Echo setup:", e);
        }
    }

    // --- [8] بدء التشغيل ---
    console.log("🚀 [Startup] Initializing page functions...");
    handleBrowserNotifications();
    fetchNotifications();
    setupEcho();
});
</script>
@endpush