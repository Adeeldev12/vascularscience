<?php

namespace App\Filament\Resources\Availabilities;

use Closure;
use BackedEnum;
use Carbon\Carbon;
use Filament\Tables\Table;
use Ramsey\Uuid\Type\Time;
use App\Models\Availability;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\DateColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Mail\AvailabilityNotificationMail;
use App\Filament\Resources\Availabilities\Pages\EditAvailability;
use App\Filament\Resources\Availabilities\Pages\CreateAvailability;
use App\Filament\Resources\Availabilities\Pages\ListAvailabilities;
use App\Filament\Resources\Availabilities\Schemas\AvailabilityForm;
use App\Filament\Resources\Availabilities\Tables\AvailabilitiesTable;

class AvailabilityResource extends Resource
{
    protected static ?string $model = Availability::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        // ✅ Build components dynamically
        $fields = [];

        // Scientist field (dynamic)
        if (Auth::guard('scientist')->check()) {
            $fields[] = Hidden::make('scientist_id')
                ->default(Auth::guard('scientist')->id());
        } else {
            $fields[] = Select::make('scientist_id')
                ->label('Scientist')
               ->relationship('scientist', 'name')
    ->preload()          // ✅ preload all scientists
    ->native(false)
                ->searchable()
                ->required();
        }

        // // Add remaining form fields
        // $fields[] = DatePicker::make('date')->required()->native(false);
$fields[] = DatePicker::make('date')
    ->native(false)
    ->required()
    ->minDate(now()->timezone('Europe/London')->toDateString())
    ->rule(function () {
        return function ($attribute, $value, $fail) {
            if (Carbon::parse($value)->isBefore(today('Europe/London'))) {
                $fail('You cannot select a past date.');
            }
        };
    });



       // START TIME
$fields[] = TimePicker::make('start_time')
    ->seconds(false)
    ->format('H:i')
    ->timezone('Europe/London')
    ->required()
    ->rule(function (callable $get) {
    return function ($attribute, $value, $fail) use ($get) {

        $date = $get('date');
        if (!$date || !$value) return;

        $start = Carbon::parse("$date $value");
        $endValue = $get('end_time');

        // ❗ Block past times
        $now = Carbon::now('Europe/London');
        if ($start->lt($now)) {
            $fail('You cannot select a past time.');
            return;
        }

        if (!$endValue) return;

        $end = Carbon::parse("$date $endValue");

        if ($start >= $end) {
            $fail('Start time must be before end time.');
        }

        if ($start->diffInMinutes($end) < 30) {
            $fail('Minimum duration must be 30 minutes.');
        }
    };
});



// END TIME
$fields[] = TimePicker::make('end_time')
    ->seconds(false)
    ->format('H:i')
    ->timezone('Europe/London')
    ->required()
     ->rule(function (callable $get) {
    return function ($attribute, $value, $fail) use ($get) {

        $date = $get('date');
        if (!$date || !$value) return;

        $end = Carbon::parse("$date $value");
        $startValue = $get('start_time');

        // ❗ Block past times
        $now = Carbon::now('Europe/London');
        if ($end->lt($now)) {
            $fail('You cannot select a past time.');
            return;
        }

        if (!$startValue) return;

        $start = Carbon::parse("$date $startValue");

        if ($end <= $start) {
            $fail('End time must be after start time.');
        }

        if ($start->diffInMinutes($end) < 30) {
            $fail('Availability must be at least 30 minutes.');
        }
    };
});


        // Status — remove "pending"
        $fields[] = Select::make('status')
            ->options([
                'available' => 'Available',
                'unavailable' => 'Unavailable',
            ])
            ->native(false)
            ->required();

        $fields[] = Textarea::make('note')->rows(3);

        // ✅ Pass built fields to schema()
        return AvailabilityForm::configure($schema)->schema($fields);
    }

    public static function table(Table $table): Table
    {
        return AvailabilitiesTable::configure($table)

        ->columns([
            TextColumn::make('scientist.name')->label('Scientist'),
            TextColumn::make('date')
                ->label('Date')
                ->date('Y-m-d'), // format the date,

TextColumn::make('start_time')
    ->label('Start Time')
    ->time(),

TextColumn::make('end_time')
    ->label('End Time')
    ->time(),

                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'available',
                        'danger' => 'unavailable',
                    ]),
            TextColumn::make('note')
                ->limit(50),
            ])
            ->defaultSort('created_at', 'desc'); // ✅ newest first;
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
            'index' => ListAvailabilities::route('/'),
            'create' => CreateAvailability::route('/create'),
            'edit' => EditAvailability::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();

    // If a scientist is logged in, show only their availabilities
    if (Auth::guard('scientist')->check()) {
        return $query->where('scientist_id', Auth::guard('scientist')->id());
    }

    // Otherwise (e.g. admin), show all records
    return $query;
}

// private static function validateTimeRules(array $data)
// {
//     $start = Carbon::parse("{$data['date']} {$data['start_time']}");
//     $end   = Carbon::parse("{$data['date']} {$data['end_time']}");

//     if ($start >= $end) {
//         abort(422, "End time must be after the start time.");
//     }

//     if ($start->diffInMinutes($end) < 30) {
//         abort(422, "Availability must be at least 30 minutes long.");
//     }
// }




protected function afterCreate(): void
{
    $availability = $this->record;

    Mail::to(config('mail.admin_address'))
        ->send(new AvailabilityNotificationMail($availability, 'created'));

    $this->emit('availabilityUpdated');
}

protected function afterSave(): void
{
    Log::info('✅ afterSave triggered for Availability ID: ' . $this->record->id);

    $availability = $this->record;

    Mail::to(config('mail.admin_address'))
        ->send(new AvailabilityNotificationMail($availability, 'updated'));

    $this->emit('availabilityUpdated');
}


protected function beforeCreate(): void
    {
        $data = $this->data;
        self::validateAvailabilityOverlap($data);
    }

    protected function beforeSave(): void
    {
        $data = $this->data;
        self::validateAvailabilityOverlap($data, $this->record->id);
    }

    // Validate overlap with existing availability records
    private static function validateAvailabilityOverlap(array $data, $ignoreId = null)
{
    $date = $data['date'];
    $scientistId = $data['scientist_id'];

    $newStart = Carbon::parse("{$date} {$data['start_time']}");
    $newEnd   = Carbon::parse("{$date} {$data['end_time']}");

    $query = Availability::where('scientist_id', $scientistId)
        ->where('date', $date);

    if ($ignoreId) {
        $query->where('id', '!=', $ignoreId);
    }

    $conflict = $query->where(function ($q) use ($newStart, $newEnd) {
        $q->whereTime('start_time', '<', $newEnd->format('H:i:s'))
          ->whereTime('end_time',   '>', $newStart->format('H:i:s'));
    })->exists();

    if ($conflict) {
        abort(422, "This time slot overlaps with an existing availability. Select a different time.");
    }
}



}
