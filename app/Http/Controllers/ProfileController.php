<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function me(): RedirectResponse
    {
        return redirect()->route('profile.show', Auth::user()->name);
    }

    public function show(string $name): View
    {
        $user = User::where('name', $name)->firstOrFail();
        $isOwner = Auth::check() && Auth::id() === $user->id;

        $stats = ['themes' => 0, 'posts' => 0, 'services' => 0, 'votes_given' => 0];
        try {
            $stats['themes'] = $user->themes()->count();
        } catch (\Exception $e) {
        }
        try {
            $stats['posts'] = $user->posts()->count();
        } catch (\Exception $e) {
        }
        try {
            $stats['services'] = $user->services()->count();
        } catch (\Exception $e) {
        }
        try {
            $stats['votes_given'] = $user->votes()->count();
        } catch (\Exception $e) {
        }

        $recentThemes = collect();
        try {
            $recentThemes = $user->themes()
                ->with('category')->latest()->take(5)->get();
        } catch (\Exception $e) {
        }

        $allThemes = collect();
        try {
            $allThemes = $user->themes()
                ->with(['category', 'tags'])
                ->withCount('posts as posts_count')
                ->withTotalVotes()
                ->latest()
                ->paginate(10);
        } catch (\Exception $e) {
        }

        $favorites = collect();
        if ($isOwner) {
            try {
                // Получаем сырые записи из таблицы favorites
                $rawFavorites = \DB::table('favorites')
                    ->where('user_id', $user->id)
                    ->orderByDesc('created_at')
                    ->get();

                $result = collect();

                foreach ($rawFavorites as $fav) {
                    // Избранные ТЕМЫ
                    if ($fav->favoriteable_type === 'App\\Models\\Theme') {
                        $theme = \App\Models\Theme::with('category')->find($fav->favoriteable_id);
                        if ($theme) {
                            $result->push([
                                'type'  => 'theme',
                                'label' => 'Тема',
                                'title' => $theme->title,
                                'url'   => route('forum.theme', $theme->slug),
                                'meta'  => $theme->category?->name ?? '',
                                'date'  => \Carbon\Carbon::parse($fav->created_at)->diffForHumans(),
                            ]);
                        }
                    }

                    // Избранные ОТВЕТЫ
                    if ($fav->favoriteable_type === 'App\\Models\\Post') {
                        $post = \App\Models\Post::with('theme')->find($fav->favoriteable_id);
                        if ($post && $post->theme) {
                            $result->push([
                                'type'  => 'post',
                                'label' => 'Ответ',
                                'title' => \Illuminate\Support\Str::limit($post->content, 100),
                                'url'   => route('forum.theme', $post->theme->slug) . '#post-' . $post->id,
                                'meta'  => 'в теме: ' . $post->theme->title,
                                'date'  => \Carbon\Carbon::parse($fav->created_at)->diffForHumans(),
                            ]);
                        }
                    }
                }

                $favorites = $result;

            } catch (\Exception $e) {
                $favorites = collect();
            }
        }

        $tabs = $isOwner
            ? ['activity' => 'Активность', 'themes' => 'Темы', 'favorites' => 'Избранное', 'settings' => 'Настройки']
            : ['activity' => 'Активность', 'themes' => 'Темы', 'favorites' => 'Избранное'];

        return view('profile.show', compact(
            'user', 'isOwner', 'stats',
            'recentThemes', 'allThemes',
            'favorites', 'tabs'
        ));
    }

    /**
     * Обновить профиль + аватар (с поддержкой delete_avatar).
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $validated = $request->validated();

        $user->update([
            'bio' => $validated['bio'] ?? null,
            'region' => $validated['region'] ?? null,
        ]);

        // Удалить аватар если запрошено
        if ($request->boolean('delete_avatar')) {
            $user->clearMediaCollection('avatar');
        }

        // Загрузить новый аватар (заменяет текущий)
        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatar');
            $user->addMediaFromRequest('avatar')
                ->toMediaCollection('avatar');
        }

        return redirect()->route('profile.show', $user->name)
            ->with('success', 'Профиль обновлён');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        Auth::user()->update([
            'password' => Hash::make($request->validated()['password']),
        ]);

        return redirect()->route('profile.show', Auth::user()->name)
            ->with('success', 'Пароль изменён');
    }
}
