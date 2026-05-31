@extends('layouts.app')

@section('title', $news->title)
@section('mode', 'sand')

@section('content')

    <section class="py-4 px-6" style="background: var(--bg-section-alt);">
        <div class="workspace">
            <div class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-widest text-muted-c" style="letter-spacing: 2px;">
                <a href="{{ route('home') }}" class="text-brown hover:underline">Главная</a>
                <span>/</span>
                <a href="{{ route('news.index') }}" class="text-brown hover:underline">Новости</a>
                <span>/</span>
                <span class="truncate max-w-xs">{{ $news->title }}</span>
            </div>
        </div>
    </section>
    <section class="py-12 px-6">
        <div class="workspace">
            <div class="grid lg:grid-cols-[1fr_280px] gap-12">

                <article>

                    @if($news->getFirstMediaUrl('cover'))
                        <div class="overflow-hidden rounded-2xl mb-8" style="box-shadow: var(--shadow-hover); max-height: 480px;">
                            <img src="{{ $news->getFirstMediaUrl('cover') }}"
                                 alt="{{ $news->title }}"
                                 class="w-full object-cover">
                        </div>
                    @endif

                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        @if($news->news_category)
                            <span class="badge badge-forest">{{ $news->news_category }}</span>
                        @endif
                        <span class="text-xs text-muted-c uppercase tracking-widest" style="letter-spacing: 2px;">
                        {{ $news->published_at->translatedFormat('j F Y') }}
                    </span>
                        <span class="text-xs text-muted-c">{{ $news->views_count }} просмотров</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl mb-4 reveal" style="line-height: 1.15;">{{ $news->title }}</h1>

                    <div class="flex items-center gap-3 mb-8 pb-6" style="border-bottom: 1px solid var(--border-soft);">
                        <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0" style="background: var(--forest);">
                            @if($news->author?->getFirstMediaUrl('avatar'))
                                <img src="{{ $news->author->getFirstMediaUrl('avatar') }}" alt="" class="w-full h-full object-cover">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#f5efe0;font-family:'Karelle',serif;font-size:16px;">
                                    {{ mb_strtoupper(mb_substr($news->author?->name ?? 'A', 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm font-medium" style="color: var(--text-primary);">
                                {{ $news->author?->name ?? 'Редакция TerraForum' }}
                            </div>
                            <div class="text-xs text-muted-c">Редакция</div>
                        </div>
                    </div>

                    @if($news->excerpt)
                        <p class="text-lg mb-6 leading-relaxed" style="color: var(--text-secondary); border-left: 3px solid var(--brown); padding-left: 1rem;">
                            {{ $news->excerpt }}
                        </p>
                    @endif

                    <div class="leading-relaxed whitespace-pre-line" style="color: var(--text-primary); font-size: 1rem; line-height: 1.8;">
                        {!! $news->content !!}
                    </div>

                    @if($news->tags->isNotEmpty())
                        <div class="flex flex-wrap gap-2 mt-8 pt-6" style="border-top: 1px solid var(--border-soft);">
                            @foreach($news->tags as $tag)
                                <span class="tag">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                    <div class="mt-12">
                        <div class="divider mb-6"><span>комментарии ({{ $comments->count() }})</span></div>

                        @auth
                            <form action="{{ route('news.comment', $news->slug) }}" method="POST"
                                  class="card-flat p-5 mb-6 space-y-3">
                                @csrf
                                <textarea name="content" rows="3" required
                                          placeholder="Ваш комментарий..."
                                          class="input-field w-full px-3 py-2 text-sm @error('content') error @enderror">{{ old('content') }}</textarea>
                                @error('content')
                                <p class="text-xs" style="color: var(--error);">{{ $message }}</p>
                                @enderror
                                <button type="submit" class="btn btn-filled">Опубликовать</button>
                            </form>
                        @else
                            <div class="card-flat p-5 text-center mb-6">
                                <p class="text-sm text-secondary-c mb-3">Чтобы оставить комментарий, войдите</p>
                                <button type="button" onclick="openModal('login-modal')" class="btn btn-filled">Войти</button>
                            </div>
                        @endauth

                        <div class="space-y-4">
                            @forelse($comments as $comment)
                                <div class="card p-5 flex gap-4">
                                    <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0" style="background: var(--forest);">
                                        @if($comment->user?->getFirstMediaUrl('avatar'))
                                            <img src="{{ $comment->user->getFirstMediaUrl('avatar') }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#f5efe0;font-family:'Karelle',serif;font-size:14px;">
                                                {{ mb_strtoupper(mb_substr($comment->user?->name ?? '?', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="text-sm font-medium">{{ $comment->user?->name }}</span>
                                            <span class="text-xs text-muted-c">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-secondary-c leading-relaxed">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-secondary-c text-sm">Комментариев пока нет</div>
                            @endforelse
                        </div>
                    </div>
                </article>

                <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
                    @if($related->isNotEmpty())
                        <div class="card-flat p-5">
                            <div class="divider mb-4"><span>похожие</span></div>
                            <div class="space-y-4">
                                @foreach($related as $item)
                                    <a href="{{ route('news.show', $item->slug) }}" class="flex gap-3 group">
                                        <div class="w-16 h-16 flex-shrink-0 rounded-lg overflow-hidden" style="background: var(--bg-input);">
                                            @if($item->getFirstMediaUrl('cover'))
                                                <img src="{{ $item->getFirstMediaUrl('cover') }}"
                                                     alt=""
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-xs text-muted-c mb-1">{{ $item->published_at->translatedFormat('j F') }}</div>
                                            <div class="text-sm leading-snug group-hover:text-brown transition" style="color: var(--text-primary);">
                                                {{ \Illuminate\Support\Str::limit($item->title, 60) }}
                                            </div>
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
