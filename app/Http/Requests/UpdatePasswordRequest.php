<?php

namespace App\Http\Requests\Profile;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(8)->letters()->numbers(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Введите текущий пароль',

            'password.required' => 'Введите новый пароль',
            'password.confirmed' => 'Пароли не совпадают',
            'password.different' => 'Новый пароль должен отличаться от текущего',
            'password.min' => 'Пароль должен быть не менее :min символов',
            'password.letters' => 'Пароль должен содержать хотя бы одну букву',
            'password.numbers' => 'Пароль должен содержать хотя бы одну цифру',
        ];
    }

    /**
     * Проверка, что текущий пароль введён правильно.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! Hash::check($this->input('current_password'), auth()->user()->password)) {
                $validator->errors()->add('current_password', 'Текущий пароль введён неверно');
            }
        });
    }
}
