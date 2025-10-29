<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\Proposal;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;

class GroupProposals extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    public Group $group;

    public function viewProposalAction(): Action
    {
        return Action::make('viewProposal')
            ->modalAutofocus(false)
            ->modalHeading(fn (array $arguments) => 'Proposal: '.Proposal::find($arguments['proposal'])->title)
            ->modalWidth('4xl')
            ->modalSubmitActionLabel('Update Proposal')
            ->modalCancelActionLabel('Close')
            ->fillForm(function (array $arguments): array {
                $proposal = Proposal::find($arguments['proposal']);

                return [
                    'title' => $proposal->title,
                    'description' => $proposal->description,
                    'status' => $proposal->status,
                    'is_final' => $proposal->is_final,
                    'remarks' => $proposal->remarks,
                ];
            })
            ->form([
                TextInput::make('title')
                    ->label('Title')
                    ->disabled(),

                RichEditor::make('description')
                    ->label('Description')
                    ->disabled(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'revision' => 'Needs Revision',
                    ])
                    ->required(),

                Textarea::make('remarks')
                    ->label('Instructor Remarks')
                    ->helperText('Add feedback or comments about this proposal.')
                    ->columnSpanFull(),

                Toggle::make('is_final')
                    ->label('Mark as Final Proposal')
                    ->helperText('Once marked as final, this cannot be undone.'),
            ])
            ->action(function (array $data, array $arguments): void {
                $proposal = Proposal::find($arguments['proposal']);

                $proposal->update([
                    'status' => $data['status'],
                    'is_final' => $data['is_final'],
                    'remarks' => $data['remarks'],
                ]);

                Notification::make()
                    ->title('Proposal updated successfully')
                    ->success()
                    ->send();
            });
    }

    public function render()
    {
        return view('livewire.group-proposals', [
            'proposals' => $this->group->proposals()->latest()->get(),
        ]);
    }
}
