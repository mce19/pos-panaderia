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
            $table->string('sku', 25)->nullable();
            $table->string('name', 60);
            $table->text('description')->nullable();
            $table->enum('type', ['service', 'physical'])->default('physical');
            $table->enum('status', ['available', 'out_of_stock'])->default('available');
            $table->decimal('cost', 10, 2);
            $table->decimal('price', 10, 2);
            $table->tinyInteger('manage_stock')->default(1);
            $table->integer('stock_qty');
            $table->integer('low_stock');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('category_id')->constrained('categories');
            $table->softDeletes();
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
