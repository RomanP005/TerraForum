<div id="register-modal" class="modal-overlay" role="dialog" aria-labelledby="register-title">
    <div class="modal-card">
        <button type="button" class="modal-close" onclick="closeModal('register-modal')" aria-label="Закрыть">×</button>

        <div class="text-center mb-6">
            <div class="divider mb-4"><span>добро пожаловать</span></div>
            <h2 id="register-title" class="text-3xl">Регистрация</h2>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4" novalidate>
            @csrf

            <div>
                <label for="reg-name"
                       class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                       style="letter-spacing: 2px;">Никнейм</label>
                <input type="text" id="reg-name" name="name"
                       value="{{ old('name') }}" required autocomplete="username"
                       class="input-field w-full px-3 py-2 text-sm @error('name') error @enderror">
                @error('name')
                <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reg-email"
                       class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                       style="letter-spacing: 2px;">Email</label>
                <input type="email" id="reg-email" name="email"
                       value="{{ old('email') }}" required autocomplete="email"
                       class="input-field w-full px-3 py-2 text-sm @error('email') error @enderror">
                @error('email')
                <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reg-region"
                       class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                       style="letter-spacing: 2px;">
                    Регион <span class="normal-case opacity-60">(необязательно)</span>
                </label>
                <input type="text" id="reg-region" name="region"
                       value="{{ old('region') }}" placeholder="Например, Подмосковье"
                       class="input-field w-full px-3 py-2 text-sm @error('region') error @enderror">
                @error('region')
                <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reg-password"
                       class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                       style="letter-spacing: 2px;">Пароль</label>
                <div style="position: relative;">
                    <input type="password" id="reg-password" name="password"
                           required autocomplete="new-password"
                           class="input-field w-full text-sm @error('password') error @enderror"
                           style="padding: 8px 44px 8px 12px;">
                    <button type="button"
                            onclick="togglePassword('reg-password', 'eye-reg')"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
                                   background: transparent; border: none; cursor: pointer;
                                   color: var(--text-muted); font-size: 16px; padding: 4px; line-height: 1;"
                            tabindex="-1">
                        <span id="eye-reg">👁</span>
                    </button>
                </div>
                <p class="mt-1 text-xs text-muted-c">Минимум 8 символов, буквы и цифры</p>
                @error('password')
                <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reg-password-confirm"
                       class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                       style="letter-spacing: 2px;">Подтверждение пароля</label>
                <div style="position: relative;">
                    <input type="password" id="reg-password-confirm" name="password_confirmation"
                           required autocomplete="new-password"
                           class="input-field w-full text-sm"
                           style="padding: 8px 44px 8px 12px;">
                    <button type="button"
                            onclick="togglePassword('reg-password-confirm', 'eye-reg-confirm')"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
                                   background: transparent; border: none; cursor: pointer;
                                   color: var(--text-muted); font-size: 16px; padding: 4px; line-height: 1;"
                            tabindex="-1">
                        <span id="eye-reg-confirm">👁</span>
                    </button>
                </div>
            </div>

            <div class="flex items-start gap-2 pt-2">
                <input type="checkbox" id="reg-terms" name="terms" value="1"
                       {{ old('terms') ? 'checked' : '' }}
                       class="mt-1 w-4 h-4" style="accent-color: var(--brown);">
                <label for="reg-terms" class="text-xs text-secondary-c cursor-pointer">
                    Принимаю условия использования платформы
                </label>
            </div>
            @error('terms')
            <p class="text-xs" style="color: var(--error);">{{ $message }}</p>
            @enderror

            <button type="submit" class="btn btn-filled w-full mt-4">Зарегистрироваться</button>
        </form>

        <div class="mt-6 text-center text-xs uppercase tracking-widest text-secondary-c"
             style="letter-spacing: 2px;">
            Уже с нами?
            <button type="button"
                    onclick="closeModal('register-modal'); openModal('login-modal');"
                    class="text-brown ml-2 hover:underline bg-transparent border-0 cursor-pointer uppercase tracking-widest"
                    style="letter-spacing: 2px;">
                Войти
            </button>
        </div>
    </div>
