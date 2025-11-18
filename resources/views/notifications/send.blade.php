@extends('layouts.dashboard')

@section('title', 'إرسال إشعار')

@include('notifications.partials.styles')

@section('content')
<div class="notifications-page">
    <div class="notifications-container">
        <div class="notifications-header">
            <div class="header-content">
                <div class="header-title">
                    <i class="bi bi-send-fill title-icon"></i>
                    <h1>إرسال إشعار مخصص</h1>
                </div>
                <div class="header-actions">
                    <a href="{{ route('notifications') }}" class="secondary-btn" title="رجوع لمركز الإشعارات">
                        <i class="bi bi-arrow-right"></i>
                        رجوع
                    </a>
                </div>
            </div>
        </div>

        <div class="modal-body">
            <!-- عرض المستخدمين المؤهلين -->
            <section id="eligible-users-view">
                <div class="eligible-search-bar">
                    <input type="text" id="eligible-search" placeholder="ابحث بالاسم أو البريد أو رقم الجوال" />
                    <button id="eligible-search-clear" title="مسح"><i class="bi bi-x-circle"></i></button>
                </div>
                <div class="eligible-info">
                    <i class="bi bi-info-circle"></i>
                    يتم عرض العملاء الذين لديهم Device Token مفعل فقط.
                </div>
                <div class="eligible-table-wrapper">
                    <table class="eligible-table" id="eligible-users-table">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>الجوال</th>
                                <th>البريد</th>
                                <th>عدد الأجهزة</th>
                                <th>اختيار</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- سيتم تعبئة الصفوف عبر الجافاسكريبت -->
                        </tbody>
                    </table>
                    <div id="eligible-loading" class="eligible-loading" style="display:none;">
                        <i class="bi bi-arrow-clockwise"></i>
                        جاري تحميل المستخدمين...
                    </div>
                    <div id="eligible-empty" class="eligible-empty" style="display:none;">
                        <i class="bi bi-people"></i>
                        لا يوجد مستخدمون مؤهلون حالياً
                    </div>
                    <div id="eligible-auth-warning" class="eligible-warning" style="display:none;">
                        <i class="bi bi-shield-lock"></i>
                        يلزم تسجيل الدخول كأدمن لاستخدام هذه الميزة.
                    </div>
                </div>
            </section>

            <!-- كتابة الإشعار -->
            <section id="compose-view" style="display:none;">
                <div class="compose-target">
                    <i class="bi bi-person-check"></i>
                    الإرسال إلى: <strong id="selected-user-name"></strong>
                </div>
                <div class="compose-form">
                    <label for="notif-title">عنوان الإشعار</label>
                    <input type="text" id="notif-title" maxlength="255" placeholder="مثال: تحديث حالة طلبك" />

                    <label for="notif-body">نص الإشعار</label>
                    <textarea id="notif-body" rows="4" placeholder="أدخل وصفاً واضحاً ومختصراً"></textarea>

                    <label for="notif-link">رابط اختياري</label>
                    <input type="text" id="notif-link" placeholder="app://admin/... أو رابط صفحة" />

                    <div class="compose-actions">
                        <button id="back-to-list" class="secondary-btn"><i class="bi bi-arrow-right"></i>رجوع للقائمة</button>
                        <button id="send-notif-btn" class="primary-btn"><i class="bi bi-send-check"></i>إرسال</button>
                    </div>
                    <div id="compose-error" class="compose-error" style="display:none;"></div>
                </div>
            </section>

            <!-- النتيجة -->
            <section id="result-view" style="display:none;">
                <div class="result-summary">
                    <i class="bi bi-activity"></i>
                    حالة الإرسال
                </div>
                <div class="result-panels">
                    <div class="panel">
                        <div class="panel-title"><i class="bi bi-bell"></i>إشعار داخل التطبيق</div>
                        <div class="panel-body">
                            <div>رقم الإشعار: <span id="result-notif-id">—</span></div>
                            <div>تم البث الفوري: <span id="result-broadcasted">—</span></div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-title"><i class="bi bi-phone"></i>دفع عبر FCM</div>
                        <div class="panel-body">
                            <div>مفعّل لدى المستخدم: <span id="result-push-enabled">—</span></div>
                            <div>عدد الأجهزة: <span id="result-tokens-count">—</span></div>
                            <div>وضع المحاكاة المحلي: <span id="result-simulate">—</span></div>
                            <div id="result-tokens-list"></div>
                        </div>
                    </div>
                </div>
                <div class="result-raw">
                    <details>
                        <summary><i class="bi bi-code-slash"></i>تفاصيل تقنية (JSON)</summary>
                        <pre id="result-json"></pre>
                    </details>
                </div>
                <div class="compose-actions">
                    <button id="send-another" class="secondary-btn"><i class="bi bi-plus-circle"></i>إرسال إشعار آخر</button>
                    <a href="{{ route('notifications') }}" class="primary-btn"><i class="bi bi-check2-circle"></i>تم</a>
                </div>
            </section>
        </div>
    </div>
    </div>
@endsection

@include('notifications.partials.send-page-scripts')