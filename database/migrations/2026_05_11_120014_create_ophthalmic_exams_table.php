<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ophthalmic_exams', function (Blueprint $table) {
            $table->id();
            $table->string('exam_code')->unique();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('consultation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->restrictOnDelete();
            $table->string('exam_type');
            $table->date('exam_date');
            $table->text('result')->nullable();
            $table->text('interpretation')->nullable();
            $table->string('file_path')->nullable();
            $table->string('status')->default('pending'); // pending, done, reviewed
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'exam_date']);
            $table->index('exam_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ophthalmic_exams');
    }
};
