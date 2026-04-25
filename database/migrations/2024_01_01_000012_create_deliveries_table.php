<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('livreur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('depot_id')->constrained('depots')->onDelete('cascade');
            $table->enum('status', ['pending', 'proposition', 'livrer', 'annuler'])->default('pending');
            $table->boolean('has_substitution')->default(false);
            $table->date('delivery_date')->nullable();
            $table->decimal('total_ht', 15, 2)->default(0.00);
            $table->decimal('total_tva', 15, 2)->default(0.00);
            $table->decimal('total_ttc', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
