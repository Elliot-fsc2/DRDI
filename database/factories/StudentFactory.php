<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        $middleName = fake()->lastName(); // Using lastName for middle name variety

        // Create the associated user with the specified email format and password
        $user = User::factory()->create([
            'name' => "{$firstName} {$middleName} {$lastName}",
            'email' => strtolower("{$lastName}.{$firstName}@ncst.edu.ph"),
            'password' => $lastName,
            'role' => 'student',
        ]);

        return [
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'student_id' => fake()->unique()->numerify('########'), // 8-digit student ID
            'course_id' => Course::inRandomOrder()->first()->id,
            'user_id' => $user->id,
        ];
    }
}
