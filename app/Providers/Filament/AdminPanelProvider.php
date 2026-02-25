<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use App\Filament\Pages\Auth\Register;
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

class AdminPanelProvider extends PanelProvider

{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default() // ✅ ensures Filament builds a full panel instance
            ->id('admin')
            ->path('admin')
            ->brandLogo(asset('images/Vescular-Science-light.png'))
            ->brandName('Vascular Science')
            ->favicon(asset('images/vascularscience-favicon.webp'))


            // ✅ Authentication pages setup
            ->login() // use default Filament login page
            // ->registration(Register::class) // 👈 custom registration page
            // ->registration()
            // ✅ Basic panel colors
            ->colors([
                'primary' => Color::Amber,
            ])



            // ✅ Auto-discovery
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')

            // ✅ Extra widgets
            ->widgets([
                AccountWidget::class,
                // FilamentInfoWidget::class,
            ])

            // ✅ Middlewares (required by Filament)
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

            // ✅ Auth middleware (for Filament admin routes)
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
