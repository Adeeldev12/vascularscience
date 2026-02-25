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
        Schema::table('scientists', function (Blueprint $table) {
            // Personal Documents
            $table->string('cv_path')->nullable();
            $table->string('hcpc_registration_path')->nullable();
            $table->date('hcpc_issue_date')->nullable();

            // Compliance & Certificates Documents
            $table->string('enhanced_dbs_path')->nullable();
            $table->date('enhanced_dbs_issue_date')->nullable();

            $table->string('immunisation_record_path')->nullable();
            $table->date('immunisation_issue_date')->nullable();

            $table->string('bls_certificate_path')->nullable();
            $table->date('bls_issue_date')->nullable();

            $table->string('health_safety_certification_path')->nullable();
            $table->date('health_safety_issue_date')->nullable();

            $table->string('professional_indemnity_insurance_path')->nullable();
            $table->date('professional_indemnity_issue_date')->nullable();

            $table->string('avs_cpd_updates_path')->nullable();
            $table->date('avs_cpd_issue_date')->nullable();

            // Contract Agreement
            $table->string('signed_contract_path')->nullable();
            $table->date('contract_issue_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scientists', function (Blueprint $table) {
            // Personal Documents
            $table->dropColumn('cv_path');
            $table->dropColumn('hcpc_registration_path');
            $table->dropColumn('hcpc_issue_date');

            // Compliance & Certificates Documents
            $table->dropColumn('enhanced_dbs_path');
            $table->dropColumn('enhanced_dbs_issue_date');

            $table->dropColumn('immunisation_record_path');
            $table->dropColumn('immunisation_issue_date');

            $table->dropColumn('bls_certificate_path');
            $table->dropColumn('bls_issue_date');

            $table->dropColumn('health_safety_certification_path');
            $table->dropColumn('health_safety_issue_date');

            $table->dropColumn('professional_indemnity_insurance_path');
            $table->dropColumn('professional_indemnity_issue_date');

            $table->dropColumn('avs_cpd_updates_path');
            $table->dropColumn('avs_cpd_issue_date');

            // Contract Agreement
            $table->dropColumn('signed_contract_path');
            $table->dropColumn('contract_issue_date');
        });
    }
};
