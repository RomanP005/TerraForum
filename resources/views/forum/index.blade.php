@extends('layouts.app')
@section('title', 'Форум')
@section('mode', 'earth')
@section('content')

    <section style="background: var(--bg-section-alt); padding: 48px 0 32px;">
        <div class="workspace" style="text-align: center;" class="reveal">
            <div class="divider" style="margin-bottom: 16px;"><span>обсуждения</span></div>
            <h1 style="font-size: clamp(2rem, 5vw, 3.5rem); line-height: 1.1; margin-bottom: 12px;">Форум</h1>
            <p class="text-secondary-c" style="max-width: 480px; margin: 0 auto; font-size: 15px;">
                Задавайте вопросы, делитесь опытом, находите ответы.
            </p>
        </div>
    </section>

    <section style="padding: 0 0 24px; position: relative; z-index: 10; margin-top: -20px;">
        <div class="workspace">
            <div class="card-flat" style="padding: 20px;">
                <form action="{{ route('forum.index') }}" method="GET"
                      style="display: flex; flex-wrap: wrap; gap: 10px;">
                    @if($categorySlug)<input type="hidden" name="category" value="{{ $categorySlug }}">@endif
                    @if($sort && $sort !== 'latest')<input type="hidden" name="sort" value="{{ $sort }}">@endif
                    @foreach($selectedTags as $tag)<input type="hidden" name="tags[]" value="{{ $tag }}">@endforeach

                    <input type="search" name="q" value="{{ $search }}"
                           placeholder="Поиск по темам и сообщениям..."
                           class="input-field px-4 py-3"
                           style="flex: 1 1 200px; font-size: 14px;">

                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <button type="submit" class="btn btn-filled">Найти</button>
                        @auth @can('create themes')
                            <button type="button" onclick="openModal('create-theme-modal')" class="btn btn-ghost">
                                Создать тему
                            </button>
                        @endcan @endauth
                    </div>
                </form>

                @if($search || $categorySlug || $selectedTags->isNotEmpty())
                    <div style="margin-top: 12px; display: flex; flex-wrap: wrap; align-items: center; gap: 8px; font-size: 12px;">
                        <span class="text-muted-c" style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Фильтры:</span>
                        @if($search)
                            <span class="badge badge-soft" style="display: inline-flex; align-items: center; gap: 6px;">
                            «{{ $search }}» <a href="{{ request()->fullUrlWithoutQuery('q') }}" class="text-brown">×</a>
                        </span>
                        @endif
                        @if($categorySlug)
                            <span class="badge badge-soft" style="display: inline-flex; align-items: center; gap: 6px;">
                            {{ \App\Models\Category::where('slug', $categorySlug)->value('name') }}
                            <a href="{{ request()->fullUrlWithoutQuery('category') }}" class="text-brown">×</a>
                        </span>
                        @endif
                        @foreach($selectedTags as $tag)
                            <span class="badge badge-soft" style="display: inline-flex; align-items: center; gap: 6px;">
                            #{{ $tag }} <a href="{{ request()->fullUrlWithoutQuery('tags') }}" class="text-brown">×</a>
                        </span>
                        @endforeach
                        <a href="{{ route('forum.index') }}" class="text-brown" style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; text-decoration: none; margin-left: 4px;">Сбросить</a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section style="padding-bottom: 48px;">
        <div class="workspace">

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 20px;">
                <span class="text-muted-c" style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Сортировать:</span>
                @php $sortOptions = ['latest' => 'Новые', 'popular' => 'Популярные', 'active' => 'Активные']; @endphp
                @foreach($sortOptions as $key => $label)
                    <a href="{{ request()->fullUrlWithQuery(['sort' => $key]) }}"
                       style="padding: 6px 12px; border-radius: 8px; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; transition: all 0.2s; text-decoration: none;
                          {{ $sort === $key ? 'background: linear-gradient(135deg, var(--brown-bright), var(--brown)); color: #fff;' : 'color: var(--text-secondary);' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <span class="text-muted-c" style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-left: auto;">
                Найдено: {{ $themes->total() }}
            </span>
            </div>

            <div class="forum-layout" style="display: grid; grid-template-columns: 1fr 280px; gap: 20px;">

                <div style="min-width: 0;">
                    @forelse($themes as $theme)
                        <article class="card reveal" style="padding: 20px; display: flex; gap: 16px; margin-bottom: 12px;">
                            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding-top: 4px; min-width: 52px; flex-shrink: 0;">
                                <div class="gradient-number" style="font-family: 'Karelle', serif; font-size: 1.5rem;">{{ $theme->total_votes ?? 0 }}</div>
                                <div class="text-muted-c" style="font-size: 9px; letter-spacing: 1px; text-transform: uppercase; margin-top: 2px;">голосов</div>
                            </div>

                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 6px; margin-bottom: 8px;">
                                    @if($theme->is_pinned)<span class="badge badge-pinned">Закреплено</span>@endif
                                    @if($theme->is_closed)<span class="badge badge-brown">Закрыто</span>@endif
                                    @if($theme->category)
                                        <a href="{{ route('forum.index', ['category' => $theme->category->slug]) }}"
                                           class="text-brown" style="font-size: 10px; letter-spacing: 2px; text-transform: uppercase; text-decoration: none;">
                                            {{ $theme->category->name }}
                                        </a>
                                    @endif
                                </div>

                                <h2 style="font-size: clamp(1rem, 2.5vw, 1.35rem); margin-bottom: 8px; line-height: 1.3;">
                                    <a href="{{ route('forum.theme', $theme->slug) }}" class="title-link text-cream">{{ $theme->title }}</a>
                                </h2>

                                <p class="text-secondary-c" style="font-size: 13px; line-height: 1.6; margin-bottom: 10px;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($theme->content), 160) }}
                                </p>

                                @if($theme->tags->isNotEmpty())
                                    <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 10px;">
                                        @foreach($theme->tags as $tag)
                                            <a href="{{ route('forum.index', ['tags' => [$tag->name]]) }}" class="tag">#{{ $tag->name }}</a>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="text-muted-c" style="display: flex; flex-wrap: wrap; gap: 12px; font-size: 11px;">
                                <span>
                                    <a href="{{ route('profile.show', $theme->user->name) }}" class="text-brown" style="text-decoration: none;">{{ $theme->user->name }}</a>
                                    · {{ $theme->created_at->diffForHumans() }}
                                </span>
                                    <span>{{ $theme->posts_count ?? 0 }} ответов</span>
                                    <span>{{ $theme->views_count }} просмотров</span>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="card-flat" style="padding: 64px 24px; text-align: center;">
                            <p class="text-secondary-c" style="margin-bottom: 8px;">Тем не найдено</p>
                            @if($search || $categorySlug || $selectedTags->isNotEmpty())
                                <a href="{{ route('forum.index') }}" class="text-brown" style="font-size: 13px; text-decoration: none;">Сбросить фильтры</a>
                            @endif
                        </div>
                    @endforelse

                    @if($themes->hasPages())
                        <div style="margin-top: 24px;">{{ $themes->links() }}</div>
                    @endif
                </div>

                <aside class="forum-sidebar" style="min-width: 0;">
                    <div class="card-flat" style="padding: 20px; margin-bottom: 16px;">
                        <div class="divider" style="margin-bottom: 16px;"><span>категории</span></div>
                        <ul style="list-style: none; margin: 0; padding: 0;">
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('forum.index', ['category' => $category->slug]) }}"
                                       style="display: block; padding: 8px 12px; border-radius: 8px; font-size: 13px; text-decoration: none; color: var(--text-primary); transition: all 0.2s;
                                          {{ $categorySlug === $category->slug ? 'background: rgba(212,165,116,0.12); border-left: 2px solid var(--brown-bright); padding-left: 14px;' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                    @foreach($category->children as $child)
                                        <a href="{{ route('forum.index', ['category' => $child->slug]) }}"
                                           style="display: block; padding: 5px 12px 5px 28px; font-size: 11px; text-decoration: none; transition: color 0.2s;
                                              color: {{ $categorySlug === $child->slug ? 'var(--brown-bright)' : 'var(--text-muted)' }};">
                                            — {{ $child->name }}
                                        </a>
                                    @endforeach
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if($popularTags->isNotEmpty())
                        <div class="card-flat" style="padding: 20px;">
                            <div class="divider" style="margin-bottom: 16px;"><span>популярные теги</span></div>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @foreach($popularTags as $tag)
                                    <a href="{{ route('forum.index', ['tags' => [$tag->name]]) }}" class="tag">#{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>

    @auth @can('create themes')
        @include('forum._create_modal', ['categories' => $categories])
    @endcan @endauth

@endsection
