<?php

namespace App\Http\Requests\Forum;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateThemeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('create themes');
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required', 'string', 'min:10', 'max:255',
            ],
            'content' => [
                'required', 'string', 'min:20', 'max:10000',
            ],
            'category_id' => [
                'required', 'exists:categories,id',
            ],
            'tags' => [
                'nullable', 'array', 'max:5',
            ],
            'tags.*' => [
                'string', 'max:80',
            ],
            'attachments' => [
                'nullable', 'array', 'max:5',
            ],
            'attachments.*' => [
                'image', 'mimes:jpeg,jpg,png,webp', 'max:5120', // 5 МБ
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Введите заголовок темы',
            'title.min' => 'Заголовок должен быть не короче :min символов',
            'title.max' => 'Заголовок не должен превышать :max символов',

            'content.required' => 'Опишите тему',
            'content.min' => 'Текст должен быть не короче :min символов',
            'content.max' => 'Текст слишком длинный (максимум :max символов)',

            'category_id.required' => 'Выберите категорию',
            'category_id.exists' => 'Категория не найдена',

            'tags.max' => 'Можно добавить не более 5 тегов',
            'tags.*.max' => 'Длина тега не должна превышать :max символов',

            'attachments.max' => 'Можно прикрепить не более 5 файлов',
            'attachments.*.image' => 'Можно прикреплять только изображения',
            'attachments.*.mimes' => 'Поддерживаются форматы JPEG, PNG, WebP',
            'attachments.*.max' => 'Файл не должен превышать 5 МБ',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'create-theme-modal')
        );
    }
}
