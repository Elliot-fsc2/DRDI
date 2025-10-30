<?php

namespace App\Filament\Instructor\Pages;

use Filament\Pages\Page;
use App\Models\AcademicYear;
use App\Models\Announcement;
use Illuminate\Support\Collection;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use BackedEnum;

class InstructorDashboard extends Page
{
  protected string $view = 'filament.instructor.pages.instructor-dashboard';
  protected static string|BackedEnum|null $navigationIcon = Heroicon::Home;
  protected static ?string $navigationLabel = 'Dashboard';
  protected static ?string $title = 'Dashboard';


  /**
   * Return published announcements ordered by pinned + published date.
   *
   * @return \Illuminate\Support\Collection
   */
  public function getAnnouncements(): Collection
  {
    return Announcement::with('author')
      ->published()
      ->orderBy('is_pinned', 'desc')
      ->orderBy('published_at', 'desc')
      ->get();
  }

  /**
   * The currently active academic year (if any).
   */
  public function getCurrentAcademicYear(): ?AcademicYear
  {
    return AcademicYear::where('is_active', true)->first();
  }

  /**
   * Sections handled by the authenticated instructor for the active year.
   *
   * @return \Illuminate\Support\Collection
   */
  public function getHandledSections(): Collection
  {
    $instructor = Auth::user()?->instructor;

    if (! $instructor) {
      return collect();
    }

    return $instructor->sections()
      ->with(['course', 'academicYear'])
      ->whereRelation('academicYear', 'is_active', true)
      ->get();
  }

  public static function canAccess(): bool
  {
    $user = Auth::user();

    if (! $user || ! $user->instructor) {
      return false;
    }

    // Deny access to instructors who have the 'DRDI Head' role. Only allow
    // access when the instructor does NOT have that role.
    return ! $user->instructor->roles()
      ->whereIn('name', [
        'DRDI Head',
      ])->exists();
  }
}
