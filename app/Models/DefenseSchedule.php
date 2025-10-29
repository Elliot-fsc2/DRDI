<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefenseSchedule extends Model
{
    //
    protected $fillable = [
        'group_id',
        'instructor_id',
        'defense_type',
        'scheduled_at',
        'venue',
        'notes',
        'feedback',
        'grade',
        'panel_members', // This could be a JSON field or a relation to another table
        'passed', // e.g., scheduled, completed, cancelled
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
