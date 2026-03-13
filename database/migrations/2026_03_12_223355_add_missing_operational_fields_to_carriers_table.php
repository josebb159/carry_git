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
            if (!Schema::hasColumn('carriers', 'transport_license')) {
                $table->string('transport_license')->nullable();
            }
            if (!Schema::hasColumn('carriers', 'insurance_policy')) {
                $table->string('insurance_policy')->nullable();
            }
            if (!Schema::hasColumn('carriers', 'gps_tracking')) {
                $table->boolean('gps_tracking')->default(false);
            }
            if (!Schema::hasColumn('carriers', 'adr_enabled')) {
                $table->boolean('adr_enabled')->default(false);
            }
            if (!Schema::hasColumn('carriers', 'pallet_exchange')) {
                $table->boolean('pallet_exchange')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carriers', function (Blueprint $table) {
            $table->dropColumn([
                'transport_license',
                'insurance_policy',
                'gps_tracking',
                'adr_enabled',
                'pallet_exchange'
            ]);
        });
    }
};
