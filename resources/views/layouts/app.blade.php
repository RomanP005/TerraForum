<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TerraForum') — сообщество садоводов и фермеров</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'Karelle';
            src: url('{{ asset('fonts/Karelle.otf') }}') format('opentype');
            font-display: swap;
        }
        :root {
            --forest: #3d4f33;
            --forest-dark: #2e3d27;
            --forest-light: #6b8a5c;
            --brown: #b88858;
            --brown-bright: #d4a574;
            --brown-dark: #8c6638;
            --error: #c45653;
            --success: #6b8a5c;
        }

        body.mode-sand {
            --bg-page: #f5efe0; --bg-card: #ffffff; --bg-card-hover: #fffdf8;
            --bg-section-alt: #ebe0c8; --bg-input: #ffffff; --bg-input-focus: #fffdf8;
            --text-primary: #2a2622; --text-secondary: #5c5048; --text-muted: #8c7e6a;
            --border-soft: rgba(107,68,35,0.1); --border-medium: rgba(107,68,35,0.18);
            --border-strong: rgba(107,68,35,0.3);
            --shadow-soft: 0 4px 16px -8px rgba(107,68,35,0.15);
            --shadow-hover: 0 12px 28px -12px rgba(107,68,35,0.25);
        }
        body.mode-linen {
            --bg-page: #ebe0c8; --bg-card: #f5efe0; --bg-card-hover: #faf4e4;
            --bg-section-alt: #e0d4b8; --bg-input: #f5efe0; --bg-input-focus: #fffdf8;
            --text-primary: #2a2622; --text-secondary: #5c5048; --text-muted: #8c7e6a;
            --border-soft: rgba(107,68,35,0.12); --border-medium: rgba(107,68,35,0.22);
            --border-strong: rgba(107,68,35,0.35);
            --shadow-soft: 0 4px 16px -8px rgba(107,68,35,0.12);
            --shadow-hover: 0 12px 28px -12px rgba(107,68,35,0.2);
        }
        body.mode-earth {
            --bg-page: #3a3024; --bg-card: #4a3e30; --bg-card-hover: #574736;
            --bg-section-alt: #2e2620; --bg-input: #2e2620; --bg-input-focus: #352c24;
            --text-primary: #ffffff; --text-secondary: #e8dcc4; --text-muted: #a08c70;
            --border-soft: rgba(212,189,158,0.15); --border-medium: rgba(212,189,158,0.25);
            --border-strong: rgba(212,189,158,0.4);
            --shadow-soft: 0 8px 24px -12px rgba(0,0,0,0.4);
            --shadow-hover: 0 16px 40px -16px rgba(0,0,0,0.6);
        }

        *, *::before, *::after { box-sizing: border-box; }
        * { -webkit-font-smoothing: antialiased; }

        html { height: 100%; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-primary);
            font-weight: 300;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }
        main { flex: 1 0 auto; }
        footer { flex-shrink: 0; }

        h1, h2, h3, h4 {
            font-family: 'Karelle', Georgia, serif;
            font-weight: normal;
            letter-spacing: 0.5px;
            color: var(--text-primary);
            margin: 0;
        }

        .workspace {
            max-width: 1120px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
            padding-left: 24px;
            padding-right: 24px;
        }
        .green-header {
            background: linear-gradient(135deg, var(--forest) 0%, var(--forest-dark) 100%);
            border-bottom: 1px solid rgba(0,0,0,0.2);
            box-shadow: 0 4px 20px -8px rgba(0,0,0,0.3);
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
        }
        .header-logo {
            font-family: 'Karelle', serif;
            letter-spacing: 3px;
            font-size: 1rem;
            color: #f5efe0;
            text-decoration: none;
            text-transform: uppercase;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .desktop-nav {
            display: flex;
            align-items: center;
            gap: 28px;
        }
        .desktop-auth {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        .burger-btn { display: none; }

        .burger-btn {
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            background: transparent;
            border: none;
            cursor: pointer;
            gap: 5px;
            padding: 6px;
            flex-shrink: 0;
        }
        .burger-btn span {
            display: block;
            width: 22px;
            height: 2px;
            background: rgba(245,239,224,0.9);
            border-radius: 2px;
            transition: all 0.3s;
        }
        .burger-btn.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .burger-btn.open span:nth-child(2) { opacity: 0; }
        .burger-btn.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .mobile-menu {
            display: none;
            flex-direction: column;
            background: var(--forest-dark);
            border-top: 1px solid rgba(255,255,255,0.1);
            padding: 8px 0;
            max-height: calc(100vh - 60px);
            overflow-y: auto;
        }
        .mobile-menu.open { display: flex; }
        .mobile-nav-link {
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(245,239,224,0.85);
            padding: 14px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            background: transparent;
            border-left: none;
            border-right: none;
            border-top: none;
            cursor: pointer;
            text-align: left;
            width: 100%;
            display: block;
        }
        .mobile-nav-link:hover, .mobile-nav-link.active {
            color: #ffffff;
            background: rgba(255,255,255,0.05);
        }
        .mobile-nav-link:last-child { border-bottom: none; }

        .nav-link {
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(245,239,224,0.85);
            position: relative;
            transition: color 0.25s;
            padding: 4px 0;
            text-decoration: none;
            white-space: nowrap;
            background: transparent;
            border: none;
            cursor: pointer;
        }
        .nav-link:hover { color: #ffffff; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0; right: 0;
            height: 1px;
            background: var(--brown-bright);
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.3s;
        }
        .nav-link:hover::after, .nav-link.active::after { transform: scaleX(1); }
        .nav-link.active { color: #ffffff; }

        .header-reg-btn {
            padding: 7px 16px;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brown-bright), var(--brown));
            color: #2a2218;
            box-shadow: 0 6px 16px -6px rgba(212,165,116,0.5);
            transition: all 0.25s;
            font-weight: 500;
            border: 0;
            cursor: pointer;
            white-space: nowrap;
        }
        .header-reg-btn:hover { transform: translateY(-2px); }

        .btn {
            display: inline-block;
            padding: 10px 24px;
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-weight: 500;
            border: 1px solid currentColor;
            border-radius: 10px;
            transition: all 0.25s;
            cursor: pointer;
            background: transparent;
            text-decoration: none;
            text-align: center;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn-light { color: #ffffff; border-color: #ffffff; }
        .btn-light:hover { background: #ffffff; color: var(--bg-page); }
        .btn-filled {
            background: linear-gradient(135deg, var(--brown-bright), var(--brown));
            color: #ffffff; border-color: var(--brown);
        }
        .btn-filled:hover { background: linear-gradient(135deg, var(--brown), var(--brown-dark)); }
        .btn-ghost { color: var(--text-secondary); border-color: var(--border-medium); }
        .btn-ghost:hover { color: var(--text-primary); border-color: var(--brown); background: var(--bg-input); }

        .input-field {
            background-color: var(--bg-input);
            border: 1px solid var(--border-medium);
            color: var(--text-primary);
            border-radius: 10px;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            width: 100%;
        }
        .input-field::placeholder { color: var(--text-muted); }
        .input-field:focus {
            outline: none;
            border-color: var(--brown);
            background-color: var(--bg-input-focus);
            box-shadow: 0 0 0 3px rgba(184,136,88,0.18);
        }
        .input-field.error { border-color: var(--error); }
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

        .divider {
            display: flex; align-items: center; justify-content: center;
            gap: 16px; font-size: 10px; letter-spacing: 3px;
            color: var(--brown-dark); text-transform: uppercase;
        }
        body.mode-earth .divider { color: var(--brown-bright); }
        .divider::before, .divider::after {
            content: ''; height: 1px; flex: 1; max-width: 80px;
            background: linear-gradient(90deg, transparent, currentColor, transparent);
            opacity: 0.5;
        }

        .section-number {
            font-family: 'Karelle', serif; font-size: 12px;
            letter-spacing: 3px; color: var(--brown-dark); text-transform: uppercase;
        }
        body.mode-earth .section-number { color: var(--brown-bright); }

        .tag {
            display: inline-block; padding: 4px 10px; font-size: 11px;
            border-radius: 8px; background: var(--bg-input);
            border: 1px solid var(--border-medium); color: var(--brown-dark);
            transition: all 0.2s; text-decoration: none; white-space: nowrap;
        }
        body.mode-earth .tag { color: var(--brown-bright); }
        .tag:hover { border-color: var(--brown); transform: translateY(-1px); }

        .badge {
            display: inline-block; padding: 3px 10px; font-size: 10px;
            letter-spacing: 2px; text-transform: uppercase; border-radius: 8px; font-weight: 500;
        }
        .badge-forest { background: rgba(61,79,51,0.85); color: #ffffff; }
        .badge-brown { background: linear-gradient(135deg, var(--brown-bright), var(--brown)); color: #ffffff; }
        .badge-soft { background: var(--bg-input); color: var(--text-secondary); border: 1px solid var(--border-soft); }
        .badge-pinned {
            background: linear-gradient(135deg, var(--forest), var(--forest-dark));
            color: #ffffff;
            animation: pulse-glow 2.5s ease-in-out infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(107,138,92,0.3); }
            50% { box-shadow: 0 0 0 6px rgba(107,138,92,0); }
        }

        .gradient-number {
            font-family: 'Karelle', serif;
            background: linear-gradient(135deg, var(--brown-bright), var(--brown));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .avatar {
            border-radius: 50%; overflow: hidden;
            border: 2px solid var(--border-medium);
            background: var(--bg-input); transition: all 0.3s;
            display: inline-block; flex-shrink: 0;
        }
        .avatar:hover { border-color: var(--brown); transform: scale(1.05); }
        .avatar-square {
            border-radius: 10px; overflow: hidden;
            border: 1px solid var(--border-medium);
            background: var(--bg-input); box-shadow: var(--shadow-soft); flex-shrink: 0;
        }
        .avatar-fallback {
            display: flex; align-items: center; justify-content: center;
            width: 100%; height: 100%;
            background: linear-gradient(135deg, var(--forest), #6b8a5c);
            color: #ffffff; font-family: 'Karelle', serif;
        }

        .vote-btn {
            background: transparent; border: 0; font-size: 18px;
            color: var(--text-secondary); cursor: pointer;
            transition: all 0.2s; padding: 4px 8px; border-radius: 6px; display: block;
        }
        .vote-btn:hover { color: var(--brown-bright); background: var(--bg-input); transform: scale(1.15); }

        .title-link { display: inline; transition: color 0.2s; text-decoration: none; color: inherit; }
        .title-link:hover { color: var(--brown); }
        body.mode-earth .title-link:hover { color: var(--brown-bright); }

        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px); z-index: 50;
            display: none; align-items: flex-start; justify-content: center;
            padding: 5vh 16px; overflow-y: auto;
        }
        .modal-overlay.active { display: flex; animation: fadeIn 0.25s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-card {
            background: var(--bg-card); border: 1px solid var(--border-medium);
            border-radius: 18px; padding: 32px 28px; width: 100%; max-width: 440px;
            box-shadow: 0 24px 60px -16px rgba(0,0,0,0.6);
            position: relative; animation: slideUp 0.4s cubic-bezier(0.2,0.8,0.2,1);
        }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-close {
            position: absolute; top: 12px; right: 14px;
            width: 32px; height: 32px; border: 0; border-radius: 50%;
            background: transparent; color: var(--text-secondary);
            font-size: 22px; cursor: pointer; transition: all 0.2s;
        }
        .modal-close:hover { background: var(--bg-input); color: var(--text-primary); transform: rotate(90deg); }

        .hero {
            position: relative; min-height: 60vh;
            display: flex; align-items: center; justify-content: center;
            text-align: center; color: #ffffff; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(42,38,34,0.5) 0%, rgba(42,38,34,0.85) 100%);
            z-index: 1;
        }
        .hero-content { position: relative; z-index: 2; padding: 60px 20px; max-width: 860px; width: 100%; }
        .hero h1 { color: #ffffff; }

        .reveal {
            opacity: 0; transform: translateY(30px);
            transition: opacity 0.7s cubic-bezier(0.2,0.8,0.2,1), transform 0.7s cubic-bezier(0.2,0.8,0.2,1);
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }

        .flash-message {
            border-radius: 12px; border-left: 3px solid var(--success);
            background: rgba(107,138,92,0.12); color: var(--text-primary);
            animation: slideDown 0.4s cubic-bezier(0.2,0.8,0.2,1);
            padding: 12px 20px; font-size: 14px;
        }
        .flash-error { border-left-color: var(--error); background: rgba(196,86,83,0.12); }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        .footer-bg {
            background: linear-gradient(135deg, var(--forest-dark), #1f2a1c);
            border-top: 1px solid rgba(0,0,0,0.2);
            color: #f5efe0;
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-page); }
        ::-webkit-scrollbar-thumb { background: var(--border-medium); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--brown); }
        html { scroll-behavior: smooth; }

        @media (min-width: 901px) {
            .burger-btn { display: none !important; }
            .desktop-nav { display: flex !important; }
            .desktop-auth { display: flex !important; }
            .mobile-menu { display: none !important; }
        }


        @media (max-width: 900px) {
            .burger-btn { display: flex !important; }
            .desktop-nav { display: none !important; }
            .desktop-auth { display: none !important; }

            .workspace { padding-left: 20px; padding-right: 20px; }


            .forum-layout { grid-template-columns: 1fr !important; }
            .forum-sidebar { display: none !important; }


            .profile-layout { grid-template-columns: 1fr !important; }
            .profile-sidebar { position: static !important; }


            .news-layout { grid-template-columns: 1fr !important; }
            .news-sidebar { display: none !important; }


            .services-layout { grid-template-columns: 1fr !important; }

            .forum-filters { flex-direction: column !important; align-items: flex-start !important; }
            .forum-found { margin-top: 8px !important; }

            .home-section-grid { grid-template-columns: 1fr !important; }
            .home-features { grid-template-columns: 1fr 1fr !important; }
            .gallery-grid { grid-template-columns: 1fr 1fr !important; }

            .service-card-inner { flex-direction: column !important; }
            .service-card-img { width: 100% !important; height: 200px !important; }
        }

        /* Телефон ≤ 640px */
        @media (max-width: 640px) {
            .workspace { padding-left: 16px; padding-right: 16px; }

            h1 { font-size: clamp(1.6rem, 6vw, 2.4rem) !important; }
            h2 { font-size: clamp(1.3rem, 5vw, 2rem) !important; }

            .hero { min-height: 50vh; }
            .hero-content { padding: 40px 16px; }

            .home-features { grid-template-columns: 1fr !important; }

            .btn { padding: 9px 18px; font-size: 10px; }

            .profile-tabs { overflow-x: auto !important; flex-wrap: nowrap !important; padding-bottom: 4px; }
            .tab-btn { white-space: nowrap; padding: 8px 12px !important; font-size: 9px !important; flex-shrink: 0 !important; }

            .profile-stats-grid { grid-template-columns: 1fr 1fr !important; }

            .theme-author-col { width: auto !important; min-width: 0 !important; }

            .py-24 { padding-top: 40px !important; padding-bottom: 40px !important; }
            .py-16 { padding-top: 32px !important; padding-bottom: 32px !important; }
            .py-12 { padding-top: 28px !important; padding-bottom: 28px !important; }
            .py-10 { padding-top: 20px !important; padding-bottom: 20px !important; }

            .modal-card { padding: 24px 18px; border-radius: 14px; }
        }

        @media (max-width: 375px) {
            .workspace { padding-left: 12px; padding-right: 12px; }
            .profile-tabs { gap: 2px !important; }
            .tab-btn { padding: 7px 10px !important; font-size: 8px !important; }
        }

        nav[role="navigation"] svg { width: 16px; height: 16px; }
        nav[role="navigation"] { color: var(--text-secondary); }
    </style>
</head>

<body class="mode-@yield('mode', 'sand')">

<header class="green-header">
    <div class="workspace">
        <div class="header-inner">

            <a href="{{ route('home') }}" class="header-logo">TerraForum</a>

            <nav class="desktop-nav">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Главная</a>
                <a href="{{ route('forum.index') }}" class="nav-link {{ request()->routeIs('forum.*') ? 'active' : '' }}">Форум</a>
                <a href="{{ route('news.index') }}" class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">Новости</a>
                <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">Услуги</a>
            </nav>

            <div class="desktop-auth">
                @auth
                    @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                    <a href="{{ route('notifications.index') }}"
                       class="nav-link"
                       title="Уведомления"
                       style="position: relative; font-size: 18px; line-height: 1;">
                        🕭
                        @if($unreadCount > 0)
                            <span style="position:absolute;top:-6px;right:-8px;background:var(--brown);color:#fff;border-radius:50%;min-width:16px;height:16px;font-size:9px;font-weight:600;display:flex;align-items:center;justify-content:center;padding:0 3px;">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                        @endif
                    </a>
                    <a href="{{ route('profile.me') }}" class="nav-link">{{ auth()->user()->name }}</a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="nav-link">Выйти</button>
                    </form>
                @else
                    <button type="button" onclick="openModal('login-modal')" class="nav-link">Войти</button>
                    <button type="button" onclick="openModal('register-modal')" class="header-reg-btn">Регистрация</button>
                @endauth
            </div>

            <button class="burger-btn" id="burger" aria-label="Меню" type="button">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <nav class="mobile-menu" id="mobile-menu">
        <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Главная</a>
        <a href="{{ route('forum.index') }}" class="mobile-nav-link {{ request()->routeIs('forum.*') ? 'active' : '' }}">Форум</a>
        <a href="{{ route('news.index') }}" class="mobile-nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">Новости</a>
        <a href="{{ route('services.index') }}" class="mobile-nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">Услуги</a>
        @auth
            @php $uc = auth()->user()->unreadNotifications->count(); @endphp
            <a href="{{ route('notifications.index') }}" class="mobile-nav-link">
                🕭 Уведомления
                @if($uc > 0)
                    <span style="background:var(--brown);color:#fff;border-radius:8px;padding:1px 7px;font-size:10px;margin-left:6px;">{{ $uc }}</span>
                @endif
            </a>
            <a href="{{ route('profile.me') }}" class="mobile-nav-link">Профиль ({{ auth()->user()->name }})</a>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="mobile-nav-link">Выйти</button>
            </form>
        @else
            <button type="button" onclick="openModal('login-modal');closeMobileMenu();" class="mobile-nav-link">Войти</button>
            <button type="button" onclick="openModal('register-modal');closeMobileMenu();" class="mobile-nav-link" style="color:var(--brown-bright);">Регистрация</button>
        @endauth
    </nav>
</header>

@if(session('success'))
    <div class="workspace" style="padding-top: 16px;">
        <div class="flash-message">✓ {{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="workspace" style="padding-top: 16px;">
        <div class="flash-message flash-error">✕ {{ session('error') }}</div>
    </div>
@endif

<main>@yield('content')</main>

<footer class="footer-bg" style="padding: 40px 0; margin-top: 64px;">
    <div class="workspace" style="text-align: center;">
        <div style="font-family:'Karelle',serif;letter-spacing:3px;font-size:1rem;text-transform:uppercase;margin-bottom:12px;">TerraForum</div>
        <div class="divider" style="margin-bottom:12px; color: var(--brown-bright);">
            <span>est. 2026</span>
        </div>
        <p style="font-size:11px;letter-spacing:2px;text-transform:uppercase;opacity:0.7;margin:0;">
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
        setTimeout(() => { const f = m.querySelector('input:not([type=hidden])'); if (f) f.focus(); }, 100);
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
            document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
            document.body.style.overflow = '';
        }
    });

    const burger = document.getElementById('burger');
    const mobileMenu = document.getElementById('mobile-menu');
    function closeMobileMenu() {
        if (burger) burger.classList.remove('open');
        if (mobileMenu) mobileMenu.classList.remove('open');
        document.body.style.overflow = '';
    }
    if (burger && mobileMenu) {
        burger.addEventListener('click', () => {
            const open = mobileMenu.classList.contains('open');
            if (open) { closeMobileMenu(); }
            else { burger.classList.add('open'); mobileMenu.classList.add('open'); document.body.style.overflow = 'hidden'; }
        });
    }
    document.querySelectorAll('.mobile-nav-link, .mobile-nav-link a').forEach(l => {
        l.addEventListener('click', () => setTimeout(closeMobileMenu, 50));
    });

    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    @if($errors->any() && session('open_modal'))
    window.addEventListener('DOMContentLoaded', () => openModal('{{ session('open_modal') }}'));
    @endif
</script>
</body>
</html>
