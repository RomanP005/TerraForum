<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CreatePostRequest;
use App\Models\Post;
use App\Models\Theme;
use App\Notifications\ReplyToTheme;
use App\Services\ReputationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    public function store(CreatePostRequest $request, string $slug): RedirectResponse
    {
        $theme = Theme::where('slug', $slug)->firstOrFail();

        abort_if($theme->is_closed, 403, 'Тема закрыта для ответов');

        $post = $theme->posts()->create([
            'content' => $request->validated()['content'],
            'user_id' => auth()->id(),
        ]);


        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $post->addMedia($file)->toMediaCollection('attachments');
            }
        }


        $theme->increment('comments_count');
        $theme->update(['last_activity_at' => now()]);


        if ($theme->user_id !== auth()->id()) {
            try {
                $theme->user->notify(new ReplyToTheme($theme, $post->load('user')));
            } catch (\Exception $e) {

            }
        }

        return redirect()
            ->route('forum.theme', $theme->slug)
            ->with('success', 'Ответ опубликован');
    }

    public function vote(Post $post): RedirectResponse
    {
        $user  = auth()->user();
        $value = request('value');

        abort_unless(in_array($value, ['up', 'down']), 400);
        abort_if($post->user_id === $user->id, 403, 'Нельзя голосовать за свои ответы');

        $author = $post->user;

        $existingVote = DB::table('votes')
            ->where('user_id', $user->id)
            ->where('votable_id', $post->id)
            ->where('votable_type', get_class($post))
            ->first();

        if ($existingVote) {
            $isSameVote = ($existingVote->votes === 1  && $value === 'up')
                || ($existingVote->votes === -1 && $value === 'down');

            if ($isSameVote) {
                DB::table('votes')
                    ->where('user_id', $user->id)
                    ->where('votable_id', $post->id)
                    ->where('votable_type', get_class($post))
                    ->delete();

                $action = $existingVote->votes === 1
                    ? 'vote_up_cancelled'
                    : 'vote_down_cancelled';
                ReputationService::updateForVote($author, $action);

                return back();
            }
        }

        $value === 'up' ? $user->upvote($post) : $user->downvote($post);

        $action = $value === 'up' ? 'vote_up_received' : 'vote_down_received';
        ReputationService::updateForVote($author, $action);

        return back();
    }

    public function markBestAnswer(Post $post): RedirectResponse
    {
        $theme = $post->theme;

        abort_unless(
            auth()->id() === $theme->user_id ||
            auth()->user()->hasAnyRole(['moderator', 'admin']),
            403
        );

        $oldBest = $theme->posts()->where('is_best_answer', true)->first();
        if ($oldBest && $oldBest->id !== $post->id) {
            $oldBest->user->decrement('rating', ReputationService::POINTS['best_answer']);
            if ($oldBest->user->rating < 0) {
                $oldBest->user->update(['rating' => 0]);
            }
        }

        $theme->posts()->where('is_best_answer', true)->update(['is_best_answer' => false]);
        $post->update(['is_best_answer' => true]);

        ReputationService::updateForVote($post->user, 'best_answer');

        return back()->with('success', 'Лучший ответ отмечен');
    }
}
