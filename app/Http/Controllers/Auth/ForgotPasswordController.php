<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ForgotPasswordController extends Controller
{
    public function showForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email'], [
            'email.required' => 'Введите email',
            'email.email'    => 'Некорректный email',
        ]);

        $status = Password::sendResetLink(['email' => $request->email]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Ссылка для сброса пароля отправлена на ваш email')
            : back()->withErrors(['email' => __($status)]);
    }
}
