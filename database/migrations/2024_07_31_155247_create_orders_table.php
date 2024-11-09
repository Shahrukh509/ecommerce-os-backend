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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('checkout_id')->constrained('checkouts')->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->string('shipping_address');
            $table->string('billing_address');
            $table->string('payment_method');
            $table->enum('payment_status', ['pending', 'completed', 'refunded']);
            $table->enum('order_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled']);
            $table->string('shipping_method');
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
