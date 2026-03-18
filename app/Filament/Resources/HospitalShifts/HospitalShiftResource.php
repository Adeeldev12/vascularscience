<?php

namespace App\Filament\Resources\HospitalShifts;

use App\Filament\Resources\HospitalShifts\Pages\CreateHospitalShift;
use App\Filament\Resources\HospitalShifts\Pages\EditHospitalShift;
use App\Filament\Resources\HospitalShifts\Pages\ListHospitalShifts;
use App\Filament\Resources\HospitalShifts\Schemas\HospitalShiftForm;
use App\Filament\Resources\HospitalShifts\Tables\HospitalShiftsTable;
use App\Models\HospitalShift;
use App\Models\ScientistRate;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Date;

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

    Grid::make(2) // 👈 controls only time fields
            ->schema([
                TimePicker::make('start_time')
                    ->label('Start Time')
                    ->seconds(false)
                    ->required(),

                TimePicker::make('end_time')
    ->label('End Time')
    ->seconds(false)
    ->required()
    ->rules([
        function ($get) {
            return function (string $attribute, $value, \Closure $fail) use ($get) {
                $start = $get('start_time');

                if (!$start || !$value) {
                    return;
                }

                $startTime = \Carbon\Carbon::parse($start);
                $endTime = \Carbon\Carbon::parse($value);

                // ❌ End must be after start
                if ($endTime->lessThanOrEqualTo($startTime)) {
                    $fail('End time must be greater than start time.');
                }

                // ❌ Minimum 30 minutes difference
                if ($startTime->diffInMinutes($endTime) < 30) {
                    $fail('Shift must be at least 30 minutes long.');
                }
            };
        },
    ]),
            ]),
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
                ->date('d M Y'),

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

                TextColumn::make('start_time')
    ->label('Start Time')
    ->time('h:i A'),

TextColumn::make('end_time')
    ->label('End Time')
    ->time('h:i A'),

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
