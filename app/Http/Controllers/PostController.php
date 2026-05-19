<?php

namespace App\Http\Controllers;

use App\Http\Requests\Forum\CreatePostRequest;
use App\Models\Post;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Создать ответ в теме.
     */
    public function store(CreatePostRequest $request, Theme $theme): RedirectResponse
    {
        // Нельзя отвечать в закрытой теме
        abort_if($theme->is_closed, 403, 'Тема закрыта для ответов');

        $post = Post::create([
            'content' => $request->validated()['content'],
            'theme_id' => $theme->id,
            'user_id' => auth()->id(),
            'parent_post_id' => $request->validated()['parent_post_id'] ?? null,
        ]);

        // Прикрепления
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $post->addMedia($file)->toMediaCollection('attachments');
            }
        }

        // Обновляем активность темы
        $theme->update([
            'last_activity_at' => now(),
            'comments_count' => $theme->posts()->count(),
        ]);

        return redirect()->route('forum.theme', $theme->slug)
            ->with('success', 'Ответ опубликован')
            ->withFragment('post-' . $post->id);
    }

    /**
     * Голосование за сообщение.
     */
    public function vote(Request $request, Post $post): RedirectResponse
    {
        abort_unless(auth()->check() && auth()->user()->can('vote'), 403);

        $value = $request->input('value');

        if (! in_array($value, ['up', 'down'])) {
            abort(400);
        }

        if ($value === 'up') {
            auth()->user()->upvote($post);
        } else {
            auth()->user()->downvote($post);
        }

        return back()->withFragment('post-' . $post->id);
    }

    /**
     * Отметить пост как лучший ответ (только автор темы).
     */
    public function markBestAnswer(Post $post): RedirectResponse
    {
        $theme = $post->theme;

        // Только автор темы или модератор может отмечать лучший ответ
        abort_unless(
            auth()->id() === $theme->user_id || auth()->user()->hasAnyRole(['moderator', 'admin']),
            403
        );

        // Снимаем флаг с других сообщений этой темы
        $theme->posts()->update(['is_best_answer' => false]);

        // Ставим этому посту
        $post->update(['is_best_answer' => true]);

        return back()->withFragment('post-' . $post->id);
    }
}
