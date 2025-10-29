<?php

namespace App\Filament\Instructor\Pages\ManageSecion;

use App\Models\Course;
use App\Models\Section;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\HtmlString;

class SectionDetails extends Page implements HasSchemas
{
  use InteractsWithSchemas;

  protected string $view = 'filament.instructor.pages.manage-secion.section-details';

  protected static bool $shouldRegisterNavigation = false;

  protected static ?string $slug = 'manage-sections/{section}';

  public ?Section $section = null;

  public function mount(Section $section): void
  {
    $this->section = $section;
  }

  public function getTitle(): string
  {
    return strtoupper($this->section->name);
  }

  public function schema(Schema $schema): Schema
  {
    return $schema
      ->record($this->section)
      ->components([
        // Define components to display section details here
        Flex::make([
          Tabs::make('Tabs')
            ->contained(false)
            ->tabs([
              Tab::make('Details')
                ->label('Section Details')
                ->schema([
                  // Add components to show section details
                  \Filament\Schemas\Components\Section::make('Details')
                    ->headerActions([
                      Action::make('Edit Section')
                        ->modalWidth('md')
                        ->fillForm(fn(Section $record) => [
                          'name' => $record->name,
                          'instructor_id' => $record->instructor_id,
                          'course_id' => $record->course_id,
                        ])
                        ->schema([
                          TextInput::make('name')
                            ->label('Section Name')
                            ->scopedUnique(modifyQueryUsing: fn($query) => $query->whereActive())
                            ->extraAlpineAttributes([
                              'x-on:input' => '$el.value = $el.value.toUpperCase()',
                            ])
                            ->required()
                            ->trim()
                            ->maxLength(length: 255),
                          Select::make('instructor_id')
                            ->label('Instructor')
                            ->relationship(
                              'instructor',
                              modifyQueryUsing: fn($query) => $query->whereInstructorDepartment(auth()->user()->instructor->department_id)
                            )
                            ->getOptionLabelFromRecordUsing(fn($record) => $record?->full_name)
                            ->searchable()
                            ->preload()
                            ->required(),
                          Select::make('course_id')
                            ->label('Course')
                            ->options(
                              fn() => Course::whereDepartment(auth()->user()->instructor->department_id)
                                ->pluck('code', 'id')
                                ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        ])
                        ->action(function (array $data, Section $record) {
                          $record->update($data);
                          Notification::make()
                            ->title('Section updated successfully')
                            ->success()
                            ->send();
                        }),
                    ])
                    ->schema([
                      Grid::make(2)
                        ->schema([
                          TextEntry::make('name')
                            ->weight(FontWeight::Bold)
                            ->label('Section Name'),
                          TextEntry::make('course.name')
                            ->weight(FontWeight::Bold)
                            ->label('Course'),
                          TextEntry::make('course.department.name')
                            ->weight(FontWeight::Bold)
                            ->label('Department'),
                          TextEntry::make('instructor.full_name')
                            ->weight(FontWeight::Bold)
                            ->label('Instructor'),
                          TextEntry::make('academicYear.year')
                            ->weight(FontWeight::Bold)
                            ->label('Academic Year'),

                        ]),
                    ]),
                ]),
              Tab::make('Students')
                ->label('Enrolled Students')
                ->schema([
                  \Filament\Schemas\Components\Section::make('Enrolled Students')
                    ->headerActions([
                      Action::make('AddStudent')
                        ->form([
                          Select::make('student_id')
                            ->label('Student')
                            ->relationship(
                              'students',
                              'first_name',
                              modifyQueryUsing: fn($query) => $query->whereNotInSection()
                                ->whereStudentInDepartment(auth()->user()->instructor->department_id)
                            )
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->first_name} {$record->last_name}")
                            ->searchable()
                            ->preload()
                            ->multiple()
                            ->required(),
                        ])
                        ->modalWidth('md')
                        ->action(function (array $data, Section $record) {
                          // dd($data);
                          // $record->students()->attach($data['student_id']);
                          $record->refresh();
                          Notification::make()
                            ->title('Student(s) added successfully')
                            ->success()
                            ->send();
                        })
                        ->color('primary')
                        ->icon('heroicon-o-plus')
                        ->modalHeading('Add Student to ' . $this->section->name)
                        ->modalSubheading('Select a student to enroll in this section.'),
                    ])
                    ->schema([
                      RepeatableEntry::make('students')
                        ->hiddenLabel()
                        ->table([
                          TableColumn::make('Name')
                            ->alignCenter(),
                          TableColumn::make('Email')
                            ->alignCenter(),
                          TableColumn::make('Actions')
                            ->alignCenter(),
                        ])
                        ->schema([
                          TextEntry::make('full_name')
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-user')
                            ->alignCenter(),

                          TextEntry::make('user.email')
                            ->weight(FontWeight::Bold)
                            ->alignCenter(),

                          Actions::make([
                            Action::make('Remove Student')
                              ->label('Remove')
                              ->iconButton()
                              ->color('danger')
                              ->icon('heroicon-o-trash')
                              ->requiresConfirmation()
                              ->action(function ($livewire, $record) {
                                $livewire->section->students()->detach($record->id);
                                $livewire->section->refresh();
                                Notification::make()
                                  ->title('Student removed successfully')
                                  ->success()
                                  ->send();
                              }),
                          ])
                            ->alignCenter(),

                        ])
                        ->placeholder(
                          new HtmlString('<div class="text-center text-lg font-bold">No students enrolled yet.</div>')
                        )
                        ->columns(3)
                        ->contained(false),
                    ]),

                ]),
            ]),
          \Filament\Schemas\Components\Section::make('Additional Information')
            ->extraAttributes(['class' => 'md:mt-19'])
            ->schema([
              TextEntry::make('academicyear.year')
                ->label('Academic Year')
                ->weight(FontWeight::Bold),
              TextEntry::make('groups_count')
                ->counts('groups')
                ->label('Total Groups')
                ->weight(FontWeight::Bold),
            ])
            ->grow(false),
        ])->from('md'),
      ]);
  }
}
