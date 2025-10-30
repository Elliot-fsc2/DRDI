<?php

namespace App\Http\Response;

use App\Filament\Instructor\Pages\InstructorDashboard;
use App\Filament\Student\Pages\StudentDashbboard;
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
      // The Instructor model stores roles as a belongsToMany relation and may
      // also cast a role_id attribute to an array. Check both safely.
      $instructor = $user->instructor; // hasOne relation returns the model or null

      $hasRoleFour = false;

      if ($instructor) {
        // If there's a role_id attribute cast to array, check it first
        if (is_array($instructor->role_id) && in_array(4, $instructor->role_id, true)) {
          $hasRoleFour = true;
        }

        // Otherwise, check the roles relation for role id 4. Qualify the column
        // name with the roles table to avoid ambiguous-column errors on sqlite.
        if (! $hasRoleFour && $instructor->roles()->where('roles.id', 4)->exists()) {
          $hasRoleFour = true;
        }
      }

      if ($hasRoleFour) {
        return redirect()->to(Dashboard::getUrl(panel: 'instructor'));
      }

      return redirect()->to(InstructorDashboard::getUrl(panel: 'instructor'));
    }

    return redirect()->to(StudentDashbboard::getUrl(panel: 'student'));
  }
}
