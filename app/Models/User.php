<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'role',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var list<string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function canAccessPanel(Panel $panel): bool
  {
    return match ($panel->getId()) {
      'instructor' => $this->role === 'instructor',
      'student' => $this->role === 'student' || $this->role === 'instructor',
      default => $this->role === 'admin', // Allow admin access to default panel
    };
  }

  public function instructor(): \Illuminate\Database\Eloquent\Relations\HasOne
  {
    return $this->hasOne(Instructor::class);
  }

  public function student(): \Illuminate\Database\Eloquent\Relations\HasOne
  {
    return $this->hasOne(Student::class);
  }

  public function groups(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
  {
    return $this->hasManyThrough(
      Group::class,      // Final model
      Section::class,    // Intermediate model
      'instructor_id',   // Foreign key on sections table (points to users.id)
      'section_id',      // Foreign key on groups table (points to sections.id)
      'id',             // Local key on users table
      'id'              // Local key on sections table
    );
  }

  public function section()
  {
    return $this->belongsToMany(Section::class, 'section_student', 'student_id', 'section_id')
      ->withTimestamps();
  }

  public function isAdmin(): bool
  {
    return $this->role === 'admin';
  }

  public function isInstructor(): bool
  {
    return $this->role === 'instructor';
  }

  public function isStudent(): bool
  {
    return $this->role === 'student';
  }
}
