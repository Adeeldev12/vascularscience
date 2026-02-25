<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hospital_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('scientist_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->date('shift_date');
            $table->time('shift_start');
            $table->time('shift_end');

            // dynamic rate (per shift)
            $table->decimal('hourly_rate', 10, 2)->nullable();

            // execution
            $table->time('actual_start')->nullable();
            $table->time('actual_end')->nullable();

            $table->decimal('worked_hours', 6, 2)->nullable();
            $table->decimal('total_pay', 10, 2)->nullable();

            $table->enum('status', [
                'empty',
                'assigned',
                'completed',
                'cancelled',
            ])->default('empty');

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_shifts');
    }
};
