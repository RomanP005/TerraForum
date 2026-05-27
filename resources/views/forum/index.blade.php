@extends('layouts.app')

@section('title', 'Форум')
@section('mode', 'earth')

@section('content')

    <section class="py-12 px-6" style="background: var(--bg-section-alt);">
        <div class="workspace text-center reveal">
            <div class="divider mb-4"><span>обсуждения</span></div>
            <h1 class="text-5xl md:text-6xl mb-3" style="line-height: 1.1;">Форум</h1>
            <p class="text-base text-secondary-c max-w-xl mx-auto">
                Задавайте вопросы, делитесь опытом, находите ответы.
            </p>
        </div>
    </section>

    <section class="px-6 -mt-6 mb-8 relative z-10">
        <div class="workspace">
            <div class="card-flat p-5 reveal">
                <form action="{{ route('forum.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    @if($categorySlug)<input type="hidden" name="category" value="{{ $categorySlug }}">@endif
                    @if($sort && $sort !== 'latest')<input type="hidden" name="sort" value="{{ $sort }}">@endif
                    @foreach($selectedTags as $tag)<input type="hidden" name="tags[]" value="{{ $tag }}">@endforeach

                    <input type="search" name="q" value="{{ $search }}"
                           placeholder="Поиск по темам и сообщениям..."
                           class="input-field flex-1 px-4 py-3 text-sm">

                    <button type="submit" class="btn btn-filled">Найти</button>

                    @auth @can('create themes')
                        <button type="button" onclick="openModal('create-theme-modal')" class="btn btn-ghost">
                            Создать тему
                        </button>
                    @endcan @endauth
                </form>

                @if($search || $categorySlug || $selectedTags->isNotEmpty())
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                        <span class="uppercase tracking-widest text-muted-c" style="letter-spacing: 2px;">Фильтры:</span>
                        @if($search)
                            <span class="badge badge-soft inline-flex items-center gap-2">
                            «{{ $search }}»
                            <a href="{{ request()->fullUrlWithoutQuery('q') }}" class="text-brown">×</a>
                        </span>
                        @endif
                        @if($categorySlug)
                            <span class="badge badge-soft inline-flex items-center gap-2">
                            {{ \App\Models\Category::where('slug', $categorySlug)->value('name') }}
                            <a href="{{ request()->fullUrlWithoutQuery('category') }}" class="text-brown">×</a>
                        </span>
                        @endif
                        @foreach($selectedTags as $tag)
                            <span class="badge badge-soft inline-flex items-center gap-2">
                            #{{ $tag }}
                            <a href="{{ request()->fullUrlWithoutQuery('tags') }}" class="text-brown">×</a>
                        </span>
                        @endforeach
                        <a href="{{ route('forum.index') }}" class="text-brown hover:underline uppercase tracking-widest ml-2 text-xs" style="letter-spacing: 2px;">
                            Сбросить
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="px-6">
        <div class="workspace">

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-1 text-xs uppercase tracking-widest" style="letter-spacing: 2px;">
                    <span class="text-muted-c mr-2">Сортировать:</span>
                    @php $sortOptions = ['latest' => 'Новые', 'popular' => 'Популярные', 'active' => 'Активные']; @endphp
                    @foreach($sortOptions as $key => $label)
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $key]) }}"
                           class="px-3 py-1.5 rounded-md transition-all
                              {{ $sort === $key ? 'text-cream' : 'text-secondary-c hover:text-cream' }}"
                           style="{{ $sort === $key ? 'background: linear-gradient(135deg, var(--brown-bright), var(--brown));' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
                <div class="text-xs text-muted-c uppercase tracking-widest" style="letter-spacing: 2px;">
                    Найдено: {{ $themes->total() }}
                </div>
            </div>

            <div class="grid lg:grid-cols-[1fr_280px] gap-6">

                <div class="space-y-4">
                    @forelse($themes as $theme)
                        <article class="card p-6 flex gap-5 reveal">
                            <div class="flex flex-col items-center text-center pt-1 min-w-[60px]">
                                <div class="text-3xl gradient-number">{{ $theme->total_votes ?? 0 }}</div>
                                <div class="text-xs uppercase tracking-widest text-muted-c mt-1" style="letter-spacing: 2px;">голосов</div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    @if($theme->is_pinned)<span class="badge badge-pinned">Закреплено</span>@endif
                                    @if($theme->is_closed)<span class="badge badge-brown">Закрыто</span>@endif
                                    @if($theme->category)
                                        <a href="{{ route('forum.index', ['category' => $theme->category->slug]) }}"
                                           class="text-xs uppercase tracking-widest text-brown hover:underline"
                                           style="letter-spacing: 2px;">
                                            {{ $theme->category->name }}
                                        </a>
                                    @endif
                                </div>

                                <h2 class="text-2xl mb-2">
                                    <a href="{{ route('forum.theme', $theme->slug) }}" class="title-link text-cream">
                                        {{ $theme->title }}
                                    </a>
                                </h2>

                                <p class="text-sm leading-relaxed mb-3 text-secondary-c">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($theme->content), 180) }}
                                </p>

                                @if($theme->tags->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        @foreach($theme->tags as $tag)
                                            <a href="{{ route('forum.index', ['tags' => [$tag->name]]) }}" class="tag">
                                                #{{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="flex flex-wrap items-center gap-4 text-xs text-muted-c">
                                <span>
                                    <a href="{{ route('profile.show', $theme->user->name) }}" class="text-brown hover:underline">
                                        {{ $theme->user->name }}
                                    </a>
                                    · {{ $theme->created_at->diffForHumans() }}
                                </span>
                                    <span>{{ $theme->posts_count ?? 0 }} ответов</span>
                                    <span>{{ $theme->views_count }} просмотров</span>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="card-flat py-16 text-center">
                            <p class="text-base text-secondary-c mb-2">Тем не найдено</p>
                            @if($search || $categorySlug || $selectedTags->isNotEmpty())
                                <a href="{{ route('forum.index') }}" class="text-brown hover:underline text-sm">Сбросить фильтры</a>
                            @endif
                        </div>
                    @endforelse

                    @if($themes->hasPages())
                        <div class="mt-8">{{ $themes->links() }}</div>
                    @endif
                </div>
                
                <aside class="space-y-4">
                    <div class="card-flat p-5">
                        <div class="divider mb-4"><span>категории</span></div>
                        <ul class="space-y-1 text-sm">
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('forum.index', ['category' => $category->slug]) }}"
                                       class="block py-2 px-3 rounded-lg transition-all
                                          {{ $categorySlug === $category->slug ? 'text-cream' : 'text-cream hover:text-brown' }}"
                                       style="{{ $categorySlug === $category->slug ? 'background: rgba(212, 165, 116, 0.12); border-left: 2px solid var(--brown-bright); padding-left: 14px;' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                    @foreach($category->children as $child)
                                        <a href="{{ route('forum.index', ['category' => $child->slug]) }}"
                                           class="block pl-6 py-1.5 text-xs transition-all
                                              {{ $categorySlug === $child->slug ? 'text-brown' : 'text-muted-c hover:text-brown' }}">
                                            — {{ $child->name }}
                                        </a>
                                    @endforeach
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if($popularTags->isNotEmpty())
                        <div class="card-flat p-5">
                            <div class="divider mb-4"><span>популярные теги</span></div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($popularTags as $tag)
                                    <a href="{{ route('forum.index', ['tags' => [$tag->name]]) }}" class="tag">
                                        #{{ $tag->name }}
                                    </a>
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
