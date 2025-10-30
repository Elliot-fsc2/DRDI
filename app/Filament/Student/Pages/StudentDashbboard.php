<?php

namespace App\Filament\Student\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class StudentDashbboard extends Page
{
  protected string $view = 'filament.student.pages.student-dashbboard';
  protected static string|BackedEnum|null $navigationIcon = Heroicon::Home;
  protected static ?string $navigationLabel = 'Dashboard';
  protected static ?string $title = 'Dashboard';
}
