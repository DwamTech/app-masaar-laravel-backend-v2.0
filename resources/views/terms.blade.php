<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مسار - الشروط والأحكام</title>
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
                            <h1 class="m-0" style="color: #FC8700; font-weight: 800;">الشروط والأحكام</h1>
                            <div class="text-muted">آخر تحديث: {{ now()->format('Y-m-d') }}</div>
                        </div>

                        <p class="text-muted mb-4" style="line-height: 1.9;">
                            باستخدامك لمنصة/تطبيق مسار فإنك توافق على هذه الشروط والأحكام. تُطبّق هذه الشروط على جميع الخدمات المقدمة عبر مسار بما يشمل (على سبيل المثال لا الحصر) خدمات السفر، المطاعم، تأجير السيارات، الفنادق، العقارات، والتصاريح الأمنية.
                        </p>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">1) نطاق الخدمة</h4>
                        <p class="mb-4" style="line-height: 1.9;">
                            مسار منصة تساعد المستخدم على الوصول إلى خدمات متعددة وإتمام الطلبات وحجز الخدمات عبر مزودين وشركاء. قد تختلف شروط الخدمة التفصيلية بحسب نوع الخدمة ومزودها، ويُعتبر استكمال الطلب موافقة على الشروط الخاصة بتلك الخدمة إن وُجدت.
                        </p>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">2) الحساب والهوية</h4>
                        <ul class="mb-4" style="line-height: 1.9;">
                            <li>يلتزم المستخدم بتقديم بيانات صحيحة ومحدثة عند التسجيل أو تنفيذ أي طلب.</li>
                            <li>يتحمل المستخدم مسؤولية حماية بيانات الدخول وعدم مشاركتها مع الغير.</li>
                            <li>يحق لمسار طلب معلومات إضافية للتحقق عند الحاجة وبما يتوافق مع الأنظمة.</li>
                        </ul>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">3) الطلبات، الأسعار، والمدفوعات</h4>
                        <ul class="mb-4" style="line-height: 1.9;">
                            <li>قد تتغير الأسعار والتوافر وفقًا لوقت الطلب وسياسات مزودي الخدمة.</li>
                            <li>قد تُضاف رسوم خدمة أو رسوم معالجة أو ضرائب وفق النظام المعمول به.</li>
                            <li>في حال حدوث خطأ تسعيري أو تقني، يحق لمسار إلغاء الطلب أو تصحيحه بعد إشعار المستخدم.</li>
                        </ul>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">4) الإلغاء والاسترجاع</h4>
                        <p class="mb-4" style="line-height: 1.9;">
                            تختلف سياسات الإلغاء والاسترجاع حسب نوع الخدمة ومزودها. سيتم عرض السياسات المتاحة أثناء تنفيذ الطلب إن وُجدت. عند استحقاق الاسترجاع، تتم معالجته بالوسيلة ذاتها قدر الإمكان وضمن مدة زمنية تختلف بحسب مزود الدفع والبنك.
                        </p>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">5) الاستخدام المقبول</h4>
                        <ul class="mb-4" style="line-height: 1.9;">
                            <li>يُمنع إساءة استخدام المنصة أو محاولة تعطيلها أو الوصول غير المصرح به.</li>
                            <li>يُمنع إدخال محتوى مخالف للأنظمة أو حقوق الغير أو يتضمن احتيالًا أو تضليلًا.</li>
                            <li>يحق لمسار تعليق أو إنهاء الحساب عند مخالفة الشروط أو الاشتباه بممارسات غير نظامية.</li>
                        </ul>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">6) المسؤولية وحدودها</h4>
                        <p class="mb-4" style="line-height: 1.9;">
                            يلتزم مسار ببذل العناية المعقولة لتقديم الخدمة، مع عدم ضمان خلوها من الانقطاعات أو الأخطاء التقنية. تكون مسؤولية تنفيذ الخدمة الأساسية (مثل تقديم الحجز أو الطلب) على مزود الخدمة المعني وفق سياساته. لا يتحمل مسار مسؤولية أي خسائر غير مباشرة أو تبعية إلا بما يقتضيه النظام.
                        </p>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">7) الملكية الفكرية</h4>
                        <p class="mb-4" style="line-height: 1.9;">
                            جميع العلامات والشعارات والمحتوى والواجهة الخاصة بمسار مملوكة لمسار أو لمرخصيه، ويُحظر استخدامها دون إذن مسبق.
                        </p>

                        <h4 class="mb-2" style="color: #FC8700; font-weight: 700;">8) التعديلات</h4>
                        <p class="mb-0" style="line-height: 1.9;">
                            قد نقوم بتحديث هذه الشروط من وقت لآخر، وسيتم نشر النسخة المحدثة على هذه الصفحة. استمرارك في استخدام مسار بعد التحديث يعني موافقتك على الشروط المعدلة.
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
