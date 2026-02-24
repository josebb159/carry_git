<?php

namespace App\Domains\Orders\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'uuid',
        'client_id',
        'carrier_id',
        'fleet_id',
        'user_id',
        'order_number',
        'cargo_type',
        'temperature',
        'request_cmr',
        'request_delivery_note',
        'status',
        'payment_status',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'status' => \App\Shared\Enums\OrderStatus::class ,
        'payment_status' => \App\Shared\Enums\PaymentStatus::class ,
        'request_cmr' => 'boolean',
        'request_delivery_note' => 'boolean',
        'temperature' => 'string', // Assuming temperature is stored as a string (e.g., "20°C" or "20.5")
    ];

    public function client()
    {
        return $this->belongsTo(\App\Domains\Clients\Models\Client::class);
    }

    public function carrier()
    {
        return $this->belongsTo(\App\Domains\Carriers\Models\Carrier::class);
    }

    public function locations()
    {
        return $this->hasMany(\App\Domains\Orders\Models\OrderLocation::class);
    }

    public function events()
    {
        return $this->hasMany(\App\Domains\Events\Models\Event::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Domains\Billing\Models\Invoice::class);
    }
}
