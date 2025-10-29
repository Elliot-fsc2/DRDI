<?php

namespace App\Livewire;

use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ManageStudentsContent extends Component implements HasActions, HasForms
{
  use WithPagination, InteractsWithActions, InteractsWithForms;

  #[Url(except: '')]
  public string $search = '';

  public function students()
  {
    return Student::whereStudentInDepartment(Auth::user()->instructor->department_id)
      ->with(['user', 'course', 'groups'])
      ->when($this->search, function ($query) {
        $query->where(function ($q) {
          $search = "%{$this->search}%";
          $q->where('first_name', 'like', $search)
            ->orWhere('last_name', 'like', $search)
            ->orWhere('middle_name', 'like', $search)
            ->orWhere('student_id', 'like', $search)
            ->orWhereHas('user', function ($userQuery) use ($search) {
              $userQuery->where('email', 'like', $search);
            })
            ->orWhereHas('course', function ($courseQuery) use ($search) {
              $courseQuery->where('name', 'like', $search)
                ->orWhere('code', 'like', $search);
            });
        });
      })
      ->latest()
      ->paginate(12);
  }

  public function updatedSearch(): void
  {
    $this->resetPage();
  }

  public function createAction(): Action
  {
    return Action::make('create')
      ->label('Create Student')
      ->color('primary')
      ->icon('heroicon-o-plus')
      ->modalWidth(Width::Large)
      ->modalHeading('Create New Student')
      ->modalSubheading('Add a new student to the system.')
      ->form([
        Grid::make(2)
          ->schema([
            TextInput::make('first_name')
              ->label('First Name')
              ->required()
              ->extraAlpineAttributes([
                'x-on:input' => '$el.value = $el.value.split(\' \').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(\' \')',
              ])
              ->maxLength(255)
              ->placeholder('e.g., John'),

            TextInput::make('middle_name')
              ->label('Middle Name')
              ->maxLength(255)
              ->extraAlpineAttributes([
                'x-on:input' => '$el.value = $el.value.split(\' \').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(\' \')',
              ])
              ->placeholder('e.g., Doe (Optional)'),

            TextInput::make('last_name')
              ->label('Last Name')
              ->required()
              ->extraAlpineAttributes([
                'x-on:input' => '$el.value = $el.value.split(\' \').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(\' \')',
              ])
              ->maxLength(255)
              ->placeholder('e.g., Smith'),

            TextInput::make('student_number')
              ->label('Student ID')
              ->required()
              ->unique()
              ->maxLength(20)
              ->placeholder('e.g., 2024-00001'),

            Select::make('course_id')
              ->label('Course')
              ->options(function () {
                return Course::where('department_id', Auth::user()->instructor->department_id)
                  ->pluck('name', 'id')
                  ->toArray();
              })
              ->columnSpanFull()
              ->searchable()
              ->preload()
              ->required(),
          ])
      ])
      ->action(function (array $data): void {
        // Create the student (observer will create the user automatically)
        Student::create([
          'first_name' => $data['first_name'],
          'middle_name' => $data['middle_name'],
          'last_name' => $data['last_name'],
          'student_number' => $data['student_number'],
          'course_id' => $data['course_id'],
        ]);

        Notification::make()
          ->title('Student created successfully')
          ->success()
          ->send();
      });
  }

  public function render()
  {
    return view('livewire.manage-students-content', [
      'students' => $this->students(),
    ]);
  }
}
