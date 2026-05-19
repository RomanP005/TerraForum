@extends('layouts.app')

@section('title', 'Профиль ' . $user->name)
@section('mode', 'linen')

@section('content')

    <div class="workspace px-6 py-10">
        <div class="grid lg:grid-cols-[300px_1fr] gap-8 items-start">

            {{-- =====================
                 САЙДБАР (sticky)
                 ===================== --}}
            <aside class="card p-6 lg:sticky lg:top-24 space-y-5">

                {{-- Аватар + имя --}}
                <div class="flex flex-col items-center text-center">
                    <div class="avatar-square w-24 h-24 mb-4">
                        @if($user->getFirstMediaUrl('avatar', 'preview'))
                            <img src="{{ $user->getFirstMediaUrl('avatar', 'preview') }}"
                                 alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="avatar-fallback" style="font-size: 44px;">
                                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="section-number mb-1">
                        @forelse($user->roles as $role) {{ $role->name }} @empty гость @endforelse
                    </div>
                    <h1 class="text-3xl leading-tight mb-1">{{ $user->name }}</h1>

                    @if($user->region)
                        <p class="text-xs uppercase tracking-widest text-muted-c" style="letter-spacing: 2px;">
                            {{ $user->region }}
                        </p>
                    @endif
                </div>

                {{-- Разделитель --}}
                <div style="height: 1px; background: var(--border-soft);"></div>

                {{-- Репутация --}}
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-widest text-muted-c" style="letter-spacing: 2px;">Репутация</span>
                    <span class="text-4xl gradient-number">{{ $user->rating ?? 0 }}</span>
                </div>

                {{-- Статистика --}}
                <div class="space-y-2">
                    @foreach([
                        ['label' => 'Темы', 'value' => $stats['themes']],
                        ['label' => 'Сообщения', 'value' => $stats['posts']],
                        ['label' => 'Услуги', 'value' => $stats['services']],
                        ['label' => 'Голоса', 'value' => $stats['votes_given']],
                    ] as $stat)
                        <div class="flex items-center justify-between py-1.5 px-3 rounded-lg"
                             style="background: var(--bg-input);">
                            <span class="text-sm text-secondary-c">{{ $stat['label'] }}</span>
                            <span class="text-sm font-medium">{{ $stat['value'] }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Разделитель --}}
                <div style="height: 1px; background: var(--border-soft);"></div>

                {{-- О себе --}}
                @if($user->bio)
                    <div>
                        <div class="text-xs uppercase tracking-widest text-muted-c mb-2" style="letter-spacing: 2px;">О себе</div>
                        <p class="text-sm text-secondary-c leading-relaxed">{{ $user->bio }}</p>
                    </div>
                    <div style="height: 1px; background: var(--border-soft);"></div>
                @endif

                {{-- Дата регистрации --}}
                <div class="text-xs text-muted-c text-center uppercase tracking-widest" style="letter-spacing: 2px;">
                    На платформе с {{ $user->created_at->translatedFormat('F Y') }}
                </div>

                {{-- Кнопки (только владелец) --}}
                @if($isOwner)
                    <button type="button"
                            onclick="document.getElementById('tab-settings').click()"
                            class="btn btn-filled w-full text-center">
                        Редактировать профиль
                    </button>
                @endif

            </aside>

            {{-- =====================
                 ОСНОВНАЯ ЧАСТЬ
                 ===================== --}}
            <div>

                {{-- Табы --}}
                <div class="card-flat mb-6 p-1.5 flex gap-1 overflow-x-auto">
                    @php
                        $tabs = [
                            'activity' => 'Активность',
                            'themes'   => 'Темы',
                            'favorites' => 'Избранное',
                        ];
                        if ($isOwner) $tabs['settings'] = 'Настройки';
                    @endphp
                    @foreach($tabs as $key => $label)
                        <button id="tab-{{ $key }}"
                                onclick="switchTab('{{ $key }}')"
                                class="tab-btn flex-shrink-0 px-5 py-2 text-xs uppercase tracking-widest rounded-lg transition-all"
                                style="letter-spacing: 2px;">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                {{-- === АКТИВНОСТЬ === --}}
                <div id="panel-activity" class="tab-panel space-y-3">

                    {{-- Последние темы --}}
                    @if($stats['themes'] > 0)
                        @php
                            $recentThemes = $user->themes()
                                ->with('category')
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp
                        @foreach($recentThemes as $theme)
                            <div class="card p-5 reveal">
                                <div class="text-xs uppercase tracking-widest text-muted-c mb-2" style="letter-spacing: 2px;">
                                    {{ $theme->created_at->diffForHumans() }} · создал тему
                                </div>
                                <h3 class="text-xl mb-1">
                                    <a href="{{ route('forum.theme', $theme->slug) }}" class="title-link">
                                        {{ $theme->title }}
                                    </a>
                                </h3>
                                @if($theme->category)
                                    <div class="text-xs text-secondary-c">
                                        в категории «{{ $theme->category->name }}»
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="card-flat py-12 text-center">
                            <p class="text-secondary-c text-sm">Тем пока нет</p>
                            @if($isOwner)
                                <a href="{{ route('forum.index') }}" class="text-brown hover:underline text-sm mt-2 inline-block">
                                    Создать первую тему
                                </a>
                            @endif
                        </div>
                    @endif

                </div>

                {{-- === ТЕМЫ === --}}
                <div id="panel-themes" class="tab-panel hidden space-y-3">
                    @php
                        $allThemes = $user->themes()
                            ->with(['category', 'tags'])
                            ->withCount('posts as posts_count')
                            ->latest()
                            ->paginate(10);
                    @endphp

                    @forelse($allThemes as $theme)
                        <div class="card p-5 flex gap-4 reveal">
                            <div class="flex flex-col items-center min-w-[50px] text-center pt-1">
                                <div class="text-2xl gradient-number">{{ $theme->total_votes ?? 0 }}</div>
                                <div class="text-xs text-muted-c uppercase" style="letter-spacing: 1px; font-size: 9px;">голосов</div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl mb-1">
                                    <a href="{{ route('forum.theme', $theme->slug) }}" class="title-link">
                                        {{ $theme->title }}
                                    </a>
                                </h3>
                                @if($theme->category)
                                    <div class="text-xs text-muted-c mb-2">{{ $theme->category->name }}</div>
                                @endif
                                @if($theme->tags->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($theme->tags->take(3) as $tag)
                                            <span class="tag">#{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="text-xs text-muted-c mt-2">
                                    {{ $theme->created_at->diffForHumans() }} ·
                                    {{ $theme->posts_count }} ответов ·
                                    {{ $theme->views_count }} просмотров
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card-flat py-12 text-center">
                            <p class="text-secondary-c text-sm">Тем пока нет</p>
                        </div>
                    @endforelse

                    @if($allThemes->hasPages())
                        <div class="mt-4">{{ $allThemes->links() }}</div>
                    @endif
                </div>

                {{-- === ИЗБРАННОЕ === --}}
                <div id="panel-favorites" class="tab-panel hidden space-y-3">
                    @if($isOwner && $favorites->isNotEmpty())
                        @foreach($favorites as $favorite)
                            <div class="card p-5 reveal">
                                <div class="text-xs uppercase tracking-widest text-brown mb-2" style="letter-spacing: 2px;">
                                    {{ class_basename($favorite->favoriteable_type) }}
                                </div>
                                <p class="text-sm text-secondary-c leading-relaxed">
                                    {{ $favorite->favoriteable?->title ?? $favorite->favoriteable?->content ?? 'Удалённый объект' }}
                                </p>
                            </div>
                        @endforeach
                    @else
                        <div class="card-flat py-12 text-center">
                            <p class="text-secondary-c text-sm">
                                @if($isOwner)
                                    Вы ещё ничего не добавили в избранное.
                                @else
                                    Избранное приватное.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                {{-- === НАСТРОЙКИ (только владелец) === --}}
                @if($isOwner)
                    <div id="panel-settings" class="tab-panel hidden space-y-6">

                        {{-- Редактирование профиля --}}
                        <div class="card-flat p-6">
                            <div class="divider mb-6"><span>основные данные</span></div>

                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                                @csrf
                                @method('PATCH')

                                {{-- Аватар --}}
                                <div>
                                    <label class="block text-xs uppercase tracking-widest text-secondary-c mb-3" style="letter-spacing: 2px;">Аватар</label>
                                    <div class="flex items-center gap-4">
                                        <div class="avatar-square w-20 h-20 flex-shrink-0">
                                            @if($user->getFirstMediaUrl('avatar', 'thumb'))
                                                <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <div class="avatar-fallback" style="font-size: 32px;">
                                                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"
                                                   class="text-xs text-secondary-c">
                                            <p class="text-xs mt-1 text-muted-c">JPEG / PNG / WebP до 2 МБ, от 100×100 px</p>
                                            @error('avatar')
                                            <p class="text-xs mt-1" style="color: var(--error);">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        @if($user->getFirstMediaUrl('avatar'))
                                            <form action="{{ route('profile.avatar.delete') }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-xs uppercase tracking-widest hover:underline"
                                                        style="letter-spacing: 2px; color: var(--error);"
                                                        onclick="return confirm('Удалить аватар?')">
                                                    Удалить
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                {{-- Регион --}}
                                <div>
                                    <label for="region" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Регион</label>
                                    <input type="text" id="region" name="region"
                                           value="{{ old('region', $user->region) }}"
                                           placeholder="Например, Подмосковье"
                                           class="input-field w-full px-3 py-2 text-sm @error('region') error @enderror">
                                    @error('region')
                                    <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Биография --}}
                                <div>
                                    <label for="bio" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">О себе</label>
                                    <textarea id="bio" name="bio" rows="4" maxlength="500"
                                              placeholder="Расскажите о своём опыте, что выращиваете, как давно занимаетесь..."
                                              class="input-field w-full px-3 py-2 text-sm @error('bio') error @enderror">{{ old('bio', $user->bio) }}</textarea>
                                    <p class="text-xs mt-1 text-muted-c">До 500 символов</p>
                                    @error('bio')
                                    <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-filled">Сохранить изменения</button>
                            </form>
                        </div>

                        {{-- Смена пароля --}}
                        <div class="card-flat p-6">
                            <div class="divider mb-6"><span>безопасность</span></div>

                            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="current_password" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Текущий пароль</label>
                                    <input type="password" id="current_password" name="current_password" required
                                           autocomplete="current-password"
                                           class="input-field w-full px-3 py-2 text-sm @error('current_password') error @enderror">
                                    @error('current_password')
                                    <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="new_password" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Новый пароль</label>
                                    <input type="password" id="new_password" name="password" required
                                           autocomplete="new-password"
                                           class="input-field w-full px-3 py-2 text-sm @error('password') error @enderror">
                                    <p class="text-xs mt-1 text-muted-c">Минимум 8 символов, буквы и цифры</p>
                                    @error('password')
                                    <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Подтверждение</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" required
                                           autocomplete="new-password"
                                           class="input-field w-full px-3 py-2 text-sm">
                                </div>

                                <button type="submit" class="btn btn-filled">Изменить пароль</button>
                            </form>
                        </div>

                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- =====================
         JS ТАБОВ
         ===================== --}}
    <script>
        const tabs = {{ json_encode(array_keys($tabs ?? ['activity' => '', 'themes' => '', 'favorites' => ''])) }};

        function switchTab(name) {
            // Скрыть все панели
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
            // Сбросить стили кнопок
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.style.background = '';
                b.style.color = 'var(--text-secondary)';
            });

            // Показать нужную панель
            const panel = document.getElementById('panel-' + name);
            if (panel) panel.classList.remove('hidden');

            // Выделить активную кнопку
            const btn = document.getElementById('tab-' + name);
            if (btn) {
                btn.style.background = 'linear-gradient(135deg, var(--brown-bright), var(--brown))';
                btn.style.color = '#ffffff';
            }

            // Запомнить в URL
            const url = new URL(window.location);
            url.searchParams.set('tab', name);
            window.history.replaceState({}, '', url);
        }

        // Инициализация
        document.addEventListener('DOMContentLoaded', () => {
            // Начальный стиль всех кнопок
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.style.color = 'var(--text-secondary)';
            });

            // Открыть вкладку из URL или первую
            const urlTab = new URLSearchParams(window.location.search).get('tab') || 'activity';
            switchTab(urlTab);
        });
    </script>

@endsection
