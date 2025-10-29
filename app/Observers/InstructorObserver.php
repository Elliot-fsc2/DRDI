<?php

namespace App\Observers;

use App\Models\instructor;
use App\Models\User;

class InstructorObserver
{
  /**
   * Handle the instructor "created" event.
   */
  public function creating(Instructor $instructor)
  {
    $last = preg_replace('/\s+/', '', $instructor->last_name);
    $first = preg_replace('/\s+/', '', $instructor->first_name);
    $baseEmail = strtolower($last . '.' . $first) . '@ncst.edu.ph';
    $email = $baseEmail;

    while (User::where('email', $email)->exists()) {
      $randomNumber = rand(1000, 9999);
      $email = strtolower(trim($instructor->last_name . '.' . $instructor->first_name)) . $randomNumber . '@ncst.edu.ph';
    }

    $user = User::create([
      'name' => $instructor->first_name . ' ' . $instructor->last_name,
      'email' => $email,
      'password' => strtolower(str_replace(' ', '', $instructor->last_name)), // Default password, should be changed later
      'role' => 'instructor',
    ]);

    $instructor->user_id = $user->id;
  }

  public function created(instructor $instructor): void {}

  public function updating(Instructor $instructor)
  {
    $baseEmail = strtolower(trim($instructor->last_name . '.' . $instructor->first_name)) . '@ncst.edu.ph';
    $email = $baseEmail;

    while (User::where('email', $email)->exists()) {
      $randomNumber = rand(1000, 9999);
      $email = strtolower(trim($instructor->last_name . '.' . $instructor->first_name)) . $randomNumber . '@ncst.edu.ph';
    }

    $instructor->user->update([
      'name' => $instructor->first_name . ' ' . $instructor->last_name,
      'email' => $email,
      'password' => strtolower(str_replace(' ', '', $instructor->last_name)), // Default password, should be changed later
    ]);
  }

  /**
   * Handle the instructor "updated" event.
   */
  public function updated(instructor $instructor): void {}

  /**
   * Handle the instructor "deleted" event.
   */
  public function deleted(instructor $instructor): void
  {
    //
  }

  /**
   * Handle the instructor "restored" event.
   */
  public function restored(instructor $instructor): void
  {
    //
  }

  /**
   * Handle the instructor "force deleted" event.
   */
  public function forceDeleted(instructor $instructor): void
  {
    //
  }
}
