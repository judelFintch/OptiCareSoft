<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_number')->unique();
            $table->foreignId('consultation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->restrictOnDelete();
            $table->text('instructions')->nullable();
            $table->text('notes')->nullable();
            $table->date('valid_until')->nullable();
            $table->timestamps();
        });

        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_prescription_id')->constrained()->cascadeOnDelete();
            $table->string('drug_name');
            $table->string('generic_name')->nullable();
            $table->string('dosage')->nullable();
            $table->string('form')->nullable(); // comprimé, collyre, pommade...
            $table->string('frequency')->nullable(); // 3x/jour
            $table->string('duration')->nullable(); // 7 jours
            $table->string('route')->nullable(); // oral, topique, injectable
            $table->text('instructions')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
        Schema::dropIfExists('medical_prescriptions');
    }
};
