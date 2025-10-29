<?php

namespace App\Filament\Instructor\Pages;

use App\Livewire\InstructorSectionHandle;
use App\Models\Instructor;
use App\Models\Role;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class InstructorDetails extends Page
{
    protected string $view = 'filament.instructor.pages.instructor-details';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'manage-instructors/{instructor}';

    public ?Instructor $instructor;

    public static function canAccess(): bool
    {
        return auth()->user()->instructor?->canManage() ?? false;
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('Edit')
                ->icon('heroicon-o-pencil')
                ->modalWidth('xl')
                ->form([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('first_name')
                                ->label('First Name')
                                ->required()
                                ->maxLength(50)
                                ->trim()
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
                            Select::make('role_id')
                                ->label('Role')
                                ->multiple()
                                ->options(Role::pluck('name', 'id'))
                                ->required(),
                        ]),
                ])
                ->fillForm([
                    'first_name' => $this->instructor->first_name,
                    'middle_name' => $this->instructor->middle_name,
                    'last_name' => $this->instructor->last_name,
                    'role_id' => $this->instructor->roles->pluck('id')->toArray(),
                ])
                ->action(function (array $data) {
                    $this->instructor->update([
                        'first_name' => $data['first_name'],
                        'middle_name' => $data['middle_name'],
                        'last_name' => $data['last_name'],
                    ]);

                    // Sync roles
                    $this->instructor->roles()->sync($data['role_id']);

                    Notification::make()
                        ->title('Instructor updated successfully')
                        ->success()
                        ->send();
                }),

            Action::make('delete')
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Delete Instructor')
                ->modalDescription('Are you sure you want to delete this instructor? This action cannot be undone and will also delete the associated user account.')
                ->modalSubmitActionLabel('Delete')
                ->action(function () {
                    // Store user reference before deleting instructor
                    $user = $this->instructor->user;

                    // Delete the instructor first
                    $this->instructor->delete();

                    // Then delete the associated user if it exists
                    if ($user) {
                        $user->delete();
                    }

                    Notification::make()
                        ->title('Instructor deleted successfully')
                        ->success()
                        ->send();
                })
                ->successRedirectUrl(route('filament.instructor.pages.manage-instructors')),
        ];
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->record($this->instructor)
            ->components([
                Flex::make([
                    Tabs::make('tabs')
                        ->persistTabInQueryString()
                        ->contained(false)
                        ->tabs([
                            Tab::make('Personal Information')
                                ->schema([
                                    // Add components for personal information here

                                    Section::make()
                                        ->schema([
                                            TextEntry::make('user.name')
                                                ->label('Full Name'),
                                            TextEntry::make('user.email')
                                                ->label('Email Address'),
                                            TextEntry::make('sections_count')
                                                ->counts('sections')
                                                ->label('Total Sections'),
                                            TextEntry::make('groups_count')
                                                ->counts('groups')
                                                ->label('Total Groups'),

                                        ]),

                                ])
                                ->icon('heroicon-o-user'),

                            Tab::make('Sections Handled')
                                ->schema([
                                    Section::make()
                                        ->schema([
                                            Livewire::make(InstructorSectionHandle::class, ['instructor' => $this->instructor]),
                                        ]),
                                ])
                                ->icon('heroicon-o-user'),

                        ]),

                    // Section::make('testing')
                    //     ->grow(false)
                ])->from('md'),
            ]);
    }
}
