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
        Schema::create('school_time_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->integer('period_number')->nullable()->comment('Null for breaks/non-teaching slots');
            $table->string('label'); // e.g. "Jam 1", "Istirahat"
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            // Unique constraint to prevent overlapping periods logic in DB if we wanted, 
            // but simplified to just unique Period Number per Day per School? 
            // Actually, we can have unique period number, but breaks won't have it.
            // Let's rely on application logic for now to avoid complexity with nulls in composite unique keys on some DBs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_time_settings');
    }
};
