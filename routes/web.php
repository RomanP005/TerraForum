<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Публичные
Route::get('/', [HomeController::class, 'index'])->name('home');

// Новости
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');

// Форум
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/themes/{theme:slug}', [ForumController::class, 'showTheme'])->name('forum.theme');

// Профиль (просмотр)
Route::get('/profile/{name}', [ProfileController::class, 'show'])->name('profile.show');

// Гостевые
Route::middleware('guest')->group(function () {
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
    Route::post('/login', [LoginController::class, 'store'])->name('login');
});

// Авторизованные
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Профиль
    Route::get('/profile', [ProfileController::class, 'me'])->name('profile.me');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Форум
    Route::post('/forum/themes', [ForumController::class, 'storeTheme'])->name('forum.theme.store');
    Route::post('/forum/themes/{theme:slug}/vote', [ForumController::class, 'vote'])->name('forum.theme.vote');
    Route::post('/forum/themes/{theme:slug}/posts', [PostController::class, 'store'])->name('forum.post.store');
    Route::post('/forum/posts/{post}/vote', [PostController::class, 'vote'])->name('forum.post.vote');
    Route::post('/forum/posts/{post}/best', [PostController::class, 'markBestAnswer'])->name('forum.post.best');

    // Комментарии к новостям
    Route::post('/news/{news:slug}/comments', [CommentController::class, 'storeForNews'])->name('news.comment');
});
