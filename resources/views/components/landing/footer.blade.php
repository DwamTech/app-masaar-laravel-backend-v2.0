<footer class="landing-footer bg-black">
    <div class="container mx-auto px-6 py-8">
        <div class="footer-bar text-sm">
             <div class="footer-right text-white">
                @php $isEn = request()->is('en'); @endphp
                <a href="/privacy-policy" class="hover:text-indigo-300 hover:underline">{{ $isEn ? 'Privacy Policy' : 'سياسة الخصوصية' }}</a>
                <span class="mx-2 text-gray-500">-</span>
                <a href="/terms" class="hover:text-indigo-300 hover:underline">{{ $isEn ? 'Terms of Service' : 'شروط الخدمة' }}</a>
            </div>
            <div class="footer-center text-gray-300">
              {{ $isEn ? 'All rights reserved. © 2025 DUBISALE FOR MARKETING AND PR.' : 'جميع الحقوق محفوظة.  © 2025 DUBISALE FOR MARKETING AND PR.' }}
            </div>
           
            <div class="footer-left text-gray-400">
                {{ $isEn ? 'Sole proprietorship licensed in Dubai, United Arab Emirates.' : 'مؤسسة فردية مرخصة في دبي، الإمارات العربية المتحدة.' }}
            </div>
        </div>
    </div>
</footer>