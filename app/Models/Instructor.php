<?php

namespace App\Models;

use App\Observers\InstructorObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(InstructorObserver::class)]
class Instructor extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'middle_name', 'last_name', 'department_id', 'user_id'];

    protected $casts = [
        'role_id' => 'array',
    ];

    protected $appends = ['full_name'];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->first_name} {$this->middle_name} {$this->last_name}",
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'instructor_role', 'instructor_id', 'role_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function groups()
    {
        return $this->hasManyThrough(Group::class, Section::class);
    }

    public function canManage(): bool
    {
        return $this->roles()->whereIn('name', [
            'Cluster Head',
            'DRDI Head',
        ])->exists();
    }

    // public function canManageCourse(): bool
    // {
    //     return $this->roles()->whereIn('name', [
    //         'Cluster Head',
    //         'DRDI Head',
    //     ])->exists();
    // }

    public function scopeWhereInstructorDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }
}
