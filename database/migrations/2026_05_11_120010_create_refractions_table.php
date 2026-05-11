<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refractions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            // Right eye (OD)
            $table->decimal('right_sphere', 6, 2)->nullable();
            $table->decimal('right_cylinder', 6, 2)->nullable();
            $table->unsignedSmallInteger('right_axis')->nullable();
            $table->decimal('right_addition', 6, 2)->nullable();
            // Left eye (OG)
            $table->decimal('left_sphere', 6, 2)->nullable();
            $table->decimal('left_cylinder', 6, 2)->nullable();
            $table->unsignedSmallInteger('left_axis')->nullable();
            $table->decimal('left_addition', 6, 2)->nullable();
            // Pupillary distance
            $table->decimal('pd_right', 5, 1)->nullable();
            $table->decimal('pd_left', 5, 1)->nullable();
            $table->string('lens_type')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refractions');
    }
};
