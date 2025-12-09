<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolTimeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'day_of_week',
        'period_number', // Nullable
        'label',
        'start_time',
        'end_time',
    ];
}
