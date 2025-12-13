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
        // First set default for existing nulls/invalid
        DB::table('exams')->whereNotIn('category', ['daily', 'mid_term', 'final_term'])->update(['category' => 'daily']);

        Schema::table('exams', function (Blueprint $table) {
            // Modify column to enum. 
            // Note: 'change()' requires dbal. If not present, we might need raw SQL or just 'string'.
            // User requested ENUM type.
            $table->enum('category', ['daily', 'mid_term', 'final_term'])->default('daily')->change();
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->string('category')->nullable()->change();
        });
    }
};
