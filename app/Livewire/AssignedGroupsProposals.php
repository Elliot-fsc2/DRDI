<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\Proposal;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Livewire\Component;
use Livewire\Attributes\Computed;

class AssignedGroupsProposals extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions, InteractsWithSchemas;

    public Group $group;

    public function viewProposalAction(): Action
    {
        return Action::make('viewProposal')
            ->modalAutofocus(false)
            ->modalHeading(fn(array $arguments) => 'Proposal: ' . $arguments['proposal']['title'])
            ->modalWidth('4xl')
            ->schema(function (array $arguments) {
                $proposal = $arguments['proposal'];
                return [
                    Section::make('Proposal Information')
                        ->schema([
                            TextEntry::make('title')
                                ->label('Title')
                                ->weight(FontWeight::Bold)
                                ->size('lg')
                                ->state($proposal['title']),

                            TextEntry::make('description')
                                ->label('Description')
                                ->html()
                                ->columnSpanFull()
                                ->state($proposal['description']),

                            TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->state(ucfirst($proposal['status']))
                                ->color(fn(): string => match ($proposal['status']) {
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'revision' => 'warning',
                                    'submitted' => 'info',
                                    'pending' => 'gray',
                                    default => 'gray',
                                }),

                            IconEntry::make('is_final')
                                ->label('Final Proposal')
                                ->boolean()
                                ->state($proposal['is_final'])
                                ->trueIcon('heroicon-o-check-circle')
                                ->falseIcon('heroicon-o-x-circle')
                                ->trueColor('success')
                                ->falseColor('gray'),

                            TextEntry::make('created_at')
                                ->label('Submitted On')
                                ->dateTime()
                                ->state($proposal['created_at']),

                            TextEntry::make('updated_at')
                                ->label('Last Updated')
                                ->since()
                                ->state($proposal['updated_at']),
                        ])
                        ->columns(2),

                    Section::make('Instructor Feedback')
                        ->schema([
                            TextEntry::make('remarks')
                                ->label('Remarks')
                                ->placeholder('No remarks provided yet')
                                ->columnSpanFull()
                                ->state($proposal['remarks'] ?? ''),
                        ])
                        ->visible(fn(): bool => !empty($proposal['remarks'])),
                ];
            })
            ->modalCancelAction(false)
            ->modalSubmitAction(false);
    }

    #[Computed]
    public function proposals()
    {
        return $this->group->proposals()->latest()->get();
    }
    public function render()
    {
        return view('livewire.assigned-groups-proposals');
    }
}
