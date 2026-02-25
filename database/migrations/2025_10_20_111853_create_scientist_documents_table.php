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
        Schema::create('scientist_documents', function (Blueprint $table) {
            $table->id();
           $table->foreignId('scientist_id')->constrained()->onDelete('cascade');

            // Document Type and Details
            $table->enum('document_type', [
                'cv',
                'hcpc_registration',
                'enhanced_dbs',
                'immunisation_record',
                'bls_certificate',
                'health_safety_certification',
                'professional_indemnity_insurance',
                'avs_cpd_updates',
                'signed_contract'
            ]);

            $table->string('document_name');
            $table->string('document_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('issuing_authority')->nullable();

            // Verification Status
            $table->boolean('is_verified')->default(false);
            $table->text('verification_notes')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            // Ensure each scientist has only one of each document type
            $table->unique(['scientist_id', 'document_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scientist_documents');
    }
};
