<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Privacy Policy</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/css/privacy.css', 'resources/js/app.js'])
        @endif
    </head>
    <body>
        <x-landing.header />

        <main class="policy-wrapper" dir="ltr">
            <div class="policy-card">
                <header class="policy-header">
                    <h1 class="policy-title">Privacy Policy for Dubisale</h1>
                    <p class="policy-subtitle">Your privacy matters. Please read this carefully.</p>
                </header>

                <section class="policy-content">
                    <p>
                        Dubisale ("we," "our," or "us") respects your privacy and is committed to protecting the personal
                        information you share with us. This Privacy Policy explains how we collect, use, store, and protect your
                        information when you use our mobile application and related services.
                    </p>
                    <p>
                        By using Dubisale, you agree to the terms described in this Privacy Policy. If you do not agree, please
                        discontinue using the application.
                    </p>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>1. Introduction</h2>
                    <p>
                        This policy outlines the types of data we collect, our purposes for processing, and your rights. It applies
                        to our mobile application and any related services or features.
                    </p>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>2. Information We Collect</h2>
                    <ul class="bullet-list">
                        <li>
                            <strong>Personal Information:</strong> Name, email address, phone number, and other details provided
                            during registration or when posting ads.
                        </li>
                        <li>
                            <strong>Usage Information:</strong> Data on how you use the app, including browsing patterns, search
                            queries, and interaction with ads.
                        </li>
                        <li>
                            <strong>Device Information:</strong> Device model, operating system, IP address, and unique identifiers.
                        </li>
                        <li>
                            <strong>Location Data:</strong> Approximate or precise location (if you allow access).
                        </li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>3. How We Use Your Information</h2>
                    <ul class="bullet-list">
                        <li>To create and manage user accounts.</li>
                        <li>To allow you to post and manage ads.</li>
                        <li>To improve the app’s functionality, performance, and user experience.</li>
                        <li>To communicate with you about updates, promotions, or support.</li>
                        <li>To detect and prevent fraudulent, unauthorized, or illegal activities.</li>
                        <li>To comply with legal obligations.</li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>4. Sharing of Information</h2>
                    <p>
                        We do not sell or rent your personal data to third parties. However, we may share information in the following
                        cases:
                    </p>
                    <ul class="bullet-list">
                        <li>With service providers who help operate the app (e.g., payment processors, hosting providers).</li>
                        <li>If required by law, regulation, or court order.</li>
                        <li>In case of a business transfer such as a merger, acquisition, or sale of assets.</li>
                        <li>With other users (for example, your contact details may be visible on your ads).</li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>5. Data Security</h2>
                    <p>
                        We implement appropriate technical and organizational measures to protect your personal information from
                        unauthorized access, loss, misuse, or disclosure. However, no method of transmission or storage is 100%
                        secure, and we cannot guarantee absolute security.
                    </p>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>6. Data Retention</h2>
                    <p>
                        We retain your personal information for as long as necessary to provide services, comply with legal obligations,
                        resolve disputes, and enforce our agreements.
                    </p>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>7. Your Rights</h2>
                    <p>Depending on your jurisdiction, you may have the right to:</p>
                    <ul class="bullet-list">
                        <li>Access and request a copy of the personal data we hold about you.</li>
                        <li>Request correction or deletion of your personal data.</li>
                        <li>Object to or restrict the processing of your data.</li>
                        <li>Withdraw your consent at any time (for data collected on the basis of consent).</li>
                    </ul>
                    <p>Requests can be made by contacting us (see Section 10).</p>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>8. Third-Party Links & Services</h2>
                    <p>
                        Our app may contain links to third-party websites or services. We are not responsible for the privacy practices
                        or content of such third parties.
                    </p>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>9. Children’s Privacy</h2>
                    <p>
                        The app is not intended for children under 18 years of age. We do not knowingly collect personal information
                        from minors. If we discover such information has been provided, it will be deleted immediately.
                    </p>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>10. Contact Us</h2>
                    <p>If you have questions, concerns, or requests related to this Privacy Policy, you can contact us at:</p>
                    <ul class="contact-list">
                        <li>Email: support@[appname].com</li>
                        <li>Phone: [Insert number]</li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>11. Changes to This Privacy Policy</h2>
                    <p>
                        We may update this Privacy Policy from time to time. Any changes will be posted within the application, and
                        continued use of the app after such updates constitutes acceptance of the revised policy.
                    </p>
                </section>
            </div>
        </main>

        <x-landing.footer />
    </body>
</html>