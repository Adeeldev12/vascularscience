<?php

namespace App\Providers;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use App\Filament\Scientist\Widgets\AvailabilityCalendarWidget;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     //
    // }
//     public function boot()
// {
//     Livewire::component('availability-calendar-widget', AvailabilityCalendarWidget::class);
// }
}
