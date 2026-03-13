<?php

namespace App\Domains\Fleet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;
    protected $fillable = [
        'carrier_id',
        'plate',
        'truck_type',
        'refrigeration_type',
        'capacity_cbm',
        'total_owned_trucks',
        'three_axle_trucks',
        'tauliner_trucks',
        'container_chassis',
        'mega_trailers',
        'frigo_trucks',
        'frigo_bitemp_trucks',
        'double_deck_trucks',
        'preferred_destinations',
        'adr_enabled',
        'adr_classes',
        'pallet_exchange',
        'gps_tracking',
        'subcontractors_trucks',
        'double_driver',
        'multimodal_solutions',
        'partial_loads'
    ];

    protected $casts = [
        'preferred_destinations' => 'array',
        'adr_enabled' => 'boolean',
        'pallet_exchange' => 'boolean',
        'gps_tracking' => 'boolean',
        'subcontractors_trucks' => 'boolean',
        'double_driver' => 'boolean',
        'multimodal_solutions' => 'boolean',
        'partial_loads' => 'boolean',
    ];

    public function carrier()
    {
        return $this->belongsTo(\App\Domains\Carriers\Models\Carrier::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\FleetFactory::new ();
    }
}
