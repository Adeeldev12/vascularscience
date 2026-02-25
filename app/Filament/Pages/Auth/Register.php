<?php

namespace App\Filament\Pages\Auth;

use App\Models\Scientist;
use Filament\Actions\Action;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class Register extends BaseRegister
{
    //     public function mount(): void
    // {
    //     dd('✅ Custom Register Page Loaded');
    // }

    // public function form(Schema $schema): Schema
    // {
    //     return $schema->schema([
    //         Wizard::make([
    //             Step::make('Personal Details')
    //                 ->schema([
    //                     TextInput::make('name')->label('Full Name')->required(),
    //                     TextInput::make('email')->label('Email Address')->email()->required(),
    //                     TextInput::make('phone')->label('Phone Number')->required(),
    //                     TextInput::make('address')->label('Address')->required(),
    //                     FileUpload::make('cv_path')->label('Upload Updated CV')->required(),
    //                     FileUpload::make('hcpc_registration_path')->label('Proof of HCPC or AHCS Registration')->required(),
    //                     DatePicker::make('hcpc_issue_date')->label('HCPC/AHCS Registration Issue Date')->required(),
    //                     TextInput::make('password')->label('Password')->password()->required()->revealable(),
    //                 ]),

    //             Step::make('Compliance & Certificates')
    //                 ->schema([
    //                     FileUpload::make('enhanced_dbs_path')->label('Enhanced DBS/CRB Check')->required(),
    //                     DatePicker::make('enhanced_dbs_issue_date')->label('DBS Issue Date')->required(),
    //                     FileUpload::make('immunisation_record_path')->label('Immunisation Record')->required(),
    //                     DatePicker::make('immunisation_issue_date')->label('Immunisation Issue Date')->required(),
    //                     FileUpload::make('bls_certificate_path')->label('Basic Life Support (BLS) Certificate')->required(),
    //                     DatePicker::make('bls_issue_date')->label('BLS Issue Date')->required(),
    //                     FileUpload::make('health_safety_certification_path')->label('Health & Safety Certification')->required(),
    //                     DatePicker::make('health_safety_issue_date')->label('Health & Safety Issue Date')->required(),
    //                     FileUpload::make('professional_indemnity_insurance_path')->label('Professional Indemnity Insurance')->required(),
    //                     DatePicker::make('professional_indemnity_issue_date')->label('Insurance Issue Date')->required(),
    //                     FileUpload::make('avs_cpd_updates_path')->label('Evidence of AVS/CPD Updates')->required(),
    //                     DatePicker::make('avs_cpd_issue_date')->label('AVS/CPD Issue Date')->required(),
    //                 ]),

    //             Step::make('Bank Details')
    //                 ->schema([
    //                     TextInput::make('bank_name')->label('Bank Name')->required(),
    //                     TextInput::make('account_holder_name')->label('Account Holder Name')->required(),
    //                     TextInput::make('account_number')->label('Account Number')->required(),
    //                     TextInput::make('sort_code')->label('Sort Code')->required(),
    //                 ]),

    //             Step::make('Contract Agreement')
    //                 ->schema([
    //                     Checkbox::make('agreed_to_terms')
    //                         ->label('I have read and agree to the Terms & Conditions')
    //                         ->required()
    //                         ->accepted(),
    //                     FileUpload::make('signed_contract_path')->label('Signed Contract PDF')->required(),
    //                     DatePicker::make('contract_issue_date')->label('Contract Issue Date')->required(),
    //                 ]),
    //         ])->skippable(false)
    //     ]);
    // }
    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Wizard::make([
                Step::make('Personal Details')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required(),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required(),

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
                            ->helperText('UK numbers only: 07 mobile, 01/02 landline, or +44 as 44...'),

                        TextInput::make('address')
                            ->label('Address')
                            ->required(),

                        FileUpload::make('cv_path')
                            ->label('Upload Updated CV')
                            ->disk('public')
                            ->directory('scientists/cv')
                            ->visibility('public')
                            ->required(),

                        FileUpload::make('hcpc_registration_path')
                            ->label('Proof of HCPC or AHCS Registration')
                            ->disk('public')
                            ->directory('scientists/hcpc')
                            ->visibility('public')
                            ->required(),

                        DatePicker::make('hcpc_issue_date')
                            ->label('HCPC/AHCS Registration Issue Date')
                            ->required()
                             ->maxDate(now())                  // no future
    ->minDate(now()->subYear()),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(),
                    ]),

                Step::make('Compliance & Certificates')
                    ->schema([
                        FileUpload::make('enhanced_dbs_path')
                            ->label('Enhanced DBS/CRB Check')
                            ->disk('public')
                            ->directory('scientists/dbs')
                            ->visibility('public')
                            ->required(),

                        DatePicker::make('enhanced_dbs_issue_date')
                            ->label('DBS/CRB Issue Date')
                            ->required()
                             ->maxDate(now())                  // no future
    ->minDate(now()->subYears(3)),

                        FileUpload::make('immunisation_record_path')
                            ->label('Immunisation Record')
                            ->disk('public')
                            ->directory('scientists/immunisation')
                            ->visibility('public')
                            ->required(),

    //                     DatePicker::make('immunisation_issue_date')
    //                         ->label('Immunisation Issue Date')
    //                         ->required()
    //                          ->maxDate(now())                  // no future
    // ->minDate(now()->subYear()),

                        FileUpload::make('bls_certificate_path')
                            ->label('Basic Life Support (BLS) Certificate')
                            ->disk('public')
                            ->directory('scientists/bls')
                            ->visibility('public')
                            ->required(),

                        DatePicker::make('bls_issue_date')
                            ->label('BLS Issue Date')
                            ->required()
                             ->maxDate(now())                  // no future
    ->minDate(now()->subYear()),

                        FileUpload::make('health_safety_certification_path')
                            ->label('Health & Safety Certification')
                            ->disk('public')
                            ->directory('scientists/health_safety')
                            ->visibility('public')
                            ->required(),

                        DatePicker::make('health_safety_issue_date')
                            ->label('Health & Safety Issue Date')
                            ->required()
                             ->maxDate(now())                  // no future
    ->minDate(now()->subYear()),

                        FileUpload::make('professional_indemnity_insurance_path')
                            ->label('Professional Indemnity Insurance')
                            ->disk('public')
                            ->directory('scientists/insurance')
                            ->visibility('public')
                            ->required(),

                        DatePicker::make('professional_indemnity_issue_date')
                            ->label('Insurance Issue Date')
                            ->required()
                             ->maxDate(now())                  // no future
    ->minDate(now()->subYear()),

                        FileUpload::make('avs_cpd_updates_path')
                            ->label('Evidence of AVS/CPD Updates')
                            ->disk('public')
                            ->directory('scientists/avs_cpd')
                            ->visibility('public')
                            ->required(),

                        DatePicker::make('avs_cpd_issue_date')
                            ->label('AVS/CPD Issue Date')
                            ->required()
                             ->maxDate(now())                  // no future
    ->minDate(now()->subYear()),
                    ]),

                Step::make('Bank Details')
                    ->schema([
                        TextInput::make('bank_name')
                            ->label('Bank Name')
                            ->required(),

                        TextInput::make('account_holder_name')
                            ->label('Account Holder Name')
                            ->required(),

                        TextInput::make('account_number')
                            ->label('Account Number')
                            ->required(),
                            // ->numeric()
                            // ->mask('99999999')
                            // ->rule('digits:8'),

                        TextInput::make('sort_code')
                            ->label('Sort Code')
                            ->required()
                            ->rule('regex:/^[0-9]+$/')   // only digits allowed
                            ->helperText('Enter Sort Code without slahes(-)')
                            // ->numeric()
                            // ->mask('999999')
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
                    ]),

                Step::make('Contract Agreement')
                    ->schema([
                        Checkbox::make('agreed_to_terms')
                            ->label('I have read and agree to the Terms & Conditions')
                            ->required()
                            ->accepted(),

                        FileUpload::make('signed_contract_path')
                            ->label('Signed Contract PDF')
                            ->disk('public')
                            ->directory('scientists/contract')
                            ->visibility('public')
                            ->required(),

                        DatePicker::make('contract_issue_date')
                            ->label('Contract Issue Date')
                            ->required()
                             ->maxDate(now())                  // no future
    ->minDate(now()->subYear()),
                    ]),
            ])
                ->skippable(false)
                ->submitAction(new HtmlString('')),
        ]);
    }

    // protected function handleRegistration(array $data): Scientist
    // {
    //     // Clean sort code (remove dashes like 12-34-56)
    //     $data['sort_code'] = str_replace('-', '', $data['sort_code']);

    //     // Final safety validation
    //     if (! ctype_digit($data['sort_code']) || strlen($data['sort_code']) !== 6) {
    //         throw ValidationException::withMessages([
    //             'sort_code' => 'Invalid sort code format.',
    //         ]);
    //     }

    //     if (! ctype_digit($data['account_number']) || strlen($data['account_number']) !== 8) {
    //         throw ValidationException::withMessages([
    //             'account_number' => 'Account number must be exactly 8 digits.',
    //         ]);
    //     }

    //     $data['password'] = Hash::make($data['password']);

    //     return Scientist::create($data);
    // }

    protected function getFormActions(): array
    {
        return [
            Action::make('register')
                ->label('Sign Up')
                ->submit('register')
                ->color('primary')
                ->visible(function () {
                    // Check if we're on the last step
                    $livewire = \Livewire\Livewire::current();
                    $data = $livewire->form->getRawState();

                    // If we have data for all previous steps, show the button
                    // This is a simple check - adjust based on your needs
                    return filled($data['bank_name'] ?? null);
                }),
        ];
    }
}
