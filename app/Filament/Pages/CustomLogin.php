<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Auth\Pages\Login;

class CustomLogin extends Login
{
    protected string $view = 'filament.pages.custom-login';
}
