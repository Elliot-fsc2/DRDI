<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = [
        'group_id',
        'title',
        'description',
        'status',
        'is_final',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'is_final' => 'boolean',
        ];
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
