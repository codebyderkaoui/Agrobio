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
            $table->foreignId('farm_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');              // Légumes, Fruits, Huiles & Épices, etc.
            $table->decimal('price', 10, 2);
            $table->string('unit', 30);              // kg, pièce, litre, etc.
            $table->enum('stock_status', ['ok', 'low', 'out'])->default('ok');
            $table->integer('stock_quantity')->default(0);
            $table->string('emoji', 10)->default('🌿');
            $table->string('bg_class', 10)->default('bg1'); // bg1..bg8
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
