<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Scientist\Widgets\AvailabilityCalendarWidget;
use App\Filament\Resources\Availabilities\AvailabilityResource;

class ScientistPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('scientist')
            ->path('scientist')

            ->resources([
            AvailabilityResource::class,
        ])
            ->brandLogo(asset('images/Vescular-Science-light.png'))
            ->brandName('Vascular Science')
            ->favicon(asset('images/vascularscience-favicon.webp'))
            ->renderHook(
    'panels::footer',
    fn () => view('filament.footer')
)

            ->authGuard('scientist') // 👈 use the scientist guard
            ->login()                // 👈 enable login page
            ->registration(\App\Filament\Pages\Auth\Register::class) // 👈 use your custom multi-step register form
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Scientist/Resources'), for: 'App\Filament\Scientist\Resources')
            ->discoverPages(in: app_path('Filament/Scientist/Pages'), for: 'App\Filament\Scientist\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Scientist/Widgets'), for: 'App\Filament\Scientist\Widgets')
            ->widgets([
                AccountWidget::class,
                // FilamentInfoWidget::class, 
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
             ->renderHook(
            'panels::body.end',
            fn (): string => '<style>
                /* Hide register button on all steps except the last one */
                .register-button-hidden {
                    display: none !important;
                }

                /* Show the button only on step 4 (Contract Agreement) */
                [data-step="3"] .register-button-hidden {
                    display: block !important;
                }
            </style>'
        );
    }

}
