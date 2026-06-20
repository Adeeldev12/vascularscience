<?php

namespace App\Filament\Resources\Hospitals;

use BackedEnum;
use App\Models\Hospital;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Hospitals\Pages\EditHospital;
use App\Filament\Resources\Hospitals\Pages\ListHospitals;
use App\Filament\Resources\Hospitals\Pages\CreateHospital;
use App\Filament\Resources\Hospitals\Schemas\HospitalForm;
use App\Filament\Resources\Hospitals\Tables\HospitalsTable;

class HospitalResource extends Resource
{
    protected static ?string $model = Hospital::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;


    // protected static ?string $navigationIcon = 'heroicon-o-building-office';
    // protected static ?string $navigationGroup = 'Medical Management';
    protected static ?int $navigationSort = 3;


    protected static ?string $recordTitleAttribute = 'hospital';

    public static function form(Schema $schema): Schema
    {
        return HospitalForm::configure($schema)

        ->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            // TextInput::make('registration_number')
            //     // ->required()
            //     ->unique(ignoreRecord: true),

            // TextInput::make('year_established')
            //     ->numeric()
            //     ->minValue(1800)
            //     ->maxValue(now()->year),

            TextInput::make('official_email')
                ->email(),
                // ->required(),

            TextInput::make('phone_number'),
                // ->required(),

            TextInput::make('emergency_contact_number'),
                // ->required(),

            TextInput::make('website_url')
                ->url(),

            // TextInput::make('fax_number'),

            Textarea::make('address')
                // ->required()
                ->columnSpanFull(),

            TextInput::make('administrator_name'),
                // ->required(),

            TextInput::make('administrator_email')
                ->email(),
                // ->required(),

            TextInput::make('administrator_phone'),
                // ->required(),

            // Select::make('ownership_type')
            //     // ->required()
            //     ->options([
            //         'government' => 'Government',
            //         'private' => 'Private',
            //         'ngo' => 'NGO',
            //         'university' => 'University / Teaching Hospital',
            //     ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return HospitalsTable::configure($table)

         ->columns([
                TextColumn::make('name')->searchable(),
                // TextColumn::make('registration_number'),
                // TextColumn::make('ownership_type')->badge(),
                TextColumn::make('official_email'),
                TextColumn::make('phone_number'),
                // TextColumn::make('created_at')->date(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHospitals::route('/'),
            'create' => CreateHospital::route('/create'),
            'edit' => EditHospital::route('/{record}/edit'),
        ];
    }
}
