<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'school_id',
        'academic_year_id',
        'uploaded_by',
        'reviewed_by',
        'title',
        'report_type',
        'report_period',
        'file_path',
        'description',
        'status',
        'dinas_feedback',
        'reviewed_at',
    ];

    protected $casts = [
        'report_period' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function histories()
    {
        return $this->hasMany(SchoolReportHistory::class)->latest(); // Sort by newest version
    }
}
