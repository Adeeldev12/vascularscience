<?php

namespace App\Providers;

use App\Filament\Pages\ScientistLogin;
use App\Filament\Pages\ScientistRegistration;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class FilamentRouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            // Public scientist routes
            Route::middleware('web')
                ->prefix('admin')
                ->group(function () {
                    Route::get('/scientist-registration', ScientistRegistration::class)
                         ->name('scientist.registration');

                    Route::get('/scientist-login', ScientistLogin::class)
                         ->name('scientist.login');
                });
        });
    }
}
