<?php

namespace App\Filament\Instructor\Pages;

use App\Models\Group;
use App\Models\Section;
use App\Models\Student;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Livewire\GroupProposals;
use App\Livewire\MyGroupMembers;
use App\Livewire\GroupPersonnels;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use App\Livewire\AssignedGroupsConsultations;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Components\Section as ComponentsSection;

class MyGroupDetails extends Page implements HasActions, HasSchemas
{
  use InteractsWithActions, InteractsWithSchemas;

  protected string $view = 'filament.instructor.pages.my-group-details';

  protected static bool $shouldRegisterNavigation = false;

  protected static ?string $slug = 'my-sections/{section}/{group}';

  public ?Section $section;

  public ?Group $group;

  public function getTitle(): string|Htmlable
  {
    return $this->group->name ?? 'Group Details';
  }

  public function mount(Section $section, Group $group): void
  {
    $this->authorize('view', $group);

    $this->section = $section;
    $this->group = $group;
  }

  public function schema(Schema $schema): Schema
  {
    return $schema
      ->components([
        Tabs::make('tabs')
          ->persistTabInQueryString()
          ->contained(false)
          ->tabs([
            Tab::make('Members')
              ->schema([
                Livewire::make(MyGroupMembers::class, ['group' => $this->group, 'section' => $this->section]),
              ]),
            Tab::make('Proposals')
              ->schema([
                ComponentsSection::make()
                  ->schema([
                    Livewire::make(GroupProposals::class, ['group' => $this->group]),
                  ]),
              ]),
            Tab::make('Personnel')
              ->schema([
                ComponentsSection::make()
                  ->schema([
                    Livewire::make(GroupPersonnels::class, ['group' => $this->group]),
                  ]),
              ]),
            Tab::make('Consultations')
              ->schema([
                ComponentsSection::make()
                  ->schema([
                    Livewire::make(AssignedGroupsConsultations::class, ['group' => $this->group]),
                  ]),
              ]),
          ]),
      ]);
  }
}
