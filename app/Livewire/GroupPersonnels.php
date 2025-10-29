<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\GroupPersonel;
use App\Models\Instructor;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;

class GroupPersonnels extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    public Group $group;

    public function assignPersonnelAction(): Action
    {
        return Action::make('assignPersonnel')
            ->label('Assign Personnel')
            ->modalHeading('Assign Personnel to Group')
            ->modalWidth('md')
            ->modalSubmitActionLabel('Assign')
            ->modalCancelActionLabel('Cancel')
            ->form([
                Select::make('instructor_id')
                    ->label('Select Instructor')
                    ->options(
                        Instructor::with('user')
                            ->whereNotIn(
                                'instructors.id',
                                $this->group->personnels()->pluck('instructor_id')
                            )
                            ->get()
                            ->pluck('user.name', 'id')
                    )
                    ->searchable()
                    ->required(),

                Select::make('role')
                    ->label('Role')
                    ->options([
                        'technical_adviser' => 'Technical Adviser',
                        'language_critic' => 'Language Critic',
                        'statistician' => 'Statistician',
                        'grammarian' => 'Grammarian',
                    ])
                    ->required(),
            ])
            ->action(function (array $data): void {
                // Check if instructor is already assigned to this group
                $existingAssignment = GroupPersonel::where('group_id', $this->group->id)
                    ->where('instructor_id', $data['instructor_id'])
                    ->first();

                if ($existingAssignment) {
                    Notification::make()
                        ->title('Instructor already assigned')
                        ->body('This instructor is already assigned to this group.')
                        ->warning()
                        ->send();

                    return;
                }

                GroupPersonel::create([
                    'group_id' => $this->group->id,
                    'instructor_id' => $data['instructor_id'],
                    'role' => $data['role'],
                ]);

                Notification::make()
                    ->title('Personnel assigned successfully')
                    ->success()
                    ->send();

                // $this->dispatch('$refresh');
            })
            ->icon('heroicon-o-user-plus');
    }

    public function editPersonnelAction(): Action
    {
        return Action::make('editPersonnel')
            ->label('Edit Role')
            ->color('primary')
            ->modalHeading('Edit Personnel Role')
            ->modalWidth('md')
            ->modalSubmitActionLabel('Update')
            ->modalCancelActionLabel('Cancel')
            ->fillForm(function (array $arguments): array {
                $personnel = GroupPersonel::find($arguments['personnel']);

                return [
                    'role' => $personnel->role,
                ];
            })
            ->form([
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'technical_adviser' => 'Technical Adviser',
                        'language_critic' => 'Language Critic',
                        'statistician' => 'Statistician',
                        'grammarian' => 'Grammarian',
                    ])
                    ->required(),
            ])
            ->action(function (array $data, array $arguments): void {
                $personnel = GroupPersonel::find($arguments['personnel']);

                if ($personnel) {
                    $personnel->update([
                        'role' => $data['role'],
                    ]);

                    Notification::make()
                        ->title('Personnel role updated successfully')
                        ->success()
                        ->send();

                    $this->dispatch('$refresh');
                }
            })
            ->icon('heroicon-o-pencil');
    }

    public function removePersonnelAction(): Action
    {
        return Action::make('removePersonnel')
            ->label('Remove')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Remove Personnel')
            ->modalDescription('Are you sure you want to remove this personnel from the group?')
            ->action(function (array $arguments): void {
                $personnel = GroupPersonel::find($arguments['personnel']);

                if ($personnel) {
                    $personnel->delete();

                    Notification::make()
                        ->title('Personnel removed successfully')
                        ->success()
                        ->send();

                    $this->dispatch('$refresh');
                }
            })
            ->icon('heroicon-o-trash');
    }

    public function render()
    {
        return view('livewire.group-personnels', [
            'personnels' => $this->group->personnels()->with(['instructor.user'])->get(),
        ]);
    }
}
