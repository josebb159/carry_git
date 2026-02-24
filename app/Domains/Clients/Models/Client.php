<?php

namespace App\Domains\Clients\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'legal_name',
        'vat_number',
        'commercial_registry_number',
        'country',
        'activity_category',
        'economic_activity',
        'address',
        'city',
        'state',
        'zip_code',
        'shipping_address',
        'shipping_city',
        'shipping_country',
        'shipping_zip_code',
        'shipping_email',
        'billing_contact_name',
        'billing_contact_phone',
        'billing_contact_email',
        'logistics_contact_name',
        'logistics_contact_role',
        'logistics_contact_phone',
        'logistics_contact_email',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_email',
        'payment_terms_days',
        'payment_conditions',
        'billing_procedure',
        'doc_invoice_email',
        'doc_invoice_postal',
        'doc_cmr_email',
        'doc_cmr_postal',
        'doc_delivery_note_email',
        'doc_delivery_note_postal',
        'doc_temp_report_email',
        'doc_temp_report_postal',
        'filled_by_name',
        'filled_by_role',
        'filled_by_phone',
        'filled_by_date',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Domains\Users\Models\User::class);
    }
    public function orders()
    {
        return $this->hasMany(\App\Domains\Orders\Models\Order::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\ClientFactory::new ();
    }
}
