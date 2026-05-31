@extends('layouts.app')

@section('title', 'Главная')
@section('mode', 'sand')

@section('content')

    <section style="
    min-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    background: radial-gradient(ellipse at 60% 40%, #7a8c6e 0%, #5a6b52 35%, #4a5c44 60%, #3d4f38 100%);
    position: relative;
    overflow: hidden;
">
        <div style="
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 30% 70%, rgba(100, 120, 85, 0.3), transparent 60%);
        pointer-events: none;
    "></div>
        <div style="position: relative; z-index: 2; padding: 100px 24px; max-width: 800px;">
            <div class="divider mb-8 reveal" style="color: rgba(245, 239, 224, 0.7);">
                <span>est. 2026</span>
            </div>
            <h1 class="reveal reveal-delay-1" style="
            font-family: 'Karelle', 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            line-height: 1.1;
            color: #f5efe0;
            font-weight: normal;
            margin-bottom: 1.5rem;
            letter-spacing: 0.5px;
        ">
                Сообщество, которое<br>растёт вместе с вами
            </h1>
            <p class="reveal reveal-delay-2" style="
            font-size: 1rem;
            color: rgba(245, 239, 224, 0.8);
            max-width: 520px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
            font-weight: 300;
        ">
                Место для садоводов и фермеров, где обмениваются опытом,
                ищут проверенные советы и предлагают услуги.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center reveal reveal-delay-3">
                @guest
                    <button type="button" onclick="openModal('register-modal')"
                            class="btn btn-light">
                        Присоединиться
                    </button>
                    <button type="button" onclick="openModal('login-modal')"
                            class="btn btn-light">
                        Войти
                    </button>
                @else
                    <a href="{{ route('forum.index') }}" class="btn btn-light">
                        Перейти на форум
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <section class="py-24 px-6">
        <div class="workspace">
            <div class="text-center mb-16 reveal">
                <div class="divider mb-6"><span>что вас ждёт</span></div>
                <h2 class="text-4xl md:text-5xl">Три причины остаться</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="card p-8 text-center reveal">
                    <div class="section-number mb-4">— 01 —</div>
                    <h3 class="text-3xl mb-3">Форум</h3>
                    <p class="text-sm text-secondary-c leading-relaxed">
                        Тысячи тем о выращивании, уходе и борьбе с вредителями.
                        Спросите соседей по полю — ответ придёт.
                    </p>
                </div>
                <div class="card p-8 text-center reveal reveal-delay-1">
                    <div class="section-number mb-4">— 02 —</div>
                    <h3 class="text-3xl mb-3">Новости</h3>
                    <p class="text-sm text-secondary-c leading-relaxed">
                        Что происходит в агросекторе: погода, рынок,
                        свежие исследования и события сезона.
                    </p>
                </div>
                <div class="card p-8 text-center reveal reveal-delay-2">
                    <div class="section-number mb-4">— 03 —</div>
                    <h3 class="text-3xl mb-3">Услуги</h3>
                    <p class="text-sm text-secondary-c leading-relaxed">
                        Каталог локальных мастеров — вспашка, обрезка,
                        полив, уборка урожая.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 px-6" style="background: var(--bg-section-alt);">
        <div class="workspace">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1 reveal">
                    <div class="section-number mb-4">— 01 наше начало —</div>
                    <h2 class="text-4xl md:text-5xl mb-6" style="line-height: 1.2;">
                        От первой<br>грядки до большого поля
                    </h2>
                    <p class="text-base mb-4 leading-relaxed text-secondary-c">
                        TerraForum родился из простого желания — собрать в одном месте людей,
                        которые любят землю. Садоводов, фермеров, всех, кто помнит вкус настоящего
                        помидора с грядки и шум пшеничного поля на ветру.
                    </p>
                    <p class="text-base leading-relaxed text-secondary-c">
                        Мы верим, что лучшие знания о земле живут не в учебниках,
                        а в опыте людей, которые проводят на ней свою жизнь.
                    </p>
                </div>
                <div class="order-1 md:order-2 reveal reveal-delay-1">
                    <div class="aspect-[4/3] bg-cover bg-center rounded-2xl"
                         style="background-image: url('{{ asset('images/about-1.jpg') }}'); box-shadow: var(--shadow-hover);">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 px-6">
        <div class="workspace">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="reveal">
                    <div class="aspect-[4/3] bg-cover bg-center rounded-2xl"
                         style="background-image: url('{{ asset('images/about-2.jpg') }}'); box-shadow: var(--shadow-hover);">
                    </div>
                </div>
                <div class="reveal reveal-delay-1">
                    <div class="section-number mb-4">— 02 наша миссия —</div>
                    <h2 class="text-4xl md:text-5xl mb-6" style="line-height: 1.2;">
                        Знания,<br>проверенные временем
                    </h2>
                    <p class="text-base mb-4 leading-relaxed text-secondary-c">
                        Здесь делятся опытом, который не найти в инструкциях.
                        Соседская мудрость о том, когда сажать томаты,
                        как подрезать яблоню, чем поить виноград засушливым летом.
                    </p>
                    <p class="text-base leading-relaxed text-secondary-c">
                        Каждый совет проходит через коллективный фильтр доверия —
                        система рейтингов показывает, какие ответы действительно работают.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 px-6" style="background: var(--bg-section-alt);">
        <div class="workspace text-center max-w-3xl mx-auto reveal">
            <div class="text-7xl mb-6 opacity-25 gradient-number" style="line-height: 0.5;">«</div>
            <p class="text-2xl md:text-3xl mb-8 font-light leading-relaxed"
               style="font-family: 'Karelle', serif; color: var(--text-primary);">
                Хороший сад вырастает не за один сезон —
                он растёт вместе с тем, кто его понимает.
            </p>
            <div class="divider"><span>народная мудрость</span></div>
        </div>
    </section>

    <section class="py-24 px-6">
        <div class="workspace">
            <div class="text-center mb-12 reveal">
                <div class="divider mb-4"><span>наша земля</span></div>
                <h2 class="text-4xl md:text-5xl">Кадры из жизни</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 reveal">
                @foreach(['gallery-1.jpg', 'gallery-2.jpg', 'gallery-3.jpg', 'gallery-4.jpg'] as $img)
                    <div class="aspect-square bg-cover bg-center rounded-xl cursor-pointer"
                         style="background-image: url('{{ asset('images/' . $img) }}');
                            box-shadow: var(--shadow-soft);
                            transition: transform 0.3s, box-shadow 0.3s;"
                         onmouseover="this.style.transform='scale(1.04)'; this.style.boxShadow='var(--shadow-hover)'"
                         onmouseout="this.style.transform=''; this.style.boxShadow='var(--shadow-soft)'">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-24 px-6" style="background-color: #4a5c44;">
        <div class="workspace text-center reveal">
            <div class="divider mb-6" style="color: rgba(245, 239, 224, 0.6);">
                <span style="color: rgba(245, 239, 224, 0.7);">приглашение</span>
            </div>
            <h2 class="text-4xl md:text-5xl mb-6" style="color: #f5efe0;">
                Земля любит тех, кто её слушает
            </h2>
            <p class="max-w-xl mx-auto mb-10" style="color: rgba(245, 239, 224, 0.75);">
                Регистрация бесплатная. Знания — бесценные.
            </p>
            @guest
                <button type="button" onclick="openModal('register-modal')"
                        class="btn"
                        style="color: #f5efe0; border-color: rgba(245, 239, 224, 0.5);"
                        onmouseover="this.style.background='rgba(245, 239, 224, 0.12)'"
                        onmouseout="this.style.background='transparent'">
                    Создать аккаунт
                </button>
            @else
                <a href="{{ route('forum.index') }}"
                   class="btn"
                   style="color: #f5efe0; border-color: rgba(245, 239, 224, 0.5);"
                   onmouseover="this.style.background='rgba(245, 239, 224, 0.12)'"
                   onmouseout="this.style.background='transparent'">
                    Перейти на форум
                </a>
            @endguest
        </div>
    </section>

@endsection
