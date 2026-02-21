<?php

namespace App\Domains\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Users\Models\User;

class PaymentRequest extends Model
{
    protected $fillable = [
        'user_id',
        'payment_account_id',
        'amount',
        'status',
        'note',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentAccount()
    {
        return $this->belongsTo(PaymentAccount::class);
    }
}
