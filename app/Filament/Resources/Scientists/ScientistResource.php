<?php

namespace App\Filament\Resources\Scientists;

use BackedEnum;
use App\Models\Scientist;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\Scientists\Pages\EditScientist;
use App\Filament\Resources\Scientists\Pages\ListScientists;
use App\Filament\Resources\Scientists\Pages\CreateScientist;
use App\Filament\Resources\Scientists\Schemas\ScientistForm;
use App\Filament\Resources\Scientists\Tables\ScientistsTable;

class ScientistResource extends Resource
{
    protected static ?string $model = Scientist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;


    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ScientistForm::configure($schema)

        ->schema([
            // Section 1: Personal Details - Beautiful Card Style
            Section::make('1. 🧑‍💼 Personal Details')
                ->description('Complete your personal information and upload required documents')
                ->icon('heroicon-o-user')
                ->schema([
                    Grid::make()
                        ->schema([
                            TextInput::make('name')
                                ->label('Full Name')
                                ->placeholder('Enter your full name')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(1),

                            TextInput::make('email')
                                ->label('Email Address')
                                // ->placeholder('your.email@example.com')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->columnSpan(1),
                        ])->columns(1),

                    Grid::make()
                        ->schema([
                            TextInput::make('phone')
                            ->label('Phone Number')
                            ->required()
                            ->placeholder('07..., 01..., 02..., or +44 (enter as 44...)')
                            ->mask('9999999999999')
                            ->rule(function () {
                                return function (string $attribute, $value, $fail) {

                                    // VALID UK PREFIXES (MOBILE + LANDLINE)
                                    $validStarts = [
                                        '07',   // UK Mobile (national)
                                        '447',  // UK Mobile (international without +)
                                        '01',   // UK Landline
                                        '02',   // UK Landline
                                        '441',  // UK Landline (international without +)
                                        '442',  // UK Landline (international without +)
                                    ];

                                    $isValidPrefix = false;

                                    foreach ($validStarts as $prefix) {
                                        if (str_starts_with($value, $prefix)) {
                                            $isValidPrefix = true;
                                            break;
                                        }
                                    }

                                    if (! $isValidPrefix) {
                                        return $fail('Number must start with 07, 01, 02, 447, 441, or 442 (UK only).');
                                    }

                                    // Optional: Enforce reasonable length
                                    if (strlen($value) < 10 || strlen($value) > 13) {
                                        return $fail('Enter a valid UK number (10–13 digits).');
                                    }
                                };
                            })
                            ->helperText('UK numbers only: 07 mobile, 01/02 landline, or +44 as 44...')
                                ->columnSpan(1),

                            Textarea::make('address')
                                ->label('Full Address')
                                ->placeholder('Enter your complete address including postcode')
                                ->required()
                                ->rows(3)
                                ->columnSpan(1),
                        ])->columns(1),

                    Grid::make()
                        ->schema([
                            FileUpload::make('cv_path')
                                ->label('📄 Upload Updated CV')
                                ->placeholder('Click to upload your CV (PDF, DOC, DOCX)')
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                ->maxSize(10240)
                                ->required()
                                ->disk('public')
                                ->directory('scientists/cv')
                                ->columnSpan(1),

                            FileUpload::make('hcpc_registration_path')
                                ->label('🏥 Proof of HCPC or AHCS Registration')
                                ->placeholder('Upload HCPC/AHCS registration document')
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                ->maxSize(10240)
                                ->required()
                                ->disk('public')
                                ->directory('scientists/hcpc')
                                ->columnSpan(1),
                        ])->columns(1),

                    DatePicker::make('hcpc_issue_date')
                        ->label('HCPC/AHCS Registration Issue Date')
                        ->placeholder('Select issue date')
                        ->required()
                        ->displayFormat('d F Y')
                        ->native(false)
                         ->maxDate(now())                  // no future
    ->minDate(now()->subYear())
                        ->columnSpan(1),
                ])
                ->collapsible(),

            // Section 2: Compliance & Certificates - Beautiful Grid
            Section::make('2. 📋 Compliance & Certificates')
                ->description('Upload all required compliance documents and certificates')
                ->icon('heroicon-o-document-check')
                ->schema([
                    Grid::make()
                        ->schema([
                            // Row 1
                            FileUpload::make('enhanced_dbs_path')
                                ->label('🔒 Enhanced DBS/CRB Check')
                                ->placeholder('Upload DBS certificate')
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                ->maxSize(10240)
                                ->required()
                                ->disk('public')
                                ->directory('scientists/dbs'),

                            DatePicker::make('enhanced_dbs_issue_date')
                                ->label('DBS Issue Date')
                                ->placeholder('Select issue date')
                                ->required()
                                ->displayFormat('d F Y')
                                ->native(false)
                                 ->maxDate(now())                  // no future
    ->minDate(now()->subYears(3)),

                            // Row 2
                            FileUpload::make('immunisation_record_path')
                                ->label('💉 Immunisation Record')
                                ->placeholder('Upload immunisation records')
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                ->maxSize(10240)
                                ->required()
                                ->disk('public')
                                ->directory('scientists/immunisation'),

    //                         DatePicker::make('immunisation_issue_date')
    //                             ->label('Immunisation Issue Date')
    //                             ->placeholder('Select issue date')
    //                             ->required()
    //                             ->displayFormat('d F Y')
    //                             ->native(false)
    //                              ->maxDate(now())                  // no future
    // ->minDate(now()->subYear()),

                            // Row 3
                            FileUpload::make('bls_certificate_path')
                                ->label('🫀 Basic Life Support (BLS) Certificate')
                                ->placeholder('Upload BLS certificate')
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                ->maxSize(10240)
                                ->required()
                                ->disk('public')
                                ->directory('scientists/bls'),

                            DatePicker::make('bls_issue_date')
                                ->label('BLS Issue Date')
                                ->placeholder('Select issue date')
                                ->required()
                                ->displayFormat('d F Y')
                                ->native(false)
                                 ->maxDate(now())                  // no future
    ->minDate(now()->subYear()),

                            // Row 4
                            FileUpload::make('health_safety_certification_path')
                                ->label('🛡️ Health & Safety Certification')
                                ->placeholder('Upload Health & Safety certificate')
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                ->maxSize(10240)
                                ->required()
                                ->disk('public')
                                ->directory('scientists/health-safety'),

                            DatePicker::make('health_safety_issue_date')
                                ->label('Health & Safety Issue Date')
                                ->placeholder('Select issue date')
                                ->required()
                                ->displayFormat('d F Y')
                                ->native(false)
                                 ->maxDate(now())                  // no future
    ->minDate(now()->subYear()),

                            // Row 5
                            FileUpload::make('professional_indemnity_insurance_path')
                                ->label('📊 Professional Indemnity Insurance')
                                ->placeholder('Upload insurance document')
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                ->maxSize(10240)
                                ->required()
                                ->disk('public')
                                ->directory('scientists/insurance'),

                            DatePicker::make('professional_indemnity_issue_date')
                                ->label('Insurance Issue Date')
                                ->placeholder('Select issue date')
                                ->required()
                                ->displayFormat('d F Y')
                                ->native(false)
                                 ->maxDate(now())                  // no future
    ->minDate(now()->subYear()),

                            // Row 6
                            FileUpload::make('avs_cpd_updates_path')
                                ->label('📚 Evidence of AVS/CPD Updates')
                                ->placeholder('Upload CPD evidence')
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                ->maxSize(10240)
                                ->required()
                                ->disk('public')
                                ->directory('scientists/avs-cpd'),

                            DatePicker::make('avs_cpd_issue_date')
                                ->label('CPD Issue Date')
                                ->placeholder('Select issue date')
                                ->required()
                                ->displayFormat('d F Y')
                                ->native(false)
                                 ->maxDate(now())                  // no future
    ->minDate(now()->subYear()),
                        ])->columns(2),
                ])
                ->collapsible(),

            // Section 3: Bank Details - Clean Card
            Section::make('3. 💳 Bank Details')
                ->description('Provide your bank account information for payments')
                ->icon('heroicon-o-credit-card')
                ->schema([
                    Grid::make()
                        ->schema([
                            TextInput::make('bank_name')
                                ->label('Bank Name')
                                ->placeholder('e.g., Barclays, HSBC, Lloyds')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('account_holder_name')
                                ->label('Account Holder Name')
                                ->placeholder('Name as it appears on bank account')
                                ->required()
                                ->maxLength(255),
                        ])->columns(2),

                    Grid::make()
                        ->schema([
                            TextInput::make('account_number')
                                ->label('Account Number')
                                ->placeholder('12345678')
                                ->required()
                                ->numeric()
                                ->minLength(8)
                                ->maxLength(17)
                                ->mask('99999999')
                            ->rule('digits:8'),

                            TextInput::make('sort_code')
                                ->label('Sort Code')
                                ->placeholder('123456')
                                ->required()
                                ->numeric()
                                ->length(6)
                                ->numeric()
                            ->mask('999999')
                            ->rule('regex:/^[0-9]+$/')   // only digits allowed
                            // ->rule(function () {
                            //     return function (string $attribute, $value, $fail) {
                            //         // Accept 12-34-56 or 123456
                            //         $clean = str_replace('-', '', $value);

                            //         if (! ctype_digit($clean)) {
                            //             return $fail('Sort code must contain digits only.');
                            //         }

                            //         if (strlen($clean) !== 6) {
                            //             return $fail('Sort code must be exactly 6 digits.');
                            //         }

                            //         $prefix = substr($clean, 0, 2);

                            //         $validPrefixes = [
                            //             '01', '02', '04', '05', '07', '08',
                            //             '10', '11', '12', '16', '18',
                            //             '20', '22', '23', '24', '30',
                            //             '40', '60', '80',
                            //         ];

                            //         if (! in_array($prefix, $validPrefixes)) {
                            //             return $fail('Invalid UK sort code format.');
                            //         }
                            //     };
                            // }),
                        ])->columns(1),
                ])
                ->collapsible(),

            // Section 4: Contract Agreement - Elegant Design
            Section::make('4. 📝 Contract Agreement')
                ->description('Review and accept the terms and conditions')
                ->icon('heroicon-o-document-text')
                ->schema([
                    Grid::make()
                        ->schema([
                             // Use Checkbox instead of Toggle for required acceptance
                Checkbox::make('agreed_to_terms')
                    ->label(' I have read and agree to the Terms & Conditions')
                    ->required()
                    ->rule('accepted') // This ensures it must be checked
                    ->validationMessages([
                        'accepted' => 'You must accept the Terms & Conditions to proceed.',
                         ]),

                            FileUpload::make('signed_contract_path')
                                ->label('📄 Signed Contract PDF')
                                ->placeholder('Upload signed contract document')
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                ->maxSize(10240)
                                ->required()
                                ->disk('public')
                                ->directory('scientists/contracts'),

                            DatePicker::make('contract_issue_date')
                                ->label('Contract Issue Date')
                                ->placeholder('Select contract date')
                                ->required()
                                ->displayFormat('d F Y')
                                ->native(false)
                                 ->maxDate(now())                  // no future
    ->minDate(now()->subYear()),
                        ])->columns(1),
                ])
                ->collapsible(),

            // Authentication Section (Hidden from scientists, for admin only)
            Section::make('🔐 Authentication (Admin Only)')
                ->description('Set login credentials for the scientist')
                ->icon('heroicon-o-lock-closed')
                ->schema([
                    TextInput::make('password')
                        ->password()
                        ->placeholder('Enter secure password')
                        ->required(fn ($operation) => $operation === 'create')
                        ->minLength(8)
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->columnSpan(1),
                ])
                // ->visible(fn () => auth()->user()->isAdmin()) // Only show to admin
                ->collapsible(),

            // Account Status Section (Admin Only)
            Section::make('📊 Account Status (Admin Only)')
                ->description('Manage scientist account status and verification')
                ->icon('heroicon-o-chart-bar')
                ->schema([
                    Grid::make()
                        ->schema([
                            Toggle::make('is_verified')
                                ->label('Verified Scientist')
                                ->default(false)
                                ->onColor('success')
                                ->offColor('danger'),

                            Toggle::make('is_active')
                                ->label('Active Account')
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger'),

                            Toggle::make('profile_completed')
                                ->label('Profile Completed')
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger'),
                        ])->columns(3),
                ])
                // ->visible(fn () => auth()->user()->isAdmin()) // Only show to admin
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return ScientistsTable::configure($table)

        ->columns([
            // Name with avatar
            TextColumn::make('name')
            ->label('Scientists')
                ->searchable()
                ->sortable()
                ->weight('medium')
                ->description(fn ($record) => $record->email)
                ->icon('heroicon-o-user'),

            // Contact Information
            TextColumn::make('phone')
                ->searchable()
                ->icon('heroicon-o-phone')
                ->color('gray')
                ->toggleable(isToggledHiddenByDefault: false),

            // Document Status - Comprehensive Overview
            IconColumn::make('cv_path')
                ->label('CV')
                ->icon(fn ($state) => $state ? 'heroicon-o-document-check' : 'heroicon-o-document')
                ->color(fn ($state) => $state ? 'success' : 'danger')
                ->tooltip(fn ($state) => $state ? 'CV Uploaded' : 'CV Missing'),

            IconColumn::make('hcpc_registration_path')
                ->label('HCPC')
                ->icon(fn ($state) => $state ? 'heroicon-o-document-check' : 'heroicon-o-document')
                ->color(fn ($state) => $state ? 'success' : 'danger')
                ->tooltip(fn ($state) => $state ? 'HCPC Uploaded' : 'HCPC Missing'),

            IconColumn::make('enhanced_dbs_path')
                ->label('DBS')
                ->icon(fn ($state) => $state ? 'heroicon-o-shield-check' : 'heroicon-o-shield-exclamation')
                ->color(fn ($state) => $state ? 'success' : 'danger')
                ->tooltip(fn ($state) => $state ? 'DBS Uploaded' : 'DBS Missing'),

            IconColumn::make('signed_contract_path')
                ->label('Contract')
                ->icon(fn ($state) => $state ? 'heroicon-o-check-badge' : 'heroicon-o-document')
                ->color(fn ($state) => $state ? 'success' : 'danger')
                ->tooltip(fn ($state) => $state ? 'Contract Signed' : 'Contract Missing'),

            // Overall Document Status
            IconColumn::make('all_documents_complete')
                ->label('All Docs')
                ->getStateUsing(fn ($record) => $record->hasAllDocuments())
                ->boolean()
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-x-circle')
                ->trueColor('success')
                ->falseColor('danger')
                ->tooltip(fn ($record) => $record->hasAllDocuments() ? 'All documents uploaded' : 'Some documents missing'),

            // Account Status
            IconColumn::make('is_verified')
                ->label('Verified')
                ->boolean()
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-clock')
                ->trueColor('success')
                ->falseColor('warning')
                ->tooltip(fn ($state) => $state ? 'Account Verified' : 'Pending Verification'),

            IconColumn::make('is_active')
                ->label('Active')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-pause-circle')
                ->trueColor('success')
                ->falseColor('danger')
                ->tooltip(fn ($state) => $state ? 'Account Active' : 'Account Inactive'),

            // Registration Date
            TextColumn::make('created_at')
                ->label('Registered')
                ->dateTime('d M Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->icon('heroicon-o-calendar')
                ->color('gray'),

            // Last Updated
            TextColumn::make('updated_at')
                ->label('Last Updated')
                ->dateTime('d M Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->color('gray'),
        ])

        // Filters for easy document management
        ->filters([
            // Document Status Filters
            Filter::make('Document Status')
                ->form([
                    Checkbox::make('missing_cv')
                        ->label('Missing CV'),
                    Checkbox::make('missing_hcpc')
                        ->label('Missing HCPC'),
                    Checkbox::make('missing_dbs')
                        ->label('Missing DBS'),
                    Checkbox::make('missing_contract')
                        ->label('Missing Contract'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['missing_cv'] ?? false, fn ($query) => $query->whereNull('cv_path'))
                        ->when($data['missing_hcpc'] ?? false, fn ($query) => $query->whereNull('hcpc_registration_path'))
                        ->when($data['missing_dbs'] ?? false, fn ($query) => $query->whereNull('enhanced_dbs_path'))
                        ->when($data['missing_contract'] ?? false, fn ($query) => $query->whereNull('signed_contract_path'));
                }),

            // Account Status Filters
            TernaryFilter::make('is_verified')
                ->label('Verification Status')
                ->placeholder('All Scientists')
                ->trueLabel('Verified Only')
                ->falseLabel('Not Verified'),

            TernaryFilter::make('is_active')
                ->label('Active Status')
                ->placeholder('All Accounts')
                ->trueLabel('Active Only')
                ->falseLabel('Inactive Only'),

            Filter::make('documents_complete')
                ->label('All Documents Complete')
                ->query(fn ($query) => $query->whereNotNull('cv_path')
                    ->whereNotNull('hcpc_registration_path')
                    ->whereNotNull('enhanced_dbs_path')
                    ->whereNotNull('immunisation_record_path')
                    ->whereNotNull('bls_certificate_path')
                    ->whereNotNull('health_safety_certification_path')
                    ->whereNotNull('professional_indemnity_insurance_path')
                    ->whereNotNull('avs_cpd_updates_path')
                    ->whereNotNull('signed_contract_path')),
        ])

        // Actions for document management
        ->actions([
            // View Action - Quick Overview
            ViewAction::make()
                ->color('info')
                ->icon('heroicon-o-eye'),

            // Edit Action
            EditAction::make()
                ->color('primary')
                ->icon('heroicon-o-pencil'),

            // Verify Action
            Action::make('verify')
                ->label('Verify')
                ->action(function (Scientist $record) {
                    $record->update(['is_verified' => true]);
                })
                ->requiresConfirmation()
                ->modalHeading('Verify Scientist')
                ->modalDescription('Are you sure you want to verify this scientist? This will grant them full access to the system.')
                ->modalSubmitActionLabel('Yes, Verify')
                ->color('success')
                ->icon('heroicon-o-check-badge')
                ->hidden(fn (Scientist $record) => $record->is_verified),

    //             Action::make('viewAvailability')
    // ->label('View Availability')
    // ->icon('heroicon-o-calendar')
    // ->url(fn($record) => Pages\ViewAvailability::getUrl(['record' => $record]))
    // ->openUrlInNewTab(),



                 // Professional Document Viewer Action
    Action::make('view_documents')
        ->label('View Docs')
        ->color('success')
        ->icon('heroicon-o-document')
        ->modalHeading(fn ($record) => "Documents for {$record->name}")
        ->modalSubmitAction(false)
        ->modalCancelActionLabel('Close')
        ->size('xl')
        ->form(function ($record) {
            $components = [];

            // Document groups with proper styling
            $documentGroups = [
                'Personal Documents' => [
                    ['label' => 'CV/Resume', 'field' => 'cv_path', 'icon' => '📄', 'color' => 'primary'],
                    ['label' => 'HCPC Registration', 'field' => 'hcpc_registration_path', 'icon' => '🏥', 'color' => 'success'],
                ],
                'Compliance Documents' => [
                    ['label' => 'DBS Check', 'field' => 'enhanced_dbs_path', 'icon' => '🔒', 'color' => 'warning'],
                    ['label' => 'Immunisation Record', 'field' => 'immunisation_record_path', 'icon' => '💉', 'color' => 'danger'],
                    ['label' => 'BLS Certificate', 'field' => 'bls_certificate_path', 'icon' => '🫀', 'color' => 'success'],
                ],
                'Certifications' => [
                    ['label' => 'Health & Safety', 'field' => 'health_safety_certification_path', 'icon' => '🛡️', 'color' => 'info'],
                    ['label' => 'Professional Insurance', 'field' => 'professional_indemnity_insurance_path', 'icon' => '📊', 'color' => 'gray'],
                    ['label' => 'CPD Updates', 'field' => 'avs_cpd_updates_path', 'icon' => '📚', 'color' => 'warning'],
                ],
                'Contract' => [
                    ['label' => 'Signed Contract', 'field' => 'signed_contract_path', 'icon' => '📝', 'color' => 'success'],
                ]
            ];

            foreach ($documentGroups as $groupName => $documents) {
                $components[] = Section::make($groupName)
                    ->schema(
                        collect($documents)->map(function ($doc) use ($record) {
                            return Actions::make([
                                Action::make("view_{$doc['field']}")
                                    ->label("{$doc['icon']} {$doc['label']}")
                                    ->color($record->{$doc['field']} ? $doc['color'] : 'gray')
                                    ->icon($record->{$doc['field']} ? 'heroicon-o-eye' : 'heroicon-o-x-circle')
                                    ->url($record->{$doc['field']} ? asset("storage/{$record->{$doc['field']}}") : null)
                                    ->openUrlInNewTab()
                                    ->disabled(!$record->{$doc['field']})
                                    ->extraAttributes(['class' => 'w-full justify-start']),
                            ]);
                        })->toArray()
                    )->columns(2);
            }

            return $components;
            }),
            // View Documents Action
            // Action::make('view_documents')
            //     ->label('Documents')
            //     // ->url(fn (Scientist $record) => ScientistResource::getUrl('view', ['record' => $record]))
            //     ->color('warning')
            //     ->icon('heroicon-o-folder-open')
            //     ->openUrlInNewTab(),

            // Download All Documents Action
            // Action::make('download_documents')
            //     ->label('Download All')
            //     ->action(function (Scientist $record) {
            //         // This would trigger a zip download of all documents
            //         // We'll implement this functionality later
            //     })
            //     ->color('gray')
            //     ->icon('heroicon-o-arrow-down-tray')
            //     ->requiresConfirmation()
            //     ->modalHeading('Download All Documents')
            //     ->modalDescription('This will download all uploaded documents as a ZIP file.')
            //     ->hidden(fn (Scientist $record) => !$record->hasAllDocuments()),

            // Deactivate/Activate Action
            Action::make('toggle_active')
                ->label(fn (Scientist $record) => $record->is_active ? 'Deactivate' : 'Activate')
                ->action(function (Scientist $record) {
                    $record->update(['is_active' => !$record->is_active]);
                })
                ->requiresConfirmation()
                ->modalHeading(fn (Scientist $record) => $record->is_active ? 'Deactivate Scientist' : 'Activate Scientist')
                ->modalDescription(fn (Scientist $record) => $record->is_active
                    ? 'Are you sure you want to deactivate this scientist? They will lose access to the system.'
                    : 'Are you sure you want to activate this scientist? They will gain access to the system.')
                ->color(fn (Scientist $record) => $record->is_active ? 'danger' : 'success')
                ->icon(fn (Scientist $record) => $record->is_active ? 'heroicon-o-pause-circle' : 'heroicon-o-play-circle'),
        ])

        // Bulk Actions
        ->bulkActions([
            BulkActionGroup::make([
                // Bulk Verify
                BulkAction::make('bulk_verify')
                    ->label('Verify Selected')
                    ->action(function ($records) {
                        $records->each->update(['is_verified' => true]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Verify Multiple Scientists')
                    ->modalDescription('Are you sure you want to verify the selected scientists?')
                    ->modalSubmitActionLabel('Yes, Verify All')
                    ->color('success')
                    ->icon('heroicon-o-check-badge'),

                // Bulk Activate/Deactivate
                BulkAction::make('bulk_activate')
                    ->label('Activate Selected')
                    ->action(function ($records) {
                        $records->each->update(['is_active' => true]);
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-play-circle'),

                BulkAction::make('bulk_deactivate')
                    ->label('Deactivate Selected')
                    ->action(function ($records) {
                        $records->each->update(['is_active' => false]);
                    })
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-pause-circle'),

                // Bulk Delete
                DeleteBulkAction::make()
                    ->label('Delete Selected')
                    ->icon('heroicon-o-trash'),
            ]),
        ])

        // Empty State
        ->emptyStateHeading('No scientists found')
        ->emptyStateDescription('Once you create your first scientist, it will appear here.')
        ->emptyStateIcon('heroicon-o-user-group')
        ->emptyStateActions([
            Action::make('create')
                ->label('Create scientist')
                ->url(ScientistResource::getUrl('create'))
                ->icon('heroicon-o-plus')
                ->button(),
        ])

        // Default Sorting
        ->defaultSort('created_at', 'desc');

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
            'index' => ListScientists::route('/'),
            'create' => CreateScientist::route('/create'),
            'edit' => EditScientist::route('/{record}/edit'),
           // 'availability' => Pages\ViewAvailability::route('/{record}/availability'),
        ];
    }
}
