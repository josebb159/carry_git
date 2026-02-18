<?php

namespace App\Domains\Events\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'uuid',
        'order_id',
        'user_id',
        'type',
        'description',
        'location',
        'meta_data',
        'proof_url',
    ];

    protected $casts = [
        'type' => \App\Shared\Enums\EventType::class ,
        'location' => 'array',
        'meta_data' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(\App\Domains\Orders\Models\Order::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Domains\Users\Models\User::class);
    }
}
