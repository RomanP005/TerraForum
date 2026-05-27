<?php
namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    // Список всех уведомлений
    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        // Помечаем все как прочитанные при открытии страницы
        auth()->user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }

    // Пометить одно как прочитанное и перейти по ссылке
    public function read(string $id): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('home');

        return redirect($url);
    }

    // Пометить все как прочитанные
    public function readAll(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Все уведомления прочитаны');
    }

    // Удалить уведомление
    public function destroy(string $id): RedirectResponse
    {
        auth()->user()->notifications()->findOrFail($id)->delete();
        return back()->with('success', 'Уведомление удалено');
    }
}
