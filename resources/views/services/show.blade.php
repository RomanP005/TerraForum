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
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-8 reveal">
                            @foreach($service->getMedia('photos') as $photo)
                                <a href="{{ $photo->getUrl() }}" target="_blank">
                                    <img src="{{ $photo->getUrl() }}" alt=""
                                         class="w-full aspect-square object-cover rounded-xl hover:opacity-90 transition"
                                         style="box-shadow: var(--shadow-soft);">
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

                    <div class="card-flat p-6">
                        <div class="divider mb-4"><span>исполнитель</span></div>

                        <div class="flex items-center gap-3 mb-4">
                            <a href="{{ route('profile.show', $service->user->name) }}" class="avatar w-14 h-14">
                                @if($service->user->getFirstMediaUrl('avatar', 'thumb'))
                                    <img src="{{ $service->user->getFirstMediaUrl('avatar', 'thumb') }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="avatar-fallback" style="font-size: 22px;">
                                        {{ mb_strtoupper(mb_substr($service->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </a>
                            <div>
                                <a href="{{ route('profile.show', $service->user->name) }}" class="title-link text-base font-medium">
                                    {{ $service->user->name }}
                                </a>
                                <div class="text-xs text-muted-c">
                                    Репутация: {{ $service->user->rating ?? 0 }}
                                </div>
                            </div>
                        </div>
                        @auth
                            @if(auth()->id() !== $service->user_id)
                                <div class="card-flat p-5">
                                    <div class="divider mb-4"><span>связаться</span></div>
                                    <form action="{{ route('services.contact', $service->slug) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <textarea name="message" rows="4" required
                                                  placeholder="Опишите что вас интересует..."
                                                  class="input-field w-full px-3 py-2 text-sm @error('message') error @enderror">{{ old('message') }}</textarea>
                                        @error('message')
                                        <p class="text-xs" style="color: var(--error);">{{ $message }}</p>
                                        @enderror
                                        <button type="submit" class="btn btn-filled w-full">
                                            Отправить сообщение
                                        </button>
                                    </form>
                                    <p class="text-xs text-muted-c mt-3 text-center">
                                        Исполнитель получит уведомление на платформе
                                    </p>
                                </div>
                            @endif
                        @else
                            <div class="card-flat p-5">
                                <div class="divider mb-4"><span>связаться</span></div>
                                <p class="text-sm text-secondary-c mb-3 text-center">
                                    Войдите чтобы написать исполнителю
                                </p>
                                <button type="button" onclick="openModal('login-modal')" class="btn btn-filled w-full">
                                    Войти
                                </button>
                            </div>
                        @endauth
                        {{-- Контакты --}}
                        @if($service->phone)
                            <div class="py-3 px-4 rounded-lg mb-3" style="background: var(--bg-input); border: 1px solid var(--border-medium);">
                                <div class="text-xs uppercase tracking-widest text-muted-c mb-1" style="letter-spacing: 2px;">Телефон</div>
                                <div class="text-base font-medium">{{ $service->phone }}</div>
                            </div>
                        @endif

                        <a href="{{ route('profile.show', $service->user->name) }}" class="btn btn-ghost w-full text-center">
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
