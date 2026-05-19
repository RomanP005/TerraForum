<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'bio'            => ['nullable', 'string', 'max:500'],
            'region'         => ['nullable', 'string', 'max:100'],
            'delete_avatar'  => ['nullable', 'boolean'],
            'avatar'         => [
                'nullable', 'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
                'dimensions:min_width=100,min_height=100',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'bio.max'              => 'Биография не должна превышать :max символов',
            'region.max'           => 'Название региона слишком длинное',
            'avatar.image'         => 'Файл должен быть изображением',
            'avatar.mimes'         => 'Поддерживаются JPEG, PNG, WebP',
            'avatar.max'           => 'Размер аватара не должен превышать 2 МБ',
            'avatar.dimensions'    => 'Изображение должно быть не менее 100×100 px',
        ];
    }
}
