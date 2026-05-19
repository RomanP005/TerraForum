<div id="login-modal" class="modal-overlay" role="dialog" aria-labelledby="login-title">
    <div class="modal-card">
        <button type="button" class="modal-close" onclick="closeModal('login-modal')" aria-label="Закрыть">×</button>

        <div class="text-center mb-6">
            <div class="divider mb-4"><span>с возвращением</span></div>
            <h2 id="login-title" class="text-3xl">Вход</h2>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4" novalidate>
            @csrf

            <div>
                <label for="login-email" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Email</label>
                <input type="email" id="login-email" name="email" value="{{ old('email') }}" required autocomplete="email"
                       class="input-field w-full px-3 py-2 text-sm @error('email') error @enderror">
                @error('email')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="login-password" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Пароль</label>
                <input type="password" id="login-password" name="password" required autocomplete="current-password"
                       class="input-field w-full px-3 py-2 text-sm @error('password') error @enderror">
                @error('password')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center pt-1">
                <label class="flex items-center gap-2 text-xs text-secondary-c cursor-pointer">
                    <input type="checkbox" name="remember" value="1" class="w-4 h-4 rounded" style="accent-color: var(--brown);">
                    <span class="uppercase tracking-widest" style="letter-spacing: 2px;">Запомнить меня</span>
                </label>
            </div>

            <button type="submit" class="btn btn-filled w-full mt-4">Войти</button>
        </form>

        <div class="mt-6 text-center text-xs uppercase tracking-widest text-secondary-c" style="letter-spacing: 2px;">
            Впервые здесь?
            <button type="button" onclick="closeModal('login-modal'); openModal('register-modal');"
                    class="text-brown ml-2 hover:underline bg-transparent border-0 cursor-pointer uppercase tracking-widest" style="letter-spacing: 2px;">
                Регистрация
            </button>
        </div>
    </div>
</div>
