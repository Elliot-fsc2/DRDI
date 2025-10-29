<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get department IDs by name
        $computerStudies = \App\Models\Department::where('name', 'Computer Studies')->first();
        $criminalJustice = \App\Models\Department::where('name', 'Criminal Justice')->first();
        $education = \App\Models\Department::where('name', 'Education')->first();
        $engineering = \App\Models\Department::where('name', 'Engineering')->first();
        $hospitalityManagement = \App\Models\Department::where('name', 'Hospitality Management')->first();
        $businessAdmin = \App\Models\Department::where('name', 'Business Administration')->first();

        $courses = [
            // Computer Studies
            ['name' => 'Bachelor of Science in Information Technology', 'code' => 'BSIT', 'department_id' => $computerStudies?->id],
            ['name' => 'Bachelor of Science in Computer Science', 'code' => 'BSCS', 'department_id' => $computerStudies?->id],
            ['name' => 'Associate in Computer Technology', 'code' => 'ACT', 'department_id' => $computerStudies?->id],

            // Criminal Justice
            ['name' => 'Bachelor of Science in Criminology', 'code' => 'BSCrim', 'department_id' => $criminalJustice?->id],

            // Education
            ['name' => 'Bachelor of Secondary Education', 'code' => 'BSEd', 'department_id' => $education?->id],

            // Engineering
            ['name' => 'Bachelor of Science in Civil Engineering', 'code' => 'BSCE', 'department_id' => $engineering?->id],
            ['name' => 'Bachelor of Science in Mechanical Engineering', 'code' => 'BSME', 'department_id' => $engineering?->id],
            ['name' => 'Bachelor of Science in Electrical Engineering', 'code' => 'BSEE', 'department_id' => $engineering?->id],

            // Hospitality Management
            ['name' => 'Bachelor of Science in Hospitality Management', 'code' => 'BSHM', 'department_id' => $hospitalityManagement?->id],
            ['name' => 'Bachelor of Science in Tourism Management', 'code' => 'BSTM', 'department_id' => $hospitalityManagement?->id],

            // Business Administration
            ['name' => 'Bachelor of Science in Business Administration', 'code' => 'BSBA', 'department_id' => $businessAdmin?->id],
            ['name' => 'Bachelor of Science in Accountancy', 'code' => 'BSA', 'department_id' => $businessAdmin?->id],
        ];

        foreach ($courses as $course) {
            if ($course['department_id']) { // Only create if department exists
                \App\Models\Course::firstOrCreate([
                    'code' => $course['code'],
                ], [
                    'name' => $course['name'],
                    'department_id' => $course['department_id'],
                ]);
            }
        }
    }
}
