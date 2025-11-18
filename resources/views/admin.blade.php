<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - لوحة التحكم</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* فرض خط Cairo على صفحة الأدمن بالكامل */
        html, body { font-family: 'Cairo', sans-serif !important; }
        * { font-family: inherit !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h1 class="text-xl font-bold">لوحة تحكم الأدمن</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-blue-600 hover:text-blue-800">تسجيل الخروج</button>
            </form>
        </div>
    </header>

    <!-- Sidebar and Content -->
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white h-screen p-4">
            <nav>
                <ul>
                    <li><a href="{{ route('dashboard') }}" class="block py-2 px-4 hover:bg-gray-700 rounded">الرئيسية</a></li>
                    <li class="mt-4">
                        <div class="text-gray-300 text-sm mb-2">إدارة العقارات</div>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('admin.properties.index') }}" class="block py-2 px-4 hover:bg-gray-700 rounded">كل العقارات</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.properties.create') }}" class="block py-2 px-4 hover:bg-gray-700 rounded">إضافة عقار</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>