<?php

namespace App\Domains\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Users\Models\User;

class PaymentAccount extends Model
{
    protected $fillable = [
        'user_id',
        'account_type',
        'account_name',
        'account_number',
        'bank_name',
        'branch',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentRequests()
    {
        return $this->hasMany(PaymentRequest::class);
    }
}
