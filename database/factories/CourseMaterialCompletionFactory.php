<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;
use App\Models\CourseMaterial;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseMaterialCompletion>
 */
class CourseMaterialCompletionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'course_material_id' => CourseMaterial::factory(),
            'completed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
