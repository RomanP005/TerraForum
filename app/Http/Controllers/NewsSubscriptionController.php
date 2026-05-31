<?php
namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class NewsSubscriptionController extends Controller
{
    public function toggle(): RedirectResponse
    {
        $user = auth()->user();

        $current = $user->news_subscribed ?? false;
        $user->update(['news_subscribed' => !$current]);

        $message = !$current
            ? 'Вы подписались на новости платформы'
            : 'Вы отписались от новостей';

        return back()->with('success', $message);
    }
}
