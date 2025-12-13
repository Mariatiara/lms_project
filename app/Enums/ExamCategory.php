<?php

namespace App\Enums;

enum ExamCategory: string {
    case Daily = 'daily';
    case MidTerm = 'mid_term';
    case FinalTerm = 'final_term';

    public function label(): string {
        return match($this) {
            self::Daily => 'Ulangan Harian',
            self::MidTerm => 'UTS (Ujian Tengah Semester)',
            self::FinalTerm => 'UAS (Ujian Akhir Semester)',
        };
    }
}
