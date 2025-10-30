<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use App\Models\Student;
use App\Models\Instructor;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasAppAuthentication, HasEmailAuthentication, HasAppAuthenticationRecovery
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
    'app_authentication_secret',
    'app_authentication_recovery_codes',
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
      'app_authentication_secret' => 'encrypted',
      'app_authentication_recovery_codes' => 'encrypted:array',
      'has_email_authentication' => 'boolean',
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

  public function getAppAuthenticationSecret(): ?string
  {
    return $this->app_authentication_secret;
  }

  public function saveAppAuthenticationSecret(?string $secret): void
  {
    $this->app_authentication_secret = $secret;
    $this->save();
  }

  public function getAppAuthenticationHolderName(): string
  {
    return $this->email;
  }

  /**
   * @return ?array<string>
   */
  public function getAppAuthenticationRecoveryCodes(): ?array
  {
    // This method should return the user's saved app authentication recovery codes.

    return $this->app_authentication_recovery_codes;
  }

  /**
   * @param  array<string> | null  $codes
   */
  public function saveAppAuthenticationRecoveryCodes(?array $codes): void
  {
    // This method should save the user's app authentication recovery codes.

    $this->app_authentication_recovery_codes = $codes;
    $this->save();
  }
  public function hasEmailAuthentication(): bool
  {
    // This method should return true if the user has enabled email authentication.

    return $this->has_email_authentication;
  }

  public function toggleEmailAuthentication(bool $condition): void
  {
    // This method should save whether or not the user has enabled email authentication.

    $this->has_email_authentication = $condition;
    $this->save();
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
