<?php

namespace App\Domains\Fleet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;
    protected $fillable = [
        'carrier_id',
        'truck_type',
        'refrigeration_type',
        'capacity_cbm',
        'double_driver',
    ];

    public function carrier()
    {
        return $this->belongsTo(\App\Domains\Carriers\Models\Carrier::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\FleetFactory::new();
    }
}
