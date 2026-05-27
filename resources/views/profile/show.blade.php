@extends('layouts.app')
@section('title', 'Профиль ' . $user->name)
@section('mode', 'linen')
@section('content')

    <div class="workspace" style="padding-top: 40px; padding-bottom: 40px;">
        <div class="profile-layout" style="display: grid; grid-template-columns: 300px 1fr; gap: 24px; align-items: start;">

            {{-- САЙДБАР --}}
            <aside class="card profile-sidebar" style="padding: 24px; position: sticky; top: 80px;">

                {{-- Аватар + имя --}}
                <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 20px;">
                    <div class="avatar-square" style="width: 96px; height: 96px; margin-bottom: 16px;">
                        @if($user->getFirstMediaUrl('avatar'))
                            <img src="{{ $user->getFirstMediaUrl('avatar') }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="avatar-fallback" style="font-size: 40px;">
                                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="section-number" style="margin-bottom: 4px;">
                        @forelse($user->roles as $role) {{ $role->name }} @empty гость @endforelse
                    </div>
                    <h1 style="font-size: 1.75rem; line-height: 1.2; margin-bottom: 4px;">{{ $user->name }}</h1>
                    @if($user->region)
                        <p class="text-muted-c" style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin: 0;">{{ $user->region }}</p>
                    @endif
                </div>

                <div style="height: 1px; background: var(--border-soft); margin-bottom: 20px;"></div>

                {{-- Репутация --}}
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                    <span class="text-muted-c" style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Репутация</span>
                    <span class="gradient-number" style="font-size: 2.5rem; font-family: 'Karelle', serif;">{{ $user->rating ?? 0 }}</span>
                </div>

                {{-- Статистика --}}
                <div style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 20px;">
                    @foreach([['Темы', $stats['themes']], ['Сообщения', $stats['posts']], ['Услуги', $stats['services']], ['Голоса', $stats['votes_given']]] as [$label, $val])
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; border-radius: 8px; background: var(--bg-input);">
                            <span class="text-secondary-c" style="font-size: 13px;">{{ $label }}</span>
                            <span style="font-size: 13px; font-weight: 500;">{{ $val }}</span>
                        </div>
                    @endforeach
                </div>

                @if($user->bio)
                    <div style="height: 1px; background: var(--border-soft); margin-bottom: 20px;"></div>
                    <div style="margin-bottom: 20px;">
                        <div class="text-muted-c" style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">О себе</div>
                        <p class="text-secondary-c" style="font-size: 13px; line-height: 1.6; margin: 0;">{{ $user->bio }}</p>
                    </div>
                @endif

                <div style="height: 1px; background: var(--border-soft); margin-bottom: 16px;"></div>

                <div class="text-muted-c" style="text-align: center; font-size: 10px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 16px;">
                    На платформе с {{ $user->created_at->translatedFormat('F Y') }}
                </div>

                @if($isOwner)
                    <button type="button" onclick="switchTab('settings')" class="btn btn-filled" style="width: 100%; text-align: center;">
                        Редактировать профиль
                    </button>
                @endif
            </aside>

            {{-- ОСНОВНАЯ ЧАСТЬ --}}
            <div style="min-width: 0;">

                {{-- Вкладки --}}
                <div class="card-flat profile-tabs" style="margin-bottom: 20px; padding: 6px; display: flex; gap: 4px; overflow-x: auto;">
                    @foreach($tabs as $key => $label)
                        <button id="tab-{{ $key }}"
                                onclick="switchTab('{{ $key }}')"
                                class="tab-btn"
                                style="flex-shrink: 0; padding: 8px 18px; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; background: transparent;">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                {{-- АКТИВНОСТЬ --}}
                <div id="panel-activity" class="tab-panel" style="display: flex; flex-direction: column; gap: 10px;">
                    @forelse($recentThemes as $theme)
                        <div class="card reveal" style="padding: 20px;">
                            <div class="text-muted-c" style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 6px;">
                                {{ $theme->created_at->diffForHumans() }} · создал тему
                            </div>
                            <h3 style="font-size: 1.2rem; margin-bottom: 4px;">
                                <a href="{{ route('forum.theme', $theme->slug) }}" class="title-link">{{ $theme->title }}</a>
                            </h3>
                            @if($theme->category)
                                <div class="text-secondary-c" style="font-size: 12px;">в категории «{{ $theme->category->name }}»</div>
                            @endif
                        </div>
                    @empty
                        <div class="card-flat" style="padding: 48px; text-align: center;">
                            <p class="text-secondary-c" style="font-size: 13px; margin-bottom: 8px;">Активности пока нет</p>
                            @if($isOwner)
                                <a href="{{ route('forum.index') }}" class="text-brown" style="font-size: 13px; text-decoration: none;">Создать первую тему</a>
                            @endif
                        </div>
                    @endforelse
                </div>

                {{-- ТЕМЫ --}}
                <div id="panel-themes" class="tab-panel" style="display: none; flex-direction: column; gap: 10px;">
                    @forelse($allThemes as $theme)
                        <div class="card reveal" style="padding: 20px; display: flex; gap: 14px;">
                            <div style="display: flex; flex-direction: column; align-items: center; min-width: 48px; text-align: center; padding-top: 4px; flex-shrink: 0;">
                                <div class="gradient-number" style="font-family: 'Karelle', serif; font-size: 1.4rem;">{{ $theme->total_votes ?? 0 }}</div>
                                <div class="text-muted-c" style="font-size: 9px; letter-spacing: 1px; text-transform: uppercase;">голосов</div>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <h3 style="font-size: 1.1rem; margin-bottom: 4px;">
                                    <a href="{{ route('forum.theme', $theme->slug) }}" class="title-link">{{ $theme->title }}</a>
                                </h3>
                                @if($theme->category)
                                    <div class="text-muted-c" style="font-size: 11px; margin-bottom: 6px;">{{ $theme->category->name }}</div>
                                @endif
                                @if($theme->tags->isNotEmpty())
                                    <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 8px;">
                                        @foreach($theme->tags->take(3) as $tag)
                                            <span class="tag">#{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="text-muted-c" style="font-size: 11px;">
                                    {{ $theme->created_at->diffForHumans() }} · {{ $theme->posts_count ?? 0 }} ответов · {{ $theme->views_count }} просмотров
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card-flat" style="padding: 48px; text-align: center;">
                            <p class="text-secondary-c" style="font-size: 13px;">Тем пока нет</p>
                        </div>
                    @endforelse
                    @if(method_exists($allThemes, 'hasPages') && $allThemes->hasPages())
                        <div style="margin-top: 16px;">{{ $allThemes->links() }}</div>
                    @endif
                </div>

                {{-- ИЗБРАННОЕ --}}
                <div id="panel-favorites" class="tab-panel" style="display: none; flex-direction: column; gap: 10px;">
                    @if($isOwner && $favorites->isNotEmpty())
                        @foreach($favorites as $item)
                            <a href="{{ $item['url'] }}" class="card reveal"
                               style="padding: 20px; display: flex; gap: 14px; text-decoration: none;">
                                <div style="flex-shrink: 0; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
                                        background: {{ $item['type'] === 'theme' ? 'rgba(61,79,51,0.15)' : 'rgba(184,136,88,0.15)' }}; min-width: 40px;">
                                <span style="font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;
                                             color: {{ $item['type'] === 'theme' ? 'var(--forest-light)' : 'var(--brown)' }};">
                                    {{ $item['type'] === 'theme' ? 'ТМ' : 'ОТ' }}
                                </span>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                                        <span class="badge badge-soft">{{ $item['label'] }}</span>
                                        <span class="text-muted-c" style="font-size: 11px;">{{ $item['date'] }}</span>
                                    </div>
                                    <div style="font-size: 13px; line-height: 1.5; color: var(--text-primary); margin-bottom: 2px;">{{ $item['title'] }}</div>
                                    @if($item['meta'])
                                        <div class="text-muted-c" style="font-size: 11px;">{{ $item['meta'] }}</div>
                                    @endif
                                </div>
                                <div class="text-muted-c" style="align-self: center; flex-shrink: 0;">→</div>
                            </a>
                        @endforeach
                    @else
                        <div class="card-flat" style="padding: 48px; text-align: center;">
                            <p class="text-secondary-c" style="font-size: 13px;">
                                @if($isOwner) Вы ещё ничего не добавили в избранное. @else Избранное приватное. @endif
                            </p>
                        </div>
                    @endif
                </div>

                {{-- НАСТРОЙКИ --}}
                @if($isOwner)
                    <div id="panel-settings" class="tab-panel" style="display: none; flex-direction: column; gap: 20px;">

                        <div class="card-flat" style="padding: 24px;">
                            <div class="divider" style="margin-bottom: 24px;"><span>основные данные</span></div>

                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px;">
                                @csrf
                                @method('PATCH')

                                {{-- Аватар --}}
                                <div>
                                    <label class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 12px;">Аватар</label>
                                    <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                                        <div class="avatar-square" style="width: 80px; height: 80px; flex-shrink: 0;">
                                            @if($user->getFirstMediaUrl('avatar'))
                                                <img src="{{ $user->getFirstMediaUrl('avatar') }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <div class="avatar-fallback" style="font-size: 32px;">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</div>
                                            @endif
                                        </div>
                                        <div style="flex: 1;">
                                            <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="text-secondary-c" style="font-size: 12px;">
                                            <p class="text-muted-c" style="font-size: 11px; margin-top: 4px;">JPEG / PNG / WebP до 2 МБ</p>
                                            @error('avatar')<p style="font-size: 11px; color: var(--error); margin-top: 4px;">{{ $message }}</p>@enderror
                                        </div>
                                    </div>
                                    @if($user->getFirstMediaUrl('avatar'))
                                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin-top: 10px;">
                                            <input type="checkbox" name="delete_avatar" value="1" style="accent-color: var(--error); width: 14px; height: 14px;">
                                            <span style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: var(--error);">Удалить аватар</span>
                                        </label>
                                    @endif
                                </div>

                                <div>
                                    <label for="region" class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">Регион</label>
                                    <input type="text" id="region" name="region" value="{{ old('region', $user->region) }}"
                                           placeholder="Например, Подмосковье"
                                           class="input-field @error('region') error @enderror" style="padding: 10px 14px;">
                                    @error('region')<p style="font-size: 11px; color: var(--error); margin-top: 4px;">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="bio" class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">О себе</label>
                                    <textarea id="bio" name="bio" rows="4" maxlength="500"
                                              placeholder="Расскажите о своём опыте..."
                                              class="input-field @error('bio') error @enderror"
                                              style="padding: 10px 14px; resize: vertical;">{{ old('bio', $user->bio) }}</textarea>
                                    <p class="text-muted-c" style="font-size: 11px; margin-top: 4px;">До 500 символов</p>
                                    @error('bio')<p style="font-size: 11px; color: var(--error); margin-top: 4px;">{{ $message }}</p>@enderror
                                </div>

                                <button type="submit" class="btn btn-filled" style="align-self: flex-start;">Сохранить изменения</button>
                            </form>
                        </div>

                        <div class="card-flat" style="padding: 24px;">
                            <div class="divider" style="margin-bottom: 24px;"><span>безопасность</span></div>

                            <form action="{{ route('profile.password.update') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="current_password" class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">Текущий пароль</label>
                                    <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                                           class="input-field @error('current_password') error @enderror" style="padding: 10px 14px;">
                                    @error('current_password')<p style="font-size: 11px; color: var(--error); margin-top: 4px;">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="new_password" class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">Новый пароль</label>
                                    <input type="password" id="new_password" name="password" required autocomplete="new-password"
                                           class="input-field @error('password') error @enderror" style="padding: 10px 14px;">
                                    <p class="text-muted-c" style="font-size: 11px; margin-top: 4px;">Минимум 8 символов, буквы и цифры</p>
                                    @error('password')<p style="font-size: 11px; color: var(--error); margin-top: 4px;">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">Подтверждение</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                           class="input-field" style="padding: 10px 14px;">
                                </div>

                                <button type="submit" class="btn btn-filled" style="align-self: flex-start;">Изменить пароль</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function switchTab(name) {
            document.querySelectorAll('.tab-panel').forEach(p => p.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.style.background = 'transparent';
                b.style.color = 'var(--text-muted)';
            });
            const panel = document.getElementById('panel-' + name);
            if (panel) panel.style.display = 'flex';
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
            document.querySelectorAll('.tab-btn').forEach(b => b.style.color = 'var(--text-muted)');
            const urlTab = new URLSearchParams(window.location.search).get('tab') || 'activity';
            switchTab(urlTab);
        });
    </script>

@endsection
