<!DOCTYPE html>
<html lang="ru" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TerraForum') — сообщество садоводов и фермеров</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'Karelle';
            src: url('{{ asset('fonts/Karelle.otf') }}') format('opentype');
            font-display: swap;
        }

        /* === ПАЛИТРА === */
        :root {
            --forest: #3d4f33;
            --forest-dark: #2e3d27;
            --forest-light: #6b8a5c;
            --brown: #b88858;
            --brown-bright: #d4a574;
            --brown-dark: #8c6638;
            --brown-deep: #6b4423;
            --error: #c45653;
            --success: #6b8a5c;
        }

        /* === РЕЖИМЫ === */
        body.mode-sand {
            --bg-page: #f5efe0;
            --bg-card: #ffffff;
            --bg-card-hover: #fffdf8;
            --bg-section-alt: #ebe0c8;
            --bg-input: #ffffff;
            --bg-input-focus: #fffdf8;
            --text-primary: #2a2622;
            --text-secondary: #5c5048;
            --text-muted: #8c7e6a;
            --border-soft: rgba(107, 68, 35, 0.1);
            --border-medium: rgba(107, 68, 35, 0.18);
            --border-strong: rgba(107, 68, 35, 0.3);
            --shadow-soft: 0 4px 16px -8px rgba(107, 68, 35, 0.15);
            --shadow-hover: 0 12px 28px -12px rgba(107, 68, 35, 0.25);
        }

        body.mode-linen {
            --bg-page: #ebe0c8;
            --bg-card: #f5efe0;
            --bg-card-hover: #faf4e4;
            --bg-section-alt: #e0d4b8;
            --bg-input: #f5efe0;
            --bg-input-focus: #fffdf8;
            --text-primary: #2a2622;
            --text-secondary: #5c5048;
            --text-muted: #8c7e6a;
            --border-soft: rgba(107, 68, 35, 0.12);
            --border-medium: rgba(107, 68, 35, 0.22);
            --border-strong: rgba(107, 68, 35, 0.35);
            --shadow-soft: 0 4px 16px -8px rgba(107, 68, 35, 0.12);
            --shadow-hover: 0 12px 28px -12px rgba(107, 68, 35, 0.2);
        }

        body.mode-earth {
            --bg-page: #3a3024;
            --bg-card: #4a3e30;
            --bg-card-hover: #574736;
            --bg-section-alt: #2e2620;
            --bg-input: #2e2620;
            --bg-input-focus: #352c24;
            --text-primary: #ffffff;
            --text-secondary: #e8dcc4;
            --text-muted: #a08c70;
            --border-soft: rgba(212, 189, 158, 0.15);
            --border-medium: rgba(212, 189, 158, 0.25);
            --border-strong: rgba(212, 189, 158, 0.4);
            --shadow-soft: 0 8px 24px -12px rgba(0, 0, 0, 0.4);
            --shadow-hover: 0 16px 40px -16px rgba(0, 0, 0, 0.6);
        }

        /* === БАЗОВЫЕ === */
        * {
            -webkit-font-smoothing: antialiased;
            box-sizing: border-box;
        }

        /* Прижатый футер */
        html, body { height: 100%; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-primary);
            font-weight: 300;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1 0 auto;
        }

        footer {
            flex-shrink: 0;
        }

        h1, h2, h3, h4 {
            font-family: 'Karelle', 'Cormorant Garamond', Georgia, serif;
            font-weight: normal;
            letter-spacing: 0.5px;
            color: var(--text-primary);
        }

        .workspace {
            max-width: 1120px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }

        /* === КАРТОЧКИ === */
        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-soft);
            border-radius: 14px;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .card:hover {
            background-color: var(--bg-card-hover);
            border-color: var(--border-medium);
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }
        .card-flat {
            background-color: var(--bg-card);
            border: 1px solid var(--border-soft);
            border-radius: 14px;
            box-shadow: var(--shadow-soft);
        }

        /* === ТЕКСТ === */
        .text-cream { color: var(--text-primary); }
        .text-secondary-c { color: var(--text-secondary); }
        .text-muted-c { color: var(--text-muted); }
        .text-brown { color: var(--brown-dark); }
        body.mode-earth .text-brown { color: var(--brown-bright); }

        /* === РАЗДЕЛИТЕЛИ === */
        .section-number {
            font-family: 'Karelle', serif;
            font-size: 12px;
            letter-spacing: 3px;
            color: var(--brown-dark);
            text-transform: uppercase;
        }
        body.mode-earth .section-number { color: var(--brown-bright); }

        .divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            font-size: 10px;
            letter-spacing: 3px;
            color: var(--brown-dark);
            text-transform: uppercase;
        }
        body.mode-earth .divider { color: var(--brown-bright); }
        .divider::before, .divider::after {
            content: '';
            height: 1px;
            background: linear-gradient(90deg, transparent, currentColor, transparent);
            flex: 1;
            max-width: 80px;
            opacity: 0.5;
        }

        /* === ШАПКА === */
        .green-header {
            background: linear-gradient(135deg, var(--forest) 0%, var(--forest-dark) 100%);
            border-bottom: 1px solid rgba(0, 0, 0, 0.2);
            box-shadow: 0 4px 20px -8px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 40;
        }

        /* === НАВИГАЦИЯ === */
        .nav-link {
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(245, 239, 224, 0.85);
            position: relative;
            transition: color 0.25s;
            padding: 4px 0;
            text-decoration: none;
            white-space: nowrap;
        }
        .nav-link:hover { color: #ffffff; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--brown-bright);
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .nav-link:hover::after,
        .nav-link.active::after { transform: scaleX(1); }
        .nav-link.active { color: #ffffff; }

        /* === КНОПКА БУРГЕР === */
        .burger-btn {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 36px;
            height: 36px;
            background: transparent;
            border: none;
            cursor: pointer;
            gap: 5px;
            padding: 4px;
        }
        .burger-btn span {
            display: block;
            width: 22px;
            height: 2px;
            background: rgba(245, 239, 224, 0.9);
            border-radius: 2px;
            transition: all 0.3s;
        }
        .burger-btn.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }
        .burger-btn.open span:nth-child(2) {
            opacity: 0;
        }
        .burger-btn.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* === МОБИЛЬНОЕ МЕНЮ === */
        .mobile-menu {
            display: none;
            flex-direction: column;
            background: var(--forest-dark);
            border-top: 1px solid rgba(255,255,255,0.1);
            padding: 16px 24px;
            gap: 4px;
        }
        .mobile-menu.open {
            display: flex;
        }
        .mobile-nav-link {
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(245, 239, 224, 0.85);
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-decoration: none;
            transition: color 0.2s;
        }
        .mobile-nav-link:last-child {
            border-bottom: none;
        }
        .mobile-nav-link:hover,
        .mobile-nav-link.active {
            color: #ffffff;
        }

        /* === КНОПКА РЕГИСТРАЦИИ === */
        .header-reg-btn {
            padding: 7px 16px;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brown-bright), var(--brown));
            color: #2a2218;
            box-shadow: 0 6px 16px -6px rgba(212, 165, 116, 0.5);
            transition: all 0.25s;
            font-weight: 500;
            border: 0;
            cursor: pointer;
            white-space: nowrap;
        }
        .header-reg-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px -8px rgba(212, 165, 116, 0.6);
        }

        /* === КНОПКИ === */
        .btn {
            display: inline-block;
            padding: 10px 24px;
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-weight: 500;
            border: 1px solid currentColor;
            border-radius: 10px;
            transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);
            cursor: pointer;
            background: transparent;
            text-decoration: none;
            text-align: center;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn:active { transform: translateY(0); }

        .btn-light { color: #ffffff; border-color: #ffffff; }
        .btn-light:hover {
            background: #ffffff;
            color: var(--text-primary);
            box-shadow: 0 8px 20px -8px rgba(255, 255, 255, 0.3);
        }
        .btn-filled {
            background: linear-gradient(135deg, var(--brown-bright), var(--brown));
            color: #ffffff;
            border-color: var(--brown);
        }
        .btn-filled:hover {
            background: linear-gradient(135deg, var(--brown), var(--brown-dark));
            box-shadow: 0 10px 24px -8px rgba(184, 136, 88, 0.5);
        }
        .btn-ghost {
            color: var(--text-secondary);
            border-color: var(--border-medium);
        }
        .btn-ghost:hover {
            color: var(--text-primary);
            border-color: var(--brown);
            background: var(--bg-input);
        }

        /* === ПОЛЯ ВВОДА === */
        .input-field {
            background-color: var(--bg-input);
            border: 1px solid var(--border-medium);
            color: var(--text-primary);
            border-radius: 10px;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            width: 100%;
            font-size: 14px;
        }
        .input-field::placeholder { color: var(--text-muted); }
        .input-field:focus {
            outline: none;
            border-color: var(--brown);
            background-color: var(--bg-input-focus);
            box-shadow: 0 0 0 3px rgba(184, 136, 88, 0.18);
        }
        .input-field.error { border-color: var(--error); }

        /* === ТЕГИ === */
        .tag {
            display: inline-block;
            padding: 4px 10px;
            font-size: 11px;
            border-radius: 8px;
            background: var(--bg-input);
            border: 1px solid var(--border-medium);
            color: var(--brown-dark);
            transition: all 0.2s;
            text-decoration: none;
            white-space: nowrap;
        }
        body.mode-earth .tag { color: var(--brown-bright); }
        .tag:hover {
            border-color: var(--brown);
            transform: translateY(-1px);
        }

        /* === БЕЙДЖИ === */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 8px;
            font-weight: 500;
        }
        .badge-forest { background: rgba(61, 79, 51, 0.85); color: #ffffff; }
        .badge-brown { background: linear-gradient(135deg, var(--brown-bright), var(--brown)); color: #ffffff; }
        .badge-soft { background: var(--bg-input); color: var(--text-secondary); border: 1px solid var(--border-soft); }
        .badge-pinned {
            background: linear-gradient(135deg, var(--forest), var(--forest-dark));
            color: #ffffff;
            animation: pulse-glow 2.5s ease-in-out infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(107, 138, 92, 0.3); }
            50% { box-shadow: 0 0 0 6px rgba(107, 138, 92, 0); }
        }

        /* === ГРАДИЕНТНЫЕ ЧИСЛА === */
        .gradient-number {
            font-family: 'Karelle', serif;
            background: linear-gradient(135deg, var(--brown-bright), var(--brown));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* === АВАТАР === */
        .avatar {
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid var(--border-medium);
            background: var(--bg-input);
            transition: all 0.3s;
            display: inline-block;
            flex-shrink: 0;
        }
        .avatar:hover { border-color: var(--brown); transform: scale(1.05); }
        .avatar-square {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border-medium);
            background: var(--bg-input);
            box-shadow: var(--shadow-soft);
            flex-shrink: 0;
        }
        .avatar-fallback {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--forest), var(--forest-light));
            color: #ffffff;
            font-family: 'Karelle', serif;
        }

        /* === ГОЛОСОВАНИЕ === */
        .vote-btn {
            background: transparent;
            border: 0;
            font-size: 18px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s;
            padding: 4px 8px;
            border-radius: 6px;
            display: block;
        }
        .vote-btn:hover {
            color: var(--brown-bright);
            background: var(--bg-input);
            transform: scale(1.15);
        }

        /* === МОДАЛКИ === */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            z-index: 50;
            display: none;
            align-items: flex-start;
            justify-content: center;
            padding: 5vh 16px;
            overflow-y: auto;
        }
        .modal-overlay.active { display: flex; animation: fadeIn 0.25s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .modal-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-medium);
            border-radius: 18px;
            padding: 32px 28px;
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            box-shadow: 0 24px 60px -16px rgba(0, 0, 0, 0.6);
            position: relative;
            animation: slideUp 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-close {
            position: absolute;
            top: 12px;
            right: 14px;
            width: 32px;
            height: 32px;
            border: 0;
            border-radius: 50%;
            background: transparent;
            color: var(--text-secondary);
            font-size: 22px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .modal-close:hover {
            background: var(--bg-input);
            color: var(--text-primary);
            transform: rotate(90deg);
        }

        /* === HERO === */
        .hero {
            position: relative;
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #ffffff;
            overflow: hidden;
        }
        @media (max-width: 768px) {
            .hero { min-height: 50vh; }
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(42,38,34,0.5) 0%, rgba(42,38,34,0.85) 100%);
            z-index: 1;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            padding: 60px 20px;
            max-width: 860px;
            width: 100%;
        }
        .hero h1 { color: #ffffff; }

        /* === REVEAL === */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s cubic-bezier(0.2, 0.8, 0.2, 1),
            transform 0.7s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }

        /* === ФЛЕШ === */
        .flash-message {
            border-radius: 12px;
            border-left: 3px solid var(--success);
            background: rgba(107, 138, 92, 0.12);
            color: var(--text-primary);
            animation: slideDown 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
            padding: 12px 20px;
            font-size: 14px;
        }
        .flash-error { border-left-color: var(--error); background: rgba(196, 86, 83, 0.12); }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* === ПОДВАЛ === */
        .footer-bg {
            background: linear-gradient(135deg, var(--forest-dark), #1f2a1c);
            border-top: 1px solid rgba(0, 0, 0, 0.2);
            color: #f5efe0;
        }

        /* === СКРОЛЛБАР === */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-page); }
        ::-webkit-scrollbar-thumb { background: var(--border-medium); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--brown); }

        html { scroll-behavior: smooth; }

        /* === АДАПТИВ — МОБИЛЬНЫЕ <= 375px === */
        @media (max-width: 375px) {
            .workspace { padding-left: 12px !important; padding-right: 12px !important; }

            h1 { font-size: 1.75rem !important; }
            h2 { font-size: 1.4rem !important; }
            h3 { font-size: 1.1rem !important; }

            .hero-content { padding: 40px 16px; }
            .hero { min-height: 45vh; }

            .card { border-radius: 10px; }
            .card-flat { border-radius: 10px; }
            .modal-card { padding: 24px 18px; border-radius: 14px; }

            .btn { padding: 9px 18px; font-size: 10px; letter-spacing: 2px; }

            /* Профиль — колонки в стопку */
            .lg\:grid-cols-\[300px_1fr\] { grid-template-columns: 1fr !important; }

            /* Форум — убрать двойную колонку */
            .lg\:grid-cols-\[1fr_280px\] { grid-template-columns: 1fr !important; }
            aside { display: none !important; }

            /* Таблица статистики */
            .grid-cols-2 { grid-template-columns: 1fr 1fr; }
            .md\:grid-cols-4 { grid-template-columns: 1fr 1fr !important; }
        }

        /* === АДАПТИВ — ТЕЛЕФОНЫ <= 640px === */
        @media (max-width: 640px) {
            .burger-btn { display: flex; }
            .desktop-nav { display: none !important; }
            .desktop-auth { display: none !important; }

            .workspace { padding-left: 16px; padding-right: 16px; }

            /* Форум — скрыть сайдбар */
            .lg\:grid-cols-\[1fr_280px\] { grid-template-columns: 1fr !important; }
            aside.space-y-4 { display: none !important; }

            /* Профиль — стопка */
            .lg\:grid-cols-\[300px_1fr\] { grid-template-columns: 1fr !important; }
            .lg\:sticky { position: static !important; }

            /* Карточки услуг — убрать фото сбоку */
            .md\:flex-row { flex-direction: column !important; }
            .md\:w-56 { width: 100% !important; }

            /* Форма создания услуги */
            .md\:grid-cols-2 { grid-template-columns: 1fr !important; }

            .hero-content { padding: 48px 16px; }
            .py-24 { padding-top: 48px !important; padding-bottom: 48px !important; }
            .py-12 { padding-top: 32px !important; padding-bottom: 32px !important; }
            .py-10 { padding-top: 24px !important; padding-bottom: 24px !important; }

            /* Сетка главной — колонки в стопку */
            .md\:grid-cols-3 { grid-template-columns: 1fr !important; }
            .md\:grid-cols-2 { grid-template-columns: 1fr !important; }

            /* Галерея — 2 колонки */
            .md\:grid-cols-4 { grid-template-columns: 1fr 1fr !important; }

            /* Вкладки профиля */
            .tab-btn { padding: 8px 12px !important; font-size: 9px !important; }
        }

        /* === АДАПТИВ — ПЛАНШЕТЫ 641-1024px === */
        @media (min-width: 641px) and (max-width: 1024px) {
            .workspace { padding-left: 24px; padding-right: 24px; }

            .lg\:grid-cols-\[1fr_280px\] { grid-template-columns: 1fr 240px !important; }
            .lg\:grid-cols-\[300px_1fr\] { grid-template-columns: 260px 1fr !important; }
        }

        /* === TITLE LINK === */
        .title-link {
            display: inline;
            transition: color 0.2s;
            text-decoration: none;
            color: inherit;
        }
        .title-link:hover { color: var(--brown); }
        body.mode-earth .title-link:hover { color: var(--brown-bright); }

        /* === ПАГИНАЦИЯ === */
        nav[role="navigation"] svg { width: 16px; height: 16px; }
        nav[role="navigation"] { color: var(--text-secondary); }
    </style>
