<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\Section;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MySectionGroupTable extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions, InteractsWithSchemas, WithPagination;

    public ?Section $section;

    #[Url(except: '')]
    public string $search = '';

    public function createAction(): Action
    {
        return Action::make('create')
            ->label('Create Group')
            ->requiresConfirmation()
            ->modalHeading('Confirm Group Creation')
            ->modalSubheading('Are you sure you want to create a new group?')
            ->successNotificationTitle('Group Created')
            ->action(function (array $data): void {
                $this->section->groups()->create([
                    'status' => 'active',
                ]);
            });
    }

    public function groups()
    {
        return Group::where('section_id', $this->section->id)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->paginate(10);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.my-section-group-table', [
            'groups' => $this->groups(),
        ]);
    }
}
