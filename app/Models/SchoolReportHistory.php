<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolReportHistory extends Model
{
    protected $fillable = [
        'school_report_id',
        'file_path',
        'dinas_feedback',
        'status',
    ];

    public function report()
    {
        return $this->belongsTo(SchoolReport::class, 'school_report_id');
    }
}
