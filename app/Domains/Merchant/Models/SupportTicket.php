<?php

namespace App\Domains\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Users\Models\User;

class SupportTicket extends Model
{
    protected $fillable = ['user_id', 'subject', 'message', 'status'];

    protected $table = 'support_tickets';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
