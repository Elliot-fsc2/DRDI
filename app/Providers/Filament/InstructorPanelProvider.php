<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\RedirectToProperPanelMiddleware;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class InstructorPanelProvider extends PanelProvider
{
  public function panel(Panel $panel): Panel
  {
    return $panel
      ->id('instructor')
      ->path('instructor')
      ->brandLogo(fn() => view('filament.logo'))
      ->favicon(asset('images/ncst-logo.png'))
      ->profile(isSimple: false)
      ->colors([
        'primary' => Color::Sky,
      ])
      ->discoverResources(in: app_path('Filament/Instructor/Resources'), for: 'App\Filament\Instructor\Resources')
      ->discoverPages(in: app_path('Filament/Instructor/Pages'), for: 'App\Filament\Instructor\Pages')
      ->pages([
        Dashboard::class,
      ])
      ->spa()
      ->databaseNotifications()
      ->discoverWidgets(in: app_path('Filament/Instructor/Widgets'), for: 'App\Filament\Instructor\Widgets')
      ->widgets([])
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
      ->multiFactorAuthentication([
        AppAuthentication::make()
          ->recoverable(),
        EmailAuthentication::make(),
      ])
      ->viteTheme('resources/css/filament/instructor/theme.css')
      ->authMiddleware([
        // RedirectToProperPanelMiddleware::class,
        Authenticate::class,
      ]);
  }
}
