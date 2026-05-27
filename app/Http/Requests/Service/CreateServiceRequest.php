<?php

namespace App\Http\Requests\Service;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class CreateServiceRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'min:10', 'max:200'],
            'description'      => ['required', 'string', 'min:30', 'max:5000'],
            'service_category' => ['required', 'string'],
            'price'            => ['nullable', 'numeric', 'min:0'],
            'price_unit'       => ['nullable', 'string', 'max:50'],
            'price_negotiable' => ['nullable', 'boolean'],
            'region'           => ['required', 'string', 'max:100'],
            'city'             => ['nullable', 'string', 'max:100'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'photos'           => ['nullable', 'array', 'max:5'],
            'photos.*'         => ['image', 'mimes:jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'            => 'Введите название услуги',
            'title.min'                 => 'Название должно быть не короче :min символов',
            'description.required'      => 'Опишите услугу подробнее',
            'description.min'           => 'Описание должно быть не короче :min символов',
            'service_category.required' => 'Выберите категорию услуги',
            'region.required'           => 'Укажите регион',
        ];
    }
}
