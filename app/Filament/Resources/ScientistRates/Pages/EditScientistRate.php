<?php

namespace App\Filament\Resources\ScientistRates\Pages;

use App\Filament\Resources\ScientistRates\ScientistRateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScientistRate extends EditRecord
{
    protected static string $resource = ScientistRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
