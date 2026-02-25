<?php

namespace App\Filament\Resources\ScientistRates;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\ScientistRate;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Tables\Grouping\Group;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\ScientistRates\Pages\EditScientistRate;
use App\Filament\Resources\ScientistRates\Pages\ListScientistRates;
use App\Filament\Resources\ScientistRates\Pages\CreateScientistRate;
use App\Filament\Resources\ScientistRates\Schemas\ScientistRateForm;
use App\Filament\Resources\ScientistRates\Tables\ScientistRatesTable;

class ScientistRateResource extends Resource
{
    protected static ?string $model = ScientistRate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyPound;

    public static function form(Schema $schema): Schema
    {
        return ScientistRateForm::configure($schema)

        ->schema([
             Select::make('scientist_id')
            ->relationship('scientist', 'name')
            ->native(false)
            ->reactive()
            ->required(),

        TextInput::make('service_name')
            ->required()
            ->maxLength(255),

        TextInput::make('rate')
                ->numeric()
                ->required()
                ->prefix('£'),

        Textarea::make('applicable_hours')
                ->required()
                ->rows(2)
                ->placeholder('e.g. Mon–Fri 9am–1pm'),

        // Textarea::make('notes')
        //     ->columnSpanFull(),

        // Toggle::make('active')
        //     ->default(true),

    ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return ScientistRatesTable::configure($table)

        ->groups([
            // Group::make('hospital.name')
            //     ->label('Hospital'),

            // Group::make('shift_date')
            //     ->label('Date'),

            Group::make('scientist.name')
                ->label('Scientist'),
        ])

        ->columns([

            TextColumn::make('scientist.name')
                ->label('Scientist')
                ->sortable()
                ->searchable(),

            TextColumn::make('service_name')
                ->searchable(),

            TextColumn::make('rate')
                    ->money('GBP')
                    ->sortable(),

                TextColumn::make('applicable_hours')
                    ->wrap(),

                // IconColumn::make('active')
                //     ->boolean(),

        ])
          ->filters([
            SelectFilter::make('scientist_id')
                ->relationship('scientist', 'name')
                ->label('Scientist'),
        ],
        layout: FiltersLayout::AboveContent)
        ->filtersFormColumns(3) // 👈 forces one-row layout
//         ->filtersLayout(FiltersLayout::AboveContent)
// ->filtersFormRow(1)
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->bulkActions([
            DeleteBulkAction::make(),
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
            'index' => ListScientistRates::route('/'),
            'create' => CreateScientistRate::route('/create'),
            'edit' => EditScientistRate::route('/{record}/edit'),
        ];
    }
}
