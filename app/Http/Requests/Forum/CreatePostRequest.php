<?php

namespace App\Http\Requests\Forum;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('create posts');
    }

    public function rules(): array
    {
        return [
            'content' => [
                'required', 'string', 'min:5', 'max:10000',
            ],
            'parent_post_id' => [
                'nullable', 'exists:posts,id',
            ],
            'attachments' => [
                'nullable', 'array', 'max:3',
            ],
            'attachments.*' => [
                'image', 'mimes:jpeg,jpg,png,webp', 'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Введите текст ответа',
            'content.min' => 'Ответ должен быть не короче :min символов',
            'content.max' => 'Ответ слишком длинный',

            'attachments.max' => 'Можно прикрепить не более 3 файлов',
            'attachments.*.image' => 'Можно прикреплять только изображения',
            'attachments.*.mimes' => 'Поддерживаются JPEG, PNG, WebP',
            'attachments.*.max' => 'Файл не должен превышать 5 МБ',
        ];
    }
}
