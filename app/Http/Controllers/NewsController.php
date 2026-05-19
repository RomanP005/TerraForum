<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * Список новостей.
     */
    public function index(Request $request): View
    {
        $query = News::published()
            ->with(['author', 'media'])
            ->latest('published_at');

        // Фильтр по категории
        if ($category = $request->input('category')) {
            $query->where('news_category', $category);
        }

        $news = $query->paginate(12)->withQueryString();

        // Уникальные категории для фильтра
        $categories = News::published()
            ->whereNotNull('news_category')
            ->distinct()
            ->pluck('news_category');

        // Последние 3 для сайдбара
        $latest = News::published()
            ->with('media')
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('news.index', compact('news', 'categories', 'latest', 'category'));
    }

    /**
     * Детальная страница новости.
     */
    public function show(News $news): View
    {
        abort_unless($news->is_published, 404);

        $news->load(['author', 'media', 'tags']);
        $news->increment('views_count');

        // Комментарии
        $comments = $news->comments()
            ->with('user')
            ->whereNull('parent_id')
            ->latest()
            ->get();

        // Похожие новости
        $related = News::published()
            ->where('id', '!=', $news->id)
            ->where('news_category', $news->news_category)
            ->with('media')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('news.show', compact('news', 'comments', 'related'));
    }
}
