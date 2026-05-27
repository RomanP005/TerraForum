<?php
namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class NewsSubscriptionController extends Controller
{
    // Переключить подписку на новости
    public function toggle(): RedirectResponse
    {
        $user = auth()->user();

        // Используем мета-поле в таблице users
        // Если нет поля news_subscribed — добавьте в users миграцией
        // или используем cache/settings таблицу
        $current = $user->news_subscribed ?? false;
        $user->update(['news_subscribed' => !$current]);

        $message = !$current
            ? 'Вы подписались на новости платформы'
            : 'Вы отписались от новостей';

        return back()->with('success', $message);
    }
}
