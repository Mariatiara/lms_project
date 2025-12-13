<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Exam;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamQuestion>
 */
class ExamQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['multiple_choice', 'essay', 'true_false']);
        
        $options = null;
        $correct = 'Sample Answer';

        if ($type === 'multiple_choice') {
            $options = [
                'A' => $this->faker->word,
                'B' => $this->faker->word,
                'C' => $this->faker->word,
                'D' => $this->faker->word,
            ];
            $correct = 'A';
        } elseif ($type === 'true_false') {
            $correct = $this->faker->randomElement(['true', 'false']);
        }

        return [
            'exam_id' => Exam::factory(),
            'question_type' => $type,
            'question_text' => $this->faker->sentence(10) . '?',
            'points' => $this->faker->numberBetween(1, 10),
            'options' => $options,
            'correct_answer' => $correct,
        ];
    }
}