</head>

<body class="mode-@yield('mode', 'sand')">

{{-- ШАПКА --}}
<header class="green-header">
    <div class="workspace px-6">
        <div class="flex items-center justify-between h-16">

            {{-- Логотип --}}
            <a href="{{ route('home') }}"
               style="font-family: 'Karelle', serif; letter-spacing: 3px; font-size: 1.1rem; color: #f5efe0; text-decoration: none; white-space: nowrap; text-transform: uppercase;">
                TerraForum
            </a>

            {{-- Десктопная навигация --}}
            <nav class="desktop-nav hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Главная</a>
                <a href="{{ route('forum.index') }}" class="nav-link {{ request()->routeIs('forum.*') ? 'active' : '' }}">Форум</a>
                <a href="{{ route('news.index') }}" class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">Новости</a>
                <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">Услуги</a>
            </nav>

            {{-- Десктоп — авторизация --}}
            <div class="desktop-auth hidden md:flex items-center gap-3">
                @auth
                    @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                    {{-- Колокольчик --}}
                    <a href="{{ route('notifications.index') }}"
                       class="nav-link relative"
                       title="Уведомления"
                       style="font-size: 18px; line-height: 1;">
                        🕭
                        @if($unreadCount > 0)
                            <span style="
                                    position: absolute;
                                    top: -6px;
                                    right: -8px;
                                    background: var(--brown);
                                    color: #fff;
                                    border-radius: 50%;
                                    min-width: 16px;
                                    height: 16px;
                                    font-size: 9px;
                                    font-weight: 600;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    padding: 0 3px;
                                ">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('profile.me') }}" class="nav-link">{{ auth()->user()->name }}</a>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="nav-link">Выйти</button>
                    </form>
                @else
                    <button type="button" onclick="openModal('login-modal')" class="nav-link">Войти</button>
                    <button type="button" onclick="openModal('register-modal')" class="header-reg-btn">
                        Регистрация
                    </button>
                @endauth
            </div>

            {{-- Бургер (мобильный) --}}
            <button class="burger-btn md:hidden" id="burger" aria-label="Меню">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    {{-- Мобильное меню --}}
    <div class="mobile-menu" id="mobile-menu">
        <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Главная</a>
        <a href="{{ route('forum.index') }}" class="mobile-nav-link {{ request()->routeIs('forum.*') ? 'active' : '' }}">Форум</a>
        <a href="{{ route('news.index') }}" class="mobile-nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">Новости</a>
        <a href="{{ route('services.index') }}" class="mobile-nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">Услуги</a>

        @auth
            <a href="{{ route('notifications.index') }}" class="mobile-nav-link">
                🕭 Уведомления
                @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                @if($unreadCount > 0)
                    <span style="background: var(--brown); color: #fff; border-radius: 8px; padding: 1px 6px; font-size: 10px; margin-left: 6px;">{{ $unreadCount }}</span>
                @endif
            </a>
            <a href="{{ route('profile.me') }}" class="mobile-nav-link">Профиль ({{ auth()->user()->name }})</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="mobile-nav-link w-full text-left" style="background: transparent; border: none; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.08);">
                    Выйти
                </button>
            </form>
        @else
            <button type="button" onclick="openModal('login-modal'); closeMobileMenu();" class="mobile-nav-link text-left" style="background: transparent; border: none; cursor: pointer;">
                Войти
            </button>
            <button type="button" onclick="openModal('register-modal'); closeMobileMenu();" class="mobile-nav-link text-left" style="background: transparent; border: none; cursor: pointer; color: var(--brown-bright);">
                Регистрация
            </button>
        @endauth
    </div>
