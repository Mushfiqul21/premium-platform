<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    const TYPE_FREE    = 1;
    const TYPE_PREMIUM = 2;

    const STATUS_DRAFT     = 0;
    const STATUS_PUBLISHED = 1;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'cover_image',
        'type',
        'price',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->slug = Str::slug($post->title) . '-' . uniqid();
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isPremium()
    {
        return $this->type === self::TYPE_PREMIUM;
    }

    public function isFree()
    {
        return $this->type === self::TYPE_FREE;
    }

    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }
}
