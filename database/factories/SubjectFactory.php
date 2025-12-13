<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\School;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
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
            'name' => $this->faker->unique()->randomElement([
                'Matematika', 'Fisika', 'Kimia', 'Biologi', 
                'Sejarah', 'Geografi', 'Sosiologi', 'Ekonomi', 
                'Bahasa Indonesia', 'Bahasa Inggris', 'Seni Budaya', 'PJOK',
                'PKn', 'Agama'
            ]),
            'code' => $this->faker->unique()->bothify('??###'),
        ];
    }
}
