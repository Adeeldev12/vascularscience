<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    //
    protected $fillable = [
        'name',
        'registration_number',
        'year_established',
        'official_email',
        'phone_number',
        'emergency_contact_number',
        'website_url',
        'fax_number',
        'address',
        'administrator_name',
        'administrator_email',
        'administrator_phone',
        'ownership_type',
    ];
}
