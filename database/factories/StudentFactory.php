<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\School;
use App\Models\Classroom;
use App\Enums\StudentStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'school_id' => School::factory(),
            'classroom_id' => Classroom::factory(),
            'nama' => $this->faker->name,
            'nis' => $this->faker->unique()->numerify('####'),
            'alamat' => $this->faker->address,
            'telepon' => $this->faker->phoneNumber,
            // 'status' is likely handled by default database value or user input, 
            // but we can add it if needed. Leaving it out as per earlier inspection of seeder behavior 
            // or adding explicit status if required. 
            // FullDummyDataSeeder used StudentStatus::ACTIVE, so let's keep it safe.
            'status' => StudentStatus::ACTIVE,
        ];
    }
}
