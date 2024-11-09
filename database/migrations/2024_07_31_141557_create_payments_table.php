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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Foreign key to orders table
            $table->string('payment_method'); // Payment method (e.g., credit card, PayPal, etc.)
            $table->string('transaction_id')->unique()->nullable(); // Unique transaction ID from payment gateway
            $table->decimal('amount', 10, 2); // Amount paid
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded']); // Payment status
            $table->text('response')->nullable(); // Payment gateway response (optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
