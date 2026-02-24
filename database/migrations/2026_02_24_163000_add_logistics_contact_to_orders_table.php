<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('logistics_contact_name')->nullable()->after('user_id');
            $table->string('logistics_contact_email')->nullable()->after('logistics_contact_name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['logistics_contact_name', 'logistics_contact_email']);
        });
    }
};
