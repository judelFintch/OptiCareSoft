<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('consultation_code')->unique();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('visit_id')->constrained()->restrictOnDelete();
            $table->string('chief_complaint');
            $table->text('history_of_present_illness')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('ophthalmic_history')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('clinical_findings')->nullable();
            $table->string('primary_diagnosis')->nullable();
            $table->json('secondary_diagnoses')->nullable();
            $table->string('icd_code')->nullable();
            $table->text('treatment_plan')->nullable();
            $table->text('recommendations')->nullable();
            $table->date('next_appointment_date')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'created_at']);
            $table->index(['doctor_id', 'created_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
