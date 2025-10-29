<?php

namespace App\Filament\Instructor\Schema;

use Filament\Schemas\Schema;

class StudentsRepeat
{
    public static function getSchema(): array
    {
        return [
            self::infolist(Schema::make()),
        ];
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->record(['students' => $this->section->students])
            ->components(self::getSchema())
            ->columns(1)
            ->hiddenLabel()
            ->contained(false)
            ->grid(3);
    }
}
