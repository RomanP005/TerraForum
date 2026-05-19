@extends('layouts.app')

@section('title', 'Профиль ' . $user->name)
@section('mode', 'linen')

@section('content')

    {{-- Шапка профиля --}}
    <section class="py-16 px-6" style="background: linear-gradient(135deg, var(--bg-section-alt), var(--bg-page));">
        <div class="workspace">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8 reveal">

                <div class="avatar-square w-32 h-32 md:w-40 md:h-40 flex-shrink-0">
                    @if($user->getFirstMediaUrl('avatar', 'preview'))
                        <img src="{{ $user->getFirstMediaUrl('avatar', 'preview') }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="avatar-fallback" style="font-size: 56px;">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1 text-center md:text-left">
                    <div class="divider mb-3 md:justify-start" style="justify-content: center;">
                    <span>
                        @forelse($user->roles as $role) {{ $role->name }} @empty гость @endforelse
                    </span>
                    </div>

                    <h1 class="text-4xl md:text-5xl mb-2" style="line-height: 1.1;">{{ $user->name }}</h1>

                    @if($user->region)
                        <p class="text-sm tracking-widest uppercase text-secondary-c mb-4" style="letter-spacing: 3px;">
                            {{ $user->region }}
                        </p>
                    @endif

                    @if($user->bio)
                        <p class="text-base max-w-xl text-secondary-c leading-relaxed mb-6">{{ $user->bio }}</p>
                    @endif

                    <div class="text-xs tracking-widest uppercase text-muted-c" style="letter-spacing: 2px;">
                        На платформе с {{ $user->created_at->translatedFormat('F Y') }}
                    </div>
                </div>

                <div class="text-center md:text-right">
                    <div class="text-xs tracking-widest uppercase text-muted-c mb-2" style="letter-spacing: 3px;">Репутация</div>
                    <div class="text-6xl gradient-number">{{ $user->rating ?? 0 }}</div>
                </div>

            </div>
        </div>
    </section>

    {{-- Статистика --}}
    <section class="py-12 px-6">
        <div class="workspace">
            <div class="text-center mb-10 reveal">
                <div class="divider mb-3"><span>активность</span></div>
                <h2 class="text-3xl md:text-4xl">Вклад в сообщество</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="card text-center p-6 reveal">
                    <div class="section-number mb-2">— темы —</div>
                    <div class="text-5xl gradient-number">{{ $stats['themes'] }}</div>
                </div>
                <div class="card text-center p-6 reveal reveal-delay-1">
                    <div class="section-number mb-2">— сообщения —</div>
                    <div class="text-5xl gradient-number">{{ $stats['posts'] }}</div>
                </div>
                <div class="card text-center p-6 reveal reveal-delay-2">
                    <div class="section-number mb-2">— услуги —</div>
                    <div class="text-5xl gradient-number">{{ $stats['services'] }}</div>
                </div>
                <div class="card text-center p-6 reveal reveal-delay-3">
                    <div class="section-number mb-2">— голоса —</div>
                    <div class="text-5xl gradient-number">{{ $stats['votes_given'] }}</div>
                </div>
            </div>
        </div>
    </section>

    @if($isOwner)

        {{-- Избранное --}}
        <section class="py-12 px-6" style="background: var(--bg-section-alt);">
            <div class="workspace">
                <div class="text-center mb-8 reveal">
                    <div class="divider mb-3"><span>сохранённое</span></div>
                    <h2 class="text-3xl md:text-4xl">Избранное</h2>
                </div>

                @if($favorites->isEmpty())
                    <p class="text-center text-muted-c text-sm">
                        Пока ничего не добавлено. На форуме можно сохранить понравившуюся тему — она появится здесь.
                    </p>
                @else
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach($favorites as $favorite)
                            <div class="card-flat p-5">
                                <div class="text-xs tracking-widest uppercase mb-2 text-brown" style="letter-spacing: 2px;">
                                    {{ class_basename($favorite->favoriteable_type) }}
                                </div>
                                <p class="text-sm leading-relaxed text-cream">
                                    {{ $favorite->favoriteable?->title ?? $favorite->favoriteable?->content ?? 'Удалённый объект' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        {{-- Редактирование --}}
        <section class="py-12 px-6">
            <div class="workspace" style="max-width: 720px;">

                <div class="text-center mb-8 reveal">
                    <div class="divider mb-3"><span>настройки</span></div>
                    <h2 class="text-3xl md:text-4xl">Редактирование</h2>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 card-flat p-6">
                    @csrf
                    @method('PATCH')

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
                                <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp" class="text-xs text-secondary-c">
                                <p class="text-xs mt-1 text-muted-c">JPEG/PNG/WebP до 2 МБ, не менее 100×100 px</p>
                                @error('avatar')<p class="text-xs mt-1" style="color: var(--error);">{{ $message }}</p>@enderror
                            </div>
                            @if($user->getFirstMediaUrl('avatar'))
                                <form action="{{ route('profile.avatar.delete') }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs uppercase tracking-widest hover:underline"
                                            style="letter-spacing: 2px; color: var(--error);"
                                            onclick="return confirm('Удалить аватар?')">Удалить</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label for="region" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Регион</label>
                        <input type="text" id="region" name="region" value="{{ old('region', $user->region) }}"
                               placeholder="Например, Подмосковье"
                               class="input-field w-full px-3 py-2 text-sm @error('region') error @enderror">
                        @error('region')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="bio" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">О себе</label>
                        <textarea id="bio" name="bio" rows="5" maxlength="500"
                                  placeholder="Расскажите о своём опыте..."
                                  class="input-field w-full px-3 py-2 text-sm @error('bio') error @enderror">{{ old('bio', $user->bio) }}</textarea>
                        <p class="text-xs mt-1 text-muted-c">До 500 символов</p>
                        @error('bio')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn btn-filled">Сохранить изменения</button>
                </form>
            </div>
        </section>

        {{-- Смена пароля --}}
        <section class="py-12 px-6" style="background: var(--bg-section-alt);">
            <div class="workspace" style="max-width: 720px;">

                <div class="text-center mb-8 reveal">
                    <div class="divider mb-3"><span>безопасность</span></div>
                    <h2 class="text-3xl md:text-4xl">Смена пароля</h2>
                </div>

                <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4 card-flat p-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="current_password" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Текущий пароль</label>
                        <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                               class="input-field w-full px-3 py-2 text-sm @error('current_password') error @enderror">
                        @error('current_password')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="new_password" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Новый пароль</label>
                        <input type="password" id="new_password" name="password" required autocomplete="new-password"
                               class="input-field w-full px-3 py-2 text-sm @error('password') error @enderror">
                        <p class="text-xs mt-1 text-muted-c">Минимум 8 символов, буквы и цифры</p>
                        @error('password')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Подтверждение нового пароля</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                               class="input-field w-full px-3 py-2 text-sm">
                    </div>

                    <button type="submit" class="btn btn-filled mt-2">Изменить пароль</button>
                </form>
            </div>
        </section>

    @endif

@endsection
