@extends('layouts.app')
@section('title', 'Услуги')
@section('mode', 'sand')

@section('content')

    <section style="background: radial-gradient(ellipse at 60% 40%, #7a8c6e 0%, #5a6b52 35%, #4a5c44 60%, #3d4f38 100%); padding: 64px 24px; text-align: center; color: #f5efe0;">
        <div class="divider mb-4 reveal" style="color: rgba(245,239,224,0.6);"><span>каталог</span></div>
        <h1 class="reveal reveal-delay-1" style="font-family: 'Karelle', serif; font-size: clamp(2rem, 5vw, 3.5rem); font-weight: normal; color: #f5efe0; margin-bottom: 0.75rem;">
            Услуги садоводов и фермеров
        </h1>
        <p class="reveal reveal-delay-2" style="color: rgba(245,239,224,0.8); max-width: 520px; margin: 0 auto 1.5rem; font-size: 0.95rem;">
            Вспашка, обрезка, полив, уборка урожая и многое другое — от проверенных исполнителей.
        </p>
        @auth
            <a href="{{ route('services.create') }}" class="btn btn-light reveal reveal-delay-3">
                Разместить объявление
            </a>
        @else
            <button type="button" onclick="openModal('login-modal')" class="btn btn-light reveal reveal-delay-3">
                Войти чтобы разместить услугу
            </button>
        @endauth
    </section>

    <section class="py-10 px-6">
        <div class="workspace">

            <div class="card-flat p-5 mb-8 reveal">
                <form action="{{ route('services.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <select name="category" class="input-field px-3 py-2 text-sm flex-1">
                        <option value="">Все категории</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>

                    <select name="region" class="input-field px-3 py-2 text-sm flex-1">
                        <option value="">Все регионы</option>
                        @foreach($regions as $r)
                            <option value="{{ $r }}" {{ $region === $r ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-filled">Найти</button>

                    @if($category || $region)
                        <a href="{{ route('services.index') }}" class="btn btn-ghost">Сбросить</a>
                    @endif
                </form>
            </div>

            @forelse($services as $service)
                <article class="card mb-4 overflow-hidden reveal">
                    <div class="flex flex-col md:flex-row">

                        @if($service->getFirstMediaUrl('photos', 'thumb'))
                            <a href="{{ route('services.show', $service->slug) }}"
                               class="block md:w-56 flex-shrink-0 overflow-hidden" style="min-height: 160px;">
                                <img src="{{ $service->getFirstMediaUrl('photos', 'thumb') }}"
                                     alt="{{ $service->title }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                            </a>
                        @endif

                        <div class="p-6 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                @if($service->service_category)
                                    <span class="badge badge-forest">{{ $service->service_category }}</span>
                                @endif
                                @if($service->region)
                                    <span class="text-xs text-muted-c uppercase tracking-widest" style="letter-spacing: 2px;">
                                    {{ $service->city ? $service->city . ', ' : '' }}{{ $service->region }}
                                </span>
                                @endif
                            </div>

                            <h2 class="text-2xl mb-2">
                                <a href="{{ route('services.show', $service->slug) }}" class="title-link">
                                    {{ $service->title }}
                                </a>
                            </h2>

                            <p class="text-sm text-secondary-c leading-relaxed mb-4">
                                {{ \Illuminate\Support\Str::limit($service->description, 180) }}
                            </p>

                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <div class="text-brown font-medium" style="font-size: 1.1rem;">
                                    @if($service->price_negotiable)
                                        Договорная
                                    @elseif($service->price)
                                        {{ number_format($service->price, 0, '.', ' ') }} {{ $service->price_unit ?? 'руб.' }}
                                    @else
                                        Цена не указана
                                    @endif
                                </div>
                                <div class="text-xs text-muted-c flex items-center gap-2">
                                    <div class="avatar w-6 h-6" style="display: inline-flex;">
                                        @if($service->user?->getFirstMediaUrl('avatar', 'thumb'))
                                            <img src="{{ $service->user->getFirstMediaUrl('avatar', 'thumb') }}" alt="" class="w-full h-full object-cover" style="border-radius: 50%;">
                                        @endif
                                    </div>
                                    {{ $service->user?->name }}
                                    · {{ $service->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="card-flat py-16 text-center">
                    <p class="text-secondary-c">Услуг пока нет</p>
                    @auth
                        <a href="{{ route('services.create') }}" class="text-brown hover:underline text-sm mt-2 inline-block">
                            Разместить первое объявление
                        </a>
                    @endauth
                </div>
            @endforelse

            @if($services->hasPages())
                <div class="mt-8">{{ $services->links() }}</div>
            @endif

        </div>
    </section>

@endsection
