<?php

namespace App\Filament\Instructor\Pages;

use App\Models\Student;
use BackedEnum;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;

class ManageStudentsDetails extends Page
{
  protected string $view = 'filament.instructor.pages.manage-students-details';
  protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;
  protected static ?string $slug = 'manage-students/{student}/details';
  protected static bool $shouldRegisterNavigation = false;

  public Student $student;

  public function mount(Student $student): void
  {
    $this->student = $student;
  }

  public function getTitle(): string
  {
    return $this->student->full_name . ' - Student Details';
  }

  public function content(Schema $schema): Schema
  {
    return $schema
      ->components([
        Tabs::make('Student Details')
          ->tabs([
            Tab::make('Personal Information')
              ->icon('heroicon-o-user')
              ->schema([
                ComponentsSection::make('Basic Information')
                  ->icon('heroicon-o-identification')
                  ->columns(2)
                  ->schema([
                    TextEntry::make('first_name')
                      ->label('First Name')
                      ->icon('heroicon-o-user')
                      ->weight(FontWeight::SemiBold)
                      ->size('lg'),
                    TextEntry::make('middle_name')
                      ->label('Middle Name')
                      ->icon('heroicon-o-user')
                      ->placeholder('No middle name')
                      ->weight(FontWeight::SemiBold)
                      ->size('lg'),
                    TextEntry::make('last_name')
                      ->label('Last Name')
                      ->icon('heroicon-o-user')
                      ->weight(FontWeight::SemiBold)
                      ->size('lg')
                      ->columnSpanFull(),
                    TextEntry::make('full_name')
                      ->label('Full Name')
                      ->icon('heroicon-o-user-circle')
                      ->weight(FontWeight::Bold)
                      ->size('xl')
                      ->color('primary')
                      ->columnSpanFull(),
                    TextEntry::make('student_number')
                      ->label('Student Number')
                      ->icon('heroicon-o-identification')
                      ->badge()
                      ->color('info'),
                    TextEntry::make('user.email')
                      ->label('Email Address')
                      ->icon('heroicon-o-envelope')
                      ->copyable()
                      ->copyMessage('Email copied!')
                      ->color('gray'),
                  ]),

                ComponentsSection::make('Academic Information')
                  ->icon('heroicon-o-academic-cap')
                  ->columns(2)
                  ->schema([
                    TextEntry::make('course.name')
                      ->label('Course')
                      ->icon('heroicon-o-book-open')
                      ->badge()
                      ->color('success'),
                    TextEntry::make('course.code')
                      ->label('Course Code')
                      ->icon('heroicon-o-hashtag')
                      ->badge()
                      ->color('warning'),
                    TextEntry::make('course.department.name')
                      ->label('Department')
                      ->icon('heroicon-o-building-office')
                      ->columnSpanFull(),
                  ]),
              ]),

            Tab::make('Enrollment Details')
              ->icon('heroicon-o-academic-cap')
              ->schema([
                ComponentsSection::make('Current Enrollments')
                  ->icon('heroicon-o-clipboard-document-list')
                  ->schema([
                    RepeatableEntry::make('section')
                      ->label('Enrolled Sections')
                      ->schema([
                        TextEntry::make('name')
                          ->label('Section Name')
                          ->icon('heroicon-o-squares-2x2')
                          ->weight(FontWeight::Bold)
                          ->size('lg'),
                        TextEntry::make('course.name')
                          ->label('Course')
                          ->icon('heroicon-o-book-open')
                          ->badge()
                          ->color('info'),
                        TextEntry::make('instructor.user.name')
                          ->label('Instructor')
                          ->icon('heroicon-o-user-circle')
                          ->weight(FontWeight::SemiBold),
                        TextEntry::make('academicYear.name')
                          ->label('Academic Year')
                          ->icon('heroicon-o-calendar')
                          ->badge()
                          ->color('primary'),
                      ])
                      ->contained(false)
                      ->grid(1),
                  ]),
              ]),

            Tab::make('Group Assignments')
              ->icon('heroicon-o-user-group')
              ->schema([
                ComponentsSection::make('Current Groups')
                  ->icon('heroicon-o-users')
                  ->schema([
                    RepeatableEntry::make('groups')
                      ->label('Assigned Groups')
                      ->schema([
                          TextEntry::make('name')
                            ->label('Group Name')
                            ->icon('heroicon-o-user-group')
                            ->weight(FontWeight::Bold)
                            ->size('lg')
                            ->color('primary'),
                          TextEntry::make('section.name')
                            ->label('Section')
                            ->icon('heroicon-o-squares-2x2')
                            ->badge()
                            ->color('info'),
                          TextEntry::make('section.instructor.user.name')
                            ->label('Section Instructor')
                            ->icon('heroicon-o-user-circle')
                            ->weight(FontWeight::SemiBold),
                          TextEntry::make('section.academicYear.name')
                            ->label('Academic Year')
                            ->icon('heroicon-o-calendar')
                            ->badge()
                            ->color('warning'),
                        TextEntry::make('students_count')
                          ->label('Group Members')
                          ->icon('heroicon-o-users')
                          ->badge()
                          ->color('success')
                          ->suffix(' members'),
                      ])
                      ->contained(false)
                      ->grid(1),
                  ]),
              ]),

            Tab::make('Account Information')
              ->icon('heroicon-o-key')
              ->schema([
                ComponentsSection::make('User Account')
                  ->icon('heroicon-o-user-circle')
                  ->columns(2)
                  ->schema([
                    TextEntry::make('user.name')
                      ->label('Display Name')
                      ->icon('heroicon-o-user')
                      ->weight(FontWeight::Bold)
                      ->size('lg'),
                    TextEntry::make('user.role')
                      ->label('Role')
                      ->icon('heroicon-o-shield-check')
                      ->badge()
                      ->color('success'),
                    TextEntry::make('user.email')
                      ->label('Email Address')
                      ->icon('heroicon-o-envelope')
                      ->copyable()
                      ->copyMessage('Email copied!')
                      ->columnSpanFull(),
                    TextEntry::make('user.created_at')
                      ->label('Account Created')
                      ->icon('heroicon-o-calendar-days')
                      ->dateTime()
                      ->since(),
                    TextEntry::make('user.updated_at')
                      ->label('Last Updated')
                      ->icon('heroicon-o-clock')
                      ->dateTime()
                      ->since(),
                  ]),

          ComponentsSection::make('Student Record')
                  ->icon('heroicon-o-document-text')
                  ->columns(2)
                  ->schema([
                    TextEntry::make('created_at')
                      ->label('Student Record Created')
                      ->icon('heroicon-o-calendar-days')
                      ->dateTime()
                      ->since(),
                    TextEntry::make('updated_at')
                      ->label('Record Last Updated')
                      ->icon('heroicon-o-clock')
                      ->dateTime()
                      ->since(),
                  ]),
              ]),
          ])
          ->persistTab()
          ->id('student-details-tabs'),
      ])
      ->record($this->student);
  }
}
