<section id="contact" class="landing-contact py-14">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto text-center">
            @php $isEn = request()->is('en'); @endphp
            <div class="features-badge mx-auto mb-3">{{ $isEn ? 'Ready to help' : 'جاهزون للدعم' }}</div>
            <h2 class="section-title">{{ $isEn ? 'Contact Us' : 'تواصل معنا' }}</h2>
            <p class="section-text mb-8">{{ $isEn ? 'For any business inquiries or technical support, we are happy to connect through the following channels. We respond quickly and professionally.' : 'لأية استفسارات تجارية أو دعم فني، يسعدنا التواصل معكم عبر القنوات التالية. نحن متواجدون للرد بسرعة وباحترافية.' }}</p>

            <div class="contact-card text-start">
                <div class="contact-grid">
                    <a class="contact-item" href="mailto:abdouelsaid84@gmail.com" aria-label="Email">
                        <span class="contact-icon" aria-hidden="true">
                            <!-- mail icon -->
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.6"/>
                                <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div class="contact-content">
                            <span class="contact-label">{{ $isEn ? 'Email' : 'البريد الإلكتروني' }}</span>
                            <span class="contact-value">abdouelsaid84@gmail.com</span>
                        </div>
                    </a>

                    <a class="contact-item" href="tel:+971508236561" aria-label="Phone">
                        <span class="contact-icon" aria-hidden="true">
                            <!-- phone icon -->
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.5 3.5h0a2 2 0 0 1 2 2v2a2 2 0 0 1-.59 1.41L7 10.32a14.5 14.5 0 0 0 6.68 6.68l1.41-0.91A2 2 0 0 1 16.5 15h2a2 2 0 0 1 2 2h0a3 3 0 0 1-3 3c-8.28 0-15-6.72-15-15a3 3 0 0 1 3-3Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div class="contact-content">
                            <span class="contact-label">{{ $isEn ? 'Phone number' : 'رقم الهاتف' }}</span>
                            <span class="contact-value">971-508236561+</span>
                        </div>
                    </a>

                    <div class="contact-item" aria-label="Company">
                        <span class="contact-icon" aria-hidden="true">
                            <!-- building icon -->
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 20h16M6 20V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                <path d="M10 8h0M14 8h0M10 12h0M14 12h0M10 16h0M14 16h0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <div class="contact-content">
                            <span class="contact-label">{{ $isEn ? 'Company' : 'الشركة' }}</span>
                            <span class="contact-value">DUBISALE FOR MARKETING AND PR</span>
                        </div>
                    </div>

                    <div class="contact-item" aria-label="Location">
                        <span class="contact-icon" aria-hidden="true">
                            <!-- location icon -->
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 21s7-6 7-11a7 7 0 1 0-14 0c0 5 7 11 7 11Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.6"/>
                            </svg>
                        </span>
                        <div class="contact-content">
                            <span class="contact-label">{{ $isEn ? 'Location' : 'الموقع' }}</span>
                            <span class="contact-value">{{ $isEn ? 'Dubai, United Arab Emirates' : 'دبي، الإمارات العربية المتحدة' }}</span>
                        </div>
                    </div>
                </div>

                <div class="contact-actions text-center mt-6">
                    <a href="mailto:abdouelsaid84@gmail.com" class="contact-btn" aria-label="Send email">
                        <span class="store-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        {{ $isEn ? 'Email us' : 'راسلنا عبر البريد' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>