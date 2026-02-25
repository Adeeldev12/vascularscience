<?php

namespace App\Filament\Resources\Scientists\Pages;

use App\Filament\Resources\Scientists\ScientistResource;
use Filament\Resources\Pages\CreateRecord;

class CreateScientist extends CreateRecord
{
    protected static string $resource = ScientistResource::class;
}
