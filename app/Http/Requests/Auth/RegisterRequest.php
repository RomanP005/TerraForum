<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Регистрация доступна всем (гостям).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[A-Za-zА-Яа-яЁё0-9_\-\s]+$/u',
                'unique:users,name',
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers(),
            ],
            'region' => [
                'nullable',
                'string',
                'max:100',
            ],
            'terms' => [
                'accepted',
            ],
        ];
    }

    /**
     * Кастомные сообщения об ошибках.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Введите никнейм',
            'name.min' => 'Никнейм должен содержать минимум :min символа',
            'name.max' => 'Никнейм не должен превышать :max символов',
            'name.regex' => 'Никнейм может содержать только буквы, цифры, пробелы, дефис и подчёркивание',
            'name.unique' => 'Такой никнейм уже занят',

            'email.required' => 'Введите email',
            'email.email' => 'Введите корректный email',
            'email.unique' => 'Пользователь с таким email уже зарегистрирован',

            'password.required' => 'Введите пароль',
            'password.confirmed' => 'Пароли не совпадают',
            'password.min' => 'Пароль должен быть не менее :min символов',
            'password.letters' => 'Пароль должен содержать хотя бы одну букву',
            'password.numbers' => 'Пароль должен содержать хотя бы одну цифру',

            'region.max' => 'Название региона слишком длинное',

            'terms.accepted' => 'Необходимо принять условия использования',
        ];
    }
}
