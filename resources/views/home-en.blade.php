<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Home</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900">
        <x-landing.header />
        <x-landing.hero />

        <main class="container mx-auto px-6">
            <x-landing.about />
            <x-landing.features />
            <x-landing.contact />
        </main>
<div dir="rtl">
        <x-landing.footer />
</div>
    </body>
</html>