<?php

namespace App\Http\Middleware;

use App\Filament\Instructor\Pages\InstructorDashboard;
use Closure;
use Illuminate\Http\Request;
use Filament\Pages\Dashboard;
use Symfony\Component\HttpFoundation\Response;

class RedirectToProperPanelMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next)
  {
    if (auth()->check() && auth()->user()->isInstructor()) {
      return redirect()->to(InstructorDashboard::getUrl(panel: 'instructor'));
    }
    return $next($request);
  }
}
