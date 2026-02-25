<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScientistDocument extends Model
{
    //
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'scientist_id',
        'document_type',
        'document_name',
        'document_number',
        'issue_date',
        'expiry_date',
        'issuing_authority',
        'is_verified',
        'verification_notes',
        'verified_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function scientist()
    {
        return $this->belongsTo(Scientist::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('document_file')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/jpeg', 'image/png', 'image/gif',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]);
    }

    public function getDocumentUrlAttribute()
    {
        return $this->getFirstMediaUrl('document_file');
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(): bool
    {
        return $this->expiry_date &&
               $this->expiry_date->isFuture() &&
               $this->expiry_date->diffInDays(now()) <= 30;
    }
}
