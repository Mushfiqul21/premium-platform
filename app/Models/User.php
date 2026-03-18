<?php

namespace App\Models;
use App\Models\Payment;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
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
        ];
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function unlockedPosts()
    {
        return $this->belongsToMany(Post::class, 'payments')->wherePivot('status', Payment::STATUS_PAID);
    }

    public function hasUnlocked(Post $post): bool
    {
        return $this->unlockedPosts()->where('post_id', $post->id)->exists();
    }
}
