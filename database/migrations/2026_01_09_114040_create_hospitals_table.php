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
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration_number')->unique();
            $table->year('year_established')->nullable();

            $table->string('official_email');
            $table->string('phone_number');
            $table->string('emergency_contact_number');

            $table->string('website_url')->nullable();
            $table->string('fax_number')->nullable();

            $table->text('address');

            $table->string('administrator_name');
            $table->string('administrator_email');
            $table->string('administrator_phone');

            $table->enum('ownership_type', [
                'government',
                'private',
                'ngo',
                'university'
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
