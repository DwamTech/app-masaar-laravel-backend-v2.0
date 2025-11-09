<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مسار - منصة الخدمات المتكاملة</title>
    <link rel="icon" type="image/png" href="{{ asset('masar.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('masar.png') }}" alt="مسار" style="height: 70px; margin-left: 10px;">
                
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">الخدمات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">عن المنصة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">تواصل معنا</a>
                    </li>
                </ul>
            </div>
            <div class="ms-auto">
                <a href="#contact" class="login_btn">
                    <i class="fas fa-envelope me-2"></i> تواصل معنا 
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="hero-title">تطبيق مسار </h1>
                        <p class="hero-subtitle">منصة متكاملة تجمع جميع الخدمات في مكان واحد: حجز الطيران، المطاعم، تأجير السيارات، الفنادق، العقارات، والتصاريح الأمنية</p>
                        
                        <div class="hero-buttons">
                            <a href="#" class="btn-download-primary">
                                <i class="fab fa-google-play me-2"></i>
                                حمل من Google Play
                            </a>
                            <a href="#" class="btn-download-secondary">
                                <i class="fab fa-apple me-2"></i>
                                حمل من App Store
                            </a>
                            <a href="#contact" class="btn-contact">
                                <i class="fas fa-envelope me-2"></i>
                                تواصل معنا
                            </a>
                        </div>
                        <div class="hero-features">
                            <ul class="hero-points">
                                <li class="hero-chip">
                                    <span class="chip-icon"><i class="fas fa-check"></i></span>
                                    <span class="chip-text">خدمات متنوعة في تطبيق واحد</span>
                                </li>
                                <li class="hero-chip">
                                    <span class="chip-icon"><i class="fas fa-check"></i></span>
                                    <span class="chip-text">واجهة سهلة الاستخدام</span>
                                </li>
                                <li class="hero-chip">
                                    <span class="chip-icon"><i class="fas fa-check"></i></span>
                                    <span class="chip-text">دعم فني على مدار الساعة</span>
                                </li>
                            </ul>
                        </div>
                        <div class="download-stats">
                            <div class="stat-item">
                                <span class="stat-number">50K+</span>
                                <span class="stat-label">تحميل</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">4.8</span>
                                <span class="stat-label">تقييم</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">24/7</span>
                                <span class="stat-label">دعم فني</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-app-showcase">
                        <div class="phone-mockup">
                            <div class="phone-frame">
                                <img src="/mobile-screenshot.png" alt="تطبيق مسار" class="app-screenshot">
                            </div>
                        </div>
                        <div class="floating-icons">
                            <div class="floating-icon" style="--delay: 0s;">
                                <i class="fas fa-plane"></i>
                            </div>
                            <div class="floating-icon" style="--delay: 1s;">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="floating-icon" style="--delay: 2s;">
                                <i class="fas fa-car"></i>
                            </div>
                            <div class="floating-icon" style="--delay: 3s;">
                                <i class="fas fa-bed"></i>
                            </div>
                            <div class="floating-icon" style="--delay: 4s;">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="floating-icon" style="--delay: 5s;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <!-- Services Showcase (Redesigned) -->
    <section id="features" class="services-showcase">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="features-title">خدماتنا المتميزة</h2>
                <p class="features-subtitle">مجموعة حلول متكاملة تغطي السفر، الطعام، الإقامة، والسيارات والعقارات</p>
            </div>
            <div class="row g-4 services-grid">
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-plane"></i></div>
                        <h3 class="service-name">حجز الطيران</h3>
                        <p class="service-desc">احجز تذاكر الطيران بأفضل الأسعار لرحلاتك المحلية والدولية بسهولة</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-utensils"></i></div>
                        <h3 class="service-name">المطاعم</h3>
                        <p class="service-desc">اكتشف أفضل المطاعم واطلب وجباتك المفضلة مع توصيل سريع وموثوق</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-car"></i></div>
                        <h3 class="service-name">تأجير السيارات</h3>
                        <p class="service-desc">اختر من مجموعة واسعة من السيارات الحديثة لتلبية احتياجاتك اليومية</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-bed"></i></div>
                        <h3 class="service-name">حجز الفنادق</h3>
                        <p class="service-desc">احجز أفضل الفنادق والمنتجعات مع ضمان سعر مميز وخدمة ممتازة</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-home"></i></div>
                        <h3 class="service-name">بحث العقارات</h3>
                        <p class="service-desc">ابحث عن العقار المثالي للشراء أو الإيجار مع أدوات بحث متقدمة</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-shield-alt"></i></div>
                        <h3 class="service-name">التصريح الأمني</h3>
                        <p class="service-desc">احصل على التصاريح المطلوبة بسرعة مع متابعة فورية لحالة طلبك</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="section-title" style="color: white; margin-bottom: 20px;">إحصائيات منصة مسار</h2>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 1.2rem;">أرقام تعكس ثقة عملائنا ونجاح منصتنا</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="stats-number" data-target="50000">0</span>
                        <div class="stats-label">مستخدم نشط</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <span class="stats-number" data-target="125000">0</span>
                        <div class="stats-label">طلب مكتمل</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="stats-number" data-target="98">0</span>
                        <div class="stats-label"> % رضا العملاء</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <span class="stats-number" data-target="24">0</span>
                        <div class="stats-label">ساعة دعم فني</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Showcase (New) -->
    <section class="features-showcase">
        <div class="background-orbs" aria-hidden="true">
            <span class="orb orb-1"></span>
            <span class="orb orb-2"></span>
            <span class="orb orb-3"></span>
        </div>
        <div class="container">
            <div class="section-header text-center">
                <h2 class="features-title">لماذا تختار تطبيق مسار؟</h2>
                <p class="features-subtitle">اكتشف المميزات الفريدة التي تجعل تطبيق مسار الخيار الأمثل لجميع احتياجاتك</p>
            </div>
            <div class="row g-4 features-grid">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card-new">
                        <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                        <h4 class="feature-name">سهولة الاستخدام</h4>
                        <p class="feature-desc">واجهة بسيطة وسهلة تمكنك من الوصول لجميع الخدمات بنقرة واحدة</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card-new">
                        <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                        <h4 class="feature-name">أمان وحماية</h4>
                        <p class="feature-desc">نظام حماية متقدم يضمن أمان بياناتك ومعاملاتك المالية</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card-new">
                        <div class="feature-icon"><i class="fas fa-clock"></i></div>
                        <h4 class="feature-name">متاح 24/7</h4>
                        <p class="feature-desc">خدماتنا متاحة على مدار الساعة لتلبية احتياجاتك في أي وقت</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card-new">
                        <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                        <h4 class="feature-name">سرعة في التنفيذ</h4>
                        <p class="feature-desc">معالجة فورية للطلبات وتنفيذ سريع لجميع الخدمات</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card-new">
                        <div class="feature-icon"><i class="fas fa-headset"></i></div>
                        <h4 class="feature-name">دعم فني متميز</h4>
                        <p class="feature-desc">فريق دعم فني محترف جاهز لمساعدتك في أي وقت</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card-new">
                        <div class="feature-icon"><i class="fas fa-star"></i></div>
                        <h4 class="feature-name">جودة عالية</h4>
                        <p class="feature-desc">خدمات عالية الجودة مع ضمان الرضا التام للعملاء</p>
                    </div>
                </div>
            </div>
            <div class="cta-row text-center mt-4">
                <a href="#download" class="btn btn-primary-custom">حمّل التطبيق الآن</a>
            </div>
        </div>
    </section>

    <!-- User Reviews Section -->
    <section class="reviews-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="section-title" style="color: white;">ماذا يقول عملاؤنا؟</h2>
                    <p class="section-subtitle" style="color: white;">آراء وتقييمات حقيقية من مستخدمي تطبيق مسار</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="review-card">
                        <div class="review-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="review-text">"تطبيق رائع يوفر جميع الخدمات في مكان واحد. سهل الاستخدام وسريع في التنفيذ."</p>
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="reviewer-details">
                                <h5>أحمد محمد</h5>
                                <span>مستخدم منذ 6 أشهر</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="review-card">
                        <div class="review-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="review-text">"الدعم الفني ممتاز والخدمات متنوعة. أنصح الجميع بتجربة هذا التطبيق."</p>
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="reviewer-details">
                                <h5>فاطمة علي</h5>
                                <span>مستخدمة منذ سنة</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="review-card">
                        <div class="review-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="review-text">"واجهة جميلة وسهلة، والأسعار منافسة جداً. تطبيق يستحق التحميل."</p>
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="reviewer-details">
                                <h5>خالد السعيد</h5>
                                <span>مستخدم منذ 8 أشهر</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="cta-content">
                        <h2 class="cta-title">ابدأ رحلتك مع تطبيق مسار اليوم!</h2>
                        <p class="cta-subtitle">انضم إلى أكثر من 50,000 مستخدم واستمتع بتجربة فريدة من نوعها</p>
                        <div class="cta-buttons">
                            <a href="#" class="btn-cta-primary">
                                <i class="fab fa-google-play me-2"> </i>
                                 حمل من Google Play 
                            </a>
                            <a href="#" class="btn-cta-secondary">
                                <i class="fab fa-apple me-2"> </i>
                                 حمل من App Store 
                            </a>
                        </div>
                        <div class="cta-features">
                            <div class="cta-feature">
                                <i class="fas fa-download"></i>
                                <span>تحميل مجاني</span>
                            </div>
                            <div class="cta-feature">
                                <i class="fas fa-mobile-alt"></i>
                                <span>متوافق مع جميع الأجهزة</span>
                            </div>
                            <div class="cta-feature">
                                <i class="fas fa-lock"></i>
                                <span>آمن ومحمي</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="about" class="features-section" style="background: #f8f9fa;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-content">
                        <h2 class="section-title text-right mb-4" style="color: #FC8700; text-align: right;">عن منصة مسار</h2>
                        <div class="about-description">
                            <p class="lead mb-4">منصة مسار هي تطبيق متكامل يجمع جميع الخدمات التي تحتاجها في مكان واحد. من حجز الطيران والفنادق إلى طلب الطعام والبحث عن العقارات.</p>
                        </div>
                        <div class="features-list">
                            <div class="feature-item">
                                
                                <div class="feature-text">
                                    <h5>واجهة مستخدم سهلة وبديهية</h5>
                                    <p>تصميم عصري وسهل الاستخدام يناسب جميع الفئات العمرية</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                
                                <div class="feature-text">
                                    <h5>خدمات متنوعة في تطبيق واحد</h5>
                                    <p>جميع احتياجاتك اليومية في مكان واحد لتوفير الوقت والجهد</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                
                                <div class="feature-text">
                                    <h5>أمان عالي وحماية للبيانات</h5>
                                    <p>نستخدم أحدث تقنيات الحماية لضمان أمان معلوماتك الشخصية</p>
                                </div>
                            </div>
                            <div class="feature-item">
                               
                                <div class="feature-text">
                                    <h5>دعم فني متواصل 24/7</h5>
                                    <p>فريق دعم متخصص متاح على مدار الساعة لمساعدتك</p>
                                </div>
                            </div>
                            <div class="feature-item">
                               
                                <div class="feature-text">
                                    <h5>تحديثات مستمرة وميزات جديدة</h5>
                                    <p>نطور التطبيق باستمرار لإضافة ميزات جديدة ومفيدة</p>
                                </div>
                            </div>
                        </div>
                        <div class="about-cta" style="text-align: right;">
                            <a href="#contact" class="btn-primary-custom">تواصل معنا</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mobile-mockup">
                        <div class="mobile-frame">
                            <div class="mobile-notch"></div>
                            <div class="mobile-screen">
                                <img src="{{ asset('mobile-screenshot.png') }}" alt="تطبيق مسار" class="mobile-screenshot">
                            </div>
                            <div class="mobile-home-indicator"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <h2 class="section-title">تواصل معنا</h2>
            <div class="contact-form">
                <form action="#" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">الاسم الكامل</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject" class="form-label">الموضوع</label>
                                <select class="form-control" id="subject" name="subject" required>
                                    <option value="">اختر الموضوع</option>
                                    <option value="general">استفسار عام</option>
                                    <option value="support">دعم فني</option>
                                    <option value="business">استفسار تجاري</option>
                                    <option value="complaint">شكوى</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="form-label">الرسالة</label>
                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="اكتب رسالتك هنا..." required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane me-2"></i>
                            إرسال الرسالة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
            <hr class="my-4" style="opacity: 0.3;">
            
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animated counter for statistics
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);
            
            function updateCounter() {
                start += increment;
                if (start < target) {
                    element.textContent = Math.floor(start).toLocaleString();
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target.toLocaleString();
                }
            }
            updateCounter();
        }

        // Intersection Observer for statistics animation
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statsNumbers = entry.target.querySelectorAll('.stats-number');
                    statsNumbers.forEach(number => {
                        const target = parseInt(number.getAttribute('data-target'));
                        animateCounter(number, target);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe statistics section
        document.addEventListener('DOMContentLoaded', () => {
            const statsSection = document.querySelector('.stats-section');
            if (statsSection) {
                observer.observe(statsSection);
            }
        });



        // Navbar background on scroll
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