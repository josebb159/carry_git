<?php

namespace App\Domains\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Users\Models\User;

class Fraud extends Model
{
    protected $fillable = ['user_id', 'tracking_code', 'description', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
