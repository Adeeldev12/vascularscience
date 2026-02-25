<?php

namespace App\Filament\Resources\Availabilities\Pages;

use App\Models\Availability;
use Illuminate\Support\Facades\Mail;
use App\Mail\AvailabilityNotificationMail;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\Availabilities\AvailabilityResource;

class CreateAvailability extends CreateRecord
{
    protected static string $resource = AvailabilityResource::class;

    //  protected function afterCreate(): void
    // {
    //     $availability = $this->record;

    //     Mail::to(config('mail.admin_address'))
    //         ->send(new AvailabilityNotificationMail($availability, 'created'));
    // }

     protected function beforeCreate(): void
    {
        $data = $this->form->getState();

        if (Availability::hasOverlapFor($data)) {
            // throw ValidationException::withMessages([
            //     'start_time' => 'This time slot overlaps with an existing availability.',
            // ]);
            throw new \Exception("This time slot overlaps with an existing availability. So kindly choose differnent time of availability");

        }
    }
}
