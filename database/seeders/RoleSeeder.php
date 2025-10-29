<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Instructor'],
            ['name' => 'Cluster Head'],
            ['name' => 'Course Adviser'],
            ['name' => 'DRDI Head'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::firstOrCreate(['name' => $role['name']]);
        }
    }
}
