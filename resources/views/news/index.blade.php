@extends('layouts.app')
@section('title', 'Новости')
@section('mode', 'sand')

@section('content')

    {{-- Hero --}}
    <section style="background: radial-gradient(ellipse at 60% 40%, #7a8c6e 0%, #5a6b52 35%, #4a5c44 60%, #3d4f38 100%); padding: 64px 24px; text-align: center; color: #f5efe0;">
        <div class="divider mb-4 reveal" style="color: rgba(245,239,224,0.6);"><span>новости</span></div>
        <h1 class="reveal reveal-delay-1" style="font-family: 'Karelle', serif; font-size: clamp(2rem, 5vw, 3.5rem); font-weight: normal; color: #f5efe0; margin-bottom: 0.75rem;">
            Новости сообщества
        </h1>
        <p class="reveal reveal-delay-2" style="color: rgba(245,239,224,0.8); max-width: 480px; margin: 0 auto; font-size: 0.95rem;">
            Актуальные события агросектора, исследования, советы от редакции.
        </p>
    </section>

    <section class="py-12 px-6">
        <div class="workspace">
            <div class="grid lg:grid-cols-[1fr_280px] gap-8">
                <div>
                    @if($categories->isNotEmpty())
                        <div class="flex flex-wrap gap-2 mb-8">
                            <a href="{{ route('news.index') }}"
                               class="tag {{ !$category ? '' : '' }}"
                               style="{{ !$category ? 'background: var(--brown); color: #fff; border-color: var(--brown);' : '' }}">
                                Все
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ route('news.index', ['category' => $cat]) }}"
                                   class="tag"
                                   style="{{ $category === $cat ? 'background: var(--brown); color: #fff; border-color: var(--brown);' : '' }}">
                                    {{ $cat }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @forelse($news as $item)
                        <article class="card mb-4 overflow-hidden reveal">

                            @if($item->getFirstMediaUrl('cover'))
                                <a href="{{ route('news.show', $item->slug) }}" class="block overflow-hidden" style="height: 220px;">
                                    <img src="{{ $item->getFirstMediaUrl('cover') }}"
                                         alt="{{ $item->title }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                </a>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-3 text-xs uppercase tracking-widest text-muted-c" style="letter-spacing: 2px;">
                                    @if($item->news_category)
                                        <span class="badge badge-forest">{{ $item->news_category }}</span>
                                    @endif
                                    <span>{{ $item->published_at->translatedFormat('j F Y') }}</span>
                                </div>

                                <h2 class="text-2xl mb-2">
                                    <a href="{{ route('news.show', $item->slug) }}" class="title-link">
                                        {{ $item->title }}
                                    </a>
                                </h2>

                                @if($item->excerpt)
                                    <p class="text-sm text-secondary-c leading-relaxed mb-4">
                                        {{ \Illuminate\Support\Str::limit($item->excerpt, 200) }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-xs text-muted-c">
                                        <div class="w-7 h-7 rounded-full overflow-hidden flex-shrink-0" style="background: var(--forest);">
                                            @if($item->author?->getFirstMediaUrl('avatar'))
                                                <img src="{{ $item->author->getFirstMediaUrl('avatar') }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#f5efe0;font-family:'Karelle',serif;font-size:12px;">
                                                    {{ mb_strtoupper(mb_substr($item->author?->name ?? 'A', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        {{ $item->author?->name ?? 'Редакция' }}
                                    </div>
                                    <a href="{{ route('news.show', $item->slug) }}"
                                       class="text-xs uppercase tracking-widest text-brown hover:underline"
                                       style="letter-spacing: 2px;">
                                        Читать →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="card-flat py-16 text-center">
                            <p class="text-secondary-c">Новостей пока нет</p>
                        </div>
                    @endforelse

                    @if($news->hasPages())
                        <div class="mt-8">{{ $news->links() }}</div>
                    @endif
                </div>

                {{-- Сайдбар --}}
                <aside class="space-y-4">
                    <div class="card-flat p-5">
                        <div class="divider mb-4"><span>последние</span></div>
                        @auth
                            <div class="card-flat p-5">
                                <div class="divider mb-4"><span>подписка</span></div>
                                <p class="text-sm text-secondary-c mb-4 leading-relaxed">
                                    @if(auth()->user()->news_subscribed)
                                        Вы подписаны на новости. Уведомления будут приходить при публикации новых статей.
                                    @else
                                        Подпишитесь на новости чтобы получать уведомления о новых публикациях.
                                    @endif
                                </p>
                                <form action="{{ route('news.subscribe.toggle') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn w-full text-center {{ auth()->user()->news_subscribed ? 'btn-ghost' : 'btn-filled' }}">
                                        {{ auth()->user()->news_subscribed ? 'Отписаться' : 'Подписаться на новости' }}
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="card-flat p-5">
                                <div class="divider mb-4"><span>подписка</span></div>
                                <p class="text-sm text-secondary-c mb-4">
                                    Войдите чтобы подписаться на новости платформы.
                                </p>
                                <button type="button" onclick="openModal('login-modal')" class="btn btn-filled w-full">
                                    Войти
                                </button>
                            </div>
                        @endauth
                        <div class="space-y-4">
                            @foreach($latest as $item)
                                <a href="{{ route('news.show', $item->slug) }}" class="flex gap-3 group">
                                    <div class="w-16 h-16 flex-shrink-0 rounded-lg overflow-hidden" style="background: var(--bg-input);">
                                        @if($item->getFirstMediaUrl('cover'))
                                            <img src="{{ $item->getFirstMediaUrl('cover') }}"
                                                 alt=""
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-muted-c text-xs">
                                                Нет фото
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-muted-c mb-1">{{ $item->published_at->translatedFormat('j F') }}</div>
                                        <div class="text-sm leading-snug group-hover:text-brown transition" style="color: var(--text-primary);">
                                            {{ \Illuminate\Support\Str::limit($item->title, 60) }}
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>

@endsection
