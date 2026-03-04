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
            $table->string('company_registration_number')->nullable();
            $table->string('website')->nullable();
            $table->text('full_address')->nullable();

            // 2. Documentación Requerida
            $table->string('doc_company_registration')->nullable();
            $table->string('doc_cmr_insurance')->nullable();
            $table->string('doc_transport_license')->nullable();
            $table->string('doc_bank_certificate')->nullable();
            $table->string('doc_tax_residence')->nullable();

            // 3. Información Operativa y de Flota
            $table->integer('fleet_tauliner_count')->default(0);
            $table->integer('fleet_mega_count')->default(0);
            $table->integer('fleet_frigo_count')->default(0);
            $table->integer('fleet_double_deck_count')->default(0);
            $table->string('adr_classes')->nullable();
            $table->boolean('xl_certification')->default(false);

            // 4. Departamentos de Contacto
            $table->string('contact_traffic_email')->nullable();
            $table->string('contact_traffic_phone')->nullable();
            $table->string('contact_admin_email')->nullable();
            $table->string('contact_admin_phone')->nullable();
            $table->string('contact_sales_email')->nullable();
            $table->string('contact_sales_phone')->nullable();

            // 5. Destinos Preferidos (Cargas FTL)
            $table->json('preferred_destinations')->nullable();

            // 6. Datos Bancarios
            $table->string('bank_name')->nullable();
            $table->text('bank_address')->nullable();
            $table->string('bank_iban')->nullable();
            $table->string('bank_swift')->nullable();

            // 7. Condiciones de Pago
            $table->boolean('accept_e_invoicing')->default(false);
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
