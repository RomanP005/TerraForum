<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelVote\Traits\Votable;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

class Comment extends Model
{
    use SoftDeletes,
        Votable,
        Favoriteable;

    protected $fillable = [
        'content',
        'user_id',
        'commentable_type',
        'commentable_id',
        'parent_id',
        'rating_value',
        'is_approved',
        'is_edited',
    ];

    protected $casts = [
        'rating_value' => 'integer',
        'is_approved' => 'boolean',
        'is_edited' => 'boolean',
    ];

    // Полиморфная связь — к чему относится комментарий
    public function commentable()
    {
        return $this->morphTo();
    }

    // Автор
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Древовидные ответы
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // Скоупы
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeReviews($query)
    {
        return $query->whereNotNull('rating_value');
    }
}
