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
        Schema::create('scientist_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scientist_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('service_name');
            $table->string('shift_type'); // Day / Night / Weekend
            $table->decimal('hours', 5, 2);

            $table->decimal('rate_per_hour', 10, 2)->nullable();
            $table->decimal('flat_rate', 10, 2)->nullable();

            $table->string('currency', 5)->default('GBP');
            $table->text('notes')->nullable();

            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scientist_rates');
    }
};
