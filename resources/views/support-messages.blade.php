@extends('layouts.dashboard')

@section('title', 'استقبال رسائل الدعم الفني')

@section('content')
<style>
    :root {
        --primary-blue: #2563eb;
        --secondary-blue: #3b82f6;
        --light-blue: #dbeafe;
        --dark-blue: #1e40af;
        --accent-blue: #60a5fa;
        --gradient-blue: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #60a5fa 100%);
        --shadow-blue: rgba(37, 99, 235, 0.15);
        --hover-blue: rgba(37, 99, 235, 0.1);
    }

    .support-header {
        background: var(--gradient-blue);
        color: white;
        padding: 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px var(--shadow-blue);
        position: relative;
        overflow: hidden;
    }

    .support-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    .support-title {
        font-size: 2.2rem;
        font-weight: 800;
        text-align: center;
    }

    .support-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0.5rem 0 0 0;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-blue);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px var(--shadow-blue);
        border-color: var(--primary-blue);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-blue);
        margin: 0;
    }

    .stat-label {
        color: #6b7280;
        font-weight: 600;
        margin: 0.5rem 0 0 0;
    }

    .search-container {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .search-box {
        position: relative;
        max-width: 500px;
    }

    .search-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f9fafb;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px var(--hover-blue);
        background: white;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1.2rem;
    }

    .tabs-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .nav-tabs {
        border: none;
        background: #f8fafc;
        padding: 0.5rem;
        display: flex;
        width: 100%;
    }

    .nav-tabs .nav-item {
        flex: 1;
    }

    .nav-tabs .nav-link {
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-weight: 600;
        color: #6b7280;
        transition: all 0.3s ease;
        margin: 0 0.25rem;
        position: relative;
        width: 100%;
        text-align: center;
    }

    .nav-tabs .nav-link.active {
        background: var(--gradient-blue);
        color: white;
        box-shadow: 0 4px 15px var(--shadow-blue);
    }

    .nav-tabs .nav-link:hover:not(.active) {
        background: var(--hover-blue);
        color: var(--primary-blue);
    }

    .tab-content {
        padding: 2rem;
    }

    .messages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    @media (min-width: 768px) {
        .messages-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 767px) {
        .messages-grid {
            grid-template-columns: 1fr;
        }
    }

    .message-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .message-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--gradient-blue);
    }

    .message-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        border-color: var(--light-blue);
    }

    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        background: var(--gradient-blue);
        padding: 1rem;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }

    .message-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        animation: shimmer 3s infinite;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        backdrop-filter: blur(10px);
    }

    .user-details h6 {
        margin: 0;
        color: white;
        font-weight: 700;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .message-date {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        margin: 0.25rem 0 0 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .message-date::before {
        content: '\f017';
        font-family: 'bootstrap-icons';
        font-size: 0.8rem;
    }

    .priority-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .priority-high {
        background: #fee2e2;
        color: #dc2626;
    }

    .priority-medium {
        background: #fef3c7;
        color: #d97706;
    }

    .priority-low {
        background: #d1fae5;
        color: #059669;
    }

    .message-subject {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.75rem;
    }

    .message-preview {
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .message-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    .btn-modern {
        padding: 0.8rem 1.8rem;
        border-radius: 25px;
        font-weight: 700;
        border: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        position: relative;
        overflow: hidden;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        min-width: 140px;
    }

    .btn-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s ease;
    }

    .btn-modern:hover::before {
        left: 100%;
    }

    .btn-details {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 50%, #991b1b 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.3);
    }

    .btn-details:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 30px rgba(220, 38, 38, 0.4);
        background: linear-gradient(135deg, #b91c1c 0%, #991b1b 50%, #7f1d1d 100%);
    }

    .btn-details:active {
        transform: translateY(-1px) scale(1.02);
    }

    .btn-read {
        background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3);
    }

    .btn-read:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 30px rgba(5, 150, 105, 0.4);
        background: linear-gradient(135deg, #047857 0%, #065f46 50%, #064e3b 100%);
    }

    .btn-read:active {
        transform: translateY(-1px) scale(1.02);
    }

    .btn-read:disabled {
        background: linear-gradient(135deg, #9ca3af 0%, #6b7280 50%, #4b5563 100%);
        cursor: not-allowed;
        transform: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-read:disabled:hover {
        transform: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }

    .empty-icon {
        font-size: 4rem;
        color: var(--light-blue);
        margin-bottom: 1rem;
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f4f6;
        border-radius: 50%;
        border-top-color: var(--primary-blue);
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: var(--gradient-blue);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 1.5rem 2rem;
    }

    .modal-title {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-close {
        filter: invert(1);
    }

    .modal-body {
        padding: 2rem;
    }

    .message-full-content {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid var(--primary-blue);
        line-height: 1.8;
    }

    @media (max-width: 768px) {
        .support-title {
            font-size: 1.8rem;
        }
        
        .stats-container {
            grid-template-columns: 1fr;
        }
        
        .message-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .message-actions {
            justify-content: flex-start;
        }
    }
</style>

<!-- Header Section -->
<div class="support-header text-center">
    <h1 class="support-title">
        <i class="bi bi-headset"></i>
        استقبال رسائل الدعم الفني
    </h1>
    <p class="support-subtitle">إدارة وتتبع جميع رسائل الدعم الفني من المستخدمين</p>
</div>

<!-- Statistics Cards -->
<div class="stats-container">
    <div class="stat-card">
        <h3 class="stat-number" id="unreadCount">0</h3>
        <p class="stat-label">الرسائل غير المقروءة</p>
    </div>
    <div class="stat-card">
        <h3 class="stat-number" id="readCount">0</h3>
        <p class="stat-label">الرسائل المقروءة</p>
    </div>
    <div class="stat-card">
        <h3 class="stat-number" id="totalCount">0</h3>
        <p class="stat-label">إجمالي الرسائل</p>
    </div>
</div>

<!-- Search Section -->
<div class="search-container">
    <div class="search-box">
        <i class="bi bi-search search-icon"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="البحث في الرسائل أو أسماء المستخدمين...">
    </div>
</div>

<!-- Tabs Container -->
<div class="tabs-container">
    <ul class="nav nav-tabs" id="messagesTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab">
                <i class="bi bi-envelope"></i>
                الرسائل غير المقروءة
                <span class="badge bg-danger ms-2" id="unreadBadge">0</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="read-tab" data-bs-toggle="tab" data-bs-target="#read" type="button" role="tab">
                <i class="bi bi-envelope-open"></i>
                الرسائل المقروءة
                <span class="badge bg-success ms-2" id="readBadge">0</span>
            </button>
        </li>
    </ul>
    
    <div class="tab-content" id="messagesTabContent">
        <!-- Unread Messages Tab -->
        <div class="tab-pane fade show active" id="unread" role="tabpanel">
            <div class="messages-grid" id="unreadMessages">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <h5>لا توجد رسائل غير مقروءة</h5>
                    <p>جميع الرسائل تم قراءتها</p>
                </div>
            </div>
        </div>
        
        <!-- Read Messages Tab -->
        <div class="tab-pane fade" id="read" role="tabpanel">
            <div class="messages-grid" id="readMessages">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-envelope-open"></i>
                    </div>
                    <h5>لا توجد رسائل مقروءة</h5>
                    <p>لم يتم قراءة أي رسائل بعد</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message Details Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">
                    <i class="bi bi-envelope-open"></i>
                    تفاصيل الرسالة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="user-info mb-3">
                    <div class="user-avatar" id="modalUserAvatar">A</div>
                    <div class="user-details">
                        <h6 id="modalUserName">اسم المستخدم</h6>
                        <p class="message-date" id="modalMessageDate">تاريخ الإرسال</p>
                    </div>
                </div>
                <h6 class="message-subject" id="modalMessageSubject">عنوان الرسالة</h6>
                <div class="message-full-content" id="modalMessageContent">
                    محتوى الرسالة سيظهر هنا...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let allMessages = [];
let filteredMessages = [];
let currentSearch = '';

// تحميل الرسائل عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    loadMessages();
    
    // إعداد البحث
    document.getElementById('searchInput').addEventListener('input', function(e) {
        currentSearch = e.target.value.toLowerCase();
        filterAndDisplayMessages();
    });
});

// تحميل الرسائل من الخادم
async function loadMessages() {
    // بيانات وهمية للاختبار
    const dummyData = {
        status: true,
        messages: [
            {
                id: 1,
                user_name: 'أحمد محمد علي',
                subject: 'مشكلة في تسجيل الدخول',
                description: 'أواجه صعوبة في تسجيل الدخول إلى حسابي منذ يومين. أحاول إدخال البيانات الصحيحة ولكن النظام يرفض الدخول ويظهر رسالة خطأ غير واضحة. أرجو المساعدة في حل هذه المشكلة في أقرب وقت ممكن.',
                priority: 'عالية',
                is_read: false,
                sent_date: '2024-01-15 14:30:00'
            },
            {
                id: 2,
                user_name: 'فاطمة أحمد',
                subject: 'استفسار حول الخدمات المتاحة',
                description: 'أود الاستفسار عن الخدمات المتاحة في التطبيق وكيفية الاستفادة منها. هل يمكنكم إرسال دليل مفصل أو شرح مبسط؟',
                priority: 'متوسطة',
                is_read: false,
                sent_date: '2024-01-15 12:15:00'
            },
            {
                id: 3,
                user_name: 'محمد عبدالله',
                subject: 'طلب تحديث البيانات الشخصية',
                description: 'أحتاج إلى تحديث بياناتي الشخصية في النظام. كيف يمكنني القيام بذلك؟',
                priority: 'منخفضة',
                is_read: true,
                sent_date: '2024-01-14 16:45:00'
            },
            {
                id: 4,
                user_name: 'سارة خالد',
                subject: 'خطأ في معالجة الدفع',
                description: 'حدث خطأ أثناء عملية الدفع وتم خصم المبلغ من حسابي ولكن لم تكتمل العملية. أرجو المراجعة والحل السريع لأن هذا الأمر عاجل جداً.',
                priority: 'عالية',
                is_read: false,
                sent_date: '2024-01-15 09:20:00'
            },
            {
                id: 5,
                user_name: 'عمر حسن',
                subject: 'اقتراح تحسين التطبيق',
                description: 'لدي بعض الاقتراحات لتحسين واجهة المستخدم وجعل التطبيق أكثر سهولة في الاستخدام.',
                priority: 'منخفضة',
                is_read: true,
                sent_date: '2024-01-13 11:30:00'
            },
            {
                id: 6,
                user_name: 'نور الدين أحمد',
                subject: 'مشكلة في تحميل الصور',
                description: 'لا أستطيع تحميل الصور في التطبيق. تظهر رسالة خطأ في كل مرة أحاول فيها رفع صورة.',
                priority: 'متوسطة',
                is_read: false,
                sent_date: '2024-01-15 08:10:00'
            },
            {
                id: 7,
                user_name: 'ليلى محمود',
                subject: 'شكر وتقدير',
                description: 'أود أن أشكركم على الخدمة الممتازة والدعم السريع. التطبيق يعمل بشكل رائع الآن.',
                priority: 'منخفضة',
                is_read: true,
                sent_date: '2024-01-12 15:20:00'
            },
            {
                id: 8,
                user_name: 'يوسف عبدالرحمن',
                subject: 'مشكلة في الإشعارات',
                description: 'لا أتلقى إشعارات التطبيق على هاتفي رغم تفعيل جميع الإعدادات. أرجو المساعدة في حل هذه المشكلة.',
                priority: 'متوسطة',
                is_read: false,
                sent_date: '2024-01-14 13:45:00'
            }
        ],
        unread_count: 5,
        read_count: 3
    };
    
    // محاكاة تأخير الشبكة
    await new Promise(resolve => setTimeout(resolve, 500));
    
    allMessages = dummyData.messages;
    updateStatistics(dummyData.unread_count, dummyData.read_count);
    filterAndDisplayMessages();
    
    /* الكود الأصلي للاتصال بالخادم - معطل مؤقتاً
    try {
        const response = await fetch('/api/support-messages', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) {
            throw new Error('فشل في تحميل الرسائل');
        }
        
        const data = await response.json();
        
        if (data.status) {
            allMessages = data.messages;
            updateStatistics(data.unread_count, data.read_count);
            filterAndDisplayMessages();
        } else {
            showError('فشل في تحميل الرسائل');
        }
    } catch (error) {
        console.error('خطأ في تحميل الرسائل:', error);
        showError('حدث خطأ في تحميل الرسائل');
    }
    */
}

// تحديث الإحصائيات
function updateStatistics(unreadCount, readCount) {
    document.getElementById('unreadCount').textContent = unreadCount;
    document.getElementById('readCount').textContent = readCount;
    document.getElementById('totalCount').textContent = unreadCount + readCount;
    document.getElementById('unreadBadge').textContent = unreadCount;
    document.getElementById('readBadge').textContent = readCount;
}

// فلترة وعرض الرسائل
function filterAndDisplayMessages() {
    // فلترة الرسائل حسب البحث
    filteredMessages = allMessages.filter(message => {
        if (!currentSearch) return true;
        return message.user_name.toLowerCase().includes(currentSearch) ||
               message.subject.toLowerCase().includes(currentSearch) ||
               message.description.toLowerCase().includes(currentSearch);
    });
    
    // فصل الرسائل المقروءة وغير المقروءة
    const unreadMessages = filteredMessages.filter(msg => !msg.is_read);
    const readMessages = filteredMessages.filter(msg => msg.is_read);
    
    // عرض الرسائل
    displayMessages('unreadMessages', unreadMessages);
    displayMessages('readMessages', readMessages);
}

// عرض الرسائل في التاب المحدد
function displayMessages(containerId, messages) {
    const container = document.getElementById(containerId);
    
    if (messages.length === 0) {
        const emptyMessage = containerId === 'unreadMessages' ? 
            'لا توجد رسائل غير مقروءة' : 'لا توجد رسائل مقروءة';
        const emptyIcon = containerId === 'unreadMessages' ? 'envelope' : 'envelope-open';
        
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-${emptyIcon}"></i>
                </div>
                <h5>${emptyMessage}</h5>
                <p>${currentSearch ? 'لا توجد نتائج للبحث الحالي' : ''}</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = messages.map(message => createMessageCard(message)).join('');
}

// إنشاء بطاقة رسالة
function createMessageCard(message) {
    const userInitial = message.user_name.charAt(0).toUpperCase();
    const formattedDate = formatDate(message.sent_date);
    
    // عرض السطر الأول فقط من الرسالة
    const firstLine = message.description.split('\n')[0].split('.')[0];
    const previewText = firstLine.length > 80 ? firstLine.substring(0, 80) + '...' : firstLine + '...';
    
    return `
        <div class="message-card" data-message-id="${message.id}">
            <div class="message-header">
                <div class="user-info">
                    <div class="user-avatar">${userInitial}</div>
                    <div class="user-details">
                        <h6>${message.user_name}</h6>
                        <p class="message-date">
                            <i class="bi bi-clock"></i>
                            ${formattedDate}
                        </p>
                    </div>
                </div>
            </div>
            
            <h5 class="message-subject">
                <i class="bi bi-envelope-open"></i>
                ${message.subject}
            </h5>
            <p class="message-preview">${message.description.length > 100 ? message.description.substring(0, 100) + '...' : message.description}</p>
            
            <div class="message-actions">
                <button class="btn-modern btn-details" onclick="showMessageDetails(${message.id})">
                    <i class="bi bi-eye-fill"></i>
                    عرض التفاصيل
                </button>
                ${!message.is_read ? `
                    <button class="btn-modern btn-read" onclick="markAsRead(${message.id})">
                        <i class="bi bi-check-circle"></i>
                        تم القراءة
                    </button>
                ` : `
                    <button class="btn-modern btn-read" disabled>
                        <i class="bi bi-check-circle-fill"></i>
                        مقروءة
                    </button>
                `}
            </div>
        </div>
    `;
}

// تم حذف دالة getPriorityClass لأنه تم إزالة تصنيف الأولوية

// اقتطاع النص
function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

// تنسيق التاريخ
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('ar-EG', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// عرض تفاصيل الرسالة
function showMessageDetails(messageId) {
    const message = allMessages.find(msg => msg.id === messageId);
    if (!message) return;
    
    const userInitial = message.user_name.charAt(0).toUpperCase();
    const formattedDate = formatDate(message.sent_date);
    
    document.getElementById('modalUserAvatar').textContent = userInitial;
    document.getElementById('modalUserName').textContent = message.user_name;
    document.getElementById('modalMessageDate').textContent = formattedDate;
    document.getElementById('modalMessageSubject').textContent = message.subject;
    document.getElementById('modalMessageContent').textContent = message.description;
    
    const modal = new bootstrap.Modal(document.getElementById('messageModal'));
    modal.show();
}

// تحديد الرسالة كمقروءة
async function markAsRead(messageId) {
    try {
        const response = await fetch(`/api/support-messages/${messageId}/mark-read`, {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) {
            throw new Error('فشل في تحديث حالة الرسالة');
        }
        
        const data = await response.json();
        
        if (data.status) {
            // تحديث الرسالة في المصفوفة
            const messageIndex = allMessages.findIndex(msg => msg.id === messageId);
            if (messageIndex !== -1) {
                allMessages[messageIndex].is_read = true;
            }
            
            // إعادة تحميل الرسائل
            loadMessages();
            
            showSuccess('تم تحديث حالة الرسالة بنجاح');
        } else {
            showError('فشل في تحديث حالة الرسالة');
        }
    } catch (error) {
        console.error('خطأ في تحديث حالة الرسالة:', error);
        showError('حدث خطأ في تحديث حالة الرسالة');
    }
}

// عرض رسالة نجاح
function showSuccess(message) {
    // يمكن استخدام مكتبة Toast أو إنشاء إشعار مخصص
    alert(message);
}

// عرض رسالة خطأ
function showError(message) {
    // يمكن استخدام مكتبة Toast أو إنشاء إشعار مخصص
    alert(message);
}
</script>
@endsection