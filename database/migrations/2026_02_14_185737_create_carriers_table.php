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
        Schema::create('carriers', function (Blueprint $table) {
            $table->id();
            $table->string('legal_name');
            $table->string('tax_id')->unique();
            $table->string('transport_license')->nullable();
            $table->string('insurance_policy')->nullable();
            $table->boolean('gps_tracking')->default(false);
            $table->boolean('adr_enabled')->default(false);
            $table->boolean('pallet_exchange')->default(false);
            $table->integer('payment_terms_days')->default(30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carriers');
    }
};