</div><div id="register-modal" class="modal-overlay" role="dialog" aria-labelledby="register-title">
    <div class="modal-card">
        <button type="button" class="modal-close" onclick="closeModal('register-modal')" aria-label="Закрыть">×</button>

        <div class="text-center mb-6">
            <div class="divider mb-4"><span>добро пожаловать</span></div>
            <h2 id="register-title" class="text-3xl">Регистрация</h2>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4" novalidate>
            @csrf

            <div>
                <label for="reg-name"
                       class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                       style="letter-spacing: 2px;">Никнейм</label>
                <input type="text" id="reg-name" name="name"
                       value="{{ old('name') }}" required autocomplete="username"
                       class="input-field w-full px-3 py-2 text-sm @error('name') error @enderror">
                @error('name')
                <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reg-email"
                       class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                       style="letter-spacing: 2px;">Email</label>
                <input type="email" id="reg-email" name="email"
                       value="{{ old('email') }}" required autocomplete="email"
                       class="input-field w-full px-3 py-2 text-sm @error('email') error @enderror">
                @error('email')
                <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reg-region"
                       class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                       style="letter-spacing: 2px;">
                    Регион <span class="normal-case opacity-60">(необязательно)</span>
                </label>
                <input type="text" id="reg-region" name="region"
                       value="{{ old('region') }}" placeholder="Например, Подмосковье"
                       class="input-field w-full px-3 py-2 text-sm @error('region') error @enderror">
                @error('region')
                <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reg-password"
                       class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                       style="letter-spacing: 2px;">Пароль</label>
                <div style="position: relative;">
                    <input type="password" id="reg-password" name="password"
                           required autocomplete="new-password"
                           class="input-field w-full text-sm @error('password') error @enderror"
                           style="padding: 8px 44px 8px 12px;">
                    <button type="button"
                            onclick="togglePassword('reg-password', 'eye-reg')"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
                                   background: transparent; border: none; cursor: pointer;
                                   color: var(--text-muted); font-size: 16px; padding: 4px; line-height: 1;"
                            tabindex="-1">
                        <span id="eye-reg">👁</span>
                    </button>
                </div>
                <p class="mt-1 text-xs text-muted-c">Минимум 8 символов, буквы и цифры</p>
                @error('password')
                <p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reg-password-confirm"
                       class="block text-xs uppercase tracking-widest text-secondary-c mb-2"
                       style="letter-spacing: 2px;">Подтверждение пароля</label>
                <div style="position: relative;">
                    <input type="password" id="reg-password-confirm" name="password_confirmation"
                           required autocomplete="new-password"
                           class="input-field w-full text-sm"
                           style="padding: 8px 44px 8px 12px;">
                    <button type="button"
                            onclick="togglePassword('reg-password-confirm', 'eye-reg-confirm')"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
                                   background: transparent; border: none; cursor: pointer;
                                   color: var(--text-muted); font-size: 16px; padding: 4px; line-height: 1;"
                            tabindex="-1">
                        <span id="eye-reg-confirm">👁</span>
                    </button>
                </div>
            </div>

            <div class="flex items-start gap-2 pt-2">
                <input type="checkbox" id="reg-terms" name="terms" value="1"
                       {{ old('terms') ? 'checked' : '' }}
                       class="mt-1 w-4 h-4" style="accent-color: var(--brown);">
                <label for="reg-terms" class="text-xs text-secondary-c cursor-pointer">
                    Принимаю условия использования платформы
                </label>
            </div>
            @error('terms')
            <p class="text-xs" style="color: var(--error);">{{ $message }}</p>
            @enderror

            <button type="submit" class="btn btn-filled w-full mt-4">Зарегистрироваться</button>
        </form>

        <div class="mt-6 text-center text-xs uppercase tracking-widest text-secondary-c"
             style="letter-spacing: 2px;">
            Уже с нами?
            <button type="button"
                    onclick="closeModal('register-modal'); openModal('login-modal');"
                    class="text-brown ml-2 hover:underline bg-transparent border-0 cursor-pointer uppercase tracking-widest"
                    style="letter-spacing: 2px;">
                Войти
            </button>
        </div>
    </div>
</div>
