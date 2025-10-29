<?php

namespace App\Http\Response;

use App\Filament\Instructor\Pages\InstructorDashboard;
use App\Filament\Student\Pages\Teststudent;
use Filament\Pages\Dashboard;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as BaseLoginResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements BaseLoginResponse
{
  /**
   * Create an HTTP response for a successful login.
   *
   * Redirect instructors to the instructor panel, students to the student
   * panel (if the methods exist on the user model), otherwise fallback to
   * the intended URL or the default Filament dashboard.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse|\Livewire\Features\SupportRedirects\Redirector
   */
  public function toResponse($request): RedirectResponse | Redirector
  {
    /** @var \App\Models\User|null $user */
    $user = Auth::user();

    if (auth()->user()->isInstructor()) {
      return redirect()->to(InstructorDashboard::getUrl(panel: 'instructor'));
    }

    if ($user->isStudent()) {
      return redirect()->to(Teststudent::getUrl(panel: 'student'));
    }

    // Default: redirect to the intended URL (if any) or the main Filament dashboard
    return redirect()->intended(Dashboard::getUrl(panel: 'admin'));
  }
}
