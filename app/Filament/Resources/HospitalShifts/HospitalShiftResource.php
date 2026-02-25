<?php

namespace App\Filament\Resources\HospitalShifts;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\HospitalShift;
use App\Models\ScientistRate;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Tables\Grouping\Group;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use App\Filament\Resources\HospitalShifts\Pages\EditHospitalShift;
use App\Filament\Resources\HospitalShifts\Pages\ListHospitalShifts;
use App\Filament\Resources\HospitalShifts\Pages\CreateHospitalShift;
use App\Filament\Resources\HospitalShifts\Schemas\HospitalShiftForm;
use App\Filament\Resources\HospitalShifts\Tables\HospitalShiftsTable;

class HospitalShiftResource extends Resource
{
    protected static ?string $model = HospitalShift::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'HospitalShift';

    public static function form(Schema $schema): Schema
    {
        return HospitalShiftForm::configure($schema)

         ->schema([
             Section::make('Shift Details')
                ->schema([
                    Select::make('hospital_id')
                        ->relationship('hospital', 'name')
                        ->native(false)
                        ->required(),

                    DatePicker::make('shift_date')
                        ->required(),
                ]),

            Section::make('Scientist Assignment')
    ->schema([

        Select::make('scientist_id')
            ->label('Scientist')
            ->relationship('scientist', 'name')
            ->native(false)
            ->reactive()
            ->afterStateUpdated(function (callable $set) {
                $set('scientist_rate_id', null);
                $set('hourly_rate', null);
            })
            ->required(),

        Select::make('scientist_rate_id')
            ->label('Service')
            ->options(fn (callable $get) =>
                ScientistRate::where('scientist_id', $get('scientist_id'))
                    ->where('active', true)
                    ->pluck('service_name', 'id')
            )
            ->visible(fn (callable $get) => filled($get('scientist_id')))
            ->native(false)
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                $rate = ScientistRate::find($state);

                if ($rate) {
                    $set('hourly_rate', $rate->rate);
                }
            }),
            // ->required(),

        TextInput::make('hourly_rate')
            ->label('Rate')
            ->prefix('£')
            ->disabled()
            ->dehydrated(),

            Select::make('status')
    ->label('Shift Status')
    ->options([
        'completed' => 'Completed',
        'empty'     => 'Empty',
        'assigned'  => 'Assigned',
        'cancelled' => 'Cancelled',
    ])
    ->native(false)
    ->required()
    ->default('completed'),

    ])
    ->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return HospitalShiftsTable::configure($table)

        ->groups([
            Group::make('hospital.name')
                ->label('Hospital'),

            Group::make('shift_date')
                ->label('Date'),

            Group::make('scientist.name')
                ->label('Scientist'),
        ])


        ->columns([
            TextColumn::make('hospital.name')
    ->label('Hospital')
    ->sortable()
    ->searchable(),

            TextColumn::make('shift_date')
                ->date(),

            // TextColumn::make('shift_start')
            //     ->label('Start')
            //     ->time(),

            // TextColumn::make('shift_end')
            //     ->label('End')
            //     ->time(),

            TextColumn::make('scientist.name')
                ->label('Scientist')
                ->badge()
                ->color(fn ($record) =>
                    $record->scientist_id ? 'success' : 'danger'
                )
                ->formatStateUsing(fn ($state) => $state ?? 'Empty'),

                TextColumn::make('scientistRate.service_name')
    ->label('Service')
    ->badge(),

            TextColumn::make('hourly_rate')
                ->money('GBP')
                ->label('Pay')
                ->sortable(),

                BadgeColumn::make('status')
                ->colors([
                    'danger' => 'empty',
                    'warning' => 'assigned',
                    'success' => 'completed',
                    'gray' => 'cancelled',
                ]),
        ])
        ->defaultSort('shift_date')
        ->actions([
            EditAction::make(),
        ])->defaultSort('created_at', 'desc');
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
            'index' => ListHospitalShifts::route('/'),
            'create' => CreateHospitalShift::route('/create'),
            'edit' => EditHospitalShift::route('/{record}/edit'),
        ];
    }
}
