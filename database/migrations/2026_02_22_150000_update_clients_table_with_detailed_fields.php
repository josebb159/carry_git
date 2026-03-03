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
            if (!Schema::hasColumn('clients', 'activity_category')) {
                $table->string('activity_category')->nullable();
            }

            // Fiscal Data
            if (!Schema::hasColumn('clients', 'commercial_registry_number')) {
                $table->string('commercial_registry_number')->nullable();
            }
            if (!Schema::hasColumn('clients', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('clients', 'city')) {
                $table->string('city', 100)->nullable();
            }
            if (!Schema::hasColumn('clients', 'state')) {
                $table->string('state', 100)->nullable();
            }
            if (!Schema::hasColumn('clients', 'zip_code')) {
                $table->string('zip_code', 20)->nullable();
            }

            // Correspondence Address
            if (!Schema::hasColumn('clients', 'shipping_address')) {
                $table->string('shipping_address')->nullable();
            }
            if (!Schema::hasColumn('clients', 'shipping_city')) {
                $table->string('shipping_city', 100)->nullable();
            }
            if (!Schema::hasColumn('clients', 'shipping_country')) {
                $table->string('shipping_country')->nullable();
            }
            if (!Schema::hasColumn('clients', 'shipping_zip_code')) {
                $table->string('shipping_zip_code', 20)->nullable();
            }
            if (!Schema::hasColumn('clients', 'shipping_email')) {
                $table->string('shipping_email')->nullable();
            }

            // Contact Management - Billing
            if (!Schema::hasColumn('clients', 'billing_contact_name')) {
                $table->string('billing_contact_name')->nullable();
            }
            if (!Schema::hasColumn('clients', 'billing_contact_phone')) {
                $table->string('billing_contact_phone')->nullable();
            }
            if (!Schema::hasColumn('clients', 'billing_contact_email')) {
                $table->string('billing_contact_email')->nullable();
            }

            // Contact Management - Logistics
            if (!Schema::hasColumn('clients', 'logistics_contact_name')) {
                $table->string('logistics_contact_name')->nullable();
            }
            if (!Schema::hasColumn('clients', 'logistics_contact_role')) {
                $table->string('logistics_contact_role')->nullable();
            }
            if (!Schema::hasColumn('clients', 'logistics_contact_phone')) {
                $table->string('logistics_contact_phone')->nullable();
            }
            if (!Schema::hasColumn('clients', 'logistics_contact_email')) {
                $table->string('logistics_contact_email')->nullable();
            }

            // Contact Management - Emergencies
            if (!Schema::hasColumn('clients', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable();
            }
            if (!Schema::hasColumn('clients', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable();
            }
            if (!Schema::hasColumn('clients', 'emergency_contact_email')) {
                $table->string('emergency_contact_email')->nullable();
            }

            // Billing Procedure
            if (!Schema::hasColumn('clients', 'billing_procedure')) {
                $table->string('billing_procedure')->nullable(); // Factura por viaje, Autofacturación, Otros
            }

            // Document Management
            if (!Schema::hasColumn('clients', 'doc_invoice_email')) {
                $table->boolean('doc_invoice_email')->default(false);
            }
            if (!Schema::hasColumn('clients', 'doc_invoice_postal')) {
                $table->boolean('doc_invoice_postal')->default(false);
            }
            if (!Schema::hasColumn('clients', 'doc_cmr_email')) {
                $table->boolean('doc_cmr_email')->default(false);
            }
            if (!Schema::hasColumn('clients', 'doc_cmr_postal')) {
                $table->boolean('doc_cmr_postal')->default(false);
            }
            if (!Schema::hasColumn('clients', 'doc_delivery_note_email')) {
                $table->boolean('doc_delivery_note_email')->default(false);
            }
            if (!Schema::hasColumn('clients', 'doc_delivery_note_postal')) {
                $table->boolean('doc_delivery_note_postal')->default(false);
            }
            if (!Schema::hasColumn('clients', 'doc_temp_report_email')) {
                $table->boolean('doc_temp_report_email')->default(false);
            }
            if (!Schema::hasColumn('clients', 'doc_temp_report_postal')) {
                $table->boolean('doc_temp_report_postal')->default(false);
            }

            // Signature
            if (!Schema::hasColumn('clients', 'filled_by_name')) {
                $table->string('filled_by_name')->nullable();
            }
            if (!Schema::hasColumn('clients', 'filled_by_role')) {
                $table->string('filled_by_role')->nullable();
            }
            if (!Schema::hasColumn('clients', 'filled_by_phone')) {
                $table->string('filled_by_phone')->nullable();
            }
            if (!Schema::hasColumn('clients', 'filled_by_date')) {
                $table->date('filled_by_date')->nullable();
            }
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
