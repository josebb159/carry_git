<?php

namespace App\Domains\Merchant\Models;

use Illuminate\Database\Eloquent\Model;

class NewsOffer extends Model
{
    protected $fillable = ['title', 'body', 'image_url', 'type', 'published_at'];

    protected $table = 'news_offers';

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
