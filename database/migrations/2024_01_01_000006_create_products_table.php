<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('sku')->unique()->nullable();
            $table->decimal('price_ht', 15, 2);
            $table->decimal('tva_rate', 5, 2)->default(20.00);
            $table->decimal('weight', 8, 2)->default(0.00);
            $table->string('unit')->nullable();
            $table->enum('promo_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('promo_value', 15, 2)->default(0);
            $table->integer('promo_min_qty')->default(1);
            $table->date('promo_start_date')->nullable();
            $table->date('promo_end_date')->nullable();
            $table->boolean('is_refundable')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
