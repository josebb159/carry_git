<?php

namespace App\Domains\Fleet\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $table = 'fleet_tracking';

    public $timestamps = false; // We use created_at only

    protected $fillable = [
        'user_id',
        'carrier_id',
        'order_uuid',
        'lat',
        'lng',
        'speed',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Domains\Users\Models\User::class);
    }
}
