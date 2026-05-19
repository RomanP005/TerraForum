@extends('layouts.app')

@section('title', $theme->title)
@section('mode', 'earth')

@section('content')

    {{-- Хлебные крошки --}}
    <section class="py-4 px-6" style="background: var(--bg-section-alt);">
        <div class="workspace">
            <div class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-widest" style="letter-spacing: 2px;">
                <a href="{{ route('forum.index') }}" class="text-brown hover:underline">Форум</a>
                <span class="text-muted-c">/</span>
                @if($theme->category)
                    <a href="{{ route('forum.index', ['category' => $theme->category->slug]) }}" class="text-brown hover:underline">
                        {{ $theme->category->name }}
                    </a>
                    <span class="text-muted-c">/</span>
                @endif
                <span class="text-muted-c">Тема</span>
                @if($theme->is_pinned)<span class="badge badge-pinned ml-2">Закреплено</span>@endif
                @if($theme->is_closed)<span class="badge badge-brown ml-2">Закрыто</span>@endif
            </div>
        </div>
    </section>

    {{-- Тема --}}
    <section class="py-10 px-6">
        <div class="workspace">
            <header class="mb-8 reveal">
                <h1 class="text-4xl md:text-5xl mb-4" style="line-height: 1.15;">{{ $theme->title }}</h1>
                <div class="flex flex-wrap items-center gap-4 text-xs uppercase tracking-widest text-muted-c" style="letter-spacing: 2px;">
                    <a href="{{ route('profile.show', $theme->user->name) }}" class="text-brown hover:underline">{{ $theme->user->name }}</a>
                    <span>{{ $theme->created_at->translatedFormat('j F Y') }}</span>
                    <span>{{ $theme->views_count }} просмотров</span>
                    <span>{{ $posts->total() }} ответов</span>
                </div>
            </header>

            <article class="card-flat p-6 md:p-8 flex flex-col md:flex-row gap-6 mb-8 reveal">
                <div class="md:w-40 flex md:flex-col items-center gap-4 md:gap-3">
                    <a href="{{ route('profile.show', $theme->user->name) }}" class="avatar w-20 h-20">
                        @if($theme->user->getFirstMediaUrl('avatar', 'thumb'))
                            <img src="{{ $theme->user->getFirstMediaUrl('avatar', 'thumb') }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="avatar-fallback" style="font-size: 28px;">
                                {{ mb_strtoupper(mb_substr($theme->user->name, 0, 1)) }}
                            </div>
                        @endif
                    </a>

                    <div class="text-center">
                        <a href="{{ route('profile.show', $theme->user->name) }}" class="text-sm text-cream hover:text-brown block">{{ $theme->user->name }}</a>
                        <div class="text-xs text-muted-c">Репутация: {{ $theme->user->rating ?? 0 }}</div>
                    </div>

                    @auth @can('vote')
                        <div class="flex md:flex-col items-center gap-2 mt-2">
                            <form action="{{ route('forum.theme.vote', $theme->slug) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="value" value="up">
                                <button type="submit" class="vote-btn" title="Полезная">▲</button>
                            </form>
                            <div class="text-2xl gradient-number">{{ $theme->totalVotes() }}</div>
                            <form action="{{ route('forum.theme.vote', $theme->slug) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="value" value="down">
                                <button type="submit" class="vote-btn" title="Бесполезная">▼</button>
                            </form>
                        </div>
                    @endcan @endauth
                </div>

                <div class="flex-1 min-w-0">
                    <div class="text-cream leading-relaxed whitespace-pre-line">{{ $theme->content }}</div>

                    @if($theme->getMedia('attachments')->isNotEmpty())
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6">
                            @foreach($theme->getMedia('attachments') as $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    <img src="{{ $media->getUrl('thumb') }}" alt=""
                                         class="w-full aspect-square object-cover rounded-xl hover:opacity-90 transition"
                                         style="box-shadow: var(--shadow-soft);">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if($theme->tags->isNotEmpty())
                        <div class="flex flex-wrap gap-2 mt-6 pt-4" style="border-top: 1px solid var(--border-soft);">
                            @foreach($theme->tags as $tag)
                                <a href="{{ route('forum.index', ['tags' => [$tag->name]]) }}" class="tag">#{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </section>

    {{-- Ответы --}}
    <section class="px-6 py-10" style="background: var(--bg-section-alt);">
        <div class="workspace">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl">
                    Ответы <span class="text-muted-c text-base">({{ $posts->total() }})</span>
                </h2>
            </div>

            @forelse($posts as $post)
                <article id="post-{{ $post->id }}"
                         class="card p-6 mb-3 flex flex-col md:flex-row gap-6 reveal"
                         style="{{ $post->is_best_answer ? 'border: 2px solid var(--forest-light); box-shadow: 0 0 0 4px rgba(107, 138, 92, 0.1), var(--shadow-soft);' : '' }}">

                    <div class="md:w-32 flex md:flex-col items-center gap-3">
                        @if($post->is_best_answer)
                            <div class="badge" style="background: linear-gradient(135deg, var(--forest-light), var(--forest)); color: #ffffff; padding: 6px 14px;">Лучший ответ</div>
                        @endif

                        <a href="{{ route('profile.show', $post->user->name) }}" class="avatar w-14 h-14">
                            @if($post->user->getFirstMediaUrl('avatar', 'thumb'))
                                <img src="{{ $post->user->getFirstMediaUrl('avatar', 'thumb') }}" alt="" class="w-full h-full object-cover">
                            @else
                                <div class="avatar-fallback" style="font-size: 22px;">
                                    {{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </a>

                        <div class="text-center">
                            <a href="{{ route('profile.show', $post->user->name) }}" class="text-xs text-cream hover:text-brown block">{{ $post->user->name }}</a>
                            <div class="text-xs text-muted-c">{{ $post->user->rating ?? 0 }}</div>
                        </div>

                        @auth @can('vote')
                            <div class="flex md:flex-col items-center gap-1">
                                <form action="{{ route('forum.post.vote', $post->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="value" value="up">
                                    <button type="submit" class="vote-btn">▲</button>
                                </form>
                                <div class="text-lg gradient-number">{{ $post->totalVotes() }}</div>
                                <form action="{{ route('forum.post.vote', $post->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="value" value="down">
                                    <button type="submit" class="vote-btn">▼</button>
                                </form>
                            </div>
                        @endcan @endauth
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="text-xs uppercase tracking-widest text-muted-c mb-3" style="letter-spacing: 2px;">
                            {{ $post->created_at->translatedFormat('j F Y, H:i') }}
                            @if($post->is_edited)<span class="ml-2">(изменено)</span>@endif
                        </div>

                        <div class="text-cream leading-relaxed whitespace-pre-line">{{ $post->content }}</div>

                        @if($post->getMedia('attachments')->isNotEmpty())
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-4">
                                @foreach($post->getMedia('attachments') as $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        <img src="{{ $media->getUrl() }}" alt="" class="w-full aspect-square object-cover rounded-xl hover:opacity-90 transition">
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        @auth
                            @if(! $post->is_best_answer && (auth()->id() === $theme->user_id || auth()->user()->hasAnyRole(['moderator', 'admin'])))
                                <form action="{{ route('forum.post.best', $post->id) }}" method="POST" class="mt-4 inline">
                                    @csrf
                                    <button type="submit" class="text-xs uppercase tracking-widest text-brown hover:underline" style="letter-spacing: 2px;">
                                        Отметить как лучший ответ
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </article>
            @empty
                <div class="card-flat py-12 text-center">
                    <p class="text-base text-secondary-c">Пока нет ответов</p>
                    <p class="text-sm text-muted-c mt-2">Будьте первым, кто ответит</p>
                </div>
            @endforelse

            @if($posts->hasPages())<div class="mt-8">{{ $posts->links() }}</div>@endif
        </div>
    </section>

    @auth
        @if(! $theme->is_closed)
            @can('create posts')
                <section class="py-10 px-6">
                    <div class="workspace">
                        <h2 class="text-2xl mb-4">Ваш ответ</h2>

                        <form action="{{ route('forum.post.store', $theme->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-4 card-flat p-6">
                            @csrf

                            <textarea name="content" rows="6" required
                                      placeholder="Поделитесь опытом или задайте уточняющий вопрос..."
                                      class="input-field w-full px-3 py-2 text-sm @error('content') error @enderror">{{ old('content') }}</textarea>
                            @error('content')<p class="text-xs" style="color: var(--error);">{{ $message }}</p>@enderror

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">
                                    Прикрепить фото <span class="normal-case opacity-60">(до 3)</span>
                                </label>
                                <input type="file" name="attachments[]" multiple accept="image/jpeg,image/png,image/webp" class="text-xs text-secondary-c">
                            </div>

                            <button type="submit" class="btn btn-filled">Опубликовать ответ</button>
                        </form>
                    </div>
                </section>
            @endcan
        @else
            <section class="py-10 px-6">
                <div class="workspace text-center">
                    <p class="text-sm text-muted-c uppercase tracking-widest" style="letter-spacing: 2px;">Тема закрыта для новых ответов</p>
                </div>
            </section>
        @endif
    @else
        <section class="py-10 px-6">
            <div class="workspace text-center">
                <p class="text-sm text-secondary-c mb-4">Чтобы оставить ответ, нужно войти в систему</p>
                <button type="button" onclick="openModal('login-modal')" class="btn btn-filled">Войти</button>
            </div>
        </section>
    @endauth

@endsection
