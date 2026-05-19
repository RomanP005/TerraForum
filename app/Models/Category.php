<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'color',
        'parent_id', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // Маршрутизация по slug вместо id
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Родительская категория
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Подкатегории
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    // Темы в этой категории
    public function themes()
    {
        return $this->hasMany(Theme::class);
    }

    // Только активные категории
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Только корневые (без родителя)
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
