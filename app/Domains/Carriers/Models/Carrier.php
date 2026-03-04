<?php

namespace App\Domains\Carriers\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'vat_number',
        'transport_license',
        'insurance_policy',
        'gps_tracking',
        'adr_enabled',
        'pallet_exchange',
        'payment_terms_days',
        'company_registration_number',
        'website',
        'full_address',
        'doc_company_registration',
        'doc_cmr_insurance',
        'doc_transport_license',
        'doc_bank_certificate',
        'doc_tax_residence',
        'fleet_tauliner_count',
        'fleet_mega_count',
        'fleet_frigo_count',
        'fleet_double_deck_count',
        'adr_classes',
        'xl_certification',
        'contact_traffic_email',
        'contact_traffic_phone',
        'contact_admin_email',
        'contact_admin_phone',
        'contact_sales_email',
        'contact_sales_phone',
        'preferred_destinations',
        'bank_name',
        'bank_address',
        'bank_iban',
        'bank_swift',
        'accept_e_invoicing',
    ];

    public function fleets()
    {
        return $this->hasMany(\App\Domains\Fleet\Models\Fleet::class);
    }

    protected $casts = [
        'preferred_destinations' => 'array',
        'gps_tracking' => 'boolean',
        'adr_enabled' => 'boolean',
        'pallet_exchange' => 'boolean',
        'xl_certification' => 'boolean',
        'accept_e_invoicing' => 'boolean',
    ];

    protected static function newFactory()
    {
        return \Database\Factories\CarrierFactory::new ();
    }
}
