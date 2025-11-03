<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #3490dc;
            --secondary-blue: #3490dc;
            --light-blue: #e3f2fd;
            --primary-gray: #6c757d;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
            --white: #ffffff;
            --shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-lg: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        body { 
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .sidebar {
            min-width: 300px;
            max-width: 300px;
            background: linear-gradient(145deg, #1e3c72 0%, #2a5298 35%, #3490dc 100%);
            color: var(--white);
            min-height: 100vh;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 50%),
                linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.02) 50%, transparent 70%);
            pointer-events: none;
            animation: subtleGlow 8s ease-in-out infinite alternate;
        }
        
        @keyframes subtleGlow {
            0% { opacity: 0.8; }
            100% { opacity: 1; }
        }
        
        .sidebar .logo-section {
            padding: 1.5rem 1.2rem;
            text-align: right;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin: 1rem 1rem 1.2rem;
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: row;
            align-items: center;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%);
            border-radius: 20px;
            backdrop-filter: blur(15px);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2),
                inset 0 -1px 0 rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .sidebar .logo-section:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 12px 40px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -1px 0 rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 18px;
            margin: 0 1rem 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 
                0 8px 25px rgba(0, 0, 0, 0.12),
                0 0 0 2px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            font-size: 1.8rem;
            color: var(--primary-blue);
            font-weight: bold;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .sidebar .logo::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(52, 144, 220, 0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s ease;
            opacity: 0;
        }
        
        .sidebar .logo:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 
                0 15px 40px rgba(0, 0, 0, 0.2),
                0 0 0 3px rgba(255, 255, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }
        
        .sidebar .logo:hover::before {
            opacity: 1;
            animation: shimmer 1.5s ease-in-out;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .logo-image {
            width: 45px;
            height: 45px;
            object-fit: contain;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        .sidebar .logo-text {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: right;
            direction: ltr;
        }
        
        .sidebar h4 {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0 0 0.3rem 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, #ffffff 0%, #e3f2fd 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.3px;
            position: relative;
            line-height: 1.2;
        }
        
        .sidebar .logo-subtitle {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 400;
            margin: 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            opacity: 0.9;
        }
        
        .sidebar .nav-pills {
            position: relative;
            z-index: 1;
            
        }
        
        .sidebar .nav-link {
            color: var(--white);
            text-decoration: none;
            padding: 1rem 1.5rem;
            margin: 0.4rem 1.2rem;
            border-radius: 16px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            
        }
        
        .sidebar .nav-link i {
            margin-left: 0.75rem;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
            transition: all 0.3s ease;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
        }
        
        .sidebar .nav-link:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            color: var(--white);
            transform: translateX(-8px) scale(1.02);
            box-shadow: 
                0 8px 25px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar .nav-link:hover i {
            transform: scale(1.1) rotate(5deg);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }
        
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #1e40af;
            font-weight: 700;
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.2),
                0 0 0 2px rgba(255, 255, 255, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            transform: translateX(-5px) scale(1.02);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .sidebar .nav-link.active i {
            color: #ffffff;
            transform: scale(1.1);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.4));
            font-weight: 600;
        }
        
        /* أنماط القائمة الفرعية المحسنة */
        .dropdown-nav {
            position: relative;
            overflow: hidden;
        }
        
        .dropdown-nav .dropdown-toggle {
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            user-select: none;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
        }
        
        .dropdown-nav .dropdown-toggle:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.08) 100%) !important;
            transform: translateX(-8px) scale(1.02);
            box-shadow: 
                0 8px 25px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .dropdown-arrow {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 300;
            margin-right: auto;
            margin-left: 0.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }
        
        .dropdown-nav:hover .dropdown-arrow {
            opacity: 1;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .submenu-link {
            padding: 0.85rem 1.4rem !important;
            margin: 0.3rem 0.8rem !important;
            font-size: 0.9rem !important;
            border-radius: 14px !important;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.06) 0%, rgba(255, 255, 255, 0.02) 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-left: 3px solid rgba(255, 255, 255, 0.2) !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: relative !important;
            overflow: hidden !important;
            backdrop-filter: blur(8px) !important;
        }
        
        .submenu-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s ease;
        }
        
        .submenu-link:hover::before {
            left: 100%;
        }
        
        .submenu-link:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.08) 100%) !important;
            border-left-color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            transform: translateX(-8px) scale(1.03) !important;
            box-shadow: 
                0 8px 25px rgba(0, 0, 0, 0.25) ,
                0 0 0 1px rgba(255, 255, 255, 0.2) ,
                inset 0 1px 0 rgba(255, 255, 255, 0.1) !important;
        }
        
        .submenu-link:active {
            transform: translateX(-3px) scale(0.98) !important;
        }
        
        .submenu-link small {
            font-size: 0.65rem;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        
        .submenu-link:hover small {
            opacity: 1;
        }
        
        .dropdown-toggle .bi-chevron-down {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.9rem;
        }
        
        .dropdown-toggle[aria-expanded="true"] .bi-chevron-down {
            transform: rotate(180deg);
        }
        
        .dropdown-toggle[aria-expanded="true"] {
            background: rgba(255, 255, 255, 0.08) !important;
            border-radius: 12px 12px 0 0;
        }
        
        /* أنماط عنوان إدارة التطبيق */
        .app-management-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.06) 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 16px !important;
            margin: 0.4rem 1.2rem !important;
            padding: 1rem 1.5rem !important;
            cursor: default !important;
            backdrop-filter: blur(12px) !important;
            box-shadow: 
                0 6px 20px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2) !important;
            position: relative !important;
            overflow: hidden !important;
        }
        
        .app-management-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
            animation: headerShimmer 3s ease-in-out infinite;
        }
        
        @keyframes headerShimmer {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }
        
        .app-management-header .nav-primary {
            font-weight: 700 !important;
            font-size: 1.1rem !important;
            color: #ffffff !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4) !important;
        }
        
        .app-management-header .nav-secondary {
            font-size: 0.8rem !important;
            color: rgba(255, 255, 255, 0.9) !important;
            font-style: italic !important;
        }
        
        .app-management-header i {
            color: #ffffff !important;
            font-size: 1.3rem !important;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.4)) !important;
        }
        
        /* أنماط القائمة الفرعية القابلة للطي */
        .app-management-submenu {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.15) 0%, rgba(0, 0, 0, 0.08) 50%, rgba(0, 0, 0, 0.03) 100%);
            border-radius: 16px;
            width: 95%;
            margin: 0.5rem 0.4rem 1rem 0.4rem;
            padding-top: 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* إخفاء القائمة الفرعية عند الإغلاق */
        .app-management-submenu:not(.show) {
            max-height: 0;
            padding-top: 0;
            padding-bottom: 0;
            margin-top: 0;
            margin-bottom: 0;
            opacity: 0;
            transform: translateY(-10px);
            border-width: 0;
        }
        
        /* إظهار القائمة الفرعية عند الفتح */
        .app-management-submenu.show {
            max-height: 800px;
            opacity: 1;
            transform: translateY(0);
            padding-bottom: 0.8rem;
        }
        
        .app-management-submenu .submenu-link {
            /* padding: 0.7rem 1.2rem !important; */
            /* margin: 0.25rem 0.4rem !important; */
            width: 100%;
            font-size: 0.85rem !important;
            margin-right: -1.2rem !important; 
            
        }
        
        /* تأثيرات الحركة للقائمة */
        .collapsing {
            transition: height 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        .collapse.show {
            animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* أنماط النصوص ثنائية اللغة */
        .nav-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.3;
            width: 100%;
        }
        
        .nav-primary {
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            margin-bottom: 3px;
            direction: rtl;
            text-align: right;
            width: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .nav-secondary {
            font-size: 0.75rem;
            font-weight: 400;
            color: #ffffff;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            opacity: 0.85;
            transition: opacity 0.3s ease;
            direction: ltr;
            text-align: left;
            width: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .nav-link:hover .nav-primary {
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
        }
        
        .nav-link:hover .nav-secondary {
            opacity: 1;
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        .nav-link.active .nav-primary {
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(30, 64, 175, 0.2);
            font-weight: 700;
        }
        
        .nav-link.active .nav-secondary {
            color: #ffffff;
            opacity: 0.8;
            font-weight: 500;
        }
        
        .submenu-link .nav-primary {
            /* font-size: 0.9rem; */
            font-weight: 500;
        }
        
        .submenu-link .nav-secondary {
            font-size: 0.7rem;
            font-weight: 300;
        }
        
        .submenu-link:hover .nav-primary {
            color: #ffffff;
        }
        
        .submenu-link:hover .nav-secondary {
            color: #ffffff;
            opacity: 1;
        }
        
        /* تحسين الاستجابة للمس والأجهزة المحمولة */
        @media (hover: none) {
            .submenu-link:hover {
                transform: none !important;
            }
            
            .dropdown-nav .dropdown-toggle:hover {
                transform: none !important;
            }
            
            .sidebar .nav-link:hover {
                transform: none !important;
            }
            
            .sidebar .logo:hover {
                transform: none !important;
            }
        }
        
        /* تحسينات للأجهزة المحمولة */
        @media (max-width: 768px) {
            .sidebar {
                min-width: 280px;
                max-width: 280px;
            }
            
            .sidebar .logo-section {
                padding: 2rem 1rem;
                margin: 0.8rem 0.8rem 1.2rem;
            }
            
            .sidebar .logo {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }
            
            .sidebar h4 {
                font-size: 1.4rem;
            }
            
            .sidebar .nav-link {
                padding: 0.8rem 1.2rem;
                margin: 0.3rem 1rem;
                font-size: 0.9rem;
            }
            
            .submenu-link {
                padding: 0.7rem 1.2rem !important;
                margin: 0.25rem 0.6rem !important;
                font-size: 0.85rem !important;
            }
            
            /* تكبير حجم أسماء الصفحات الفرعية في القوائم المنسدلة على الموبايل */
            .submenu-link .nav-primary {
                font-size: 1.05rem !important;
                line-height: 1.35 !important;
            }
            
            .sidebar .user-info {
                padding: 1.2rem;
                margin: 0 1rem 1.5rem;
            }
            
            .logout-btn {
                padding: 0.8rem 1.2rem;
                margin: 1rem;
                font-size: 0.9rem;
            }
        }
        
        /* تأثيرات إضافية للتفاعل */
        .nav-link {
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-50%);
            border-radius: 2px;
        }
        
        .nav-link:hover::after {
            width: 85%;
        }
        
        .nav-link.active::after {
            width: 90%;
            background: linear-gradient(90deg, transparent, var(--primary-blue), transparent);
            height: 4px;
        }
        
        /* تأثيرات الحركة المتقدمة */
        @keyframes pulseGlow {
            0%, 100% {
                box-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
            }
            50% {
                box-shadow: 0 0 20px rgba(255, 255, 255, 0.6), 0 0 30px rgba(255, 255, 255, 0.4);
            }
        }
        
        .sidebar .nav-link.active {
            animation: pulseGlow 3s ease-in-out infinite;
        }
        
        /* تحسين الانتقالات */
        .sidebar * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* تأثير الضوء المتحرك */
        .sidebar::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.03), transparent);
            animation: movingLight 10s linear infinite;
            pointer-events: none;
            z-index: 0;
        }
        
        @keyframes movingLight {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .sidebar .user-info {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.08) 100%);
            border-radius: 10px;
            padding: 0 1.5rem ;
            margin: 0 1.2rem 0.8rem;
            direction: ltr;
            backdrop-filter: blur(15px);
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2),
                inset 0 -1px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .sidebar .user-info:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 12px 40px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -1px 0 rgba(0, 0, 0, 0.05);
        }
        
        .sidebar .user-info strong {
            font-size: 1.2rem;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            display: block;
            margin-bottom: -1.5rem;
        }
        
        .sidebar .user-info small {
            opacity: 0.85;
            font-size: 0.85rem;
            font-weight: 400;
            letter-spacing: 0.3px;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--white);
            padding: 1rem 1.5rem;
            border-radius: 16px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 1.2rem;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
            box-shadow: 
                0 6px 20px rgba(220, 53, 69, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .logout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }
        
        .logout-btn:hover {
            background: linear-gradient(135deg, #e74c3c 0%, #dc3545 100%);
            transform: translateY(-3px) scale(1.02);
            box-shadow: 
                0 10px 30px rgba(220, 53, 69, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .logout-btn:hover::before {
            left: 100%;
        }
        
        .logout-btn:active {
            transform: translateY(-1px) scale(0.98);
        }
        
        .main-content {
            background: var(--light-gray);
            min-height: 100vh;
            position: relative;
        }
        
        .content-wrapper {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        /* تحسينات للكروت */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }
        
        .card-header {
            background: var(--white);
            border-bottom: 1px solid #e9ecef;
            border-radius: 16px 16px 0 0 !important;
            padding: 1.5rem;
        }
        
        .text-primary {
            color: var(--primary-blue) !important;
        }
        
        .btn-primary {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: var(--secondary-blue);
            border-color: var(--secondary-blue);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }
        .mb-4 {
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(248, 249, 250, 0.8) 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 16px rgba(0, 123, 255, 0.08);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 123, 255, 0.1);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            
        }
        
        .mb-4::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.08), transparent);
            transition: left 0.5s ease;
            
        }
        
        .mb-4:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 123, 255, 0.12);
            border-color: rgba(0, 123, 255, 0.2);
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.08) 0%, rgba(248, 249, 250, 0.9) 100%);
        }
        
        .mb-4:hover::before {
            left: 100%;
        }
        
        .mb-4 * {
            position: relative;
            z-index: 1;
            
        }
        .row{
            width: 100%;
        }
        
        /* تحسينات للجداول */
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table thead th {
            background: var(--primary-blue);
            color: var(--white);
            border: none;
            font-weight: 600;
            padding: 1rem;
        }
        
        .table tbody tr:hover {
            background: rgba(0, 123, 255, 0.05);
        }
        
        /* تحسينات للنماذج */
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: var(--white);
            border-radius: 16px 16px 0 0;
            border: none;
        }
        
        .modal-header .btn-close {
            filter: invert(1);
        }
            .nav-pills {
        /* background: #f8f9fa; */
        border-radius: 12px;
        padding: 0.5rem;
    }
    
    .nav-pills .nav-link {
        border-radius: 8px;
        margin: 0.3rem 0.25rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        color: var(--light-gray);
        background-color: var(--primary-gray);
        transition: all 0.3s ease;
        border: none;
    }
    
    .nav-pills .nav-link:hover {
        background: rgba(0, 123, 255, 0.1);
        color: var(--dark-gray);
    }
    
    .nav-pills .nav-link.active {
        background: var(--primary-blue);
        color: var(--white);
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
    }
        /* تصميم مربع البحث المتقدم */
        .search-container {
            position: relative;
            max-width: 500px;
            margin: 0 auto 2rem;
        }
        
        .search-box {
            position: relative;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            padding: 0.75rem 3rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(20px);
            border: 2px solid transparent;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            margin-bottom: 5%;
        }
        
        .search-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, var(--primary-orange), #ff9500, var(--primary-orange));
            background-size: 200% 200%;
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
            animation: gradientShift 3s ease infinite;
        }
        
        .search-box:hover::before,
        .search-box:focus-within::before {
            opacity: 0.1;
        }
        
        .search-box:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 12px 40px rgba(252, 135, 0, 0.2);
            border-color: rgba(252, 135, 0, 0.3);
        }
        
        .search-box:focus-within {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 15px 50px rgba(252, 135, 0, 0.3);
            border-color: var(--primary-orange);
        }
        
        .search-input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--dark-gray);
            padding: 0.5rem 3rem 0.5rem 1rem;
            letter-spacing: 0.5px;
        }
        
        .search-input::placeholder {
            color: rgba(108, 117, 125, 0.7);
            font-weight: 400;
            transition: all 0.3s ease;
        }
        
        .search-input:focus::placeholder {
            color: rgba(108, 117, 125, 0.4);
            transform: translateX(5px);
        }
        
        .search-icon {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-orange);
            font-size: 1.3rem;
            transition: all 0.4s ease;
            cursor: pointer;
        }
        
        .search-box:hover .search-icon {
            transform: translateY(-50%) rotate(90deg) scale(1.1);
            color: #e67600;
        }
        
        .search-box:focus-within .search-icon {
            transform: translateY(-50%) rotate(180deg) scale(1.2);
            color: var(--primary-orange);
        }
        
        /* تأثيرات إضافية للبحث */
        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 0 0 20px 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(20px);
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }
        
        .search-suggestions.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: all;
        }
        
        .search-suggestion-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .search-suggestion-item:hover {
            background: rgba(252, 135, 0, 0.1);
            transform: translateX(5px);
        }
        
        .search-suggestion-item i {
            margin-left: 0.75rem;
            color: var(--primary-orange);
            font-size: 1.1rem;
        }
        
        /* تحريك الخلفية */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* تأثيرات الاستجابة */
        @media (max-width: 768px) {
            .search-container {
                max-width: 100%;
                margin: 0 0 1.5rem;
            }
            
            .search-box {
                padding: 0.6rem 1.2rem;
            }
            
            .search-input {
                font-size: 1rem;
                padding: 0.4rem 2.5rem 0.4rem 0.8rem;
            }
            
            .search-icon {
                right: 1.2rem;
                font-size: 1.2rem;
            }
        }
        /* تصميم حديث ومبدع لنافذة التفاصيل */
    .modern-details-modal .modal-dialog {
        max-width: 95vw;
    }

    .modern-modal-content {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #ffffff 100%);
        border-radius: 25px;
        box-shadow: 
            0 25px 80px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(252, 135, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        border: none;
        overflow: hidden;
        backdrop-filter: blur(10px);
        position: relative;
    }

    .modern-modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #FC8700, #ff9500, #FC8700);
        background-size: 200% 100%;
        animation: gradientShift 3s ease-in-out infinite;
    }

    .modern-modal-header {
        background: linear-gradient(135deg, #FC8700 0%, #ff9500 30%, #FC8700 70%, #e67600 100%);
        color: white;
        border: none;
        padding: 25px 35px;
        position: relative;
        overflow: hidden;
    }

    .modern-modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shimmerFlow 4s infinite;
    }

    .modern-modal-title {
        font-size: 1.6rem;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        z-index: 2;
    }

    .title-icon-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .title-icon {
        font-size: 2rem;
        animation: iconPulse 2s ease-in-out infinite;
        position: relative;
        z-index: 2;
    }

    .icon-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 50px;
        height: 50px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, transparent 70%);
        border-radius: 50%;
        animation: glowPulse 2s ease-in-out infinite;
    }

    .title-text {
        background: linear-gradient(45deg, #ffffff, #f0f0f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .title-decoration {
        flex: 1;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
        border-radius: 1px;
    }

    .modern-close-btn {
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        position: relative;
        z-index: 3;
    }

    .modern-close-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: rotate(90deg) scale(1.15);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .modern-close-btn i {
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .modern-modal-body {
        padding: 35px;
        background: linear-gradient(135deg, #fafbfc 0%, #ffffff 50%, #f8f9fa 100%);
        position: relative;
    }
    
    /* أنماط التفاصيل الحديثة */
    .details-container {
        display: grid;
        gap: 20px;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
    
    .details-item {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(252, 135, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .details-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #FC8700, #ff9500);
        border-radius: 0 15px 15px 0;
    }
    
    .details-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(252, 135, 0, 0.15);
    }
    
    .details-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }
    
    .details-label i {
        color: #FC8700;
        font-size: 1.1rem;
    }
    
    .details-content {
        margin-right: 25px;
    }
    
    .details-value {
        color: #555;
        font-weight: 500;
        line-height: 1.5;
    }
    
    .details-empty {
        color: #999;
        font-style: italic;
        opacity: 0.7;
    }
    
    .details-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .badge-success {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.2);
    }
    
    .badge-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }
    
    .badge-warning {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }
    
    .badge-info {
        background: rgba(252, 135, 0, 0.1);
        color: #FC8700;
        border: 1px solid rgba(252, 135, 0, 0.2);
    }
    
    .details-image-container {
        position: relative;
        display: inline-block;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .details-image-container:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    
    .details-image {
        max-width: 120px;
        max-height: 80px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .details-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
        border-radius: 12px;
    }
    
    .details-image-container:hover .details-image-overlay {
        opacity: 1;
    }
    
    .details-image-overlay i {
        color: white;
        font-size: 1.5rem;
    }
    
    .details-nested {
        margin-right: 15px;
        border-right: 2px solid rgba(252, 135, 0, 0.2);
        padding-right: 15px;
    }
    
    .nested-item {
        margin-bottom: 15px;
        background: rgba(252, 135, 0, 0.03);
        border-radius: 10px;
        padding: 15px;
        border: 1px solid rgba(252, 135, 0, 0.1);
    }
   .nav-pills{
    justify-content: center;
   }
    
    /* الرسوم المتحركة الجديدة */
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    @keyframes shimmerFlow {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    @keyframes iconPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    @keyframes glowPulse {
         0%, 100% { opacity: 0.4; transform: translate(-50%, -50%) scale(1); }
         50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.2); }
     }
     
     /* التصميم المتجاوب للشاشات الصغيرة */
     @media (max-width: 768px) {
         .modern-details-modal .modal-dialog {
             max-width: 98vw;
             margin: 10px;
         }
         
         .modern-modal-header {
             padding: 20px 25px;
         }
         
         .modern-modal-title {
             font-size: 1.3rem;
             gap: 15px;
         }
         
         .title-icon {
             font-size: 1.6rem;
         }
         
         .modern-modal-body {
             padding: 25px 20px;
         }
         
         .details-container {
             grid-template-columns: 1fr;
             gap: 15px;
         }
         
         .details-item {
             padding: 15px;
         }
         
         .details-label {
             font-size: 0.9rem;
         }
         
         .details-image {
             max-width: 100px;
             max-height: 70px;
         }
     }
     
     @media (max-width: 480px) {
         .modern-modal-header {
             padding: 15px 20px;
         }
         
         .modern-modal-title {
             font-size: 1.1rem;
             gap: 10px;
         }
         
         .title-icon {
             font-size: 1.4rem;
         }
         
         .modern-close-btn {
             width: 35px;
             height: 35px;
         }
         
         .modern-close-btn i {
             font-size: 1rem;
         }
         
         .modern-modal-body {
             padding: 20px 15px;
         }
         
         .details-item {
             padding: 12px;
         }
         
         .details-label {
             font-size: 0.85rem;
             gap: 8px;
         }
         
         .details-content {
             margin-right: 15px;
         }
         
         .details-image {
             max-width: 80px;
             max-height: 60px;
         }
     }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* ========= Mobile Header & Sidebar Toggle ========= */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            height: 75px;
            background: #294B86;
            border-bottom: 1px solid #e5e7eb;
            z-index: 1040;
            padding: 0 12px;
            align-items: center;
            justify-content: space-between;
        }
        .mobile-header .mobile-title {
            font-weight: 600;
            color: #ffffff;
            font-size: 2rem;
        }
        .hamburger-btn {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
        }
        .hamburger-btn .bar,
        .hamburger-btn .bar::before,
        .hamburger-btn .bar::after {
            content: "";
            display: block;
            width: 18px;
            height: 2px;
            background: #111827;
            border-radius: 2px;
            position: relative;
        }
        .hamburger-btn .bar::before { top: -6px; position: absolute; }
        .hamburger-btn .bar::after { top: 6px; position: absolute; }

        .mobile-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 1030;
            display: none;
        }
        .mobile-overlay.show { display: block; }

        @media (max-width: 992px) {
            .mobile-header { display: flex; }
            .main-content { padding-top: 56px; }

            /* Make sidebar offcanvas on mobile */
            .sidebar {
                position: fixed;
                top: 0;
                right: 0; /* RTL-friendly: slide from right */
                height: 100vh;
                z-index: 1050;
                transform: translateX(100%);
                transition: transform 0.25s ease-in-out;
                width: 80vw;           /* عرض 80% على الموبايل */
                max-width: 80vw;
                min-width: 80vw;
            }
            .sidebar.is-open {
                transform: translateX(0);
            }

            /* Ensure content takes full width when sidebar is hidden */
            .d-flex { flex-direction: column; }
            .main-content { width: 100%; }

            /* تكبير عناصر القائمة للوضوح على الموبايل */
            .sidebar .logo {
                width: 68px;
                height: 68px;
                font-size: 2rem;
            }
            .sidebar h4 { font-size: 1.6rem; }
            .sidebar .logo-subtitle { font-size: 1rem; }

            .sidebar .nav-link {
                padding: 1rem 1.4rem;
                font-size: 1rem;
            }
            .sidebar .nav-link i { font-size: 1.4rem; }
            .nav-primary { font-size: 2.05rem; }
            .submenu-link {
                font-size: 0.95rem !important;
                padding: 0.9rem 1.4rem !important;
            }
            .logout-btn {
                font-size: 2.05rem;
            }
            .sidebar .user-info strong {
                font-size: 2rem;
            }
            
        }
    </style>

