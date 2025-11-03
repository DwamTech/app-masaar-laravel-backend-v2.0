@extends('layouts.dashboard')

@section('content')
<style>
  .ops-card { border: none; border-radius: 16px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
  .ops-header { background: linear-gradient(135deg, #F38100, #f59f00); color: #fff; border-radius: 16px 16px 0 0; padding: 1rem 1.25rem; display: flex; align-items: center; gap: .75rem; }
  .ops-title { margin: 0; font-weight: 700; }
  .status-pill { display:inline-block; padding: .25rem .6rem; border-radius: 999px; font-size: .85rem; background:#eef2ff; color:#4338ca }
  .ops-table th, .ops-table td { vertical-align: middle; }
  .loading { padding: 1rem; color: #666; }
  .empty { padding: 1rem; color: #999; text-align: center; }
</style>

<div class="card ops-card">
  <div class="ops-header">
    <i class="bi bi-activity fs-4"></i>
    <h4 class="ops-title">تتبع عمليات النظام</h4>
  </div>
  <div class="card-body">
    <p class="text-muted mb-3">عرض موحد لأهم عمليات المستخدمين بدون تفاصيل أو إجراءات.</p>

    <div id="opsLoading" class="loading">جارِ تحميل البيانات...</div>
    <div id="opsError" class="alert alert-danger d-none"></div>

    <div class="table-responsive">
      <table class="table ops-table">
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

function renderOps(ops){
  const body = document.getElementById('opsTableBody');
  if(!ops || ops.length === 0){ body.innerHTML = '<tr><td colspan="4" class="empty">لا توجد بيانات حتى الآن</td></tr>'; return; }
  body.innerHTML = ops.map(op => `
    <tr>
      <td>${fmtDate(op.date)}</td>
      <td><span class="status-pill">${op.type}</span></td>
      <td>${op.text}</td>
      <td>${op.status}</td>
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
        status: statusTextFood(order.status)
      });
    });

    ops.sort((a,b) => new Date(b.date) - new Date(a.date));
    renderOps(ops);
  } catch(e){
    console.error(e);
    showError(e);
  } finally {
    hideLoading();
  }
}

window.addEventListener('DOMContentLoaded', () => {
  try { getTokenOrThrow(); loadOperations(); } catch(e){ showError(e); }
});
</script>
@endsection