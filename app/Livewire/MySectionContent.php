<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Section;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MySectionContent extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions, InteractsWithSchemas;
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    public function sections()
    {
        return Section::where('instructor_id', Auth::user()->instructor?->id)
            ->whereActive()
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('course', function ($courseQuery) {
                            $courseQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('code', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->with(['course', 'academicYear', 'instructor', 'students'])
            ->withCount('students')
            ->paginate(12);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function createAction(): Action
    {
        return Action::make('create')
            ->label('Create Section')
            ->color('primary')
            ->icon('heroicon-o-plus')
            ->modalWidth(Width::Large)
            ->modalHeading('Create New Section')
            ->modalSubheading('Add a new section to your current academic year.')
            ->form([
                TextInput::make('name')
                    ->label('Section Name')
                    ->required()
                    ->validationMessages([
                        'unique' => 'The section name already exists.',
                    ])
                    ->extraAlpineAttributes(['@input' => '$el.value = $el.value.toUpperCase()'])
                    ->rule('unique:sections,name,NULL,id,academic_year_id,' . AcademicYear::whereActive()->first()?->id)
                    ->trim()
                    ->maxLength(20)
                    ->placeholder('e.g., BSIT 321A'),
                Select::make('course_id')
                    ->label('Course')
                    ->options(function () {
                        return \App\Models\Course::where('department_id', Auth::user()->instructor->department_id)
                            ->pluck('code', 'id')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
            ])
            ->action(function (array $data): void {
                // Check if active academic year exists
                $activeAcademicYear = AcademicYear::whereActive()->first();

                if (! $activeAcademicYear) {
                    Notification::make()
                        ->title('No active academic year found. Contact the administrator.')
                        ->danger()
                        ->send();

                    return;
                }

                // Create the section
                Section::create([
                    'name' => strtoupper($data['name']),
                    'course_id' => $data['course_id'],
                    'academic_year_id' => $activeAcademicYear->id,
                    'instructor_id' => Auth::user()->instructor->id,
                ]);

                Notification::make()
                    ->title('Section created successfully')
                    ->success()
                    ->send();
            });
    }

    public function render()
    {
        return view('livewire.my-section-content', [
            'sections' => $this->sections(),
        ]);
    }
}
