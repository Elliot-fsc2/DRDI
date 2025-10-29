<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;


class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public $singletons = [
    \Filament\Auth\Http\Responses\Contracts\LoginResponse::class => \App\Http\Response\LoginResponse::class,
    \Filament\Auth\Http\Responses\Contracts\LogoutResponse::class => \App\Http\Response\LogoutResponse::class,
  ];

  public function register(): void {}


  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Model::automaticallyEagerLoadRelationships();
  }
}
