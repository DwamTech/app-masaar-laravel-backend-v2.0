<div class="notifications-header">
    <div class="header-content">
        <div class="header-title">
            <i class="bi bi-bell-fill title-icon"></i>
            <h1>مركز الإشعارات</h1>
        </div>
        <div class="header-actions">
            <a href="{{ route('notifications.send') }}" id="send-notification-link" class="send-notification-btn" title="إرسال إشعار">
                <i class="bi bi-send"></i>
                إرسال إشعار
            </a>
            <button id="permission-button" style="display: none;">
                <i class="bi bi-bell-slash"></i>
                تفعيل إشعارات المتصفح
            </button>
        </div>
    </div>
</div>