<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use App\Mail\ScientistAvailabilityNotification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    //
    protected $fillable = [
        'scientist_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'note',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
    ];

    public function scientist(): BelongsTo
    {
        return $this->belongsTo(Scientist::class, 'scientist_id');
    }


protected static function booted()
{
    static::created(function ($availability) {
        $scientist = $availability->scientist;
        if ($scientist) {
            Mail::to('thevascularscience@gmail.com')
                ->queue(new \App\Mail\ScientistAvailabilityNotification(
                    $scientist,
                    $availability,
                    'created'
                ));
        }
    });

    static::updated(function ($availability) {
        $scientist = $availability->scientist;
        if ($scientist) {
            Mail::to('thevascularscience@gmail.com')
                ->queue(new \App\Mail\ScientistAvailabilityNotification(
                    $scientist,
                    $availability,
                    'updated'
                ));
        }
    });
}

 // Public helper so Filament pages can use it
    public static function hasOverlapFor(array $data, ?int $ignoreId = null): bool
    {
        $date        = $data['date'];
        $scientistId = $data['scientist_id'];

        $start = date('H:i:s', strtotime($data['start_time']));
        $end   = date('H:i:s', strtotime($data['end_time']));

        $query = static::where('scientist_id', $scientistId)
            ->where('date', $date);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        // Overlap rule: existing.start < new.end AND existing.end > new.start
        return $query->where(function ($q) use ($start, $end) {
            $q->whereTime('start_time', '<', $end)
              ->whereTime('end_time',   '>', $start);
        })->exists();
    }
}
