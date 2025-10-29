<?php

namespace App\Http\Response;

use Filament\Facades\Filament;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as BaseLogoutResponse;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LogoutResponse implements BaseLogoutResponse
{
  /**
   * Create an HTTP response for a successful logout.
   *
   * By default this mirrors Filament's implementation: redirect to the
   * panel-specific login page if available, otherwise to the panel URL.
   *
   * @param  mixed  $request
   * @return RedirectResponse|Redirector
   */
  public function toResponse($request): RedirectResponse | Redirector
  {
    $panel = Filament::getCurrentPanel();
    // If we are in the instructor panel, send to the public login route.
    if ($panel !== null && $panel->getId() === 'instructor') {
      return redirect()->to('/login');
    }

    // Default Filament behavior: redirect to the panel login url if the
    // panel has a login page, otherwise redirect to the panel URL.
    return redirect()->to(
      Filament::hasLogin() ? Filament::getLoginUrl() : Filament::getUrl(),
    );
  }
}
