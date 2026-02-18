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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('carrier_id')->nullable()->constrained('carriers');
            $table->foreignId('fleet_id')->nullable()->constrained('fleets');
            $table->foreignId('user_id')->constrained('users'); // Creator
            $table->string('order_number')->unique();
            $table->string('status')->default('pending'); // Enums\OrderStatus
            $table->string('payment_status')->default('pending'); // Enums\PaymentStatus
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('order_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('type'); // pickup, delivery
            $table->string('address');
            $table->string('city');
            $table->string('country');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
