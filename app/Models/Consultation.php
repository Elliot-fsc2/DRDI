<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'group_id',
        'instructor_id',
        'consultation_date',
        'student_concerns',
        'instructor_feedback',
        'status',
        'next_consultation_date',
        'location',
        'consultation_method',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
}
