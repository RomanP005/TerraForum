<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;

class FavoriteController extends Controller
{
    /**
     * Добавить/убрать тему из избранного.
     */
    public function toggleTheme(Theme $theme): RedirectResponse
    {
        $user = auth()->user();

        if ($user->hasFavorited($theme)) {
            $user->unfavorite($theme);
            $message = 'Тема удалена из избранного';
        } else {
            $user->favorite($theme);
            $message = 'Тема добавлена в избранное';
        }

        return back()->with('success', $message);
    }

    /**
     * Добавить/убрать ответ из избранного.
     */
    public function togglePost(Post $post): RedirectResponse
    {
        $user = auth()->user();

        if ($user->hasFavorited($post)) {
            $user->unfavorite($post);
            $message = 'Ответ удалён из избранного';
        } else {
            $user->favorite($post);
            $message = 'Ответ добавлен в избранное';
        }

        return back()->with('success', $message);
    }
}
