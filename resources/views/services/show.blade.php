@extends('layouts.app')
@section('title', $service->title)
@section('mode', 'sand')

@section('content')

    <section class="py-4 px-6" style="background: var(--bg-section-alt);">
        <div class="workspace">
            <div class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-widest text-muted-c" style="letter-spacing: 2px;">
                <a href="{{ route('services.index') }}" class="text-brown hover:underline">Услуги</a>
                <span>/</span>
                <span class="truncate max-w-xs">{{ $service->title }}</span>
            </div>
        </div>
    </section>
    <section class="py-12 px-6">
        <div class="workspace">
            <div class="grid lg:grid-cols-[1fr_300px] gap-10">
                <article>
                    @if($service->getMedia('photos')->isNotEmpty())
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; margin-bottom: 28px;" class="reveal">
                            @foreach($service->getMedia('photos') as $photo)
                                <a href="{{ $photo->getUrl() }}" target="_blank"
                                   style="display: block; width: 100%; aspect-ratio: 4/3; overflow: hidden; border-radius: 12px; box-shadow: var(--shadow-soft);">
                                    <img src="{{ $photo->getUrl() }}"
                                         alt="{{ $service->title }}"
                                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; display: block;"
                                         onmouseover="this.style.transform='scale(1.05)'"
                                         onmouseout="this.style.transform='scale(1)'">
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        @if($service->service_category)
                            <span class="badge badge-forest">{{ $service->service_category }}</span>
                        @endif
                        @if($service->region)
                            <span class="text-xs text-muted-c uppercase tracking-widest" style="letter-spacing: 2px;">
                            {{ $service->city ? $service->city . ', ' : '' }}{{ $service->region }}
                        </span>
                        @endif
                    </div>

                    <h1 class="text-4xl md:text-5xl mb-4 reveal" style="line-height: 1.15;">
                        {{ $service->title }}
                    </h1>

                    <div class="mb-6 reveal">
                        <div class="text-3xl gradient-number" style="font-family: 'Karelle', serif;">
                            @if($service->price_negotiable)
                                Договорная
                            @elseif($service->price)
                                {{ number_format($service->price, 0, '.', ' ') }} {{ $service->price_unit ?? 'руб.' }}
                            @else
                                Цена не указана
                            @endif
                        </div>
                    </div>

                    <div class="divider mb-6"><span>описание</span></div>
                    <div class="text-secondary-c leading-relaxed whitespace-pre-line mb-8 reveal">
                        {{ $service->description }}
                    </div>

                </article>

                <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">

                    <div class="card-flat" style="padding: 20px;">
                        <div class="divider" style="margin-bottom: 16px;"><span>исполнитель</span></div>

                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                            <a href="{{ route('profile.show', $service->user->name) }}"
                               class="avatar" style="width: 48px; height: 48px; flex-shrink: 0;">
                                @if($service->user->getFirstMediaUrl('avatar'))
                                    <img src="{{ $service->user->getFirstMediaUrl('avatar') }}"
                                         alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div class="avatar-fallback" style="font-size: 20px;">
                                        {{ mb_strtoupper(mb_substr($service->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </a>
                            <div>
                                <a href="{{ route('profile.show', $service->user->name) }}"
                                   class="title-link" style="font-size: 15px; font-weight: 500; display: block;">
                                    {{ $service->user->name }}
                                </a>
                                <div class="text-muted-c" style="font-size: 12px;">
                                    Репутация: {{ $service->user->rating ?? 0 }}
                                </div>
                            </div>
                        </div>

                        @auth
                            @if(auth()->id() !== $service->user_id)
                                <a href="{{ route('payment.show', $service->slug) }}"
                                   class="btn btn-filled"
                                   style="display: block; width: 100%; text-align: center; margin-bottom: 10px;">
                                    Оплатить услугу
                                </a>
                            @endif
                        @else
                            <button type="button" onclick="openModal('login-modal')"
                                    class="btn btn-filled"
                                    style="display: block; width: 100%; text-align: center; margin-bottom: 10px;">
                                Войти для оплаты
                            </button>
                        @endauth

                        @if($service->phone)
                            <div style="padding: 12px 14px; border-radius: 10px; margin-bottom: 10px;
                    background: var(--bg-input); border: 1px solid var(--border-medium);">
                                <div class="text-muted-c" style="font-size: 10px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 4px;">Телефон</div>
                                <div style="font-size: 15px; font-weight: 500;">{{ $service->phone }}</div>
                            </div>
                        @endif

                        <a href="{{ route('profile.show', $service->user->name) }}"
                           class="btn btn-ghost"
                           style="display: block; width: 100%; text-align: center;">
                            Профиль исполнителя
                        </a>
                    </div>

                    @if($related->isNotEmpty())
                        <div class="card-flat p-5">
                            <div class="divider mb-4"><span>похожие</span></div>
                            <div class="space-y-3">
                                @foreach($related as $item)
                                    <a href="{{ route('services.show', $item->slug) }}" class="flex gap-3 group">
                                        <div class="w-14 h-14 flex-shrink-0 rounded-lg overflow-hidden" style="background: var(--bg-input);">
                                            @if($item->getFirstMediaUrl('photos', 'thumb'))
                                                <img src="{{ $item->getFirstMediaUrl('photos', 'thumb') }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm leading-snug group-hover:text-brown transition truncate" style="color: var(--text-primary);">
                                                {{ $item->title }}
                                            </div>
                                            <div class="text-xs text-muted-c mt-1">{{ $item->region }}</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </aside>
            </div>
        </div>
    </section>

@endsection
