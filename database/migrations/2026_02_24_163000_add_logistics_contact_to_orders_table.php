<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'logistics_contact_name')) {
                $table->string('logistics_contact_name')->nullable();
            }
            if (!Schema::hasColumn('orders', 'logistics_contact_email')) {
                $table->string('logistics_contact_email')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $colsToDrop = [];
            if (Schema::hasColumn('orders', 'logistics_contact_name')) $colsToDrop[] = 'logistics_contact_name';
            if (Schema::hasColumn('orders', 'logistics_contact_email')) $colsToDrop[] = 'logistics_contact_email';
            
            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
