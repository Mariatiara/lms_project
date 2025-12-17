<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('school_report_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_report_id')->constrained()->onDelete('cascade');
            $table->string('file_path'); // File versi sebelumnya
            $table->text('dinas_feedback')->nullable(); // Feedback yang diterima pada versi ini
            $table->enum('status', ['submitted', 'reviewed', 'accepted', 'rejected', 'revision_needed']); // Status saat diarsipkan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_report_histories');
    }
};
