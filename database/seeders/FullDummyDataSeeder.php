<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\School;
use App\Models\SchoolTimeSetting;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Course;
use App\Models\ClassSchedule;
use App\Models\CourseMaterial;
use App\Models\Assignment;
use App\Enums\UserRole;
use App\Enums\SchoolStatus;
use App\Enums\StudentStatus;

class FullDummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Create School
        $school = School::create([
            'npsn' => '12345678',
            'name' => 'SMA Negeri 1 Digital',
            'education_level' => 'SMA',
            'ownership_status' => 'negeri',
            'address' => 'Jl. Pendidikan No. 1',
            'district' => 'Kecamatan Cerdas',
            'village' => 'Kelurahan Pintar',
            'verification_doc' => 'dummy_doc.pdf',
            'logo' => 'dummy_logo.png',
            'status' => SchoolStatus::ACTIVE,
        ]);

        $this->command->info('School Created.');

        // 2. School Time Settings (Senin - Jumat)
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        // Simple schedule: 3 periods, break, 2 periods
        foreach ($days as $day) {
            // Period 1
            SchoolTimeSetting::create([
                'school_id' => $school->id,
                'day_of_week' => $day,
                'period_number' => 1,
                'label' => 'Jam Ke-1',
                'start_time' => '07:00:00',
                'end_time' => '07:45:00',
            ]);
            // Period 2
            SchoolTimeSetting::create([
                'school_id' => $school->id,
                'day_of_week' => $day,
                'period_number' => 2,
                'label' => 'Jam Ke-2',
                'start_time' => '07:45:00',
                'end_time' => '08:30:00',
            ]);
            // Break
            SchoolTimeSetting::create([
                'school_id' => $school->id,
                'day_of_week' => $day,
                'period_number' => null,
                'label' => 'Istirahat',
                'start_time' => '08:30:00',
                'end_time' => '08:45:00',
            ]);
            // Period 3
            SchoolTimeSetting::create([
                'school_id' => $school->id,
                'day_of_week' => $day,
                'period_number' => 3,
                'label' => 'Jam Ke-3',
                'start_time' => '08:45:00',
                'end_time' => '09:30:00',
            ]);
        }
        $this->command->info('Time Settings Created.');

        // 3. Academic Year
        $academicYear = AcademicYear::create([
            'school_id' => $school->id,
            'name' => '2024/2025',
            'semester' => 'ganjil',
            'is_active' => true,
        ]);

        // 4. Users & Roles
        // Headmaster
        $kepsekUser = User::create([
            'name' => 'Budi Santoso (Kepsek)',
            'email' => 'kepsek@sman1.sch.id',
            'password' => Hash::make('password'),
            'role' => UserRole::KEPALA_SEKOLAH,
            'school_id' => $school->id,
            'email_verified_at' => now(),
        ]);
        
        // School Admin
        $adminUser = User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@sman1.sch.id',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN_SEKOLAH,
            'school_id' => $school->id,
            'email_verified_at' => now(),
        ]);

        // 5. Teachers & Subjects
        $subjectsData = [
            ['name' => 'Matematika Wajib', 'code' => 'MAT-W'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIND'],
            ['name' => 'Bahasa Inggris', 'code' => 'BING'],
            ['name' => 'Fisika', 'code' => 'FIS'],
        ];

        $teachers = [];
        $subjects = [];

        foreach ($subjectsData as $idx => $data) {
            $subj = Subject::create([
                'school_id' => $school->id,
                'name' => $data['name'],
                'code' => $data['code'],
            ]);
            $subjects[] = $subj;

            // Create Teacher for this subject
            $tUser = User::create([
                'name' => 'Guru ' . $data['name'],
                'email' => 'guru' . strtolower($data['code']) . '@sman1.sch.id',
                'password' => Hash::make('password'),
                'role' => UserRole::GURU,
                'school_id' => $school->id,
                'email_verified_at' => now(),
            ]);

            $teacher = Teacher::create([
                'user_id' => $tUser->id,
                'school_id' => $school->id,
                'nip' => '1980010120000' . $idx,
                'specialization' => $subj->name,
            ]);
            $teachers[] = $teacher;
        }

        // 6. Classrooms
        $classX = Classroom::create([
            'school_id' => $school->id,
            'academic_year_id' => $academicYear->id,
            'name' => 'X IPA 1',
            'grade_level' => 10,
        ]);
        
        // 7. Students (Test Student)
        $siswaUser = User::create([
            'name' => 'Siswa Teladan',
            'email' => 'siswa@sman1.sch.id',
            'password' => Hash::make('password'),
            'role' => UserRole::SISWA,
            'school_id' => $school->id,
            'email_verified_at' => now(),
        ]);

        $student = Student::create([
            'user_id' => $siswaUser->id,
            'school_id' => $school->id,
            'classroom_id' => $classX->id,
            'nis' => '1001',
            'nama' => $siswaUser->name,
            'status' => StudentStatus::ACTIVE,
        ]);

        $this->command->info('Users, Teachers, Students Created.');

        // 8. Courses & Schedules
        // Math Course
        $mathCourse = Course::create([
            'school_id' => $school->id,
            'academic_year_id' => $academicYear->id,
            'classroom_id' => $classX->id,
            'subject_id' => $subjects[0]->id, // Math
            'teacher_id' => $teachers[0]->id, // Math Teacher
        ]);

        // Schedule Math: Monday Period 1-2
        ClassSchedule::create([
            'school_id' => $school->id,
            'course_id' => $mathCourse->id,
            'day_of_week' => 'monday',
            'start_time' => '07:00:00',
            'end_time' => '08:30:00', // P1 + P2
        ]);
        
        // Physics Course
        $physCourse = Course::create([
            'school_id' => $school->id,
            'academic_year_id' => $academicYear->id,
            'classroom_id' => $classX->id,
            'subject_id' => $subjects[3]->id, // Physics
            'teacher_id' => $teachers[3]->id, // Physics Teacher
        ]);

        // Schedule Physics: Tuesday Period 3
        ClassSchedule::create([
            'school_id' => $school->id,
            'course_id' => $physCourse->id,
            'day_of_week' => 'tuesday',
            'start_time' => '08:45:00',
            'end_time' => '09:30:00', 
        ]);

         // 9. Materials
        CourseMaterial::create([
            'course_id' => $mathCourse->id,
            'title' => 'Pengenalan Aljabar',
            'description' => 'Materi dasar aljabar linear dan penggunaannya.',
            'file_path' => 'dummy/aljabar.pdf', // Dummy path
            'file_type' => 'pdf',
        ]);

        // 10. Assignments
        Assignment::create([
             'course_id' => $mathCourse->id,
             'title' => 'Latihan Soal Aljabar 1',
             'description' => 'Kerjakan halaman 10-12 dari buku paket.',
             'due_date' => Carbon::now()->addDays(3),
        ]);

        $this->command->info('Dummy Data Generation Complete!');
    }
}
