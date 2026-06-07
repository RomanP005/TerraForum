<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Overtrue\LaravelFavorite\Traits\Favoriter;
use Overtrue\LaravelVote\Traits\Voter;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;


class User extends Authenticatable implements HasMedia, FilamentUser
{
    use HasFactory,
        Notifiable,
        HasApiTokens,
        HasRoles,
        InteractsWithMedia,
        Voter,
        Favoriter;
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'region',
        'rating',
        'news_subscribed',
        'login_attempts',
        'locked_until',
        'last_login_at',
        'last_login_ip',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'news_subscribed'   => 'boolean',
            'locked_until' => 'datetime',
            'last_login_at' => 'datetime',
            'rating' => 'integer',
            'login_attempts' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }


    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 64, 64)
            ->performOnCollections('avatar');

        $this->addMediaConversion('preview')
            ->fit(Fit::Crop, 200, 200)
            ->performOnCollections('avatar');
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }


    public function getAvatarUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('avatar', 'thumb')
            ?: 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=4d7c0f&color=fff';
    }
    
    public function themes()
    {
        return $this->hasMany(\App\Models\Theme::class);
    }

    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    public function services()
    {
        return $this->hasMany(\App\Models\Service::class);
    }
}
