<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class HospitalShift extends Model
{
    //

    protected $fillable = [
        'hospital_id',
        'scientist_id',
        'shift_date',
        'scientist_rate_id',
        'hourly_rate',
        'actual_start',
        'actual_end',
        'worked_hours',
        'total_pay',
        'status',
        'notes',
    ];

    // protected $casts = [
    //     'shift_date' => 'date',
    //     'shift_start' => 'datetime:H:i',
    //     'shift_end' => 'datetime:H:i',
    //     'actual_start' => 'datetime:H:i',
    //     'actual_end' => 'datetime:H:i',
    //     'hourly_rate' => 'decimal:2',
    //     'worked_hours' => 'decimal:2',
    //     'total_pay' => 'decimal:2',
    // ];
    protected $casts = [
    'shift_date' => 'date',
    'hourly_rate' => 'decimal:2',
    'worked_hours' => 'decimal:2',
    'total_pay' => 'decimal:2',
];


    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function scientist()
    {
        return $this->belongsTo(Scientist::class);
    }

    public function scientistRate()
{
    return $this->belongsTo(ScientistRate::class);
}

    protected static function booted()
{
    static::saving(function ($shift) {

        /*
        |--------------------------------------------------------------------------
        | STATUS HANDLING
        |--------------------------------------------------------------------------
        */

        // No scientist → empty shift
        if (is_null($shift->scientist_id)) {
            $shift->status = 'empty';
            $shift->worked_hours = null;
            $shift->total_pay = null;
            return;
        }

        // Scientist selected but no rate yet
        if ($shift->scientist_id && is_null($shift->hourly_rate)) {
            $shift->status = 'assigned';
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | TIME CALCULATION (NO CARBON)
        |--------------------------------------------------------------------------
        */

        if ($shift->shift_start && $shift->shift_end && $shift->hourly_rate) {

            // Convert HH:MM or HH:MM:SS → seconds
            $startSeconds = strtotime($shift->shift_start);
            $endSeconds   = strtotime($shift->shift_end);

            // End must be after start
            if ($endSeconds <= $startSeconds) {
                throw new \Exception('Shift end time must be after start time.');
            }

            $secondsWorked = $endSeconds - $startSeconds;
            $hoursWorked = round($secondsWorked / 3600, 2);

            // Minimum 1 hour enforcement (double safety)
            if ($hoursWorked < 1) {
                throw new \Exception('Shift duration must be at least 1 hour.');
            }

            $shift->worked_hours = $hoursWorked;
            $shift->total_pay = round($hoursWorked * $shift->hourly_rate, 2);
            $shift->status = 'completed';
        }
    });
}

}
