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
            if (!Schema::hasColumn('fleets', 'total_owned_trucks')) $table->integer('total_owned_trucks')->default(0);
            if (!Schema::hasColumn('fleets', 'three_axle_trucks')) $table->integer('three_axle_trucks')->default(0);
            if (!Schema::hasColumn('fleets', 'tauliner_trucks')) $table->integer('tauliner_trucks')->default(0);
            if (!Schema::hasColumn('fleets', 'container_chassis')) $table->integer('container_chassis')->default(0);
            if (!Schema::hasColumn('fleets', 'mega_trailers')) $table->integer('mega_trailers')->default(0);
            if (!Schema::hasColumn('fleets', 'frigo_trucks')) $table->integer('frigo_trucks')->default(0);
            if (!Schema::hasColumn('fleets', 'frigo_bitemp_trucks')) $table->integer('frigo_bitemp_trucks')->default(0);
            if (!Schema::hasColumn('fleets', 'double_deck_trucks')) $table->integer('double_deck_trucks')->default(0);

            // Destinos Preferidos (Cargas Completas - FTL)
            if (!Schema::hasColumn('fleets', 'preferred_destinations')) $table->json('preferred_destinations')->nullable();

            // Opciones Operativas Adicionales
            if (!Schema::hasColumn('fleets', 'adr_enabled')) $table->boolean('adr_enabled')->default(false);
            if (!Schema::hasColumn('fleets', 'adr_classes')) $table->string('adr_classes')->nullable();
            if (!Schema::hasColumn('fleets', 'pallet_exchange')) $table->boolean('pallet_exchange')->default(false);
            if (!Schema::hasColumn('fleets', 'gps_tracking')) $table->boolean('gps_tracking')->default(false);
            if (!Schema::hasColumn('fleets', 'subcontractors_trucks')) $table->boolean('subcontractors_trucks')->default(false);
            if (!Schema::hasColumn('fleets', 'multimodal_solutions')) $table->boolean('multimodal_solutions')->default(false);
            if (!Schema::hasColumn('fleets', 'partial_loads')) $table->boolean('partial_loads')->default(false);
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
