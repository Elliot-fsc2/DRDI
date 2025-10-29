<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\Section as ModelsSection;
use App\Models\Student;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;


class MyGroupMembers extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions, InteractsWithSchemas;

    public Group $group;
    public ModelsSection $section;

    public function schema(Schema $schema)
    {
        return $schema
            ->record($this->group)
            ->components([
                // Define your schema components here
                Section::make('Group Members')
                    ->headerActions([
                        Action::make('add_member')
                            ->modalWidth('md')
                            ->label('Add Member')
                            ->schema([
                                Select::make('student_id')
                                    ->label('Select Student')
                                    ->options(
                                        function () {
                                            return Student::whereNotInGroup($this->section->id)
                                                ->get()
                                                ->pluck('first_name', 'id');
                                        }
                                    )
                                    ->searchable()
                                    ->multiple()
                                    ->searchable()
                                    ->required(),
                            ])
                            ->action(function (array $data) {
                                $this->group->members()->attach($data['student_id']);
                                $this->group->refresh();
                                Notification::make()
                                    ->title('Add Member action triggered')
                                    ->success()
                                    ->send();
                            })
                            ->icon('heroicon-o-user-plus'),
                    ])
                    ->schema([

                        RepeatableEntry::make('members')
                            ->hiddenLabel()
                            ->label('Group Members')
                            ->columns(1)
                            ->table([
                                TableColumn::make('Name')
                                    ->alignCenter(),
                                TableColumn::make('Student Number')
                                    ->alignCenter(),
                                TableColumn::make('Is leader')
                                    ->alignCenter(),
                                TableColumn::make('Actions')
                                    ->alignCenter(),
                            ])
                            ->schema([
                                TextEntry::make('first_name')
                                    ->alignCenter()
                                    ->formatStateUsing(fn($record) => $record->first_name . ' ' . $record->last_name)
                                    ->label('Member Name'),
                                TextEntry::make('student_number')
                                    ->alignCenter()
                                    ->label('Student ID'),
                                IconEntry::make('is_leader')
                                    ->alignCenter()
                                    ->boolean()
                                    ->getStateUsing(fn($record) => $this->group->leader_id === $record->id),

                                Actions::make([
                                    Action::make('set_as_leader')
                                        ->label('Set as Leader')
                                        ->action(function ($record) {
                                            $this->group
                                                ->update([
                                                    'leader_id' => $record->id ?: null,
                                                ]);

                                            Notification::make()
                                                ->title('Group leader updated successfully')
                                                ->success()
                                                ->send();
                                            // $this->refresh();
                                        })
                                        ->color('primary')
                                        ->visible(fn($record) => $this->group->leader_id !== $record->id)
                                        ->modalWidth('md')
                                        ->icon('heroicon-o-check-circle'),

                                    Action::make('remove_member')
                                        ->iconButton()
                                        ->label('Remove Member')
                                        ->action(function ($record) {
                                            if ($this->group->leader_id === $record->id) {
                                                $this->group->update(['leader_id' => null]);
                                            }
                                            $this->group->members()->detach($record->id);
                                            $this->group->refresh();
                                            Notification::make()
                                                ->title('Member removed successfully')
                                                ->success()
                                                ->send();
                                        })
                                        ->color('danger')
                                        ->requiresConfirmation()
                                        ->icon('heroicon-o-user-minus')
                                        // ->hidden(fn($record) => $this->group->leader_id === $record->id)
                                        ->modalWidth('md'),
                                ])
                                    ->alignCenter(),
                            ]),
                    ]),
            ]);
    }

    public function render()
    {
        return view('livewire.my-group-members');
    }
}
