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
            // 1. Asegurar que double_driver existe
            if (!Schema::hasColumn('fleets', 'double_driver')) {
                $table->boolean('double_driver')->default(false);
            }

            // 2. Hacer la placa/matricula opcional para permitir datos agregados
            $table->string('plate')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fleets', function (Blueprint $table) {
            $table->string('plate')->nullable(false)->change();
            if (Schema::hasColumn('fleets', 'double_driver')) {
                $table->dropColumn('double_driver');
            }
        });
    }
};
