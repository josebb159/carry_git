<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Activity Segmentation
            $table->string('activity_category')->nullable()->after('economic_activity');

            // Fiscal Data
            $table->string('commercial_registry_number')->nullable()->after('vat_number');
            $table->string('address')->nullable()->after('commercial_registry_number');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('state', 100)->nullable()->after('city');
            $table->string('zip_code', 20)->nullable()->after('state');

            // Correspondence Address
            $table->string('shipping_address')->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_zip_code', 20)->nullable();
            $table->string('shipping_email')->nullable();

            // Contact Management - Billing
            $table->string('billing_contact_name')->nullable();
            $table->string('billing_contact_phone')->nullable();
            $table->string('billing_contact_email')->nullable();

            // Contact Management - Logistics
            $table->string('logistics_contact_name')->nullable();
            $table->string('logistics_contact_role')->nullable();
            $table->string('logistics_contact_phone')->nullable();
            $table->string('logistics_contact_email')->nullable();

            // Contact Management - Emergencies
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_email')->nullable();

            // Billing Procedure
            $table->string('billing_procedure')->nullable(); // Factura por viaje, Autofacturación, Otros

            // Document Management
            $table->boolean('doc_invoice_email')->default(false);
            $table->boolean('doc_invoice_postal')->default(false);
            $table->boolean('doc_cmr_email')->default(false);
            $table->boolean('doc_cmr_postal')->default(false);
            $table->boolean('doc_delivery_note_email')->default(false);
            $table->boolean('doc_delivery_note_postal')->default(false);
            $table->boolean('doc_temp_report_email')->default(false);
            $table->boolean('doc_temp_report_postal')->default(false);

            // Signature
            $table->string('filled_by_name')->nullable();
            $table->string('filled_by_role')->nullable();
            $table->string('filled_by_phone')->nullable();
            $table->date('filled_by_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'activity_category',
                'commercial_registry_number',
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
                'filled_by_date'
            ]);
        });
    }
};
