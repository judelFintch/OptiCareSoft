<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eye_pressures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->decimal('right_eye_pressure', 5, 1)->nullable(); // mmHg
            $table->decimal('left_eye_pressure', 5, 1)->nullable();
            $table->string('measurement_method')->nullable(); // non-contact, Goldman, iCare
            $table->timestamp('measured_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eye_pressures');
    }
};
