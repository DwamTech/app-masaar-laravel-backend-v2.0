<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مسار - سياسة الخصوصية</title>
    <link rel="icon" type="image/png" href="{{ asset('masar.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
    <style>
        html, body { font-family: 'Cairo', sans-serif !important; }
        * { font-family: inherit !important; }
        .fas, .far, .fab, .fa, .fal, .fad, .fa-solid, .fa-regular, .fa-brands { 
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important; 
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                <img src="{{ asset('masar.png') }}" alt="مسار" style="height: 70px; margin-left: 10px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#home">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#features">الخدمات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#about">عن المنصة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#contact">تواصل معنا</a>
                    </li>
                </ul>
            </div>
            <div class="ms-auto">
                <a href="{{ route('landing') }}#contact" class="login_btn">
                    <i class="fas fa-envelope me-2"></i> تواصل معنا
                </a>
            </div>
        </div>
    </nav>

    <section class="features-showcase" style="padding-top: 130px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="bg-white rounded-4 shadow p-4 p-lg-5" style="text-align: right;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <h1 class="m-0" style="color: #FC8700; font-weight: 800;">سياسة الخصوصية</h1>
                            <div class="text-muted">آخر تحديث: {{ now()->format('Y-m-d') }}</div>
                        </div>

                        <p class="text-muted mb-4" style="line-height: 1.9;">
                            توضح هذه السياسة كيف نقوم في مسار بجمع واستخدام وحماية بياناتك عند استخدامك للمنصة/التطبيق. باستخدامك لمسار فإنك توافق على ممارسات الخصوصية الموضحة هنا.
                        </p>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">1) البيانات التي نجمعها</h4>
                        <ul class="mb-4" style="line-height: 1.9;">
                            <li>بيانات الحساب: مثل الاسم، رقم الهاتف، البريد الإلكتروني، وبيانات التحقق.</li>
                            <li>بيانات الطلبات: مثل تفاصيل الحجز/الطلب، العناوين، والتفضيلات المرتبطة بالخدمة.</li>
                            <li>بيانات تقنية: مثل نوع الجهاز، عنوان IP، سجل الأخطاء، ومعرفات الجلسة لتحسين الأداء والأمان.</li>
                            <li>بيانات الموقع: عند تفعيلها وبموافقتك لتقديم خدمات تعتمد على الموقع.</li>
                        </ul>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">2) كيف نستخدم البيانات</h4>
                        <ul class="mb-4" style="line-height: 1.9;">
                            <li>تقديم الخدمات وإدارة الحساب وتنفيذ الطلبات.</li>
                            <li>التواصل معك بخصوص الطلبات أو الدعم الفني أو إشعارات مهمة.</li>
                            <li>تحسين تجربة المستخدم وتحليل الأداء وتطوير الميزات.</li>
                            <li>الحماية من الاحتيال وإساءة الاستخدام والالتزام بالمتطلبات النظامية.</li>
                        </ul>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">3) مشاركة البيانات</h4>
                        <p class="mb-3" style="line-height: 1.9;">
                            قد نشارك بيانات محدودة بالقدر اللازم مع:
                        </p>
                        <ul class="mb-4" style="line-height: 1.9;">
                            <li>مزودي الخدمات/الشركاء لتنفيذ طلبك (مثل مزود الحجز أو التوصيل أو العقار).</li>
                            <li>مزودي الدفع لمعالجة العمليات المالية عند الحاجة.</li>
                            <li>مزودي البنية التحتية التقنية (استضافة/إرسال رسائل/تحليلات) وفق اتفاقيات حماية.</li>
                            <li>الجهات النظامية عند وجود التزام قانوني أو لحماية الحقوق والأمن.</li>
                        </ul>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">4) حفظ البيانات ومدة الاحتفاظ</h4>
                        <p class="mb-4" style="line-height: 1.9;">
                            نحتفظ ببياناتك للمدة اللازمة لتقديم الخدمات والامتثال للمتطلبات النظامية وحل النزاعات وتطبيق الاتفاقيات. قد تختلف مدة الاحتفاظ حسب نوع البيانات وطبيعة الخدمة.
                        </p>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">5) حماية البيانات</h4>
                        <p class="mb-4" style="line-height: 1.9;">
                            نتخذ إجراءات أمنية معقولة لحماية البيانات من الوصول غير المصرح به أو الفقد أو التلاعب. ومع ذلك لا يمكن ضمان أمان كامل لأي نظام عبر الإنترنت.
                        </p>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">6) حقوقك وخياراتك</h4>
                        <ul class="mb-4" style="line-height: 1.9;">
                            <li>يمكنك تحديث بيانات حسابك متى ما توفرت الخيارات داخل المنصة.</li>
                            <li>يمكنك طلب الوصول أو تصحيح أو حذف بيانات معينة وفق الأنظمة السارية.</li>
                            <li>يمكنك التحكم في إعدادات الموقع والإشعارات من جهازك أو من داخل التطبيق إن وُجدت.</li>
                        </ul>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">7) ملفات تعريف الارتباط (Cookies)</h4>
                        <p class="mb-4" style="line-height: 1.9;">
                            قد نستخدم ملفات تعريف الارتباط وتقنيات مشابهة لتحسين الجلسات وتذكر التفضيلات ورفع مستوى الأمان. يمكنك التحكم في ذلك من إعدادات المتصفح.
                        </p>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">8) التواصل معنا</h4>
                        <p class="mb-0" style="line-height: 1.9;">
                            لأي استفسارات تخص الخصوصية، يمكنك التواصل معنا عبر نموذج “تواصل معنا” في الصفحة الرئيسية.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-logo">مسار</div>
            <p class="footer-text">منصة متكاملة لإدارة الخدمات والأعمال بكفاءة عالية</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
            <div class="d-flex justify-content-center gap-4 flex-wrap mt-4">
                <a href="{{ route('terms') }}" class="text-white-50 text-decoration-none">الشروط والأحكام</a>
                <a href="{{ route('privacy') }}" class="text-white-50 text-decoration-none">سياسة الخصوصية</a>
            </div>
            <hr class="my-4" style="opacity: 0.3;">
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });
    </script>
</body>
</html>
