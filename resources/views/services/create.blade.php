@extends('layouts.app')

@section('title', 'Разместить услугу')
@section('mode', 'linen')

@section('content')

    <section class="py-12 px-6">
        <div class="workspace" style="max-width: 780px;">

            <div class="text-center mb-10 reveal">
                <div class="divider mb-4"><span>новое объявление</span></div>
                <h1 class="text-4xl md:text-5xl">Разместить услугу</h1>
                <p class="text-secondary-c mt-3 text-sm">
                    После публикации объявление отправится на проверку модератору.
                    Обычно это занимает не более нескольких часов.
                </p>
            </div>
            <form action="{{ route('services.store') }}" method="POST"
                  enctype="multipart/form-data" class="space-y-6 card-flat p-6 reveal">
                @csrf
                <div>
                    <label class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">
                        Название услуги
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Например: Обрезка плодовых деревьев в Подмосковье"
                           class="input-field w-full px-3 py-2 text-sm @error('title') error @enderror">
                    @error('title')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">
                        Категория
                    </label>
                    <select name="service_category" required
                            class="input-field w-full px-3 py-2 text-sm @error('service_category') error @enderror">
                        <option value="">Выберите категорию...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('service_category') === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_category')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">
                        Описание
                    </label>
                    <textarea name="description" rows="7" required
                              placeholder="Подробно опишите услугу: опыт, оборудование, сроки, условия работы..."
                              class="input-field w-full px-3 py-2 text-sm @error('description') error @enderror">{{ old('description') }}</textarea>
                    <p class="text-xs mt-1 text-muted-c">Минимум 30 символов</p>
                    @error('description')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">
                            Цена <span class="normal-case opacity-60">(необязательно)</span>
                        </label>
                        <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01"
                               placeholder="5000"
                               class="input-field w-full px-3 py-2 text-sm @error('price') error @enderror">
                        @error('price')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">
                            Единица цены
                        </label>
                        <select name="price_unit" class="input-field w-full px-3 py-2 text-sm">
                            <option value="руб."       {{ old('price_unit') === 'руб.'       ? 'selected' : '' }}>руб.</option>
                            <option value="руб./час"   {{ old('price_unit') === 'руб./час'   ? 'selected' : '' }}>руб./час</option>
                            <option value="руб./га"    {{ old('price_unit') === 'руб./га'    ? 'selected' : '' }}>руб./га</option>
                            <option value="руб./сотка" {{ old('price_unit') === 'руб./сотка' ? 'selected' : '' }}>руб./сотка</option>
                            <option value="руб./день"  {{ old('price_unit') === 'руб./день'  ? 'selected' : '' }}>руб./день</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="price_negotiable" value="1"
                               {{ old('price_negotiable') ? 'checked' : '' }}
                               class="w-4 h-4" style="accent-color: var(--brown);">
                        <span class="text-sm text-secondary-c">Договорная цена</span>
                    </label>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">
                            Регион
                        </label>
                        <input type="text" name="region" value="{{ old('region') }}" required
                               placeholder="Московская область"
                               class="input-field w-full px-3 py-2 text-sm @error('region') error @enderror">
                        @error('region')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">
                            Город <span class="normal-case opacity-60">(необязательно)</span>
                        </label>
                        <input type="text" name="city" value="{{ old('city') }}"
                               placeholder="Серпухов"
                               class="input-field w-full px-3 py-2 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">
                        Телефон <span class="normal-case opacity-60">(необязательно)</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="+7 (999) 000-00-00"
                           class="input-field w-full px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">
                        Фотографии <span class="normal-case opacity-60">(до 5 штук)</span>
                    </label>
                    <input type="file" name="photos[]" multiple
                           accept="image/jpeg,image/png,image/webp"
                           class="text-xs text-secondary-c">
                    <p class="text-xs mt-1 text-muted-c">JPEG / PNG / WebP до 5 МБ каждый</p>
                </div>

                <div class="rounded-xl p-4 text-sm"
                     style="background: rgba(107, 138, 92, 0.12); border: 1px solid rgba(107, 138, 92, 0.3); color: var(--text-secondary);">
                    ℹ После отправки объявление будет проверено модератором и появится в каталоге.
                </div>

                <button type="submit" class="btn btn-filled">Отправить на модерацию</button>

            </form>
        </div>
    </section>

@endsection
