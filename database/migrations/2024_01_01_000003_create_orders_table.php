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
            $table->string('order_number', 20)->unique(); // CMD-098
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone', 20)->nullable();
            $table->enum('delivery_mode', ['Standard', 'Express', 'Retrait'])->default('Standard');
            $table->text('delivery_address')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('status', ['Nouveau', 'En cours', 'Livré', 'Annulé'])->default('Nouveau');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);    // price at time of order
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
