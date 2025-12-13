<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\School;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolEvent>
 */
class SchoolEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 month');
        return [
            'school_id' => School::factory(),
            'title' => $this->faker->sentence(3),
            'start_date' => $startDate,
            'end_date' => $this->faker->dateTimeBetween($startDate, '+2 days'),
            'type' => $this->faker->randomElement(['event', 'meeting', 'holiday', 'other']),
            'is_holiday' => $this->faker->boolean(20),
        ];
    }
}
