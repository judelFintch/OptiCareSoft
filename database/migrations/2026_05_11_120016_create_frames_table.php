<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frames', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('brand');
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->string('material')->nullable(); // métal, acétate, titane...
            $table->string('category')->nullable(); // homme, femme, enfant, mixte
            $table->string('size')->nullable(); // 52-18-140
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->unsignedInteger('reorder_level')->default(2);
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('brand');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frames');
    }
};
