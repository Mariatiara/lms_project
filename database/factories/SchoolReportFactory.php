<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\School;
use App\Models\AcademicYear;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolReport>
 */
class SchoolReportFactory extends Factory
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
            'academic_year_id' => AcademicYear::factory(),
            'uploaded_by' => User::factory(), // Assuming User factory exists
            'reviewed_by' => null,
            'title' => $this->faker->sentence(4),
            'report_type' => $this->faker->randomElement(['Bulanan', 'Semester', 'Tahunan']),
            'status' => 'submitted',
            'file_path' => 'reports/dummy.pdf',
        ];
    }
}
