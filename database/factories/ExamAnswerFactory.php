<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamAnswer>
 */
class ExamAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exam_attempt_id' => ExamAttempt::factory(),
            'exam_question_id' => ExamQuestion::factory(),
            'answer' => 'Sample Answer',
            'score' => $this->faker->numberBetween(0, 10),
        ];
    }
}
