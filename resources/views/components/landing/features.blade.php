<section id="features" class="landing-features py-16">
    <div class="mx-auto">
        <div class="text-center">
            @php $isEn = request()->is('en'); @endphp
            <span class="features-badge">{{ $isEn ? 'Dubisale Features' : 'مميزات Dubaisale' }}</span>
            <h2 class="section-title">{{ $isEn ? 'Safe experience and unique features' : 'تجربة آمنة ومميزات فريدة' }}</h2>
            <p class="section-text">{{ $isEn ? 'Save time and enjoy a smooth, safe, and innovative experience.' : 'اختصر وقتك واستمتع بتجربة سلسة، آمنة ومبتكرة.' }}</p>
        </div>

        <div class="features-grid mt-10">
            <!-- Feature 1 -->
            <article class="feature-card" style="--delay: .05s">
                <div class="feature-icon" aria-hidden="true">
                    <!-- lock icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="10" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h3 class="feature-title">{{ $isEn ? 'Secure and verified account' : 'حساب آمن ومُحقق' }}</h3>
                <p class="feature-text">
                    {{ $isEn
                        ? 'To protect your account and prevent unauthorized access, we use WhatsApp verification to send a one-time OTP directly to your number. It’s the fastest and safest way to confirm your identity. We use your phone number for this purpose only.'
                        : 'لضمان حماية حسابك ومنع أي وصول غير مصرح به، نستخدم خدمة التحقق عبر واتساب لإرسال رمز تحقق (OTP) لمرة واحدة مباشرة إلى رقمك. هذه هي الطريقة الأسرع والأكثر أمانًا لتأكيد هويتك. نحن نستخدم رقم هاتفك لهذا الغرض فقط.'
                    }}
                </p>
            </article>

            <!-- Feature 2 -->
            <article class="feature-card" style="--delay: .15s">
                <div class="feature-icon" aria-hidden="true">
                    <!-- rocket icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 15c-1.5 1.5-2.5 3.5-3 6 2.5-.5 4.5-1.5 6-3"></path>
                        <path d="M15 5l4 4-8 8H7v-4z"></path>
                        <path d="M10 9l5 5"></path>
                    </svg>
                </div>
                <h3 class="feature-title">{{ $isEn ? 'Post your ad in 30 seconds' : 'انشر إعلانك في 30 ثانية' }}</h3>
                <p class="feature-text">
                    {{ $isEn
                        ? 'Our simple design lets you add your ad easily, whether you are selling a car, looking for a job, or listing a property for rent.'
                        : 'تصميمنا البسيط يتيح لك إضافة إعلانك بكل سهولة، سواء كنت تبيع سيارة، أو تبحث عن وظيفة، أو تعرض عقارًا للإيجار.'
                    }}
                </p>
            </article>

            <!-- Feature 3 -->
            <article class="feature-card" style="--delay: .25s">
                <div class="feature-icon" aria-hidden="true">
                    <!-- sparkles icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3l2 4 4 2-4 2-2 4-2-4-4-2 4-2z"></path>
                        <path d="M19 13l1 2 2 1-2 1-1 2-1-2-2-1 2-1z"></path>
                    </svg>
                </div>
                <h3 class="feature-title">{{ $isEn ? 'Why are we better?' : 'لماذا نحن أفضل؟' }}</h3>
                <p class="feature-text">
                    {{ $isEn
                        ? 'We provide advanced search features, better-organized categories, and 24/7 support to ensure the best possible experience.'
                        : 'نقدم ميزات متقدمة للبحث، وفئات أكثر تنظيمًا، ودعم فني على مدار الساعة لضمان أفضل تجربة ممكنة.'
                    }}
                </p>
            </article>
        </div>
    </div>
</section>