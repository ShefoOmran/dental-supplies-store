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
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->string('sku')->nullable();
            $table->string('brand')->nullable();
            $table->text('description')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('name');
            $table->index('slug');
            $table->index('category_id');
            $table->index('price');
            $table->index('featured');
            $table->index('created_at');
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
