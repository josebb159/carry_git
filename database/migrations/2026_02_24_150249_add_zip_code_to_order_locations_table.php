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
        Schema::table('order_locations', function (Blueprint $table) {
            if (!Schema::hasColumn('order_locations', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (!Schema::hasColumn('order_locations', 'zip_code')) {
                $table->string('zip_code')->nullable()->after('state');
            }
            if (!Schema::hasColumn('order_locations', 'sequence')) {
                $table->integer('sequence')->default(1)->after('zip_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_locations', function (Blueprint $table) {
            $table->dropColumn(['state', 'zip_code', 'sequence']);
        });
    }
};
