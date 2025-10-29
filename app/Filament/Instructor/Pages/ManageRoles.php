<?php

namespace App\Filament\Instructor\Pages;

use App\Models\Role;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Computed;
use UnitEnum;

class ManageRoles extends Page implements HasSchemas, HasTable
{
    use InteractsWithSchemas, InteractsWithTable;

    protected string $view = 'filament.instructor.pages.manage-roles';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShieldExclamation;

    protected ?string $subheading = 'Manage all instructor roles';

    protected static string|UnitEnum|null $navigationGroup = 'Management';

    public static function canAccess(): bool
    {
        return auth()->user()->instructor?->canManage() ?? false;
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    $this->table,
                ])->extraAttributes(['class' => 'md:w-[750px] mx-auto']),
            ]);
    }

    #[Computed]
    public function table(Table $table): Table
    {
        return $table
            ->query(Role::query())
            ->headerActions([
                CreateAction::make('Add Role')
                    ->modalWidth('md')
                    ->schema([
                        TextInput::make('name')
                            ->label('Role Name')
                            ->required()
                            ->extraAlpineAttributes([
                                'x-on:input' => '$el.value = $el.value.split(\' \').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(\' \')',
                            ])
                            ->unique(),
                    ])
                    ->createAnother(false),
            ])

            ->columns([
                TextColumn::make('name')
                    ->label('Role Name')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->modalWidth('md')
                    ->schema([
                        TextInput::make('name')
                            ->label('Role Name'),
                    ]),
                // ->action(fn(array $data, $record) => $record->update($data)),
                DeleteAction::make('Delete')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
