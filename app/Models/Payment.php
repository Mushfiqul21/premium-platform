<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    const METHOD_STRIPE     = 1;
    const METHOD_SSLCOMMERZ = 2;

    const STATUS_PENDING = 0;
    const STATUS_PAID    = 1;
    const STATUS_FAILED  = 2;

    protected $fillable = [
        'user_id',
        'post_id',
        'amount',
        'method',
        'status',
        'transaction_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function isPaid()
    {
        return $this->status === self::STATUS_PAID;
    }
}
