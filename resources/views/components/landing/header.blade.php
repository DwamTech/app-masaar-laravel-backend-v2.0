<nav class="landing-header">
    <div class="container mx-auto px-6 py-4">
        <a href="/" class="flex items-center gap-3">
            <img src="{{ asset('/storage/dubisale_logo.png') }}" alt="DubiSale Logo" style="height: 80px; width: auto;" />
        </a>
        @php $isEn = request()->is('en'); @endphp
        <div class="landing-nav">
            <a href="{{ $isEn ? url('/en#about') : url('/#about') }}" class="nav-btn">{{ $isEn ? 'About Us' : 'من نحن' }}</a>
            <a href="{{ $isEn ? url('/en#features') : url('/#features') }}" class="nav-btn">{{ $isEn ? 'Features' : 'ميزاتنا' }}</a>
            <a href="{{ $isEn ? url('/en#contact') : url('/#contact') }}" class="nav-btn nav-btn--accent">{{ $isEn ? 'Contact Us' : 'اتصل بنا' }}</a>
            <a href="{{ $isEn ? url('/') : url('/en') }}" class="nav-btn">
                {{ $isEn ? 'العربية' : 'English' }}
            </a>
        </div>
        <!-- Hamburger button (mobile) -->
        <button id="hamburgerBtn" class="hamburger-btn" aria-label="{{ $isEn ? 'Open menu' : 'فتح القائمة' }}" aria-expanded="false" aria-controls="mobileDrawer">
            <span></span><span></span><span></span>
        </button>
    </div>

    <!-- Mobile overlay and drawer -->
    <div id="mobileOverlay" class="mobile-overlay" hidden></div>
    <aside id="mobileDrawer" class="mobile-drawer" hidden>
        <div class="drawer-header">
            <span class="drawer-title">{{ $isEn ? 'Menu' : 'القائمة' }}</span>
            <button id="drawerClose" class="drawer-close" aria-label="{{ $isEn ? 'Close menu' : 'إغلاق القائمة' }}">&times;</button>
        </div>
        <nav class="drawer-nav">
            <a href="{{ $isEn ? url('/en#about') : url('/#about') }}" class="drawer-link">{{ $isEn ? 'About Us' : 'من نحن' }}</a>
            <a href="{{ $isEn ? url('/en#features') : url('/#features') }}" class="drawer-link">{{ $isEn ? 'Features' : 'ميزاتنا' }}</a>
            <a href="{{ $isEn ? url('/en#contact') : url('/#contact') }}" class="drawer-link">{{ $isEn ? 'Contact Us' : 'اتصل بنا' }}</a>
            <a href="{{ $isEn ? url('/') : url('/en') }}" class="drawer-link">{{ $isEn ? 'العربية' : 'English' }}</a>
        </nav>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('hamburgerBtn');
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('mobileOverlay');
            const closeBtn = document.getElementById('drawerClose');

            function openDrawer() {
                drawer.removeAttribute('hidden');
                overlay.removeAttribute('hidden');
                btn.classList.add('active');
                btn.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
                // allow CSS transitions to fire
                setTimeout(() => {
                    drawer.classList.add('open');
                    overlay.classList.add('visible');
                }, 10);
            }

            function closeDrawer() {
                drawer.classList.remove('open');
                overlay.classList.remove('visible');
                btn.classList.remove('active');
                btn.setAttribute('aria-expanded', 'false');
                setTimeout(() => {
                    drawer.setAttribute('hidden', '');
                    overlay.setAttribute('hidden', '');
                    document.body.style.overflow = '';
                }, 200);
            }

            btn && btn.addEventListener('click', () => {
                if (drawer.hasAttribute('hidden')) {
                    openDrawer();
                } else {
                    closeDrawer();
                }
            });
            overlay && overlay.addEventListener('click', closeDrawer);
            closeBtn && closeBtn.addEventListener('click', closeDrawer);
            drawer.querySelectorAll('a').forEach(a => a.addEventListener('click', closeDrawer));
        });
    </script>
</nav>