@extends('layouts.app')

@section('title', 'Профиль ' . $user->name)
@section('mode', 'linen')

@section('content')

    <div class="workspace px-6 py-10">
        <div class="grid gap-6 profile-layout items-start" style="grid-template-columns: 300px 1fr;">

            {{-- =====================
                 САЙДБАР (sticky)
                 ===================== --}}
            <aside class="card p-6 profile-sidebar space-y-5" style="position: sticky; top: 88px;">

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

                <div style="height: 1px; background: var(--border-soft);"></div>

                {{-- Репутация --}}
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-widest text-muted-c" style="letter-spacing: 2px;">Репутация</span>
                    <span class="text-4xl gradient-number">{{ $user->rating ?? 0 }}</span>
                </div>

                {{-- Статистика --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between py-1.5 px-3 rounded-lg" style="background: var(--bg-input);">
                        <span class="text-sm text-secondary-c">Темы</span>
                        <span class="text-sm font-medium">{{ $stats['themes'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 px-3 rounded-lg" style="background: var(--bg-input);">
                        <span class="text-sm text-secondary-c">Сообщения</span>
                        <span class="text-sm font-medium">{{ $stats['posts'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 px-3 rounded-lg" style="background: var(--bg-input);">
                        <span class="text-sm text-secondary-c">Услуги</span>
                        <span class="text-sm font-medium">{{ $stats['services'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 px-3 rounded-lg" style="background: var(--bg-input);">
                        <span class="text-sm text-secondary-c">Голоса</span>
                        <span class="text-sm font-medium">{{ $stats['votes_given'] }}</span>
                    </div>
                </div>

                @if($user->bio)
                    <div style="height: 1px; background: var(--border-soft);"></div>
                    <div>
                        <div class="text-xs uppercase tracking-widest text-muted-c mb-2" style="letter-spacing: 2px;">О себе</div>
                        <p class="text-sm text-secondary-c leading-relaxed">{{ $user->bio }}</p>
                    </div>
                @endif

                <div style="height: 1px; background: var(--border-soft);"></div>

                <div class="text-xs text-muted-c text-center uppercase tracking-widest" style="letter-spacing: 2px;">
                    На платформе с {{ $user->created_at->translatedFormat('F Y') }}
                </div>

                @if($isOwner)
                    <button type="button"
                            onclick="switchTab('settings')"
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
                <div class="card-flat mb-6 p-1.5 flex gap-1 overflow-x-auto profile-tabs">
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
                    @forelse($recentThemes as $theme)
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
                                <div class="text-xs text-secondary-c">в категории «{{ $theme->category->name }}»</div>
                            @endif
                        </div>
                    @empty
                        <div class="card-flat py-12 text-center">
                            <p class="text-secondary-c text-sm">Активности пока нет</p>
                            @if($isOwner)
                                <a href="{{ route('forum.index') }}" class="text-brown hover:underline text-sm mt-2 inline-block">
                                    Создать первую тему
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>

                {{-- === ТЕМЫ === --}}
                <div id="panel-themes" class="tab-panel hidden space-y-3">
                    @forelse($allThemes as $theme)
                        <div class="card p-5 flex gap-4 reveal">
                            <div class="flex flex-col items-center min-w-[50px] text-center pt-1">
                                <div class="text-2xl gradient-number">{{ $theme->total_votes ?? 0 }}</div>
                                <div class="text-muted-c uppercase" style="font-size: 9px; letter-spacing: 1px;">голосов</div>
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
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach($theme->tags->take(3) as $tag)
                                            <span class="tag">#{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="text-xs text-muted-c">
                                    {{ $theme->created_at->diffForHumans() }} ·
                                    {{ $theme->posts_count ?? 0 }} ответов ·
                                    {{ $theme->views_count }} просмотров
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card-flat py-12 text-center">
                            <p class="text-secondary-c text-sm">Тем пока нет</p>
                        </div>
                    @endforelse

                    @if(method_exists($allThemes, 'hasPages') && $allThemes->hasPages())
                        <div class="mt-4">{{ $allThemes->links() }}</div>
                    @endif
                </div>

                {{-- === ИЗБРАННОЕ === --}}
                <div id="panel-favorites" class="tab-panel hidden space-y-3">

                    @if($isOwner && $favorites->isNotEmpty())
                        @foreach($favorites as $item)
                            <a href="{{ $item['url'] }}"
                               class="card p-5 flex gap-4 reveal group"
                               style="display: flex; text-decoration: none;">

                                {{-- Иконка типа --}}
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
                                     style="background: {{ $item['type'] === 'theme' ? 'rgba(61,79,51,0.15)' : 'rgba(184,136,88,0.15)' }};
                            min-width: 40px; min-height: 40px;">
                    <span class="text-xs uppercase font-medium"
                          style="color: {{ $item['type'] === 'theme' ? 'var(--forest-light)' : 'var(--brown)' }}; letter-spacing: 1px;">
                        {{ $item['type'] === 'theme' ? 'ТМ' : 'ОТ' }}
                    </span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    {{-- Тип + дата --}}
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="badge badge-soft">{{ $item['label'] }}</span>
                                        <span class="text-xs text-muted-c">{{ $item['date'] }}</span>
                                    </div>

                                    {{-- Заголовок --}}
                                    <div class="text-sm leading-snug mb-1"
                                         style="color: var(--text-primary); font-weight: 400;">
                                        {{ $item['title'] }}
                                    </div>

                                    {{-- Мета --}}
                                    @if($item['meta'])
                                        <div class="text-xs text-muted-c">{{ $item['meta'] }}</div>
                                    @endif
                                </div>

                                {{-- Стрелка --}}
                                <div class="flex-shrink-0 self-center text-muted-c"
                                     style="transition: color 0.2s;">
                                    →
                                </div>

                            </a>
                        @endforeach
                    @else
                        <div class="card-flat py-12 text-center">
                            <p class="text-secondary-c text-sm">
                                @if($isOwner)
                                    Вы ещё ничего не добавили в избранное.
                                    <br>
                                    <span class="text-muted-c text-xs mt-2 block">
                        Нажмите звёздочку на теме или ответе чтобы сохранить его здесь.
                    </span>
                                @else
                                    Избранное приватное.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                @if($isOwner)
                    <div id="panel-settings" class="tab-panel hidden space-y-6">

                        <div class="card-flat p-6">
                            <div class="divider mb-6"><span>основные данные</span></div>

                            <form action="{{ route('profile.update') }}" method="POST"
                                  enctype="multipart/form-data" class="space-y-5">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label class="block text-xs uppercase tracking-widest text-secondary-c mb-3"
                                           style="letter-spacing: 2px;">Аватар</label>
                                    <div class="flex items-center gap-4 flex-wrap">

                                        <div class="avatar-square w-20 h-20 flex-shrink-0">
                                            @if($user->getFirstMediaUrl('avatar', 'thumb'))
                                                <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}"
                                                     alt="" class="w-full h-full object-cover">
                                            @else
                                                <div class="avatar-fallback" style="font-size: 32px;">
                                                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1">
                                            <input type="file" name="avatar"
                                                   accept="image/jpeg,image/png,image/webp"
                                                   class="text-xs text-secondary-c">
                                            <p class="text-xs mt-1 text-muted-c">
                                                JPEG / PNG / WebP до 2 МБ, от 100×100 px
                                            </p>
                                            @error('avatar')
                                            <p class="text-xs mt-1" style="color: var(--error);">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        @if($user->getFirstMediaUrl('avatar'))
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="delete_avatar" value="1"
                                                       class="w-4 h-4" style="accent-color: var(--error);">
                                                <span class="text-xs uppercase tracking-widest"
                                                      style="letter-spacing: 2px; color: var(--error);">
                                    Удалить аватар
                                </span>
                                            </label>
                                        @endif
                                    </div>
                                </div>

                                {{-- Регион --}}
                                <div>
                                    <label for="region" class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                                           style="letter-spacing: 2px;">Регион</label>
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
                                    <label for="bio" class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                                           style="letter-spacing: 2px;">О себе</label>
                                    <textarea id="bio" name="bio" rows="4" maxlength="500"
                                              placeholder="Расскажите о своём опыте..."
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
                                    <label for="current_password"
                                           class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                                           style="letter-spacing: 2px;">Текущий пароль</label>
                                    <input type="password" id="current_password" name="current_password"
                                           required autocomplete="current-password"
                                           class="input-field w-full px-3 py-2 text-sm @error('current_password') error @enderror">
                                    @error('current_password')
                                    <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="new_password"
                                           class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                                           style="letter-spacing: 2px;">Новый пароль</label>
                                    <input type="password" id="new_password" name="password"
                                           required autocomplete="new-password"
                                           class="input-field w-full px-3 py-2 text-sm @error('password') error @enderror">
                                    <p class="text-xs mt-1 text-muted-c">Минимум 8 символов, буквы и цифры</p>
                                    @error('password')
                                    <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation"
                                           class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                                           style="letter-spacing: 2px;">Подтверждение</label>
                                    <input type="password" id="password_confirmation"
                                           name="password_confirmation"
                                           required autocomplete="new-password"
                                           class="input-field w-full px-3 py-2 text-sm">
                                </div>

                                <button type="submit" class="btn btn-filled">Изменить пароль</button>
                            </form>
                        </div>

                    </div>{{-- /panel-settings --}}
                @endif

            </div>
        </div>
    </div>

    <script>
        function switchTab(name) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.style.background = '';
                b.style.color = 'var(--text-muted)';
            });

            const panel = document.getElementById('panel-' + name);
            if (panel) panel.classList.remove('hidden');

            const btn = document.getElementById('tab-' + name);
            if (btn) {
                btn.style.background = 'linear-gradient(135deg, var(--brown-bright), var(--brown))';
                btn.style.color = '#ffffff';
            }

            const url = new URL(window.location);
            url.searchParams.set('tab', name);
            window.history.replaceState({}, '', url);
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.style.color = 'var(--text-muted)';
            });
            const urlTab = new URLSearchParams(window.location.search).get('tab') || 'activity';
            switchTab(urlTab);
        });
    </script>

@endsection
