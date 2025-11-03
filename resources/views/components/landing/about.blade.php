<section id="about" class="landing-about py-16">
    <div class="about-inner max-w-5xl mx-auto">
        <div class="about-card text-center">
            @php $isEn = request()->is('en'); @endphp
            <h2 class="section-title">{{ $isEn ? 'Who are we?' : 'من نحن؟' }}</h2>
            <p class="section-text">
                {{ $isEn
                    ? 'We are "DUBISALE FOR MARKETING AND PR", a sole proprietorship officially licensed by Dubai’s Department of Economy and Tourism [cite: 13, 14] under license number 1485537 [cite: 4, 5]. Our mission is to provide an advanced and reliable classifieds platform serving residents of the United Arab Emirates, with a focus on ease of use and user safety.'
                    : 'نحن "DUBISALE FOR MARKETING AND PR"، مؤسسة فردية مرخصة رسميًا من دائرة الاقتصاد والسياحة في دبي [cite: 13, 14] برخصة رقم 1485537 [cite: 4, 5]. مهمتنا هي توفير منصة إعلانات مبوبة متطورة وموثوقة تخدم سكان الإمارات العربية المتحدة، مع التركيز على سهولة الاستخدام وأمان المستخدمين.'
                }}
            </p>
        </div>
    </div>
</section>