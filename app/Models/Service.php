<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

class Service extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasSlug, InteractsWithMedia, Favoriteable;

    protected $fillable = [
        'title', 'slug', 'description',
        'service_category', 'price', 'price_unit', 'price_negotiable',
        'region', 'city', 'phone', 'user_id',
        'is_approved', 'is_active',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
        'price_negotiable' => 'boolean',
        'price' => 'decimal:2',
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 400, 300)
            ->performOnCollections('photos');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Только одобренные и активные
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true)->where('is_active', true);
    }

    // Список категорий услуг
    public static function categories(): array
    {
        return [
            'Вспашка и обработка почвы',
            'Обрезка деревьев и кустарников',
            'Полив и орошение',
            'Уборка урожая',
            'Посадка и пересадка',
            'Борьба с вредителями',
            'Удобрение и подкормка',
            'Ландшафтный дизайн',
            'Строительство теплиц',
            'Другое',
        ];
    }
}
