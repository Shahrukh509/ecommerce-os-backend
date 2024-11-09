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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Default is unsignedBigInteger
            $table->unsignedBigInteger('category_id'); // Ensure it matches the categories table
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->float('price');
            $table->float('special_price')->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('sku')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->text('description')->nullable();
            $table->text('additional_information')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
