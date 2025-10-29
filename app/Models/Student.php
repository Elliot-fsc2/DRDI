<?php

namespace App\Models;

use App\Models\Course;
use App\Observers\StudentObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(StudentObserver::class)]
class Student extends Model
{
  use HasFactory;

  protected $fillable = [
    'first_name',
    'middle_name',
    'last_name',
    'student_number',
    'course_id',
    'user_id',
  ];

  protected $appends = ['full_name'];

  protected function fullName(): Attribute
  {
    return Attribute::make(
      get: fn() => "{$this->first_name} {$this->middle_name} {$this->last_name}",
    );
  }

  public function course()
  {
    return $this->belongsTo(Course::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function section()
  {
    return $this->belongsToMany(Section::class, 'section_student', 'student_id', 'section_id');
  }

  public function groups()
  {
    return $this->belongsToMany(Group::class, 'group_student', 'student_id', 'group_id');
  }

  public function scopeWhereActiveGroup($query)
  {
    return $query->whereHas('groups', function ($groupQuery) {
      $groupQuery->whereRelation('section.academicYear', 'is_active', true);
    });
  }

  public function scopeWhereNotInSection($query)
  {
    return $query->whereDoesntHave('section', function ($query) {
      $query->whereHas('academicYear', function ($academicYearQuery) {
        $academicYearQuery->where('is_active', true);
      });
    });
  }

  public function scopeWhereStudentInDepartment($query, $departmentId): mixed
  {
    return $query->whereHas('course', function ($q) use ($departmentId) {
      $q->where('department_id', $departmentId);
    });
  }

  public function scopeWhereNotInGroup($query, $sectionId): mixed
  {
    return $query
      ->whereHas('section', function ($q) use ($sectionId) {
        $q->where('sections.id', $sectionId);
      })
      ->whereDoesntHave('groups');
  }
}
