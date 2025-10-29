<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPersonel extends Model
{
    protected $fillable = [
        'group_id',
        'instructor_id',
        'role',
    ];

    // Define relationships and other model methods here
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
}
