<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;
use Overtrue\LaravelVote\Traits\Votable;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

class Theme extends Model implements HasMedia
{
    use HasFactory,
        SoftDeletes,
        Searchable,
        HasSlug,
        HasTags,
        InteractsWithMedia,
        Votable,
        Favoriteable;
    protected $fillable = [
        'title', 'slug', 'content', 'user_id', 'category_id',
        'is_pinned', 'is_closed', 'is_approved',
        'views_count', 'comments_count', 'last_activity_at',
    ];
    protected $casts = [
        'is_pinned' => 'boolean',
        'is_closed' => 'boolean',
        'is_approved' => 'boolean',
        'views_count' => 'integer',
        'comments_count' => 'integer',
        'last_activity_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Что индексируется для умного поиска
    #[SearchUsingFullText(['title', 'content'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }

    // Только одобренные темы попадают в поиск
    public function shouldBeSearchable(): bool
    {
        return $this->is_approved && ! $this->trashed();
    }

    // Прикреплённые изображения
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 400, 300)
            ->performOnCollections('attachments');
    }

    // Связи
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Лучший ответ
    public function bestAnswer()
    {
        return $this->hasOne(Post::class)->where('is_best_answer', true);
    }

    // Скоупы для сортировки и фильтрации
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('is_pinned', 'desc')
            ->orderBy('views_count', 'desc');
    }

    public function scopeMostActive($query)
    {
        return $query->orderBy('is_pinned', 'desc')
            ->orderBy('last_activity_at', 'desc');
    }
}
