<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupThesisFee extends Model
{
    protected $fillable = [
        'group_id',
        'thesis_fee_id',
        'payment_status',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function thesisFee()
    {
        return $this->belongsTo(ThesisFee::class);
    }
}
