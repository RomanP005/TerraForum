<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение заказа</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background: #f5efe0; margin: 0; padding: 40px 20px; color: #2a2622; }
        .wrap { max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 32px rgba(107,68,35,0.15); }
        .header { background: linear-gradient(135deg, #3d4f33, #2e3d27); padding: 32px; text-align: center; }
        .header h1 { color: #f5efe0; font-size: 24px; margin: 0; letter-spacing: 2px; text-transform: uppercase; font-weight: normal; font-family: Georgia, serif; }
        .header p { color: rgba(245,239,224,0.7); font-size: 12px; margin: 8px 0 0; letter-spacing: 2px; text-transform: uppercase; }
        .body { padding: 32px; }
        .check { width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #6b8a5c, #3d4f33); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 24px; color: white; text-align: center; line-height: 56px; }
        h2 { font-size: 20px; text-align: center; margin: 0 0 8px; font-weight: normal; font-family: Georgia, serif; }
        .subtitle { color: #8c7e6a; font-size: 14px; text-align: center; margin: 0 0 28px; }
        .order-box { background: #f5efe0; border-radius: 12px; padding: 20px; margin-bottom: 24px; }
        .order-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(107,68,35,0.1); font-size: 14px; }
        .order-row:last-child { border-bottom: none; font-weight: 500; }
        .label { color: #8c7e6a; }
        .total { color: #b88858; font-size: 16px; font-family: Georgia, serif; }
        .note { background: rgba(184,136,88,0.08); border-left: 3px solid #b88858; border-radius: 0 8px 8px 0; padding: 14px 16px; font-size: 13px; color: #5c5048; margin-bottom: 24px; }
        .btn { display: block; text-align: center; background: linear-gradient(135deg, #d4a574, #b88858); color: #ffffff; text-decoration: none; padding: 14px 24px; border-radius: 10px; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 24px; }
        .footer { padding: 20px 32px; background: #f5efe0; text-align: center; font-size: 11px; color: #8c7e6a; letter-spacing: 1px; }
        @media (prefers-color-scheme: dark) { body { background: #2a2622; } }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>TerraForum</h1>
        <p>Подтверждение заказа</p>
    </div>
    <div class="body">
        <div class="check" style="color: white;">✓</div>
        <h2>Оплата прошла успешно!</h2>
        <p class="subtitle">Здравствуйте, {{ $user->name }}. Ваш заказ оформлен.</p>

        <div class="order-box">
            <div class="order-row">
                <span class="label">Номер заказа</span>
                <span style="font-family: monospace; font-weight: 500;">{{ $orderNumber }}</span>
            </div>
            <div class="order-row">
                <span class="label">Услуга</span>
                <span>{{ \Illuminate\Support\Str::limit($service->title, 40) }}</span>
            </div>
            <div class="order-row">
                <span class="label">Исполнитель</span>
                <span>{{ $service->user->name }}</span>
            </div>
            <div class="order-row">
                <span class="label">Способ оплаты</span>
                <span>{{ ['card' => 'Банковская карта', 'sbp' => 'СБП', 'wallet' => 'Кошелёк'][$paymentMethod] ?? $paymentMethod }}</span>
            </div>
            <div class="order-row">
                <span class="label">Сумма</span>
                <span class="total">
                    @if($service->price_negotiable) Договорная
                    @elseif($service->price) {{ number_format($service->price, 0, '.', ' ') }} {{ $service->price_unit ?? 'руб.' }}
                    @else — @endif
                </span>
            </div>
        </div>

        <div class="note">
            Это демонстрационный платёж. Реальные средства не списывались.
            Свяжитесь с исполнителем для уточнения деталей выполнения работ.
        </div>

        <a href="{{ route('services.show', $service->slug) }}" class="btn">Посмотреть услугу</a>
    </div>
    <div class="footer">
        © {{ date('Y.m.d') }} TerraForum — Сообщество садоводов и фермеров
    </div>
</div>
</body>
</html>
