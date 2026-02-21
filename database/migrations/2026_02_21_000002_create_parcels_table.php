<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parcels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // merchant
            $table->foreignId('shop_id')->nullable()->constrained('shops')->onDelete('set null');
            $table->string('tracking_code')->unique();
            $table->string('status')->default('pending');
            $table->string('recipient_name');
            $table->string('recipient_phone', 30);
            $table->text('recipient_address');
            $table->decimal('weight', 8, 2)->default(0);
            $table->decimal('cod_amount', 12, 2)->default(0);
            $table->decimal('delivery_charge', 12, 2)->default(0);
            $table->text('note')->nullable();
            $table->json('logs')->nullable(); // status history
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcels');
    }
};
