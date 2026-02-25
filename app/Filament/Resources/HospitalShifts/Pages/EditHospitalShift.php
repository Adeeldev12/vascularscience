<?php

namespace App\Filament\Resources\HospitalShifts\Pages;

use App\Filament\Resources\HospitalShifts\HospitalShiftResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHospitalShift extends EditRecord
{
    protected static string $resource = HospitalShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
