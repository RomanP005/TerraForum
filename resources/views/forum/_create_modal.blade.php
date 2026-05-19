<div id="create-theme-modal" class="modal-overlay" role="dialog" aria-labelledby="create-theme-title">
    <div class="modal-card" style="max-width: 600px;">
        <button type="button" class="modal-close" onclick="closeModal('create-theme-modal')" aria-label="Закрыть">×</button>

        <div class="text-center mb-6">
            <div class="divider mb-4"><span>новое обсуждение</span></div>
            <h2 id="create-theme-title" class="text-3xl">Создать тему</h2>
        </div>

        <form action="{{ route('forum.theme.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" novalidate>
            @csrf

            <div>
                <label for="theme-category" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Категория</label>
                <select id="theme-category" name="category_id" required
                        class="input-field w-full px-3 py-2 text-sm @error('category_id') error @enderror">
                    <option value="">Выберите...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @foreach($cat->children as $child)
                            <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>&nbsp;&nbsp;— {{ $child->name }}</option>
                        @endforeach
                    @endforeach
                </select>
                @error('category_id')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="theme-title" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Заголовок</label>
                <input type="text" id="theme-title" name="title" value="{{ old('title') }}" required
                       placeholder="Например: Когда лучше пересаживать яблоню?"
                       class="input-field w-full px-3 py-2 text-sm @error('title') error @enderror">
                @error('title')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="theme-content" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">Описание</label>
                <textarea id="theme-content" name="content" rows="6" required
                          placeholder="Опишите свой вопрос или ситуацию подробно..."
                          class="input-field w-full px-3 py-2 text-sm @error('content') error @enderror">{{ old('content') }}</textarea>
                <p class="text-xs mt-1 text-muted-c">От 20 до 10000 символов</p>
                @error('content')<p class="mt-1 text-xs" style="color: var(--error);">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="theme-tags-input" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">
                    Теги <span class="normal-case opacity-60">(до 5, через запятую)</span>
                </label>
                <input type="text" id="theme-tags-input" placeholder="полив, томаты, теплица"
                       class="input-field w-full px-3 py-2 text-sm"
                       value="{{ is_array(old('tags')) ? implode(', ', old('tags')) : '' }}">
                <div id="theme-tags-hidden"></div>
            </div>

            <div>
                <label for="theme-attachments" class="block text-xs uppercase tracking-widest text-secondary-c mb-2" style="letter-spacing: 2px;">
                    Фотографии <span class="normal-case opacity-60">(до 5)</span>
                </label>
                <input type="file" id="theme-attachments" name="attachments[]" multiple
                       accept="image/jpeg,image/png,image/webp" class="text-xs text-secondary-c">
            </div>

            <button type="submit" class="btn btn-filled w-full mt-4">Опубликовать тему</button>
        </form>
    </div>
</div>

<script>
    document.querySelector('#create-theme-modal form')?.addEventListener('submit', function(e) {
        const tagsInput = document.getElementById('theme-tags-input');
        const hiddenContainer = document.getElementById('theme-tags-hidden');
        hiddenContainer.innerHTML = '';
        if (tagsInput.value.trim()) {
            const tags = tagsInput.value.split(',').map(t => t.trim()).filter(t => t.length > 0);
            tags.forEach(tag => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'tags[]';
                hidden.value = tag;
                hiddenContainer.appendChild(hidden);
            });
        }
    });
</script>
