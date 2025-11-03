<section class="landing-hero relative" aria-label="قسم الهيرو">
    <div class="container mx-auto px-6 py-16 lg:py-24 text-center">
        @php $isEn = request()->is('en'); @endphp
        <div class="hero-badge mb-4">Dubisale</div>
        <h1 class="hero-title text-3xl lg:text-5xl font-extrabold mb-4">
            {{ $isEn ? 'Dubisale App: The easiest way to buy and sell in the UAE.' : 'تطبيق دبي سيل : أسهل طريقة للبيع والشراء في الإمارات.' }}
        </h1>
        <p class="hero-text text-lg lg:text-xl mb-10">
            {{ $isEn ? 'Join thousands of users on the fastest-growing classifieds platform. Safe, easy, and reliable.' : 'انضم إلى آلاف المستخدمين على منصة الإعلانات المبوبة الأسرع نموًا. آمن، سهل، وموثوق.' }}
        </p>

        <div class="flex items-center justify-center gap-4 hero-actions">
            <!-- Google Play button -->
            <button disabled aria-disabled="true" aria-label="{{ $isEn ? 'Available on Google Play soon' : 'متوفر على Google Play قريبًا' }}" class="btn-store btn-disabled" type="button">
                @php $isEn = request()->is('en'); @endphp
                @if($isEn)
                    <span class="store-icon" aria-hidden="true">
                        <!-- Simplified Google Play icon -->
                        <img src="{{ asset('/storage/logotype.png') }}" alt="Google Play" width="22" height="22">
                    </span>
                    <span>{{ $isEn ? 'Available on Google Play' : 'متوفر على Google Play' }}</span>
                    <span class="soon-badge" aria-hidden="true">Soon</span>
                @else
                    <span class="soon-badge" aria-hidden="true">Soon</span>
                    <span>{{ $isEn ? 'Available on Google Play' : 'متوفر على Google Play' }}</span>
                    <span class="store-icon" aria-hidden="true">
                        <!-- Simplified Google Play icon -->
                        <img src="{{ asset('/storage/logotype.png') }}" alt="Google Play" width="22" height="22">
                    </span>
                @endif
            </button>

            <!-- App Store button -->
            <button disabled aria-disabled="true" aria-label="{{ $isEn ? 'Available on App Store soon' : 'متوفر على App Store قريبًا' }}" class="btn-store btn-disabled" type="button">
                @if($isEn)
                    <span class="store-icon" aria-hidden="true">
                        <!-- Simplified App Store icon -->
                        <img src="{{ asset('/storage/apple.png') }}" alt="App Store" width="22" height="22">
                    </span>
                    <span>{{ $isEn ? 'Available on App Store' : 'متوفر على App Store' }}</span>
                    <span class="soon-badge" aria-hidden="true">Soon</span>
                @else
                    <span class="soon-badge" aria-hidden="true">Soon</span>
                    <span>{{ $isEn ? 'Available on App Store' : 'متوفر على App Store' }}</span>
                    <span class="store-icon" aria-hidden="true">
                        <!-- Simplified App Store icon -->
                        <img src="{{ asset('/storage/apple.png') }}" alt="App Store" width="22" height="22">
                    </span>
                @endif
            </button>
        </div>
    </div>
</section>