<?php

namespace App\Filament\Resources\Scientists\Pages;

use App\Filament\Resources\Scientists\ScientistResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScientist extends EditRecord
{
    protected static string $resource = ScientistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
