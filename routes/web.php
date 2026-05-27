<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsSubscriptionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════
// ПУБЛИЧНЫЕ (доступны всем)
// ═══════════════════════════════════════

Route::get('/', [HomeController::class, 'index'])->name('home');

// Новости
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');

// Форум
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/themes/{theme:slug}', [ForumController::class, 'showTheme'])->name('forum.theme');

// Услуги — create ВЫШЕ {slug}
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

// Профиль (просмотр — публичный)
Route::get('/profile/{name}', [ProfileController::class, 'show'])->name('profile.show');

// ═══════════════════════════════════════
// ГОСТЕВЫЕ (только незарегистрированные)
// ═══════════════════════════════════════

Route::middleware('guest')->group(function () {
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
    Route::post('/login', [LoginController::class, 'store'])->name('login');
});

// ═══════════════════════════════════════
// АВТОРИЗОВАННЫЕ (любой вошедший)
// ═══════════════════════════════════════

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Профиль (редактирование)
    Route::get('/profile', [ProfileController::class, 'me'])->name('profile.me');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

    // Форум
    Route::post('/forum/themes', [ForumController::class, 'storeTheme'])->name('forum.theme.store');
    Route::post('/forum/themes/{theme:slug}/vote', [ForumController::class, 'vote'])->name('forum.theme.vote');
    Route::post('/forum/themes/{theme:slug}/posts', [PostController::class, 'store'])->name('forum.post.store');
    Route::post('/forum/posts/{post}/vote', [PostController::class, 'vote'])->name('forum.post.vote');
    Route::post('/forum/posts/{post}/best', [PostController::class, 'markBestAnswer'])->name('forum.post.best');

    // Услуги
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::delete('/services/{service:slug}', [ServiceController::class, 'destroy'])->name('services.destroy');

    // Комментарии к новостям
    Route::post('/news/{news:slug}/comments', [CommentController::class, 'storeForNews'])->name('news.comment');

    // Избранное
    Route::post('/favorites/themes/{theme:slug}', [FavoriteController::class, 'toggleTheme'])
        ->name('favorites.theme.toggle');

    Route::post('/favorites/posts/{post}', [FavoriteController::class, 'togglePost'])
        ->name('favorites.post.toggle');
});

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Подписка на новости
    Route::post('/news/subscribe', [NewsSubscriptionController::class, 'toggle'])->name('news.subscribe.toggle');

    // Контакт по услуге
    Route::post('/services/{service:slug}/contact', [\App\Http\Controllers\ServiceController::class, 'contact'])->name('services.contact');

// ═══════════════════════════════════════
// ТОЛЬКО МОДЕРАТОР И АДМИНИСТРАТОР
// ═══════════════════════════════════════

Route::middleware(['auth', 'role:admin|moderator'])->group(function () {

    // Быстрые действия модерации тем (одобрить, закрыть)
    Route::patch('/forum/themes/{theme:slug}/approve', function (\App\Models\Theme $theme) {
        $theme->update(['is_approved' => true]);
        return back()->with('success', 'Тема одобрена');
    })->name('forum.theme.approve');

    Route::patch('/forum/themes/{theme:slug}/close', function (\App\Models\Theme $theme) {
        $theme->update(['is_closed' => !$theme->is_closed]);
        return back()->with('success', $theme->is_closed ? 'Тема закрыта' : 'Тема открыта');
    })->name('forum.theme.close');

});

/**
 * Админка
 */

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Управление категориями форума напрямую (если нужно вне Filament)
    Route::delete('/forum/themes/{theme:slug}', function (\App\Models\Theme $theme) {
        $theme->delete();
        return redirect()->route('forum.index')->with('success', 'Тема удалена');
    })->name('forum.theme.delete');

});
