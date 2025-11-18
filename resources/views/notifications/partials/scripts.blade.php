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
    
    // --- [3] منطق إشعارات المتصفح + Web Push ---
    async function handleBrowserNotifications() {
        console.log("🚦 [Browser Notifications] Checking permissions...");
        if (!("Notification" in window)) {
            console.warn("❌ هذا المتصفح لا يدعم إشعارات سطح المكتب");
            return;
        }

        // إظهار الزر إذا كانت الحالة Default
        if (Notification.permission === 'default') {
            if (permissionBtn) {
                permissionBtn.style.display = 'block';
                permissionBtn.onclick = async () => {
                    try {
                        const permission = await Notification.requestPermission();
                        console.log("🔐 Permission result:", permission);
                        if (permission === 'granted') {
                            permissionBtn.style.display = 'none';
                            // فعِّل تفضيل الدفع على الباكند
                            await enablePushPreference();
                            // سجِّل Service Worker وحاول إنشاء اشتراك Push
                            const reg = await registerServiceWorker();
                            await subscribeToPush(reg);
                            showDesktopNotification('رائع!', 'تم تفعيل إشعارات المتصفح بنجاح.');
                        } else {
                            console.warn('⚠️ المستخدم رفض الإذن بإشعارات المتصفح');
                        }
                    } catch (err) {
                        console.error('❌ فشل طلب الإذن:', err);
                    }
                };
            }
        } else if (Notification.permission === 'granted') {
            console.log("✅ الإذن مُسبقًا: سنحاول التسجيل والاشتراك تلقائيًا");
            try {
                await enablePushPreference();
                const reg = await registerServiceWorker();
                await subscribeToPush(reg);
            } catch (err) {
                console.error('❌ فشل التهيئة التلقائية:', err);
            }
        } else if (Notification.permission === 'denied') {
            console.warn('🚫 تم رفض الإذن بإشعارات المتصفح من الإعدادات');
            if (permissionBtn) {
                permissionBtn.style.display = 'block';
                permissionBtn.onclick = () => alert('الرجاء السماح بإشعارات المتصفح من إعدادات المتصفح لإتمام التفعيل.');
            }
        }
    }

    function showDesktopNotification(title, body) {
        if (Notification.permission === 'granted') {
            new Notification(title, { body: body, icon: "{{ asset('images/logo.png') }}" });
        }
    }

    // تسجيل Service Worker
    async function registerServiceWorker() {
        if (!('serviceWorker' in navigator)) {
            console.warn('❌ Service Worker غير مدعوم في هذا المتصفح');
            return null;
        }
        try {
            const reg = await navigator.serviceWorker.register('/sw.js');
            await navigator.serviceWorker.ready; // تأكيد الجاهزية
            console.log('✅ Service Worker Ready:', reg.scope);
            return reg;
        } catch (err) {
            console.error('❌ فشل تسجيل Service Worker:', err);
            return null;
        }
    }

    // تحويل Base64URL إلى Uint8Array
    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    // جلب المفتاح العام VAPID من الباكند
    async function getPublicKey() {
        try {
            const res = await fetch('/api/webpush/public-key', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': API_TOKEN ? `Bearer ${API_TOKEN}` : ''
                }
            });
            const data = await res.json();
            const key = (data && data.publicKey) ? String(data.publicKey).trim() : '';
            if (!key) {
                console.warn('⚠️ لم يتم ضبط مفتاح VAPID العام في السيرفر؛ سيتم تفعيل الإذن فقط.');
            }
            return key;
        } catch (err) {
            console.error('❌ فشل جلب المفتاح العام:', err);
            return '';
        }
    }

    // الاشتراك في PushManager وحفظ الاشتراك في الباكند
    async function subscribeToPush(reg) {
        try {
            if (!reg) return;
            const existing = await reg.pushManager.getSubscription();
            if (existing) {
                console.log('ℹ️ يوجد اشتراك Push مسبقًا');
                await saveSubscription(existing);
                return existing;
            }
            const vapidPublicKey = await getPublicKey();
            if (!vapidPublicKey) {
                console.log('ℹ️ لا يوجد مفتاح VAPID؛ نتجاوز الاشتراك حاليًا');
                return null;
            }
            const applicationServerKey = urlBase64ToUint8Array(vapidPublicKey);
            const subscription = await reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey
            });
            console.log('✅ تم إنشاء اشتراك Push جديد');
            await saveSubscription(subscription);
            return subscription;
        } catch (err) {
            console.error('❌ فشل الاشتراك في PushManager:', err);
            return null;
        }
    }

    // حفظ الاشتراك في الباكند
    async function saveSubscription(subscription) {
        try {
            const json = subscription.toJSON();
            const payload = {
                endpoint: json.endpoint,
                p256dh: json.keys && json.keys.p256dh,
                auth: json.keys && json.keys.auth,
                expirationTime: json.expirationTime || null
            };
            const res = await fetch('/api/webpush/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': API_TOKEN ? `Bearer ${API_TOKEN}` : ''
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (!res.ok || !data.status) {
                console.warn('⚠️ اشتراك Push لم يُحفظ بنجاح:', data);
            } else {
                console.log('✅ تم حفظ اشتراك Push للمستخدم');
            }
        } catch (err) {
            console.error('❌ فشل حفظ اشتراك Push في الباكند:', err);
        }
    }

    // تفعيل تفضيل استقبال إشعارات الدفع للمستخدم
    async function enablePushPreference() {
        try {
            const res = await fetch('/api/notifications/push', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': API_TOKEN ? `Bearer ${API_TOKEN}` : ''
                }
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                console.warn('⚠️ تعذر تحديث تفضيل الإشعارات في السيرفر', data);
            } else {
                console.log('✅ تم تفعيل استقبال إشعارات الدفع للمستخدم');
            }
        } catch (err) {
            console.error('❌ خطأ أثناء تفعيل تفضيل الإشعارات:', err);
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

    // --- [11] إرسال إشعار مخصص للأدمن ---
    (function setupAdminSendModal() {
        const openBtn = document.getElementById('send-notification-button');
        const modal = document.getElementById('send-notification-modal');
        const closeBtn = document.getElementById('send-modal-close');
        const eligibleView = document.getElementById('eligible-users-view');
        const composeView = document.getElementById('compose-view');
        const resultView = document.getElementById('result-view');
        const eligibleTableBody = document.querySelector('#eligible-users-table tbody');
        const eligibleLoading = document.getElementById('eligible-loading');
        const eligibleEmpty = document.getElementById('eligible-empty');
        const eligibleAuthWarning = document.getElementById('eligible-auth-warning');
        const eligibleSearchInput = document.getElementById('eligible-search');
        const eligibleSearchClear = document.getElementById('eligible-search-clear');
        const selectedUserNameEl = document.getElementById('selected-user-name');
        const backToListBtn = document.getElementById('back-to-list');
        const sendNotifBtn = document.getElementById('send-notif-btn');
        const composeError = document.getElementById('compose-error');
        const titleInput = document.getElementById('notif-title');
        const bodyInput = document.getElementById('notif-body');
        const linkInput = document.getElementById('notif-link');
        const resultNotifId = document.getElementById('result-notif-id');
        const resultBroadcasted = document.getElementById('result-broadcasted');
        const resultPushEnabled = document.getElementById('result-push-enabled');
        const resultTokensCount = document.getElementById('result-tokens-count');
        const resultSimulate = document.getElementById('result-simulate');
        const resultTokensList = document.getElementById('result-tokens-list');
        const resultJson = document.getElementById('result-json');
        const sendAnotherBtn = document.getElementById('send-another');
        const closeResultBtn = document.getElementById('close-result');

        let ELIGIBLE_USERS = [];
        let SELECTED_USER_ID = null;
        let SELECTED_USER_NAME = '';

        function openModal() {
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            // Reset state
            eligibleView.style.display = '';
            composeView.style.display = 'none';
            resultView.style.display = 'none';
            composeError.style.display = 'none';
            titleInput.value = '';
            bodyInput.value = '';
            linkInput.value = '';
            SELECTED_USER_ID = null;
            SELECTED_USER_NAME = '';
            // Load eligible users
            loadEligibleUsers('');
        }
        function closeModal() {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }

        async function loadEligibleUsers(query) {
            if (!API_TOKEN) {
                eligibleAuthWarning.style.display = 'flex';
                eligibleLoading.style.display = 'none';
                eligibleEmpty.style.display = 'none';
                eligibleTableBody.innerHTML = '';
                return;
            }
            eligibleAuthWarning.style.display = 'none';
            eligibleLoading.style.display = 'flex';
            eligibleEmpty.style.display = 'none';
            eligibleTableBody.innerHTML = '';
            try {
                const url = `/api/admin/notifications/eligible-users${query ? `?q=${encodeURIComponent(query)}` : ''}`;
                const resp = await fetch(url, {
                    headers: {
                        'Authorization': `Bearer ${API_TOKEN}`,
                        'Accept': 'application/json'
                    }
                });
                if (!resp.ok) {
                    const txt = await resp.text();
                    console.error('Eligible users request failed:', resp.status, txt);
                    if (resp.status === 401 || resp.status === 403) {
                        eligibleAuthWarning.style.display = 'flex';
                        return;
                    }
                    throw new Error(`HTTP ${resp.status}`);
                }
                const json = await resp.json();
                ELIGIBLE_USERS = Array.isArray(json.users) ? json.users : [];
                if (ELIGIBLE_USERS.length === 0) {
                    eligibleEmpty.style.display = 'flex';
                } else {
                    eligibleTableBody.innerHTML = ELIGIBLE_USERS.map(u => `
                        <tr>
                            <td>${u.name ?? '—'}</td>
                            <td>${u.phone ?? '—'}</td>
                            <td>${u.email ?? '—'}</td>
                            <td>${u.tokens_count ?? 0}</td>
                            <td><button class="select-user-btn" data-user-id="${u.id}" data-user-name="${u.name ?? ''}">اختيار</button></td>
                        </tr>
                    `).join('');
                }
            } catch (e) {
                console.error('Failed to load eligible users:', e);
                eligibleEmpty.style.display = 'flex';
            } finally {
                eligibleLoading.style.display = 'none';
            }
        }

        eligibleSearchInput?.addEventListener('input', (e) => {
            const q = e.target.value.trim();
            loadEligibleUsers(q);
        });
        eligibleSearchClear?.addEventListener('click', () => {
            eligibleSearchInput.value = '';
            loadEligibleUsers('');
        });

        eligibleTableBody?.addEventListener('click', (e) => {
            const btn = e.target.closest('.select-user-btn');
            if (!btn) return;
            SELECTED_USER_ID = Number(btn.dataset.userId);
            SELECTED_USER_NAME = String(btn.dataset.userName || '');
            selectedUserNameEl.textContent = SELECTED_USER_NAME || `مستخدم رقم ${SELECTED_USER_ID}`;
            eligibleView.style.display = 'none';
            composeView.style.display = '';
            titleInput.focus();
        });

        backToListBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            composeView.style.display = 'none';
            resultView.style.display = 'none';
            eligibleView.style.display = '';
        });

        async function sendCustomNotification() {
            composeError.style.display = 'none';
            const title = titleInput.value.trim();
            const body = bodyInput.value.trim();
            const link = linkInput.value.trim();
            if (!SELECTED_USER_ID || !title || !body) {
                composeError.textContent = 'يرجى اختيار مستخدم وإدخال العنوان والنص.';
                composeError.style.display = 'block';
                return;
            }
            if (!API_TOKEN) {
                composeError.textContent = 'يلزم تسجيل الدخول كأدمن.';
                composeError.style.display = 'block';
                return;
            }
            sendNotifBtn.disabled = true;
            sendNotifBtn.textContent = 'جاري الإرسال...';
            try {
                const resp = await fetch('/api/admin/notifications/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${API_TOKEN}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ user_id: SELECTED_USER_ID, title, body, link: link || null })
                });
                if (!resp.ok) {
                    const txt = await resp.text();
                    console.error('Send notification request failed:', resp.status, txt);
                    if (resp.status === 401 || resp.status === 403) {
                        composeError.textContent = 'غير مصرح. يلزم تسجيل الدخول كأدمن.';
                        composeError.style.display = 'block';
                        return;
                    }
                    throw new Error(`HTTP ${resp.status}`);
                }
                const json = await resp.json();
                // Populate result
                const result = json.result || {};
                const inApp = result.in_app || {};
                const push = result.push || {};
                resultNotifId.textContent = String(inApp.notification_id ?? '—');
                resultBroadcasted.textContent = inApp.broadcasted ? 'نعم' : 'لا';
                resultPushEnabled.textContent = push.enabled ? 'نعم' : 'لا';
                resultTokensCount.textContent = String(push.tokens_count ?? 0);
                resultSimulate.textContent = push.simulate ? 'نعم' : 'لا';
                // Tokens list
                const prs = push.results || {};
                const tokensHtml = Object.keys(prs).length === 0
                    ? '<div>لا نتائج للإرسال</div>'
                    : '<ul class="token-results">' + Object.entries(prs).map(([tok, res]) => {
                        const isErr = res && typeof res === 'object' && 'error' in res;
                        const badge = isErr ? '<span style="color:#b91c1c">فشل</span>' : '<span style="color:#16a34a">نجاح</span>';
                        return `<li><code>${tok}</code> — ${badge}</li>`;
                    }).join('') + '</ul>';
                resultTokensList.innerHTML = tokensHtml;
                // Raw JSON
                resultJson.textContent = JSON.stringify(json, null, 2);
                composeView.style.display = 'none';
                resultView.style.display = '';
            } catch (e) {
                composeError.textContent = 'فشل الإرسال. حاول مرة أخرى.';
                composeError.style.display = 'block';
                console.error('Send custom notification failed:', e);
            } finally {
                sendNotifBtn.disabled = false;
                sendNotifBtn.textContent = 'إرسال';
            }
        }

        sendNotifBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            sendCustomNotification();
        });
        sendAnotherBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            resultView.style.display = 'none';
            eligibleView.style.display = '';
            SELECTED_USER_ID = null;
            SELECTED_USER_NAME = '';
            titleInput.value = '';
            bodyInput.value = '';
            linkInput.value = '';
        });
        closeResultBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            closeModal();
        });

        openBtn?.addEventListener('click', openModal);
        closeBtn?.addEventListener('click', closeModal);
        modal?.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    })();
    
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