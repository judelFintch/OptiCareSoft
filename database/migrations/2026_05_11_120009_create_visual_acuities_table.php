<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visual_acuities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            // Distance vision without correction
            $table->string('right_eye_sc')->nullable(); // sc = sine correctione
            $table->string('left_eye_sc')->nullable();
            // Distance vision with correction
            $table->string('right_eye_cc')->nullable(); // cc = cum correctione
            $table->string('left_eye_cc')->nullable();
            // Near vision without correction
            $table->string('near_right_sc')->nullable();
            $table->string('near_left_sc')->nullable();
            // Near vision with correction
            $table->string('near_right_cc')->nullable();
            $table->string('near_left_cc')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visual_acuities');
    }
};
