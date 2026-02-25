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
        Schema::table('availabilities', function (Blueprint $table) {
            //
            // Drop the existing foreign key first
            $table->dropForeign(['scientist_id']);

            // Re-add it, referencing the `scientists` table instead of `users`
            $table->foreign('scientist_id')
                ->references('id')
                ->on('scientists')
                ->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            //
             // Rollback: point it back to `users` if you ever rollback the migration
            $table->dropForeign(['scientist_id']);
            $table->foreign('scientist_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};
