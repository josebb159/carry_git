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
            if (!Schema::hasColumn('fleets', 'double_driver')) {
                $table->boolean('double_driver')->default(false)->after('pallet_exchange');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fleets', function (Blueprint $table) {
            if (Schema::hasColumn('fleets', 'double_driver')) {
                $table->dropColumn('double_driver');
            }
        });
    }
};