</header>

{{-- ФЛЕШ-УВЕДОМЛЕНИЯ --}}
@if (session('success'))
    <div class="workspace px-6 pt-4">
        <div class="flash-message">✓ {{ session('success') }}</div>
    </div>
@endif
@if (session('error'))
    <div class="workspace px-6 pt-4">
        <div class="flash-message flash-error">✕ {{ session('error') }}</div>
    </div>
@endif

{{-- КОНТЕНТ --}}
<main>
    @yield('content')
</main>

{{-- ФУТЕР (прижат вниз) --}}
<footer class="footer-bg py-10 mt-16">
    <div class="workspace px-6 text-center">
        <div style="font-family: 'Karelle', serif; letter-spacing: 3px; font-size: 1.1rem; text-transform: uppercase; margin-bottom: 12px;">
            TerraForum
        </div>
        <div class="divider mb-4" style="color: var(--brown-bright);">
            <span>est. 2026</span>
        </div>
        <p style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; opacity: 0.7;">
            © {{ date('Y') }} Сообщество садоводов и фермеров
        </p>
    </div>
</footer>


@guest
    @include('auth.login')
    @include('auth.register')
@endguest

<script>

    function openModal(id) {
        const m = document.getElementById(id);
        if (!m) return;
        m.classList.add('active');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            const first = m.querySelector('input:not([type=hidden])');
            if (first) first.focus();
        }, 100);
    }
    function closeModal(id) {
        const m = document.getElementById(id);
        if (!m) return;
        m.classList.remove('active');
        document.body.style.overflow = '';
    }
    document.querySelectorAll('.modal-overlay').forEach(o => {
        o.addEventListener('click', e => { if (e.target === o) closeModal(o.id); });
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(m => {
                m.classList.remove('active');
            });
            document.body.style.overflow = '';
        }
    });


    const burger = document.getElementById('burger');
    const mobileMenu = document.getElementById('mobile-menu');

    function closeMobileMenu() {
        burger.classList.remove('open');
        mobileMenu.classList.remove('open');
        document.body.style.overflow = '';
    }

    if (burger && mobileMenu) {
        burger.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.contains('open');
            if (isOpen) {
                closeMobileMenu();
            } else {
                burger.classList.add('open');
                mobileMenu.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        });
    }


    document.querySelectorAll('.mobile-nav-link').forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });


    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    @if($errors->any() && session('open_modal'))
    window.addEventListener('DOMContentLoaded', () => openModal('{{ session('open_modal') }}'));
    @endif
</script>

</body>
</html>
