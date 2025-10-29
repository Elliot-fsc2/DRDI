<?php

namespace App\Filament\Instructor\Pages;

use App\Livewire\AssignedGroupsConsultations;
use App\Livewire\AssignedGroupsProposals;
use App\Models\Group;
use Filament\Pages\Page;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class AssignedGroupsDetails extends Page
{
    protected string $view = 'filament.instructor.pages.assigned-groups-details';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'assigned-groups/{group}/proposals';
    public Group $group;

    public function getHeading(): string
    {
        return $this->group->name;
    }

    public function getSubheading(): string
    {
        return 'Review and manage this group';
    }



    public function mount(Group $group): void
    {
        if (!$group->personnels()->where('instructor_id', Auth::user()->instructor->id)->exists()) {
            abort(404);
        }

        $this->group = $group;
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('tabs')
                    ->contained(false)
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('proposals')
                            ->label('Proposals')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Livewire::make(AssignedGroupsProposals::class, ['group' => $this->group])
                                    ])
                            ]),
                        Tab::make('Consultations')
                            ->label('Consultations')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Livewire::make(AssignedGroupsConsultations::class, ['group' => $this->group])
                                    ])
                            ]),
                    ]),
            ]);
    }
}
