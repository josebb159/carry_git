<?php

namespace App\Domains\Carriers\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'vat_number',
        'transport_license',
        'insurance_policy',
        'gps_tracking',
        'adr_enabled',
        'pallet_exchange',
        'payment_terms_days',
    ];

    public function fleets()
    {
        return $this->hasMany(\App\Domains\Fleet\Models\Fleet::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\CarrierFactory::new();
    }
}
