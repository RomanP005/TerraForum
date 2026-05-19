<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function storeForNews(Request $request, News $news): RedirectResponse
    {
        abort_unless(auth()->check(), 403);

        $validated = $request->validate([
            'content' => ['required', 'string', 'min:3', 'max:2000'],
        ], [
            'content.required' => 'Введите текст комментария',
            'content.min'      => 'Комментарий слишком короткий',
        ]);

        $news->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Комментарий опубликован');
    }
}
