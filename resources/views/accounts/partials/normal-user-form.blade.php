<div class="normal-user-form">
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">الاسم الكامل</label>
      <input type="text" name="name" class="form-control" placeholder="أدخل الاسم" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">البريد الإلكتروني</label>
      <input type="email" name="email" class="form-control" placeholder="example@domain.com" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">كلمة المرور</label>
      <input type="password" name="password" class="form-control" placeholder="••••••" minlength="6" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">رقم الهاتف</label>
      <input type="text" name="phone" class="form-control" placeholder="مثال: +201234567890" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">المحافظة</label>
      <select name="governorate" class="form-select" required>
        <option value="" selected disabled>اختر المحافظة</option>
        <option>القاهرة</option>
        <option>الجيزة</option>
        <option>القليوبية</option>
        <option>الإسكندرية</option>
        <option>البحيرة</option>
        <option>مطروح</option>
        <option>كفر الشيخ</option>
        <option>الغربية</option>
        <option>الشرقية</option>
        <option>الدقهلية</option>
        <option>المنوفية</option>
        <option>دمياط</option>
        <option>بورسعيد</option>
        <option>الإسماعيلية</option>
        <option>السويس</option>
        <option>شمال سيناء</option>
        <option>جنوب سيناء</option>
        <option>بني سويف</option>
        <option>الفيوم</option>
        <option>المنيا</option>
        <option>أسيوط</option>
        <option>سوهاج</option>
        <option>قنا</option>
        <option>الأقصر</option>
        <option>أسوان</option>
        <option>البحر الأحمر</option>
        <option>الوادي الجديد</option>
      </select>
    </div>

    <input type="hidden" name="user_type" value="normal">
  </div>
</div>