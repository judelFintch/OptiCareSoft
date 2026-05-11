<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->morphs('stockable'); // pharmacy_product, frame, lens
            $table->string('movement_type'); // in, out, adjustment, loss, return
            $table->integer('quantity'); // positive or negative
            $table->unsignedInteger('stock_before');
            $table->unsignedInteger('stock_after');
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->string('reference')->nullable(); // bon livraison, numéro prescription
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
