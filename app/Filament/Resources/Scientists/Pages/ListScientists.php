<?php

namespace App\Filament\Resources\Scientists\Pages;

use App\Filament\Resources\Scientists\ScientistResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScientists extends ListRecords
{
    protected static string $resource = ScientistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
