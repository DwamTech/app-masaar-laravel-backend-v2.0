<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Terms & Conditions</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/css/privacy.css', 'resources/js/app.js'])
        @endif
    </head>
    <body>
        <x-landing.header />

        <main class="policy-wrapper" dir="ltr">
            <div class="policy-card">
                <header class="policy-header">
                    <h1 class="policy-title">Terms & Conditions for Dubisale</h1>
                    <p class="policy-subtitle">Please review these terms before using the app.</p>
                </header>

                <section class="policy-content">
                    <h2>1. Acceptance of Terms</h2>
                    <p>
                        By accessing or using Dubisale, you agree to be bound by these Terms and Conditions, as well as all applicable
                        laws and regulations. If you do not agree with any of these terms, please do not use the application.
                    </p>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>2. Services Provided</h2>
                    <ul class="bullet-list">
                        <li>The application allows users to post classified ads to offer or request products and services.</li>
                        <li>The app does not interfere with the content of the ads or the transactions between users.</li>
                        <li>The app acts solely as a platform/mediator and bears no responsibility for any transactions conducted between users.</li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>3. Eligibility</h2>
                    <ul class="bullet-list">
                        <li>Users must be at least 18 years old to use the application.</li>
                        <li>Users must provide accurate and truthful information during registration or when posting ads.</li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>4. User Obligations</h2>
                    <ul class="bullet-list">
                        <li>Users must not post any content that is illegal, offensive, misleading, or infringes on intellectual property rights.</li>
                        <li>Users must not publish false, misleading, or inappropriate advertisements.</li>
                        <li>Users must not use the application for unlawful or harmful purposes.</li>
                        <li>Users bear full responsibility for any content they post or any transactions they engage in.</li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>5. App Rights</h2>
                    <ul class="bullet-list">
                        <li>The app reserves the right to modify or remove any advertisement that violates these terms.</li>
                        <li>The app reserves the right to suspend or terminate the account of any user who violates these terms.</li>
                        <li>These terms may be updated or modified at any time, and users will be notified through the application.</li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>6. Fees and Payments (if applicable)</h2>
                    <ul class="bullet-list">
                        <li>Some services within the application may be free, while others may require payment.</li>
                        <li>Pricing details and payment terms will be clearly stated in the app before confirming a purchase.</li>
                        <li>Paid fees are non-refundable once the service has been used, except in cases determined by the app.</li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>7. Limitation of Liability</h2>
                    <ul class="bullet-list">
                        <li>The app is not responsible for the accuracy, legality, or reliability of ads posted by users.</li>
                        <li>The app is not liable for any losses or damages resulting from user-to-user transactions.</li>
                        <li>Use of the application is entirely at the user’s own risk.</li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>8. Privacy</h2>
                    <ul class="bullet-list">
                        <li>The app is committed to protecting user data in accordance with its Privacy Policy.</li>
                        <li>By using the app, you consent to the collection, processing, and use of your personal information for operational purposes.</li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>9. Governing Law & Jurisdiction</h2>
                    <ul class="bullet-list">
                        <li>These Terms and Conditions are governed by the laws of [Insert Country].</li>
                        <li>The courts of [Insert City/Country] shall have exclusive jurisdiction over any disputes arising from the use of the application.</li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>10. Contact</h2>
                    <p>For inquiries or complaints, please contact us via:</p>
                    <ul class="contact-list">
                        <li>Email: support@[appname].com</li>
                        <li>Phone: [Insert number]</li>
                    </ul>

                    <div class="divider" aria-hidden="true"></div>

                    <h2>11. Changes to These Terms</h2>
                    <p>
                        We may update these Terms and Conditions from time to time. Any changes will be posted within the application,
                        and continued use of the app after such updates constitutes acceptance of the revised policy.
                    </p>
                </section>
            </div>
        </main>

        <x-landing.footer />
    </body>
</html>