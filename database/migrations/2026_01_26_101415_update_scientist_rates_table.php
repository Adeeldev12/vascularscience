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
        //
       Schema::table('scientist_rates', function (Blueprint $table) {

            // ❌ Remove unwanted columns
            $table->dropColumn([
                'shift_type',
                'hours',
                'rate_per_hour',
                'flat_rate',
                'currency',
                'notes',
            ]);

            // ✅ Add required columns
            $table->decimal('rate', 10, 2)->after('service_name');

            $table->text('applicable_hours')
                ->after('rate')
                ->comment('Human readable hours e.g. Mon–Fri 9am–1pm');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('scientist_rates', function (Blueprint $table) {

            // rollback added fields
            $table->dropColumn(['rate', 'applicable_hours']);

            // rollback removed fields
            $table->string('shift_type')->nullable();
            $table->integer('hours')->nullable();
            $table->decimal('rate_per_hour', 10, 2)->nullable();
            $table->decimal('flat_rate', 10, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->text('notes')->nullable();
        });
    }
};
