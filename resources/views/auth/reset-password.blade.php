@extends('layouts.app')
@section('title', 'Новый пароль')
@section('mode', 'sand')

@section('content')
    <section style="padding: 80px 0;">
        <div class="workspace" style="max-width: 440px;">
            <div style="text-align: center; margin-bottom: 32px;" class="reveal">
                <div class="divider" style="margin-bottom: 12px;"><span>новый пароль</span></div>
                <h1 style="font-size: clamp(1.6rem, 4vw, 2.4rem);">Сброс пароля</h1>
            </div>

            <div class="card-flat reveal" style="padding: 28px;">
                <form action="{{ route('password.reset.update') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">Email</label>
                        <input type="email" name="email" value="{{ old('email', request('email')) }}" required
                               class="input-field @error('email') error @enderror" style="padding: 12px 14px;">
                        @error('email')<p style="font-size: 12px; color: var(--error); margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">Новый пароль</label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="rp-pass" required
                                   autocomplete="new-password"
                                   placeholder="Минимум 8 символов"
                                   class="input-field @error('password') error @enderror"
                                   style="padding: 12px 44px 12px 14px;">
                            <button type="button" onclick="togglePassword('rp-pass','eye-rp')"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:transparent;border:none;cursor:pointer;color:var(--text-muted);font-size:16px;padding:4px;"
                                    tabindex="-1"><span id="eye-rp">👁</span></button>
                        </div>
                        @error('password')<p style="font-size: 12px; color: var(--error); margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">Подтверждение</label>
                        <div style="position: relative;">
                            <input type="password" name="password_confirmation" id="rp-pass2" required
                                   autocomplete="new-password"
                                   placeholder="Повторите пароль"
                                   class="input-field"
                                   style="padding: 12px 44px 12px 14px;">
                            <button type="button" onclick="togglePassword('rp-pass2','eye-rp2')"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:transparent;border:none;cursor:pointer;color:var(--text-muted);font-size:16px;padding:4px;"
                                    tabindex="-1"><span id="eye-rp2">👁</span></button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-filled" style="padding: 12px;">Сохранить пароль</button>
                </form>
            </div>
        </div>
    </section>
@endsection
