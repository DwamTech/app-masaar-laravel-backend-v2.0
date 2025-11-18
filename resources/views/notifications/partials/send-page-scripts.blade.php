@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const API_TOKEN = localStorage.getItem('token');
    if (!API_TOKEN) {
        console.warn('⚠️ لا يوجد API Token في localStorage. سيتم عرض تحذير عدم التفويض.');
    }

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

    let ELIGIBLE_USERS = [];
    let SELECTED_USER_ID = null;
    let SELECTED_USER_NAME = '';

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

    function resetCompose() {
        SELECTED_USER_ID = null;
        SELECTED_USER_NAME = '';
        titleInput.value = '';
        bodyInput.value = '';
        linkInput.value = '';
        composeError.style.display = 'none';
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
        resetCompose();
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
            const result = json.result || {};
            const inApp = result.in_app || {};
            const push = result.push || {};
            resultNotifId.textContent = String(inApp.notification_id ?? '—');
            resultBroadcasted.textContent = inApp.broadcasted ? 'نعم' : 'لا';
            resultPushEnabled.textContent = push.enabled ? 'نعم' : 'لا';
            resultTokensCount.textContent = String(push.tokens_count ?? 0);
            resultSimulate.textContent = push.simulate ? 'نعم' : 'لا';
            const prs = push.results || {};
            const tokensHtml = Object.keys(prs).length === 0
                ? '<div>لا نتائج للإرسال</div>'
                : '<ul class="token-results">' + Object.entries(prs).map(([tok, res]) => {
                    const isErr = res && typeof res === 'object' && 'error' in res;
                    const badge = isErr ? '<span style="color:#b91c1c">فشل</span>' : '<span style="color:#16a34a">نجاح</span>';
                    return `<li><code>${tok}</code> — ${badge}</li>`;
                }).join('') + '</ul>';
            resultTokensList.innerHTML = tokensHtml;
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
        resetCompose();
    });

    loadEligibleUsers('');
});
</script>
@endpush