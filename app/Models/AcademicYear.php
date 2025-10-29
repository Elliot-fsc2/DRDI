<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['year', 'is_active'];

    public function scopeWhereActive($query)
    {
        return $query->where('is_active', true);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
