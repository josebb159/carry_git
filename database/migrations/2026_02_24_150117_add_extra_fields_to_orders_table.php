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
                $table->string('cargo_type')->nullable()->after('order_number');
            }
            if (!Schema::hasColumn('orders', 'request_cmr')) {
                $table->boolean('request_cmr')->default(false)->after('cargo_type');
            }
            if (!Schema::hasColumn('orders', 'request_delivery_note')) {
                $table->boolean('request_delivery_note')->default(false)->after('request_cmr');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('request_delivery_note');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['cargo_type', 'request_cmr', 'request_delivery_note', 'notes']);
        });
    }
};
