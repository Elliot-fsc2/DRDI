<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Computer Studies'],
            ['name' => 'Criminal Justice'],
            ['name' => 'Education'],
            ['name' => 'Engineering'],
            ['name' => 'Hospitality Management'],
            ['name' => 'Business Administration'],
        ];

        foreach ($departments as $department) {
            \App\Models\Department::firstOrCreate(['name' => $department['name']]);
        }
    }
}
