@extends('layouts.dashboard')

@section('content')
<style>
  .ops-card { border: none; border-radius: 16px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
  .ops-header { background: linear-gradient(135deg, #F38100, #f59f00); color: #fff; border-radius: 16px 16px 0 0; padding: 1rem 1.25rem; display: flex; align-items: center; gap: .75rem; }
  .ops-title { margin: 0; font-weight: 700; }
  .status-pill { display:inline-flex; align-items:center; gap:.35rem; padding: .25rem .6rem; border-radius: 999px; font-size: .85rem; background:#eef2ff; color:#4338ca }
  .ops-table th, .ops-table td { vertical-align: middle; }
  .loading { padding: 1rem; color: #666; }
  .empty { padding: 1rem; color: #999; text-align: center; }
  .ops-toolbar { display:flex; flex-wrap:wrap; gap:.75rem; align-items:center; margin-bottom:1rem; }
  .search-wrap { position:relative; flex:1 1 320px; }
  .search-input { width:100%; padding:.6rem 2.25rem .6rem .75rem; border:1px solid #e5e7eb; border-radius:10px; outline:none; }
  .search-input:focus { border-color:#F38100; box-shadow:0 0 0 3px rgba(243,129,0,0.15); }
  .search-icon { position:absolute; right:.6rem; top:50%; transform:translateY(-50%); color:#9ca3af; }
  .type-filters { display:flex; gap:.5rem; }
  .type-pill { border:1px solid #eee; padding:.4rem .7rem; border-radius:999px; cursor:pointer; background:#fff; color:#111; }
  .type-pill.active { background:#F38100; color:#fff; border-color:#F38100; }
  .status-badge { display:inline-block; padding:.35rem .6rem; border-radius:8px; font-size:.85rem; }
  .status-success { background:#ecfdf5; color:#065f46; }
  .status-warning { background:#fff7ed; color:#9a3412; }
  .status-info { background:#eef2ff; color:#3730a3; }
  .status-danger { background:#fee2e2; color:#7f1d1d; }
  .ops-table tbody tr:hover { background: #fafafa; }
</style>

<div class="card ops-card">
  <div class="ops-header">
    <i class="bi bi-activity fs-4"></i>
    <h4 class="ops-title">تتبع عمليات النظام</h4>
  </div>
  <div class="card-body">
    <p class="text-muted mb-3">عرض موحد لأهم عمليات المستخدمين مع بحث سريع بالاسم.</p>

    <div class="ops-toolbar">
      <div class="search-wrap">
        <input id="opsSearch" class="search-input" type="text" placeholder="ابحث باسم الحساب أو اسم العميل..." />
        <i class="bi bi-search search-icon"></i>
      </div>
      <div class="type-filters">
        <button class="type-pill active" data-type="all">الكل</button>
        <button class="type-pill" data-type="معاينة عقار">معاينة عقار</button>
        <button class="type-pill" data-type="توصيلة">توصيلة</button>
        <button class="type-pill" data-type="تأجير سيارة">تأجير سيارة</button>
        <button class="type-pill" data-type="أوردر أكل">أوردر أكل</button>
      </div>
    </div>

    <div id="opsLoading" class="loading">جارِ تحميل البيانات...</div>
    <div id="opsError" class="alert alert-danger d-none"></div>

    <div class="table-responsive">
      <table class="table table-hover align-middle ops-table">
        <thead>
          <tr>
            <th style="width: 15%">التاريخ</th>
            <th style="width: 12%">العملية</th>
            <th>الوصف</th>
            <th style="width: 15%">الحالة</th>
          </tr>
        </thead>
        <tbody id="opsTableBody">
          <tr><td colspan="4" class="empty">لا توجد بيانات حتى الآن</td></tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer text-muted" id="opsFooter"></div>
</div>

<script>
let opsAll = [];
let activeType = 'all';
let searchQuery = '';

function getTokenOrThrow(){
  const t = localStorage.getItem('token');
  if(!t) throw new Error('غير مسجل الدخول');
  return t;
}

async function fetchJson(url){
  const token = getTokenOrThrow();
  const res = await fetch(url, { headers: { 'Authorization': 'Bearer '+token } });
  if(!res.ok){ throw new Error('فشل الجلب: '+res.status); }
  return res.json();
}

function fmtDate(d){
  try { const dt = new Date(d); return dt.toLocaleString('ar-EG'); } catch(e){ return d || '-'; }
}

function typeIcon(t){
  const map = {
    'معاينة عقار': 'bi-building',
    'توصيلة': 'bi-truck',
    'تأجير سيارة': 'bi-car-front',
    'أوردر أكل': 'bi-bag'
  };
  return map[t] || 'bi-activity';
}

function statusTextAppointment(s){
  const map = { pending:'بانتظار المراجعة', admin_approved:'موافقة الأدمن', provider_approved:'موافقة مقدم الخدمة', completed:'مكتمل', rejected:'مرفوض' };
  return map[s] || s;
}
function statusTextDelivery(s){
  const map = {
    pending_offers:'في انتظار العروض',
    accepted_waiting_driver:'مقبول - انتظار السائق',
    driver_arrived:'وصل السائق',
    trip_started:'بدأت الرحلة',
    trip_completed:'انتهت الرحلة',
    cancelled:'ملغي',
    rejected:'مرفوض'
  };
  return map[s] || s;
}
function statusTextCar(s){
  const map = { pending_admin:'بانتظار موافقة الأدمن', pending_provider:'بيد مكتب التأجير', accepted:'مقبول', started:'بدأت الخدمة', finished:'مكتمل', rejected:'مرفوض' };
  return map[s] || s;
}
function statusTextFood(s){
  const map = { pending:'بانتظار المعالجة', processing:'قيد التحضير', accepted_by_admin:'مقبول', rejected:'مرفوض', completed:'مكتمل' };
  return map[s] || s;
}

function statusBadge(status){
  const s = (status || '').toLowerCase();
  let cls = 'status-info';
  if (['مكتمل','accepted','completed','finished','started','driver_arrived','trip_started'].includes(s)) cls = 'status-success';
  else if (['بانتظار المراجعة','pending','pending_offers','processing','accepted_by_admin','accepted_waiting_driver','pending_admin','pending_provider'].includes(s)) cls = 'status-warning';
  else if (['مرفوض','rejected','cancelled'].includes(s)) cls = 'status-danger';
  return `<span class="status-badge ${cls}">${status}</span>`;
}

function renderOps(ops){
  const body = document.getElementById('opsTableBody');
  if(!ops || ops.length === 0){ body.innerHTML = '<tr><td colspan="4" class="empty">لا توجد بيانات حتى الآن</td></tr>'; return; }
  body.innerHTML = ops.map(op => `
    <tr>
      <td>${fmtDate(op.date)}</td>
      <td><span class="status-pill"><i class="bi ${typeIcon(op.type)}"></i> ${op.type}</span></td>
      <td>${op.text}</td>
      <td>${statusBadge(op.status)}</td>
    </tr>
  `).join('');
  document.getElementById('opsFooter').textContent = `إجمالي العمليات المعروضة: ${ops.length}`;
}

function showError(e){
  const el = document.getElementById('opsError');
  el.classList.remove('d-none');
  el.textContent = (e && e.message) ? e.message : 'حدث خطأ غير متوقع.';
}
function showLoading(){ document.getElementById('opsLoading').style.display = 'block'; }
function hideLoading(){ document.getElementById('opsLoading').style.display = 'none'; }

async function loadOperations(){
  showLoading();
  try {
    const [appointmentsRes, deliveryRes, carRes, foodRes] = await Promise.all([
      fetchJson('/api/appointments'),
      fetchJson('/api/delivery/requests'),
      fetchJson('/api/car-orders'),
      fetchJson('/api/orders')
    ]);

    const ops = [];
    const appointments = appointmentsRes.appointments || [];
    appointments.forEach(a => {
      const user = (a.customer && a.customer.name) ? a.customer.name : 'مستخدم';
      const propTitle = (a.property && (a.property.title || a.property.address)) ? (a.property.title || a.property.address) : 'عقار';
      ops.push({
        date: a.created_at || a.appointment_datetime,
        type: 'معاينة عقار',
        text: `${user} - قدم طلب معاينة - للعقار ${propTitle}`,
        userName: user,
        status: statusTextAppointment(a.status)
      });
    });

    let deliveryList = [];
    if (deliveryRes && deliveryRes.delivery_requests){
      if (Array.isArray(deliveryRes.delivery_requests)) { deliveryList = deliveryRes.delivery_requests; }
      else if (Array.isArray(deliveryRes.delivery_requests.data)) { deliveryList = deliveryRes.delivery_requests.data; }
    }
    deliveryList.forEach(d => {
      const user = (d.client && d.client.name) ? d.client.name : 'مستخدم';
      const driverName = (d.driver && d.driver.name) ? d.driver.name : null;
      const text = driverName ? `${user} - قدم طلب توصيلة - وتم مع السائق ${driverName}` : `${user} - قدم طلب توصيلة`;
      ops.push({
        date: d.created_at || d.delivery_time,
        type: 'توصيلة',
        text,
        userName: user,
        driverName: driverName || '',
        status: statusTextDelivery(d.status)
      });
    });

    const carOrders = carRes.orders || [];
    carOrders.forEach(o => {
      const user = (o.client && o.client.name) ? o.client.name : 'مستخدم';
      const officeName = (o.provider && o.provider.name) ? o.provider.name : null;
      const accepted = ['accepted','started','finished'].includes(o.status);
      const text = (officeName && accepted) ? `${user} - قدم طلب تأجير سيارة - المكتب ${officeName} وافق عليه` : `${user} - قدم طلب تأجير سيارة`;
      ops.push({
        date: o.created_at || o.updated_at,
        type: 'تأجير سيارة',
        text,
        userName: user,
        providerName: officeName || '',
        status: statusTextCar(o.status)
      });
    });

    const foodOrders = foodRes.orders || [];
    foodOrders.forEach(order => {
      const user = (order.user && order.user.name) ? order.user.name : 'مستخدم';
      const restaurantName = (order.restaurant && order.restaurant.restaurant_name) ? order.restaurant.restaurant_name : 'مطعم';
      ops.push({
        date: order.created_at,
        type: 'أوردر أكل',
        text: `${user} - طلب أوردر أكل - من المطعم ${restaurantName}`,
        userName: user,
        restaurantName: restaurantName || '',
        status: statusTextFood(order.status)
      });
    });

    ops.sort((a,b) => new Date(b.date) - new Date(a.date));
    opsAll = ops;
    applyFilters();
  } catch(e){
    console.error(e);
    showError(e);
  } finally {
    hideLoading();
  }
}

function normalize(s){ return (s || '').toString().toLowerCase().trim(); }

function applyFilters(){
  const q = normalize(searchQuery);
  const filtered = opsAll.filter(op => {
    const typeOk = (activeType === 'all') ? true : (op.type === activeType);
    if (!typeOk) return false;
    if (!q) return true;
    const fields = [op.userName, op.driverName, op.providerName, op.restaurantName, op.text];
    return fields.some(f => normalize(f).includes(q));
  });
  renderOps(filtered);
}

function debounce(fn, ms){ let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); }; }

window.addEventListener('DOMContentLoaded', () => {
  try { getTokenOrThrow(); loadOperations(); } catch(e){ showError(e); }
  const searchEl = document.getElementById('opsSearch');
  const onSearch = debounce(() => { searchQuery = searchEl.value; applyFilters(); }, 200);
  searchEl.addEventListener('input', onSearch);
  document.querySelectorAll('.type-pill').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.type-pill').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      activeType = btn.getAttribute('data-type') || 'all';
      applyFilters();
    });
  });
});
</script>
@endsection