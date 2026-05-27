<?php

namespace App\Http\Controllers;

use App\Http\Requests\Forum\CreateThemeRequest;
use App\Models\Category;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Tags\Tag;

class ForumController extends Controller
{
    public function index(Request $request): View
    {
        $query = Theme::approved()
            ->with(['user', 'category', 'tags'])
            ->withCount('posts as posts_count');

        if ($search = $request->input('q')) {
            $foundIds = Theme::search($search)->keys();

            if ($foundIds->isNotEmpty()) {
                $query->whereIn('id', $foundIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }
        if ($categorySlug = $request->input('category')) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }
        $selectedTags = collect($request->input('tags', []))->merge(
            array_filter([$request->input('tag')])
        )->unique()->values();

        if ($selectedTags->isNotEmpty()) {
            $query->withAnyTags($selectedTags->toArray());
        }

        $sort = $request->input('sort', 'latest');
        $query = match ($sort) {
            'popular' => $query->popular(),
            'active' => $query->mostActive(),
            default => $query->latest(),
        };

        $themes = $query->paginate(15)->withQueryString();

        $categories = Category::active()
            ->root()
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        $popularTags = Tag::orderByDesc('order_column')->take(20)->get();

        return view('forum.index', compact(
            'themes', 'categories', 'popularTags',
            'search', 'categorySlug', 'selectedTags', 'sort'
        ));
    }

    public function showTheme(Theme $theme): View
    {
        abort_unless($theme->is_approved, 404);

        $theme->load(['user', 'category', 'tags', 'media']);

        $theme->increment('views_count');

        $posts = $theme->posts()
            ->with(['user', 'media', 'votes'])
            ->orderByDesc('is_best_answer')
            ->orderBy('created_at')
            ->paginate(20);

        return view('forum.theme', compact('theme', 'posts'));
    }

    public function storeTheme(CreateThemeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $theme = Theme::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id' => auth()->id(),
            'last_activity_at' => now(),
        ]);

        if (! empty($validated['tags'])) {
            $theme->syncTags($validated['tags']);
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $theme->addMedia($file)->toMediaCollection('attachments');
            }
        }

        return redirect()->route('forum.theme', $theme->slug)
            ->with('success', 'Тема создана');
    }

    public function vote(string $slug): \Illuminate\Http\RedirectResponse
    {
        $theme = \App\Models\Theme::where('slug', $slug)->firstOrFail();
        $user  = auth()->user();
        $value = request('value');

        abort_unless(in_array($value, ['up', 'down']), 400);

        // Проверяем текущий голос пользователя
        $existingVote = \DB::table('votes')
            ->where('user_id', $user->id)
            ->where('votable_id', $theme->id)
            ->where('votable_type', get_class($theme))
            ->first();

        if ($existingVote) {

            $isSameVote = ($existingVote->votes === 1 && $value === 'up')
                || ($existingVote->votes === -1 && $value === 'down');

            if ($isSameVote) {
                // Тот же голос — удаляем запись (возврат к 0)
                \DB::table('votes')
                    ->where('user_id', $user->id)
                    ->where('votable_id', $theme->id)
                    ->where('votable_type', get_class($theme))
                    ->delete();

                return back();
            }
        }

        // Ставим новый голос
        $value === 'up' ? $user->upvote($theme) : $user->downvote($theme);

        return back();
    }
}
