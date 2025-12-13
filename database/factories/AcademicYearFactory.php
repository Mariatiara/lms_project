<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\School;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = $this->faker->year;
        $nextYear = $year + 1;
        
        return [
            'school_id' => School::factory(),
            'name' => "{$year}/{$nextYear}",
            'semester' => $this->faker->randomElement(['ganjil', 'genap']),
            'is_active' => true,
        ];
    }
}
