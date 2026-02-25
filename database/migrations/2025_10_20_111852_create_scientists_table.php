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
        Schema::create('scientists', function (Blueprint $table) {
            $table->id();
            // Authentication Fields
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // Personal Information - Section 1
            $table->string('phone')->nullable();
            $table->text('address')->nullable();

            // Bank Details - Section 3
            $table->string('bank_name')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('sort_code')->nullable();

            // Contract Agreement - Section 4
            $table->boolean('agreed_to_terms')->default(false);
            $table->timestamp('agreement_signed_at')->nullable();

            // Status Fields
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('profile_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scientists');
    }
};
