<?php

namespace Database\Seeders;

use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@ncst.edu.ph',
            'password' => 'admin',
            'role' => 'admin',
        ]);

        $this->call([
            DepartmentSeeder::class,
            CourseSeeder::class,
            RoleSeeder::class,
        ]);

        Instructor::factory(50)->create();
        Student::factory(500)->create();

    }
}