</head>
<body>
    <script>
        // حماية الصفحة
        if (!localStorage.getItem('token')) {
            window.location.href = '/login';
        }
    </script>
    <header class="mobile-header" dir="rtl">
        <button id="mobileMenuButton" class="hamburger-btn" aria-label="فتح القائمة" aria-expanded="false" aria-controls="dashboardSidebar">
            <span class="bar"></span>
        </button>
        <div class="mobile-title">DubaiSale</div>
    </header>
    <div id="mobileOverlay" class="mobile-overlay" hidden></div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('dashboardSidebar');
            const btn = document.getElementById('mobileMenuButton');
            const overlay = document.getElementById('mobileOverlay');

            if (!sidebar || !btn || !overlay) return;

            function openSidebar() {
                sidebar.classList.add('is-open');
                overlay.classList.add('show');
                overlay.removeAttribute('hidden');
                btn.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            }
            function closeSidebar() {
                sidebar.classList.remove('is-open');
                overlay.classList.remove('show');
                overlay.setAttribute('hidden', '');
                btn.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }
            btn.addEventListener('click', function () {
                if (sidebar.classList.contains('is-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
            overlay.addEventListener('click', closeSidebar);
            window.addEventListener('resize', function () {
                // Reset state when leaving mobile breakpoint
                if (window.innerWidth > 992) {
                    closeSidebar();
                }
            });
        });
    </script>
    <div class="d-flex">
        <nav id="dashboardSidebar" class="sidebar d-flex flex-column">
            <div class="logo-section">
                
                <div class="logo-text">
                    <h4>DubaiSale</h4>
                    <div class="logo-subtitle">لوحة التحكم</div>
                </div>
                <div class="logo">
                    <img src="{{ asset('/storage/uploads/images/dubaisale.jpeg') }}" alt="DubaiSale Logo" class="logo-image">
                </div>
            </div>
            
            <div class="user-info" id="userInfo">
                <!-- سيتم تعبئة معلومات المستخدم هنا -->
            </div>
            
            <ul class="nav nav-pills flex-column mb-auto" dir="rtl">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link">
                        <i class="bi bi-house-door"></i>
                        <div class="nav-text">
                            <span class="nav-primary">الرئيسية</span>
                        </div>
                    </a>
                </li>
                
                
                
                <li class="nav-item dropdown-nav">
                    <div class="nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#appManagementSubmenu" aria-expanded="false" aria-controls="appManagementSubmenu" role="button">
                        <i class="bi bi-gear"></i>
                        <div class="nav-text">
                            <span class="nav-primary">إدارة التطبيق</span>
                        </div>
                        <i class="bi bi-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="collapse app-management-submenu" id="appManagementSubmenu">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="/search-filter-settings" class="nav-link submenu-link">
                                    <i class="bi bi-search"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">البحث والفلتر</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/section-banners" class="nav-link submenu-link">
                                    <i class="bi bi-image"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">تعيين لافتات كل قسم</span>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="/send-notification" class="nav-link submenu-link">
                                    <i class="bi bi-bell"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">إرسال إشعار</span>
                                    </div>
                                </a>
                            </li>
                            
                            {{-- <li class="nav-item">
                                <a href="/blocked-users" class="nav-link submenu-link">
                                    <i class="bi bi-person-x"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">قائمة الحظر</span>
                                    </div>
                                </a>
                            </li> --}}
                            
                            {{-- <li class="nav-item">
                    <a href="/support-messages" class="nav-link submenu-link">
                        <i class="bi bi-headset"></i>
                        <div class="nav-text">
                            <span class="nav-primary">رسائل الدعم الفني</span>
                        </div>
                    </a>
                </li> --}}
                            
                            <li class="nav-item">
                                <a href="/ads-approval" class="nav-link submenu-link">
                                    <i class="bi bi-check-circle"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">الموافقة على الإعلانات</span>
                                    </div>
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </li>
                {{-- <li class="nav-item">
                                <a href="/system-variables" class="nav-link">
                                    <i class="bi bi-sliders"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">متغيرات النظام</span>
                                    </div>
                                </a>
                            </li> --}}
                
                <li class="nav-item dropdown-nav">
                    <div class="nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#sectionsManagementSubmenu" aria-expanded="false" aria-controls="sectionsManagementSubmenu" role="button">
                        <i class="bi bi-grid-3x3-gap"></i>
                        <div class="nav-text">
                            <span class="nav-primary">إدارة الأقسام</span>
                        </div>
                        <i class="bi bi-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="collapse app-management-submenu" id="sectionsManagementSubmenu">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="/sections/car-sale" class="nav-link submenu-link">
                                    <i class="bi bi-car-front"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">Car Sale</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/sections/car-services" class="nav-link submenu-link">
                                    <i class="bi bi-tools"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">Car Services</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/sections/car-rent" class="nav-link submenu-link">
                                    <i class="bi bi-key"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">Car Rent</span>
                                    </div>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="/sections/restaurant" class="nav-link submenu-link">
                                    <i class="bi bi-cup-hot"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">Restaurant</span>
                                    </div>
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a href="/sections/jobs" class="nav-link submenu-link">
                                    <i class="bi bi-briefcase"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">Jobs</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/sections/other-services" class="nav-link submenu-link">
                                    <i class="bi bi-three-dots"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">Other Services</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/sections/real-estate" class="nav-link submenu-link">
                                    <i class="bi bi-house"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">Real Estate</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/sections/electronics" class="nav-link submenu-link">
                                    <i class="bi bi-laptop"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">Electronics</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
               <li class="nav-item">
                                <a href="/best-advertisers" class="nav-link">
                                    <i class="bi bi-star"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">تعيين أفضل المعلنين</span>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="/users-management" class="nav-link">
                                    <i class="bi bi-people-fill"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">إدارة المستخدمين</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/ads-management" class="nav-link">
                                    <i class="bi bi-megaphone"></i>
                                    <div class="nav-text">
                                        <span class="nav-primary">إدارة الإعلانات</span>
                                    </div>
                                </a>
                            </li>
                            <button id="logoutBtn" class="logout-btn">
                <i class="bi bi-box-arrow-right me-2"></i>
                تسجيل الخروج
            </button>
            </ul>

            
        </nav>
        <main class="flex-fill main-content">
            <div class="content-wrapper">
              
                
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // عرض اسم المستخدم من localStorage
        const user = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null;
        if (user) {
            document.getElementById('userInfo').innerHTML = `
                <div>
                    <strong>Admin</strong><br>
                </div>
            `;
        }

        // زر تسجيل الخروج
        document.getElementById('logoutBtn').onclick = function () {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        };
        
        // وظائف مربع البحث المتقدم
        const searchInput = document.getElementById('globalSearch');
        const searchIcon = document.getElementById('searchIcon');
        const searchSuggestions = document.getElementById('searchSuggestions');
        
        // بيانات الاقتراحات
        const searchData = [
            { title: 'إدارة الحسابات', url: '/accounts', icon: 'bi-people' },
            { title: 'إدارة الطلبات', url: '/requests', icon: 'bi-clipboard-check' },
            { title: 'التصاريح الأمنية', url: '/securityPermits', icon: 'bi-shield-check' },
            { title: 'إدارة التطبيق', url: '/appController', icon: 'bi-gear' },
            { title: 'معلومات التطبيق', url: '/AppSettings', icon: 'bi-info-circle' },
            { title: 'إدارة المستخدمين', url: '/users-management', icon: 'bi-people-fill' },
            { title: 'الرئيسية', url: '/dashboard', icon: 'bi-house-door' }
        ];
        
        // البحث والتصفية
        function filterSuggestions(query) {
            if (!query.trim()) {
                searchSuggestions.classList.remove('show');
                return;
            }
            
            const filtered = searchData.filter(item => 
                item.title.includes(query) || 
                item.title.toLowerCase().includes(query.toLowerCase())
            );
            
            if (filtered.length > 0) {
                searchSuggestions.innerHTML = filtered.map(item => `
                    <div class="search-suggestion-item" onclick="navigateTo('${item.url}')">
                        <i class="bi ${item.icon}"></i>
                        <span>${item.title}</span>
                    </div>
                `).join('');
                searchSuggestions.classList.add('show');
            } else {
                searchSuggestions.innerHTML = `
                    <div class="search-suggestion-item">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>لا توجد نتائج</span>
                    </div>
                `;
                searchSuggestions.classList.add('show');
            }
        }
        
        // التنقل إلى الصفحة
        function navigateTo(url) {
            window.location.href = url;
        }
        
        // أحداث مربع البحث - مع فحص وجود العناصر
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                filterSuggestions(e.target.value);
            });
            
            searchInput.addEventListener('focus', function() {
                if (this.value.trim()) {
                    filterSuggestions(this.value);
                }
            });
            
            // البحث عند الضغط على Enter
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const query = this.value.trim();
                    if (query) {
                        const firstMatch = searchData.find(item => 
                            item.title.includes(query) || 
                            item.title.toLowerCase().includes(query.toLowerCase())
                        );
                        if (firstMatch) {
                            navigateTo(firstMatch.url);
                        }
                    }
                }
            });
        }
        
        // إخفاء الاقتراحات عند النقر خارجها
        document.addEventListener('click', function(e) {
            if (searchSuggestions && !e.target.closest('.search-container')) {
                searchSuggestions.classList.remove('show');
            }
        });
        
        // تأثير النقر على أيقونة البحث
        if (searchIcon) {
            searchIcon.addEventListener('click', function() {
                if (searchInput) {
                    const query = searchInput.value.trim();
                    if (query) {
                        const firstMatch = searchData.find(item => 
                            item.title.includes(query) || 
                            item.title.toLowerCase().includes(query.toLowerCase())
                        );
                        if (firstMatch) {
                            navigateTo(firstMatch.url);
                        }
                    } else {
                        searchInput.focus();
                    }
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
  
    @yield('scripts')

<script>
// تحسين وظائف القائمة الجانبية
document.addEventListener('DOMContentLoaded', function() {
    // العناصر الأساسية
    const submenuLinks = document.querySelectorAll('.submenu-link');
    const navLinks = document.querySelectorAll('.nav-link');
    
    // وظيفة الـ dropdown للقائمة الفرعية - إدارة التطبيق
    const dropdownToggle = document.querySelector('[data-bs-target="#appManagementSubmenu"]');
    const submenu = document.getElementById('appManagementSubmenu');
    const dropdownArrow = document.querySelector('.dropdown-arrow');
    
    if (dropdownToggle && submenu && dropdownArrow) {
        // إضافة مستمع الأحداث للنقر
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            if (isExpanded) {
                // إغلاق القائمة
                submenu.classList.remove('show');
                this.setAttribute('aria-expanded', 'false');
                dropdownArrow.style.transform = 'rotate(0deg)';
            } else {
                // فتح القائمة
                submenu.classList.add('show');
                this.setAttribute('aria-expanded', 'true');
                dropdownArrow.style.transform = 'rotate(180deg)';
            }
        });
        
        // تأثير hover للسهم
        dropdownToggle.addEventListener('mouseenter', function() {
            dropdownArrow.style.color = 'rgba(255, 255, 255, 0.9)';
        });
        
        dropdownToggle.addEventListener('mouseleave', function() {
            dropdownArrow.style.color = 'rgba(255, 255, 255, 0.7)';
        });
    }
    
    // وظيفة الـ dropdown للقائمة الفرعية - إدارة الأقسام
    const sectionsDropdownToggle = document.querySelector('[data-bs-target="#sectionsManagementSubmenu"]');
    const sectionsSubmenu = document.getElementById('sectionsManagementSubmenu');
    const sectionsDropdownArrow = sectionsDropdownToggle ? sectionsDropdownToggle.querySelector('.dropdown-arrow') : null;
    
    if (sectionsDropdownToggle && sectionsSubmenu && sectionsDropdownArrow) {
        // إضافة مستمع الأحداث للنقر
        sectionsDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            if (isExpanded) {
                // إغلاق القائمة
                sectionsSubmenu.classList.remove('show');
                this.setAttribute('aria-expanded', 'false');
                sectionsDropdownArrow.style.transform = 'rotate(0deg)';
            } else {
                // فتح القائمة
                sectionsSubmenu.classList.add('show');
                this.setAttribute('aria-expanded', 'true');
                sectionsDropdownArrow.style.transform = 'rotate(180deg)';
            }
        });
        
        // تأثير hover للسهم
        sectionsDropdownToggle.addEventListener('mouseenter', function() {
            sectionsDropdownArrow.style.color = 'rgba(255, 255, 255, 0.9)';
        });
        
        sectionsDropdownToggle.addEventListener('mouseleave', function() {
            sectionsDropdownArrow.style.color = 'rgba(255, 255, 255, 0.7)';
        });
    }
    const navLinks = document.querySelectorAll('.nav-link');
    // تحسين تأثيرات الروابط الفرعية
    submenuLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(-5px) scale(1.02)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0) scale(1)';
        });
        
        link.addEventListener('mousedown', function() {
            this.style.transform = 'translateX(-3px) scale(0.98)';
        });
        
        link.addEventListener('mouseup', function() {
            this.style.transform = 'translateX(-5px) scale(1.02)';
        });
    });
    
    // تحديد الرابط النشط
    const currentPath = window.location.pathname;
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath === href) {
            link.classList.add('active');
        }
    });
    
    // تأثير الموجة عند النقر
    function createRippleEffect(element, event) {
        const ripple = document.createElement('span');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
            z-index: 1;
        `;
        
        element.style.position = 'relative';
        element.style.overflow = 'hidden';
        element.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
    
    // إضافة تأثير الموجة للروابط
    [...navLinks, ...submenuLinks].forEach(link => {
        link.addEventListener('click', function(e) {
            createRippleEffect(this, e);
        });
    });
    
    // إضافة أنماط CSS للتأثيرات
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        .nav-link:focus {
            outline: 2px solid var(--primary-blue);
            outline-offset: 2px;
        }
        
        .submenu-link:focus {
            outline: 2px solid var(--white);
            outline-offset: 2px;
        }
        
        /* تحسين الحركة للأجهزة المحمولة */
        @media (max-width: 768px) {
            .dropdown-nav .dropdown-toggle:hover {
                transform: none !important;
            }
            
            .submenu-link:hover {
                transform: none !important;
            }
        }
        
        /* تأثير التحميل */
        .sidebar {
            animation: slideInLeft 0.5s ease-out;
        }
        
        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
    
    // تحسين الأداء مع Intersection Observer
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // مراقبة عناصر القائمة
    submenuLinks.forEach(link => {
        link.style.opacity = '0';
        link.style.transform = 'translateY(20px)';
        link.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        observer.observe(link);
    });
});
</script>

</body>
</html>
