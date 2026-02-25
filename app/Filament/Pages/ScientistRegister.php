<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Scientist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class ScientistRegister extends Page
{
    protected string $view = 'filament::filament.pages.scientist-register';

    protected static bool $shouldRegisterNavigation = false; // hides it from sidebar

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $phone;
    public $address;
    public $agreed_to_terms;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('email')->required()->email()->unique('scientists', 'email'),
            TextInput::make('password')->password()->required()->minLength(8),
            TextInput::make('password_confirmation')->password()->required()->same('password'),
            TextInput::make('phone')->tel()->label('Phone Number'),
            TextInput::make('address')->label('Address'),
            Checkbox::make('agreed_to_terms')
                ->label('I agree to the Terms and Conditions')
                ->accepted()
                ->required(),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $scientist = Scientist::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'agreed_to_terms' => $data['agreed_to_terms'],
            'is_active' => true,
            'is_verified' => false,
            'profile_completed' => false,
        ]);

        // Auto-login scientist
        Auth::guard('scientist')->login($scientist);

        Notification::make()
            ->title('Registration successful!')
            ->success()
            ->send();

        $this->redirect(route('filament.admin.pages.dashboard'));
    }
}
