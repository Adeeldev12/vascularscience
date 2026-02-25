<?php

namespace App\Filament\Resources\Availabilities\Pages;

use App\Models\Availability;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Mail;
use Filament\Resources\Pages\EditRecord;
use App\Mail\AvailabilityNotificationMail;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\Availabilities\AvailabilityResource;

class EditAvailability extends EditRecord
{
    protected static string $resource = AvailabilityResource::class;

     protected function afterSave(): void
    {
        $availability = $this->record;

        Mail::to(config('mail.admin_address'))
            ->send(new AvailabilityNotificationMail($availability, 'updated'));
    }
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

  protected function beforeSave(): void
    {
        $data = $this->form->getState();

        if (Availability::hasOverlapFor($data, $this->record->id)) {
            // throw ValidationException::withMessages([
            //     'start_time' => 'This time slot overlaps with an existing availability.',
            // ]);
            throw new \Exception("'start_time' => 'This time slot overlaps with an existing availability. So kindly choose differnent time of availability");

        }
    }
}
