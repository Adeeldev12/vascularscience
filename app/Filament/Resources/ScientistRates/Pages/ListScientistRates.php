<?php

namespace App\Filament\Resources\ScientistRates\Pages;

use App\Filament\Resources\ScientistRates\ScientistRateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScientistRates extends ListRecords
{
    protected static string $resource = ScientistRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
