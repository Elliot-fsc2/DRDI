<?php

namespace App\Models;

use App\Observers\SectionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(SectionObserver::class)]
class Section extends Model
{
    protected $fillable = [
        'name',
        'course_id',
        'instructor_id',
        'academic_year_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'section_student', 'section_id', 'student_id');
    }

    public function scopeWhereActive($query)
    {
        return $query->whereRelation('academicYear', 'is_active', true);
    }
}
