<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Перехватываем ошибки от Auth::attempt и редиректим на главную с открытием модалки
            return redirect()->route('home')
                ->withErrors($e->errors())
                ->withInput($request->except('password'))
                ->with('open_modal', 'login-modal');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'login_attempts' => 0,
        ]);

        return redirect()->intended(route('home'))
            ->with('success', 'Вы вошли как ' . $user->name);
    }


    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
