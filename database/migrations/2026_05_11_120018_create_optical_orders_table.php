<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('optical_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('optical_prescription_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('frame_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('right_lens_id')->nullable()->constrained('lenses')->nullOnDelete();
            $table->foreignId('left_lens_id')->nullable()->constrained('lenses')->nullOnDelete();
            // Adjusted prescription values for this order
            $table->decimal('right_sphere', 6, 2)->nullable();
            $table->decimal('right_cylinder', 6, 2)->nullable();
            $table->unsignedSmallInteger('right_axis')->nullable();
            $table->decimal('right_addition', 6, 2)->nullable();
            $table->decimal('left_sphere', 6, 2)->nullable();
            $table->decimal('left_cylinder', 6, 2)->nullable();
            $table->unsignedSmallInteger('left_axis')->nullable();
            $table->decimal('left_addition', 6, 2)->nullable();
            $table->decimal('pupillary_distance', 5, 1)->nullable();
            $table->text('special_instructions')->nullable();
            $table->decimal('price_frame', 15, 2)->default(0);
            $table->decimal('price_lenses', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->decimal('deposit_paid', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending');
            $table->date('expected_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index(['patient_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('optical_orders');
    }
};
