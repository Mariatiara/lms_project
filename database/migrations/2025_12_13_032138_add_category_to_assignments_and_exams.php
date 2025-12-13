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
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('category')->default('knowledge')->after('description'); // 'knowledge', 'skill'
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->string('category')->default('daily')->after('is_published'); // 'daily', 'mid_term', 'final_term'
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('category');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
