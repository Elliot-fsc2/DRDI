<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisFee extends Model
{
    protected $fillable = [
        'thesis_phase',
        'name',
        'description',
        'amount',
    ];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
