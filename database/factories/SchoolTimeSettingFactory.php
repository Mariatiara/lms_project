<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\School;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolTimeSetting>
 */
class SchoolTimeSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'day_of_week' => $this->faker->randomElement(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']),
            'period_number' => $this->faker->numberBetween(1, 10),
            'label' => 'Jam Pelajaran',
            'start_time' => '07:00:00',
            'end_time' => '07:45:00',
        ];
    }
}
