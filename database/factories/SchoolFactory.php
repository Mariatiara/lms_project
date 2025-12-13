<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\SchoolStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'npsn' => $this->faker->unique()->numerify('########'),
            'name' => 'SMA ' . $this->faker->city,
            'education_level' => 'SMA',
            'ownership_status' => $this->faker->randomElement(['negeri', 'swasta']),
            'address' => $this->faker->streetAddress,
            'district' => $this->faker->citySuffix,
            'village' => $this->faker->streetName,
            'verification_doc' => 'docs/dummy_verification.pdf',
            'logo' => 'images/dummy_logo.png',
            'status' => SchoolStatus::ACTIVE,
        ];
    }
}
