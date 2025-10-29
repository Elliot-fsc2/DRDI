<?php

namespace App\Observers;

use App\Models\Student;
use App\Models\User;

class StudentObserver
{
  /**
   * Handle the Student "created" event.
   */
  public function creating(Student $student): void
  {
    // Automatically create and associate a User when a Student is created
    $baseEmail = strtolower(trim($student->last_name . '.' . $student->first_name)) . '@ncst.edu.ph';
    $email = $baseEmail;

    while (User::where('email', $email)->exists()) {
      $randomNumber = rand(1000, 9999);
      $email = strtolower(trim($student->last_name . '.' . $student->first_name)) . $randomNumber . '@ncst.edu.ph';
    }

    $user = User::create([
      'name' => $student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name,
      'email' => $email,
      'password' => strtolower(str_replace(' ', '', $student->last_name)), // Default password, should be changed later
      'role' => 'student',
    ]);

    $student->user_id = $user->id;
  }

  public function created(Student $student): void
  {
    //
  }

  public function updating(Student $student): void
  {
    // Update associated User when Student is updated
    if ($student->user) {
      $baseEmail = strtolower(trim($student->last_name . '.' . $student->first_name)) . '@student.ncst.edu.ph';
      $email = $baseEmail;

      // Skip email check if it's the same user's current email
      while (User::where('email', $email)->where('id', '!=', $student->user->id)->exists()) {
        $randomNumber = rand(1000, 9999);
        $email = strtolower(trim($student->last_name . '.' . $student->first_name)) . $randomNumber . '@student.ncst.edu.ph';
      }

      $student->user->update([
        'name' => $student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name,
        'email' => $email,
        'password' => strtolower(str_replace(' ', '', $student->last_name)), // Default password, should be changed later
      ]);
    }
  }

  /**
   * Handle the Student "updated" event.
   */
  public function updated(Student $student): void
  {
    //
  }

  /**
   * Handle the Student "deleted" event.
   */
  public function deleted(Student $student): void
  {
    // Delete associated User when Student is deleted
    if ($student->user) {
      $student->user->delete();
    }
  }

  /**
   * Handle the Student "restored" event.
   */
  public function restored(Student $student): void
  {
    //
  }

  /**
   * Handle the Student "force deleted" event.
   */
  public function forceDeleted(Student $student): void
  {
    //
  }
}
