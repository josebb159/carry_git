<?php

namespace App\Domains\Clients\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'legal_name',
        'vat_number',
        'country',
        'economic_activity',
        'payment_terms_days',
        'payment_conditions',
    ];
    public function orders()
    {
        return $this->hasMany(\App\Domains\Orders\Models\Order::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\ClientFactory::new();
    }
}
