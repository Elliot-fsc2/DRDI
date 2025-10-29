<?php

namespace App\Livewire;

use App\Models\Instructor;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ManageInstructorsContent extends Component implements HasActions, HasSchemas
{
  use InteractsWithActions, InteractsWithSchemas, WithPagination;

  #[Url(except: '')]
  public string $search = '';

  public function instructors()
  {
    return Instructor::where('department_id', Auth::user()->instructor->department_id)
      ->withCount('sections')
      ->with(['user', 'department', 'roles'])
      ->when($this->search, function ($query) {
        $query->where(function ($q) {
          $q->where('first_name', 'like', '%' . $this->search . '%')
            ->orWhere('last_name', 'like', '%' . $this->search . '%')
            ->orWhere('middle_name', 'like', '%' . $this->search . '%')
            ->orWhereHas('user', function ($userQuery) {
              $userQuery->where('email', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('department', function ($deptQuery) {
              $deptQuery->where('name', 'like', '%' . $this->search . '%');
            });
        });
      })
      ->paginate(12);
  }

  public function createAction(): Action
  {
    return Action::make('create')
      ->label('Add Instructor')
      ->color('primary')
      ->icon('heroicon-o-plus')
      ->modalWidth(Width::ExtraLarge)
      ->modalHeading('Add New Instructor')
      ->modalSubheading('Create a new instructor account for your department.')
      ->form([
        Grid::make(3)
          ->schema([
            TextInput::make('first_name')
              ->label('First Name')
              ->required()
              ->trim()
              ->maxLength(50)
              ->extraAlpineAttributes([
                'x-on:input' => '$el.value = $el.value.split(\' \').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(\' \')',
              ]),
            TextInput::make('middle_name')
              ->label('Middle Name')
              ->trim()
              ->extraAlpineAttributes([
                'x-on:input' => '$el.value = $el.value.split(\' \').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(\' \')',
              ])
              ->maxLength(50),
            TextInput::make('last_name')
              ->label('Last Name')
              ->maxLength(50)
              ->trim()
              ->required()
              ->extraAlpineAttributes([
                'x-on:input' => '$el.value = $el.value.split(\' \').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(\' \')',
              ]),
            Select::make('role_ids')
              ->label('Roles')
              ->multiple()
              ->preload()
              ->options(Role::pluck('name', 'id')->toArray())
              ->columnSpanFull(),
          ]),
      ])
      ->action(function (array $data): void {
        // Create user first

        // Create instructor
        $instructor = Instructor::create([
          'first_name' => $data['first_name'],
          'middle_name' => $data['middle_name'],
          'last_name' => $data['last_name'],
          'department_id' => Auth::user()->instructor->department_id,
        ]);

        // Attach roles
        if (! empty($data['role_ids'])) {
          $instructor->roles()->attach($data['role_ids']);
        }

        Notification::make()
          ->title('Instructor created successfully')
          ->success()
          ->send();
      });
  }

  public function updatedSearch(): void
  {
    $this->resetPage();
  }

  public function render()
  {
    return view('livewire.manage-instructors-content', [
      'instructors' => $this->instructors(),
    ]);
  }
}
