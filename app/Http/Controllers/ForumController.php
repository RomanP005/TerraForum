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
    /**
     * Главная страница форума со списком тем.
     */
    public function index(Request $request): View
    {
        // Базовый запрос — одобренные темы с автором и категорией
        $query = Theme::approved()
            ->with(['user', 'category', 'tags'])
            ->withCount('posts as posts_count');

        // === УМНЫЙ ПОИСК через Laravel Scout ===
        if ($search = $request->input('q')) {
            // Scout вернёт ID найденных тем
            $foundIds = Theme::search($search)->keys();

            if ($foundIds->isNotEmpty()) {
                $query->whereIn('id', $foundIds);
            } else {
                // Если ничего не найдено — пустая выборка
                $query->whereRaw('1 = 0');
            }
        }

        // === ФИЛЬТР ПО КАТЕГОРИИ ===
        if ($categorySlug = $request->input('category')) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // === ФИЛЬТР ПО ТЕГАМ ===
        // Может быть один тег (?tag=Полив) или несколько (?tags[]=Полив&tags[]=Томаты)
        $selectedTags = collect($request->input('tags', []))->merge(
            array_filter([$request->input('tag')])
        )->unique()->values();

        if ($selectedTags->isNotEmpty()) {
            $query->withAnyTags($selectedTags->toArray());
        }

        // === СОРТИРОВКА ===
        $sort = $request->input('sort', 'latest');
        $query = match ($sort) {
            'popular' => $query->popular(),
            'active' => $query->mostActive(),
            default => $query->latest(),
        };

        // Пагинация с сохранением фильтров
        $themes = $query->paginate(15)->withQueryString();

        // Список категорий для боковой панели
        $categories = Category::active()
            ->root()
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        // Популярные теги (top-20)
        $popularTags = Tag::orderByDesc('order_column')->take(20)->get();

        return view('forum.index', compact(
            'themes', 'categories', 'popularTags',
            'search', 'categorySlug', 'selectedTags', 'sort'
        ));
    }

    /**
     * Просмотр темы.
     */
    public function showTheme(Theme $theme): View
    {
        // Проверка одобрения
        abort_unless($theme->is_approved, 404);

        // Загрузка связанных данных
        $theme->load(['user', 'category', 'tags', 'media']);

        // Инкремент просмотров (можно перенести в middleware/job)
        $theme->increment('views_count');

        // Сообщения с сортировкой: лучший ответ первым, потом по дате
        $posts = $theme->posts()
            ->with(['user', 'media', 'votes'])
            ->orderByDesc('is_best_answer')
            ->orderBy('created_at')
            ->paginate(20);

        return view('forum.theme', compact('theme', 'posts'));
    }

    /**
     * Создание новой темы (через модалку).
     */
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

        // Теги — через spatie/laravel-tags
        if (! empty($validated['tags'])) {
            $theme->syncTags($validated['tags']);
        }

        // Прикреплённые изображения
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $theme->addMedia($file)->toMediaCollection('attachments');
            }
        }

        return redirect()->route('forum.theme', $theme->slug)
            ->with('success', 'Тема создана');
    }

    /**
     * Голосование за тему (лайк/дизлайк).
     */
    public function vote(Request $request, Theme $theme): RedirectResponse
    {
        abort_unless(auth()->check() && auth()->user()->can('vote'), 403);

        $value = $request->input('value');

        if (! in_array($value, ['up', 'down'])) {
            abort(400);
        }

        if ($value === 'up') {
            auth()->user()->upvote($theme);
        } else {
            auth()->user()->downvote($theme);
        }

        return back();
    }
}
