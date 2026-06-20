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
     public function getTitle(): string
    {
        return 'Rota';
    }
    protected static string $resource = AvailabilityResource::class;

    //  protected function afterCreate(): void
    // {
    //     $availability = $this->record;

    //     Mail::to(config('mail.admin_address'))
    //         ->send(new AvailabilityNotificationMail($availability, 'created'));
    // }

    //  protected function beforeCreate(): void
    // {
    //     $data = $this->form->getState();

    //     if (Availability::hasOverlapFor($data)) {
    //         // throw ValidationException::withMessages([
    //         //     'start_time' => 'This time slot overlaps with an existing availability.',
    //         // ]);
    //         throw new \Exception("This time slot overlaps with an existing availability. So kindly choose differnent time of availability");

    //     }
    // }

//     protected function beforeCreate(): void
// {
//     $data = $this->form->getState();

//     $isScientist = \Illuminate\Support\Facades\Auth::guard('scientist')->check();

//     // 🧪 Scientist → BLOCK ALL overlaps
//     if ($isScientist) {

//         if (Availability::hasOverlapFor($data, null, null, true)) {
//             throw new \Exception(
//                 "This time slot overlaps with an existing availability. So kindly choose different time of availability"
//             );
//         }

//     } else {
//         // 👨‍💼 Admin → BLOCK only SAME STATUS overlaps

//         if (Availability::hasOverlapFor($data, null, $data['status'], false)) {
//             throw new \Exception(
//                 "This time slot with same status already exists."
//             );
//         }
//     }
// }

protected function beforeCreate(): void
{
    $data = $this->form->getState();

    $isScientist = \Illuminate\Support\Facades\Auth::guard('scientist')->check();

    if ($isScientist) {

        if (\App\Models\Availability::hasOverlapFor($data, null, null, true)) {
            throw new \Exception("This time slot overlaps with an existing availability.");
        }

    } else {

        if (\App\Models\Availability::hasOverlapFor($data, null, $data['status'], false)) {
            throw new \Exception("This time slot with same status already exists.");
        }
    }
}
}
