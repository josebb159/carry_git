<?php

namespace App\Domains\Orders\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLocation extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'address',
        'city',
        'country',
        'latitude',
        'longitude',
        'scheduled_at',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
}
