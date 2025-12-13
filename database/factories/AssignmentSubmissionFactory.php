<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Assignment;
use App\Models\Student;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssignmentSubmission>
 */
class AssignmentSubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assignment_id' => Assignment::factory(),
            'student_id' => Student::factory(),
            'file_path' => 'submissions/dummy.pdf',
            'score' => $this->faker->optional(0.7)->numberBetween(60, 100), // 70% chance of being graded
            'feedback' => $this->faker->optional()->sentence,
            'submitted_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
