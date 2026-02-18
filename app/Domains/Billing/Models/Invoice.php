<?php

namespace App\Domains\Billing\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'uuid',
        'client_id',
        'order_id',
        'invoice_number',
        'date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'status' => \App\Shared\Enums\InvoiceStatus::class,
    ];

    public function client()
    {
        return $this->belongsTo(\App\Domains\Clients\Models\Client::class);
    }

    public function order()
    {
        return $this->belongsTo(\App\Domains\Orders\Models\Order::class);
    }

    public function lines()
    {
        return $this->hasMany(\App\Domains\Billing\Models\InvoiceLine::class);
    }
}
