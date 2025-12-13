<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeWeights extends Model
{
    protected $fillable = [
        'school_id',
        'category',
        'weight'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
