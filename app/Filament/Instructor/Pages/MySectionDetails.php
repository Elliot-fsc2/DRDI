<?php

namespace App\Filament\Instructor\Pages;

use App\Livewire\MySectionGroupTable;
use App\Models\Course;
use App\Models\Group;
use App\Models\Section;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section as SchemaSection;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class MySectionDetails extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    protected string $view = 'filament.instructor.pages.my-section-details';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'my-sections/{section}';

    public ?Section $section;

    public function getTitle(): string|Htmlable
    {
        return $this->section->name;
    }

    public function mount()
    {
        $this->authorize('view', $this->section);
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->record($this->section)
            ->components([
                Flex::make([
                    Tabs::make('tabs')
                        ->persistTabInQueryString()
                        // ->vertical(fn() => request()->userAgent() && !preg_match('/Mobile|Android|iPhone|iPad/', request()->userAgent()))
                        ->contained(false)
                        ->tabs([
                            Tab::make('Details')
                                ->schema([
                                    SchemaSection::make('Section Details')
                                        ->headerActions([
                                            Action::make('Edit Section')
                                                ->modalWidth('md')
                                                ->fillForm(fn (Section $record) => [
                                                    'name' => $record->name,
                                                    'course_id' => $record->course_id,
                                                ])
                                                ->schema([
                                                    TextInput::make('name')
                                                        ->label('Section Name')
                                                        ->scopedUnique(modifyQueryUsing: fn ($query) => $query->whereActive())
                                                        ->extraAlpineAttributes([
                                                            'x-on:input' => '$el.value = $el.value.toUpperCase()',
                                                        ])
                                                        ->required()
                                                        ->trim()
                                                        ->maxLength(length: 255),
                                                    Select::make('course_id')
                                                        ->label('Course')
                                                        ->options(
                                                            fn () => Course::whereDepartment(auth()->user()->instructor->department_id)
                                                                ->pluck('code', 'id')
                                                                ->toArray()
                                                        )
                                                        ->searchable()
                                                        ->preload()
                                                        ->required(),
                                                ])
                                                ->action(function (array $data, Section $record) {
                                                    $record->update($data);
                                                    Notification::make()
                                                        ->title('Section updated successfully')
                                                        ->success()
                                                        ->send();
                                                }),
                                            Action::make('Delete Section')
                                                ->color('danger')
                                                ->icon('heroicon-o-trash')
                                                ->requiresConfirmation()
                                                ->successRedirectUrl(route('filament.instructor.pages.my-sections'))
                                                ->modalHeading('Delete Section')
                                                ->modalSubheading('Are you sure you want to delete this section? This action cannot be undone.')
                                                ->modalSubmitActionLabel('Yes, delete it')
                                                ->successNotificationTitle('Section deleted successfully')
                                                ->action(function () {
                                                    $this->authorize('delete', $this->section);
                                                    $this->section->delete();
                                                }),
                                        ])
                                        ->schema([
                                            TextEntry::make('name')
                                                ->label('Section Name')
                                                ->weight(FontWeight::Bold),
                                            TextEntry::make('course.code')
                                                ->label('Course Code')
                                                ->weight(FontWeight::Bold)
                                                ->icon('heroicon-o-book-open'),
                                            TextEntry::make('instructor.full_name')
                                                ->label('Instructor')
                                                ->weight(FontWeight::Bold)
                                                ->icon('heroicon-o-user'),
                                        ]),
                                ]),
                            Tab::make('Students Enrolled')
                                ->schema([
                                    SchemaSection::make('Students Enrolled')
                                        ->headerActions([
                                            Action::make('AddStudent')
                                                ->form([
                                                    Select::make('student_id')
                                                        ->label('Student')
                                                        ->relationship(
                                                            'students',
                                                            'first_name',
                                                            modifyQueryUsing: fn ($query) => $query->whereNotInSection()
                                                                ->whereStudentInDepartment(auth()->user()->instructor->department_id)
                                                        )
                                                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                                                        ->searchable()
                                                        ->preload()
                                                        ->multiple()
                                                        ->required(),
                                                ])
                                                ->modalWidth('md')
                                                ->action(function (array $data, Section $record) {
                                                    // dd($data);
                                                    // $record->students()->attach($data['student_id']);
                                                    $record->refresh();
                                                    Notification::make()
                                                        ->title('Student(s) added successfully')
                                                        ->success()
                                                        ->send();
                                                })
                                                ->color('primary')
                                                ->icon('heroicon-o-plus')
                                                ->modalHeading('Add Student to '.$this->section->name)
                                                ->modalSubheading('Select a student to enroll in this section.'),
                                        ])
                                        ->schema([
                                            RepeatableEntry::make('students')
                                                ->hiddenLabel()
                                                ->table([
                                                    TableColumn::make('Name')
                                                        ->alignCenter(),
                                                    TableColumn::make('Email')
                                                        ->alignCenter(),
                                                    TableColumn::make('Actions')
                                                        ->alignCenter(),
                                                ])
                                                ->schema([
                                                    TextEntry::make('full_name')
                                                        ->weight(FontWeight::Bold)
                                                        ->icon('heroicon-o-user')
                                                        ->alignCenter(),

                                                    TextEntry::make('user.email')
                                                        ->weight(FontWeight::Bold)
                                                        ->alignCenter(),

                                                    Actions::make([
                                                        Action::make('Remove Student')
                                                            ->label('Remove')
                                                            ->iconButton()
                                                            ->color('danger')
                                                            ->icon('heroicon-o-trash')
                                                            ->requiresConfirmation()
                                                            ->action(function ($livewire, $record) {
                                                                $livewire->section->students()->detach($record->id);
                                                                $livewire->section->refresh();
                                                                Notification::make()
                                                                    ->title('Student removed successfully')
                                                                    ->success()
                                                                    ->send();
                                                            }),
                                                    ])
                                                        ->alignCenter(),

                                                ])
                                                ->placeholder(
                                                    new HtmlString('<div class="text-center text-lg font-bold">No students enrolled yet.</div>')
                                                )
                                                ->columns(3)
                                                ->contained(false),
                                        ]),
                                ]),
                            Tab::make('Groups')
                                ->schema([
                                    \Filament\Schemas\Components\Section::make()
                                        ->schema([
                                            Livewire::make(MySectionGroupTable::class, [
                                                'section' => $this->section,
                                            ]),
                                        ]),
                                ]),
                        ]),
                    // SchemaSection::make('Information')
                    //     ->schema([
                    //         // Add your students components here

                    //     ])->grow(false),
                ])->from('md'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Group::query()->where('section_id', $this->section->id))
            ->headerActions([
                Action::make('Create Group')
                    ->requiresConfirmation()
                    ->action(function (): void {
                        Group::create([
                            'section_id' => $this->section->id,
                            'instructor_id' => auth()->user()->instructor->id,
                            'status' => 'active',
                        ]);

                        Notification::make()
                            ->title('Group created successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->contentGrid([
                'sm' => 2,
                'xl' => 3,
            ])
            ->columns([
                Grid::make(1)
                    ->schema([
                        ImageColumn::make('avatar_url')
                            ->label('Avatar')
                            ->imageWidth(250)
                            ->imageHeight(250)
                            ->alignCenter()
                            ->defaultImageUrl(url('https://static.vecteezy.com/system/resources/thumbnails/028/133/374/small/school-classroom-in-blur-background-without-young-student-blurry-view-of-elementary-class-room-no-kid-or-teacher-with-chairs-and-tables-in-campus-back-to-school-concept-generative-ai-photo.jpg')),
                        TextColumn::make('name')
                            ->label('Group Name')
                            ->weight(FontWeight::Bold)
                            ->alignCenter()
                            ->size('lg'),
                        TextColumn::make('leader.full_name')
                            ->placeholder(new HtmlString('<span class="font-bold">Leader: Not Assigned</span>'))
                            ->label('Group Leader')
                            ->formatStateUsing(fn (Column $column, $state): string => $state ? ('<span class="font-bold">'.$column->getLabel().':</span> '.$state) : 'N/A')
                            ->html()
                            ->alignCenter(),
                    ]),

            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
