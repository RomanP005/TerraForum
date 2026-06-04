@extends('layouts.app')
@section('title', 'Оплата прошла успешно')
@section('mode', 'sand')

@section('content')
    <section style="padding: 80px 0; text-align: center;">
        <div class="workspace" style="max-width: 560px;">
            <div class="reveal">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--forest-light), var(--forest)); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-size: 36px;">✓</div>
                <div class="divider" style="margin-bottom: 12px;"><span>заказ оформлен</span></div>
                <h1 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-bottom: 8px;">Оплата прошла успешно</h1>
                <p class="text-secondary-c" style="font-size: 15px; margin-bottom: 24px;">
                    Подтверждение отправлено на ваш email.
                </p>
            </div>

            <div class="card-flat reveal" style="padding: 24px; margin-bottom: 24px; text-align: left;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                    <span class="text-muted-c" style="font-size: 13px;">Номер заказа</span>
                    <span style="font-size: 13px; font-weight: 500; font-family: monospace;">{{ $order }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                    <span class="text-muted-c" style="font-size: 13px;">Услуга</span>
                    <span style="font-size: 13px;">{{ \Illuminate\Support\Str::limit($service->title, 40) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                    <span class="text-muted-c" style="font-size: 13px;">Исполнитель</span>
                    <span style="font-size: 13px;">{{ $service->user->name }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-top: 12px; border-top: 1px solid var(--border-soft);">
                    <span style="font-size: 14px; font-weight: 500;">Сумма</span>
                    <span class="gradient-number" style="font-family: 'Karelle', serif; font-size: 1.2rem;">
                    @if($service->price_negotiable) Договорная
                        @elseif($service->price) {{ number_format($service->price, 0, '.', ' ') }} {{ $service->price_unit ?? 'руб.' }}
                        @else — @endif
                </span>
                </div>
            </div>

            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('services.show', $service->slug) }}" class="btn btn-ghost">К услуге</a>
                <a href="{{ route('services.index') }}" class="btn btn-filled">Все услуги</a>
            </div>
        </div>
    </section>
@endsection
