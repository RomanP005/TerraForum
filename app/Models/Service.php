<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Overtrue\LaravelVote\Traits\Votable;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

class Service extends Model implements HasMedia
{
    use SoftDeletes,
        Searchable,
        HasSlug,
        HasTags,
        InteractsWithMedia,
        Votable,
        Favoriteable;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'user_id',
        'service_category',
        'price',
        'price_unit',
        'price_negotiable',
        'region',
        'city',
        'contact_phone',
        'contact_email',
        'is_active',
        'is_approved',
        'expires_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_negotiable' => 'boolean',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    #[SearchUsingFullText(['title', 'description'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'region' => $this->region,
            'service_category' => $this->service_category,
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 300, 300)
            ->performOnCollections('images');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Отзывы со звёздами (через Comment с rating_value)
    public function reviews()
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNotNull('rating_value');
    }

    // Средний рейтинг услуги
    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating_value') ?? 0, 1);
    }
}
