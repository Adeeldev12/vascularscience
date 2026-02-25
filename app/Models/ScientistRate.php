<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScientistRate extends Model
{
    //
    protected $table = 'scientist_rates';

    protected $fillable = [
        'scientist_id',
        'service_name',
        'rate',
        'applicable_hours',
        'active',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function scientist()
    {
        return $this->belongsTo(Scientist::class);
    }
}
