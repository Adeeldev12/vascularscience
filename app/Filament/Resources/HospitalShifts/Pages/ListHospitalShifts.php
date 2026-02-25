<?php

namespace App\Filament\Resources\HospitalShifts\Pages;

use App\Filament\Resources\HospitalShifts\HospitalShiftResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHospitalShifts extends ListRecords
{
    protected static string $resource = HospitalShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
