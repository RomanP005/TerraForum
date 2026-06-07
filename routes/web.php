<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NewsSubscriptionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/themes/{theme:slug}', [ForumController::class, 'showTheme'])->name('forum.theme');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/profile/{name}', [ProfileController::class, 'show'])->name('profile.show');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])
    ->name('password.forgot');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])
    ->name('password.forgot.send');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])
    ->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.reset.update');

Route::middleware('guest')->group(function () {
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
    Route::post('/login', [LoginController::class, 'store'])->name('login');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'me'])->name('profile.me');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

    Route::post('/forum/themes', [ForumController::class, 'storeTheme'])->name('forum.theme.store');
    Route::post('/forum/themes/{theme:slug}/vote', [ForumController::class, 'vote'])->name('forum.theme.vote');
    Route::post('/forum/themes/{theme:slug}/posts', [PostController::class, 'store'])->name('forum.post.store');
    Route::post('/forum/posts/{post}/vote', [PostController::class, 'vote'])->name('forum.post.vote');
    Route::post('/forum/posts/{post}/best', [PostController::class, 'markBestAnswer'])->name('forum.post.best');

    Route::post('/news/{news:slug}/comments', [CommentController::class, 'storeForNews'])->name('news.comment');
    Route::post('/news/subscribe', [NewsSubscriptionController::class, 'toggle'])->name('news.subscribe.toggle');

    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::delete('/services/{service:slug}', [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::post('/services/{service:slug}/contact', [ServiceController::class, 'contact'])->name('services.contact');


    Route::get('/payment/{service:slug}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{service:slug}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/{service:slug}/success', [PaymentController::class, 'success'])->name('payment.success');

    Route::post('/favorites/themes/{theme:slug}', [FavoriteController::class, 'toggleTheme'])->name('favorites.theme.toggle');
    Route::post('/favorites/posts/{post}', [FavoriteController::class, 'togglePost'])->name('favorites.post.toggle');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::post('/profile/password/send-link', [ProfileController::class, 'sendPasswordLink'])->name('profile.password.send-link');

});

Route::middleware(['auth', 'role:admin|moderator'])->group(function () {

    Route::patch('/forum/themes/{theme:slug}/approve', function (\App\Models\Theme $theme) {
        $theme->update(['is_approved' => true]);
        return back()->with('success', 'Тема одобрена');
    })->name('forum.theme.approve');

    Route::patch('/forum/themes/{theme:slug}/close', function (\App\Models\Theme $theme) {
        $theme->update(['is_closed' => !$theme->is_closed]);
        return back()->with('success', $theme->is_closed ? 'Тема закрыта' : 'Тема открыта');
    })->name('forum.theme.close');

});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::delete('/forum/themes/{theme:slug}', function (\App\Models\Theme $theme) {
        $theme->delete();
        return redirect()->route('forum.index')->with('success', 'Тема удалена');
    })->name('forum.theme.delete');

});
