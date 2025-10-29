<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $nameParts = array_filter([$data['first_name'] ?? null, $data['middle_name'] ?? null, $data['last_name'] ?? null]);
        $fullName = implode(' ', $nameParts);

        // Check if user already exists by full name
        $user = User::where('name', 'like', '%'.$fullName.'%')->first();

        if (! $user) {
            // Generate email from first name and last name
            $firstName = strtolower($data['first_name'] ?? '');
            $lastName = strtolower($data['last_name'] ?? '');
            $email = $lastName.'.'.$firstName.'@ncst.edu.ph';

            // Ensure email is unique by adding a number if needed
            $originalEmail = $email;
            $counter = 1;
            while (User::where('email', $email)->exists()) {
                $email = str_replace('@', $counter.'@', $originalEmail);
                $counter++;
            }

            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                // default temporary password -> will be hashed by User model cast
                'password' => $lastName ?: 'password',
                'role' => 'student',
            ]);
        }

        $data['user_id'] = $user->id;

        return $data;
    }
}
