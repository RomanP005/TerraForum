@extends('layouts.app')
@section('title', 'Оплата услуги')
@section('mode', 'sand')

@section('content')

    <section style="padding: 48px 0;">
        <div class="workspace" style="max-width: 680px;">

            <div style="text-align: center; margin-bottom: 32px;" class="reveal">
                <div class="divider" style="margin-bottom: 12px;"><span>оформление заказа</span></div>
                <h1 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-bottom: 8px;">Оплата услуги</h1>
            </div>

            <div class="card-flat reveal" style="padding: 20px; margin-bottom: 24px; display: flex; gap: 16px; align-items: center;">
                @if($service->getFirstMediaUrl('photos'))
                    <div style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 10px; overflow: hidden;">
                        <img src="{{ $service->getFirstMediaUrl('photos') }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @endif
                <div style="flex: 1; min-width: 0;">
                    <div class="badge badge-forest" style="margin-bottom: 6px;">{{ $service->service_category }}</div>
                    <h3 style="font-size: 1.1rem; margin-bottom: 4px;">{{ $service->title }}</h3>
                    <div class="text-secondary-c" style="font-size: 13px;">{{ $service->city ? $service->city . ', ' : '' }}{{ $service->region }}</div>
                </div>
                <div style="text-align: right; flex-shrink: 0;">
                    <div class="gradient-number" style="font-family: 'Karelle', serif; font-size: 1.6rem;">
                        @if($service->price_negotiable) Договорная
                        @elseif($service->price) {{ number_format($service->price, 0, '.', ' ') }} {{ $service->price_unit ?? 'руб.' }}
                        @else — @endif
                    </div>
                </div>
            </div>

            <form action="{{ route('payment.process', $service->slug) }}" method="POST" id="payment-form">
                @csrf

                <div class="card-flat reveal" style="padding: 24px; margin-bottom: 20px;">
                    <div class="divider" style="margin-bottom: 20px;"><span>способ оплаты</span></div>

                    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 24px;">

                        <label style="display: flex; align-items: center; gap: 14px; padding: 16px; border-radius: 12px; border: 2px solid var(--border-medium); cursor: pointer; transition: all 0.2s;" id="label-card"
                               onclick="selectMethod('card')">
                            <input type="radio" name="payment_method" value="card" style="accent-color: var(--brown); width: 18px; height: 18px;" id="method-card">
                            <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                                <div>
                                    <div style="font-size: 14px; font-weight: 500; color: var(--text-primary);">Банковская карта</div>
                                    <div class="text-muted-c" style="font-size: 12px;">Visa, Mastercard, МИР</div>
                                </div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; gap: 14px; padding: 16px; border-radius: 12px; border: 2px solid var(--border-medium); cursor: pointer; transition: all 0.2s;" id="label-sbp"
                               onclick="selectMethod('sbp')">
                            <input type="radio" name="payment_method" value="sbp" style="accent-color: var(--brown); width: 18px; height: 18px;" id="method-sbp">
                            <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                                <div>
                                    <div style="font-size: 14px; font-weight: 500; color: var(--text-primary);">СБП — Система быстрых платежей</div>
                                    <div class="text-muted-c" style="font-size: 12px;">Мгновенный перевод по номеру телефона</div>
                                </div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; gap: 14px; padding: 16px; border-radius: 12px; border: 2px solid var(--border-medium); cursor: pointer; transition: all 0.2s;" id="label-wallet"
                               onclick="selectMethod('wallet')">
                            <input type="radio" name="payment_method" value="wallet" style="accent-color: var(--brown); width: 18px; height: 18px;" id="method-wallet">
                            <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                                <div>
                                    <div style="font-size: 14px; font-weight: 500; color: var(--text-primary);">Электронный кошелёк</div>
                                    <div class="text-muted-c" style="font-size: 12px;">ЮMoney</div>
                                </div>
                            </div>
                        </label>
                    </div>

                    @error('payment_method')
                    <p style="font-size: 12px; color: var(--error); margin-bottom: 12px;">{{ $message }}</p>
                    @enderror

                    <div id="card-fields" style="display: none; flex-direction: column; gap: 16px;">
                        <div style="display: grid; grid-template-columns: 1fr; gap: 14px;">

                            <div>
                                <label class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">
                                    Номер карты
                                </label>
                                <input type="text" name="card_number" placeholder="0000 0000 0000 0000"
                                       maxlength="19"
                                       class="input-field @error('card_number') error @enderror"
                                       style="padding: 12px 14px; font-size: 16px; letter-spacing: 2px;"
                                       oninput="formatCard(this)">
                                @error('card_number')<p style="font-size: 11px; color: var(--error); margin-top: 4px;">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">
                                    Имя держателя
                                </label>
                                <input type="text" name="card_name" placeholder="IVAN PETROV"
                                       class="input-field @error('card_name') error @enderror"
                                       style="padding: 12px 14px; text-transform: uppercase;"
                                       oninput="this.value = this.value.toUpperCase()">
                                @error('card_name')<p style="font-size: 11px; color: var(--error); margin-top: 4px;">{{ $message }}</p>@enderror
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <div>
                                    <label class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">
                                        Срок действия
                                    </label>
                                    <input type="text" name="card_expiry" placeholder="ММ/ГГ"
                                           maxlength="5"
                                           class="input-field @error('card_expiry') error @enderror"
                                           style="padding: 12px 14px;"
                                           oninput="formatExpiry(this)">
                                    @error('card_expiry')<p style="font-size: 11px; color: var(--error); margin-top: 4px;">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="text-secondary-c" style="display: block; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">
                                        CVV / CVC
                                    </label>
                                    <div style="position: relative;">
                                        <input type="password" name="card_cvv" placeholder="•••"
                                               maxlength="3"
                                               class="input-field @error('card_cvv') error @enderror"
                                               style="padding: 12px 40px 12px 14px; letter-spacing: 4px;"
                                               id="cvv-field">
                                        <button type="button" onclick="toggleCvv()"
                                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: var(--text-muted); font-size: 16px;"
                                                id="cvv-eye">👁</button>
                                    </div>
                                    @error('card_cvv')<p style="font-size: 11px; color: var(--error); margin-top: 4px;">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="sbp-info" style="display: none; padding: 16px; border-radius: 10px; background: rgba(107,138,92,0.1); border: 1px solid rgba(107,138,92,0.2);">
                        <p style="font-size: 14px; color: var(--text-secondary); margin: 0;">
                            После нажатия «Оплатить» вы получите реквизиты для перевода через СБП на указанный номер телефона исполнителя.
                        </p>
                    </div>

                    <div id="wallet-info" style="display: none; padding: 16px; border-radius: 10px; background: rgba(184,136,88,0.1); border: 1px solid rgba(184,136,88,0.2);">
                        <p style="font-size: 14px; color: var(--text-secondary); margin: 0;">
                            Вы будете перенаправлены на страницу выбранного кошелька для завершения платежа.
                        </p>
                    </div>
                </div>

                <div class="card-flat reveal" style="padding: 20px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span class="text-secondary-c" style="font-size: 14px;">Услуга:</span>
                        <span style="font-size: 14px;">{{ \Illuminate\Support\Str::limit($service->title, 40) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid var(--border-soft);">
                        <span style="font-size: 15px; font-weight: 500;">Итого:</span>
                        <span class="gradient-number" style="font-family: 'Karelle', serif; font-size: 1.4rem;">
                        @if($service->price_negotiable) Договорная
                            @elseif($service->price) {{ number_format($service->price, 0, '.', ' ') }} {{ $service->price_unit ?? 'руб.' }}
                            @else Уточнить @endif
                    </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-filled" style="width: 100%; font-size: 13px; padding: 14px;" id="pay-btn" disabled>
                    Оплатить
                </button>

                <p class="text-muted-c" style="font-size: 11px; text-align: center; margin-top: 12px;">
                    Это демонстрационная оплата. Реальные средства не списываются.
                    После оплаты вы получите подтверждение на email.
                </p>
            </form>

        </div>
    </section>

    <script>
        function selectMethod(method) {
            ['card', 'sbp', 'wallet'].forEach(m => {
                const label = document.getElementById('label-' + m);
                if (label) label.style.borderColor = 'var(--border-medium)';
                const field = document.getElementById(m === 'card' ? 'card-fields' : m + '-info');
                if (field) field.style.display = 'none';
            });

            const label = document.getElementById('label-' + method);
            if (label) label.style.borderColor = 'var(--brown)';

            if (method === 'card') {
                document.getElementById('card-fields').style.display = 'flex';
            } else {
                document.getElementById(method + '-info').style.display = 'block';
            }

            document.getElementById('method-' + method).checked = true;
            document.getElementById('pay-btn').disabled = false;
        }

        function formatCard(input) {
            let v = input.value.replace(/\D/g, '').substring(0, 16);
            input.value = v.replace(/(.{4})/g, '$1 ').trim();
        }

        function formatExpiry(input) {
            let v = input.value.replace(/\D/g, '').substring(0, 4);
            if (v.length >= 2) v = v.substring(0, 2) + '/' + v.substring(2);
            input.value = v;
        }

        function toggleCvv() {
            const f = document.getElementById('cvv-field');
            f.type = f.type === 'password' ? 'text' : 'password';
        }
    </script>

@endsection
