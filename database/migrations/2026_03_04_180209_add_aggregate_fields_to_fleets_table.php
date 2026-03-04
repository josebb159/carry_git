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
        Schema::table('fleets', function (Blueprint $table) {
            // Datos de la Flota Propia
            $table->integer('total_owned_trucks')->default(0);
            $table->integer('three_axle_trucks')->default(0);
            $table->integer('tauliner_trucks')->default(0);
            $table->integer('container_chassis')->default(0);
            $table->integer('mega_trailers')->default(0);
            $table->integer('frigo_trucks')->default(0);
            $table->integer('frigo_bitemp_trucks')->default(0);
            $table->integer('double_deck_trucks')->default(0);

            // Destinos Preferidos (Cargas Completas - FTL)
            $table->json('preferred_destinations')->nullable();

            // Opciones Operativas Adicionales
            $table->boolean('adr_enabled')->default(false);
            $table->string('adr_classes')->nullable();
            $table->boolean('pallet_exchange')->default(false);
            $table->boolean('gps_tracking')->default(false);
            $table->boolean('subcontractors_trucks')->default(false);
            $table->boolean('multimodal_solutions')->default(false);
            $table->boolean('partial_loads')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fleets', function (Blueprint $table) {
            $table->dropColumn([
                'total_owned_trucks',
                'three_axle_trucks',
                'tauliner_trucks',
                'container_chassis',
                'mega_trailers',
                'frigo_trucks',
                'frigo_bitemp_trucks',
                'double_deck_trucks',
                'preferred_destinations',
                'adr_enabled',
                'adr_classes',
                'pallet_exchange',
                'gps_tracking',
                'subcontractors_trucks',
                'multimodal_solutions',
                'partial_loads'
            ]);
        });
    }
};
