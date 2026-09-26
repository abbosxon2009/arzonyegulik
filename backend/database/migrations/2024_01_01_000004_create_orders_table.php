<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'on_the_way', 'delivered', 'cancelled'])->default('pending');
            $table->integer('total_amount');
            $table->integer('delivery_fee')->default(0);
            $table->integer('discount')->default(0);
            $table->string('payment_method')->nullable();
            $table->text('delivery_address');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
