<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained('deliveries')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('qty_ordered');
            $table->integer('qty_delivered')->default(0);
            $table->integer('returned_quantity')->default(0);
            $table->boolean('is_substitution')->default(false);
            $table->foreignId('original_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->decimal('unit_price_ht', 15, 2)->nullable();
            $table->enum('promo_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('promo_value', 15, 2)->default(0);
            $table->decimal('tva_rate', 5, 2)->default(20.00);
            $table->decimal('total_ht', 15, 2)->nullable();
            $table->decimal('total_tva', 15, 2)->nullable();
            $table->decimal('total_ttc', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_items');
    }
};
