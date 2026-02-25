<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scientist extends Authenticatable
{
    use HasFactory, Notifiable, InteractsWithMedia;

    protected $fillable = [
        // Authentication
        'name', 'email', 'password',

        // Personal Information
        'phone', 'address',

        // Personal Documents
        'cv_path', 'hcpc_registration_path', 'hcpc_issue_date',

        // Compliance & Certificates
        'enhanced_dbs_path', 'enhanced_dbs_issue_date',
        'immunisation_record_path', 'immunisation_issue_date',
        'bls_certificate_path', 'bls_issue_date',
        'health_safety_certification_path', 'health_safety_issue_date',
        'professional_indemnity_insurance_path', 'professional_indemnity_issue_date',
        'avs_cpd_updates_path', 'avs_cpd_issue_date',

        // Bank Details
        'bank_name', 'account_holder_name', 'account_number', 'sort_code',

        // Contract Agreement
        'signed_contract_path', 'contract_issue_date', 'agreed_to_terms',

        // Status
        'is_verified', 'is_active', 'profile_completed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'agreed_to_terms' => 'boolean',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'profile_completed' => 'boolean',

        // Date fields
        'hcpc_issue_date' => 'date',
        'enhanced_dbs_issue_date' => 'date',
        'immunisation_issue_date' => 'date',
        'bls_issue_date' => 'date',
        'health_safety_issue_date' => 'date',
        'professional_indemnity_issue_date' => 'date',
        'avs_cpd_issue_date' => 'date',
        'contract_issue_date' => 'date',
    ];

    // Media Collections for file uploads
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_picture')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);

        // We'll use direct file paths instead of media library for documents
        // to keep things simple and professional
    }

    // Validation rules for registration
    public static function validationRules()
    {
        return [
            // Personal Details
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:scientists,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',

            // Personal Documents
            'cv' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'hcpc_registration' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'hcpc_issue_date' => 'required|date',

            // Compliance & Certificates Documents
            'enhanced_dbs' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'enhanced_dbs_issue_date' => 'required|date',

            'immunisation_record' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'immunisation_issue_date' => 'required|date',

            'bls_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'bls_issue_date' => 'required|date',

            'health_safety_certification' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'health_safety_issue_date' => 'required|date',

            'professional_indemnity_insurance' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'professional_indemnity_issue_date' => 'required|date',

            'avs_cpd_updates' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'avs_cpd_issue_date' => 'required|date',

            // Bank Details
            'bank_name' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|numeric|digits_between:8,17',
            'sort_code' => 'required|numeric|digits:6',

            // Contract Agreement
            'signed_contract' => 'required|file|mimes:pdf|max:10240',
            'contract_issue_date' => 'required|date',
            'agreed_to_terms' => 'required|accepted',
        ];
    }

    // Helper methods to check if documents exist
    public function hasAllDocuments(): bool
    {
        return !empty($this->cv_path) &&
               !empty($this->hcpc_registration_path) &&
               !empty($this->enhanced_dbs_path) &&
               !empty($this->immunisation_record_path) &&
               !empty($this->bls_certificate_path) &&
               !empty($this->health_safety_certification_path) &&
               !empty($this->professional_indemnity_insurance_path) &&
               !empty($this->avs_cpd_updates_path) &&
               !empty($this->signed_contract_path);
    }

}
