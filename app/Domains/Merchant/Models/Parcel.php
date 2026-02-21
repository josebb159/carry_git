<?php

namespace App\Domains\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Users\Models\User;

class Parcel extends Model
{
    protected $fillable = [
        'user_id',
        'shop_id',
        'tracking_code',
        'status',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'weight',
        'cod_amount',
        'delivery_charge',
        'note',
        'logs',
    ];

    protected $casts = [
        'logs' => 'array',
        'weight' => 'float',
        'cod_amount' => 'float',
        'delivery_charge' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Append a log entry to the parcel's log history
     */
    public function appendLog(string $status, string $note = ''): void
    {
        $logs = $this->logs ?? [];
        $logs[] = [
            'status' => $status,
            'note' => $note,
            'time' => now()->toDateTimeString(),
        ];
        $this->logs = $logs;
        $this->save();
    }
}
