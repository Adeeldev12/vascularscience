<?php

namespace App\Filament\Widgets\Shared;

use App\Models\Availability;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AvailabilityCalendarWidget extends Component
{
    public ?int $scientistId = null;

    public function render()
    {
        $scientistId = $this->scientistId ?? Auth::guard('scientist')->id();

        if (!$scientistId) {
            return view('filament.widgets.shared.availability-calendar-widget', [
                'availableDates' => [],
                'unavailableDates' => [],
                'pendingDates' => [],
            ]);
        }

        $availabilities = Availability::where('scientist_id', $scientistId)->get();

        $availableDates = $availabilities
            ->where('status', 'available')
            ->pluck('date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())
            ->values()
            ->toArray();

        $unavailableDates = $availabilities
            ->where('status', 'unavailable')
            ->pluck('date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())
            ->values()
            ->toArray();

        $pendingDates = $availabilities
            ->where('status', 'pending')
            ->pluck('date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())
            ->values()
            ->toArray();

        return view('filament.widgets.shared.availability-calendar-widget', compact(
            'availableDates',
            'unavailableDates',
            'pendingDates'
        ));
    }
}
