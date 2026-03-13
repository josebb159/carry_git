<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('carriers', function (Blueprint $table) {
            // 1. Datos de Identificación y Legales
            if (!Schema::hasColumn('carriers', 'company_registration_number')) $table->string('company_registration_number')->nullable();
            if (!Schema::hasColumn('carriers', 'website')) $table->string('website')->nullable();
            if (!Schema::hasColumn('carriers', 'full_address')) $table->text('full_address')->nullable();

            // 2. Documentación Requerida
            if (!Schema::hasColumn('carriers', 'doc_company_registration')) $table->string('doc_company_registration')->nullable();
            if (!Schema::hasColumn('carriers', 'doc_cmr_insurance')) $table->string('doc_cmr_insurance')->nullable();
            if (!Schema::hasColumn('carriers', 'doc_transport_license')) $table->string('doc_transport_license')->nullable();
            if (!Schema::hasColumn('carriers', 'doc_bank_certificate')) $table->string('doc_bank_certificate')->nullable();
            if (!Schema::hasColumn('carriers', 'doc_tax_residence')) $table->string('doc_tax_residence')->nullable();

            // 3. Información Operativa y de Flota
            if (!Schema::hasColumn('carriers', 'fleet_tauliner_count')) $table->integer('fleet_tauliner_count')->default(0);
            if (!Schema::hasColumn('carriers', 'fleet_mega_count')) $table->integer('fleet_mega_count')->default(0);
            if (!Schema::hasColumn('carriers', 'fleet_frigo_count')) $table->integer('fleet_frigo_count')->default(0);
            if (!Schema::hasColumn('carriers', 'fleet_double_deck_count')) $table->integer('fleet_double_deck_count')->default(0);
            if (!Schema::hasColumn('carriers', 'adr_classes')) $table->string('adr_classes')->nullable();
            if (!Schema::hasColumn('carriers', 'xl_certification')) $table->boolean('xl_certification')->default(false);

            // 4. Departamentos de Contacto
            if (!Schema::hasColumn('carriers', 'contact_traffic_email')) $table->string('contact_traffic_email')->nullable();
            if (!Schema::hasColumn('carriers', 'contact_traffic_phone')) $table->string('contact_traffic_phone')->nullable();
            if (!Schema::hasColumn('carriers', 'contact_admin_email')) $table->string('contact_admin_email')->nullable();
            if (!Schema::hasColumn('carriers', 'contact_admin_phone')) $table->string('contact_admin_phone')->nullable();
            if (!Schema::hasColumn('carriers', 'contact_sales_email')) $table->string('contact_sales_email')->nullable();
            if (!Schema::hasColumn('carriers', 'contact_sales_phone')) $table->string('contact_sales_phone')->nullable();

            // 5. Destinos Preferidos (Cargas FTL)
            if (!Schema::hasColumn('carriers', 'preferred_destinations')) $table->json('preferred_destinations')->nullable();

            // 6. Datos Bancarios
            if (!Schema::hasColumn('carriers', 'bank_name')) $table->string('bank_name')->nullable();
            if (!Schema::hasColumn('carriers', 'bank_address')) $table->text('bank_address')->nullable();
            if (!Schema::hasColumn('carriers', 'bank_iban')) $table->string('bank_iban')->nullable();
            if (!Schema::hasColumn('carriers', 'bank_swift')) $table->string('bank_swift')->nullable();

            // 7. Condiciones de Pago
            if (!Schema::hasColumn('carriers', 'accept_e_invoicing')) $table->boolean('accept_e_invoicing')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carriers', function (Blueprint $table) {
            $table->dropColumn([
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
                'accept_e_invoicing'
            ]);
        });
    }
};
