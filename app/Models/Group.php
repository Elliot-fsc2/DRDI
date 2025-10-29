<?php

namespace App\Models;

use App\Observers\GroupObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(GroupObserver::class)]
class Group extends Model
{
    protected $fillable = [
        'name',
        'section_id',
        'leader_id',
        'status',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function leader()
    {
        return $this->belongsTo(Student::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(Student::class, 'group_student', 'group_id', 'student_id');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    public function personnels()
    {
        return $this->hasMany(GroupPersonel::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
}
