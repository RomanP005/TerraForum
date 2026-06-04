@extends('layouts.app')
@section('title', 'Восстановление пароля')
@section('mode', 'sand')

@section('content')
    <section style="padding: 80px 0;">
        <div class="workspace" style="max-width: 440px;">
            <div style="text-align: center; margin-bottom: 32px;" class="reveal">
                <div class="divider" style="margin-bottom: 12px;"><span>восстановление</span></div>
                <h1 style="font-size: clamp(1.6rem, 4vw, 2.4rem); margin-bottom: 8px;">Забыли пароль?</h1>
                <p class="text-secondary-c" style="font-size: 14px;">Введите email и мы отправим ссылку для сброса пароля.</p>
            </div>

            <div class="card-flat reveal" style="padding: 28px;">
                @if(session('success'))
                    <div style="padding: 14px 16px; border-radius: 10px; background: rgba(107,138,92,0.12); border-left: 3px solid var(--success); margin-bottom: 20px; font-size: 14px;">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('password.forgot.send') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                    @csrf
                    <div>
                        <label class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="your@email.ru" autofocus
                               class="input-field @error('email') error @enderror"
                               style="padding: 12px 14px;">
                        @error('email')<p style="font-size: 12px; color: var(--error); margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn btn-filled" style="padding: 12px;">Отправить ссылку</button>
                    <a href="{{ route('home') }}" style="text-align: center; font-size: 12px; color: var(--text-muted); text-decoration: none; letter-spacing: 1px;">
                        ← Вернуться на главную
                    </a>
                </form>
            </div>
        </div>
    </section>
@endsection
