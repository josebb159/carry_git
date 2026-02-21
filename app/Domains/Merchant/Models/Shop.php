<?php

namespace App\Domains\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Users\Models\User;

class Shop extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'address', 'contact_person'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parcels()
    {
        return $this->hasMany(Parcel::class);
    }
}
