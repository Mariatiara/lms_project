<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Exam;
use App\Models\Student;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamAttempt>
 */
class ExamAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'student_id' => Student::factory(),
            'started_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
            'finished_at' => $this->faker->dateTimeBetween('now', '+1 hour'),
            'total_score' => $this->faker->numberBetween(0, 100),
        ];
    }
}
