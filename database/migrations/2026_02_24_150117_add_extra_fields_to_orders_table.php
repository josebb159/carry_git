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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'cargo_type')) {
                $table->string('cargo_type')->nullable();
            }
            if (!Schema::hasColumn('orders', 'request_cmr')) {
                $table->boolean('request_cmr')->default(false);
            }
            if (!Schema::hasColumn('orders', 'request_delivery_note')) {
                $table->boolean('request_delivery_note')->default(false);
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $colsToDrop = [];
            if (Schema::hasColumn('orders', 'cargo_type')) $colsToDrop[] = 'cargo_type';
            if (Schema::hasColumn('orders', 'request_cmr')) $colsToDrop[] = 'request_cmr';
            if (Schema::hasColumn('orders', 'request_delivery_note')) $colsToDrop[] = 'request_delivery_note';
            if (Schema::hasColumn('orders', 'notes')) $colsToDrop[] = 'notes';
            
            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
