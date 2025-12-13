<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('rapor_semester', 'report_cards');

        Schema::table('report_cards', function (Blueprint $table) {
            $table->renameColumn('siswa_id', 'student_id');
            $table->renameColumn('guru_id', 'teacher_id');
            $table->renameColumn('nilai_tugas', 'formative_score');
            $table->renameColumn('nilai_uts', 'mid_term_score');
            $table->renameColumn('nilai_uas', 'final_term_score');
            $table->renameColumn('nilai_akhir', 'final_grade');
            $table->renameColumn('catatan', 'comments');

            $table->dropColumn(['semester', 'mapel']);
        });

        Schema::table('report_cards', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('predicate')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('report_cards', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropForeign(['subject_id']);
            $table->dropColumn(['academic_year_id', 'subject_id', 'predicate']);
            $table->string('semester')->nullable();
            $table->string('mapel')->nullable();

            $table->renameColumn('student_id', 'siswa_id');
            $table->renameColumn('teacher_id', 'guru_id');
            $table->renameColumn('formative_score', 'nilai_tugas');
            $table->renameColumn('mid_term_score', 'nilai_uts');
            $table->renameColumn('final_term_score', 'nilai_uas');
            $table->renameColumn('final_grade', 'nilai_akhir');
            $table->renameColumn('comments', 'catatan');
        });

        Schema::rename('report_cards', 'rapor_semester');
    }
};
