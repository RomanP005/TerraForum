<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Overtrue\LaravelVote\Traits\Votable;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

class Post extends Model implements HasMedia
{
    use SoftDeletes,
        Searchable,
        InteractsWithMedia,
        Votable,
        Favoriteable;

    protected $fillable = [
        'content',
        'user_id',
        'theme_id',
        'parent_post_id',
        'is_best_answer',
        'is_approved',
        'is_edited',
    ];
    /**
     * @var string[] 
     */
    protected $casts = [
        'is_best_answer' => 'boolean',
        'is_approved' => 'boolean',
        'is_edited' => 'boolean',
    ];

    // Поиск по сообщениям
    #[SearchUsingFullText(['content'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
        ];
    }

    // Прикрепления (фото к сообщениям)
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    // Связи
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function parent()
    {
        return $this->belongsTo(Post::class, 'parent_post_id');
    }

    public function replies()
    {
        return $this->hasMany(Post::class, 'parent_post_id');
    }
}
