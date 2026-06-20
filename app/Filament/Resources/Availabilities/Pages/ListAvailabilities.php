<?php

namespace App\Filament\Resources\Availabilities\Pages;

use App\Filament\Resources\Availabilities\AvailabilityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAvailabilities extends ListRecords
{
    protected static string $resource = AvailabilityResource::class;

    protected static ?string $navigationLabel = 'Routa';


    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Rota';
    }

}
