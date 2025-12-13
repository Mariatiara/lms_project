<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\School;
use App\Models\Course;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassSchedule>
 */
class ClassScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        return [
            'school_id' => School::factory(),
            'course_id' => Course::factory(),
            'day_of_week' => $this->faker->randomElement($days),
            'start_time' => '08:00', // Simple defaults, actual logic needed in seeder for conflicts
            'end_time' => '09:00',
        ];
    }
}
