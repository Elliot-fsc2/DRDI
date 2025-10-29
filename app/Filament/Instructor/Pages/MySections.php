<?php

namespace App\Filament\Instructor\Pages;

use App\Livewire\MySectionContent;
use App\Models\AcademicYear;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationItem;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

use function Filament\Support\original_request;

class MySections extends Page implements HasSchemas, HasTable
{
    use InteractsWithSchemas, InteractsWithTable;

    protected string $view = 'filament.instructor.pages.my-sections';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected ?string $subheading = 'Manage all your sections for the current academic year';

    public function schema(Schema $schema)
    {
        return $schema
            ->schema([
                // Define your schema components here
                Flex::make([
                    Tabs::make('Tabs')
                        ->persistTabInQueryString()
                        ->contained(false)
                        ->tabs([
                            Tab::make('My Sections')
                                ->schema([
                                    Section::make()
                                        ->schema([
                                            Livewire::make(MySectionContent::class),
                                        ]),
                                ]),
                            Tab::make('Pendings')
                                ->schema([]),

                        ]),
                ])->from('md'),
            ]);
    }

    // public function table(Table $table): Table
    // {
    //     return $table
    //         ->query(\App\Models\Section::whereActive()->where('instructor_id', auth()->user()->instructor->id))
    //         ->headerActions([
    //             CreateAction::make('Create Section')
    //                 ->label('Create Section')
    //                 ->color('primary')
    //                 ->model(\App\Models\Section::class)
    //                 ->modalWidth(Width::Large)
    //                 ->schema([
    //                     TextInput::make('name')
    //                         ->label('Section Name')
    //                         ->required()
    //                         ->validationMessages([
    //                             'unique' => 'The section name already exists.',
    //                         ])
    //                         ->extraAlpineAttributes(['@input' => '$el.value = $el.value.toUpperCase()'])
    //                         ->scopedUnique(modifyQueryUsing: fn($query) => $query->whereActive())
    //                         ->trim()
    //                         ->maxLength(20)
    //                         ->placeholder('e.g., BSIT 321A'),
    //                     Select::make('course_id')
    //                         ->label('Course')
    //                         ->relationship(
    //                             'course',
    //                             'code',
    //                             fn($query) => $query->where('department_id', auth()->user()->instructor->department_id)
    //                         )
    //                         ->searchable()
    //                         ->preload()
    //                         ->required(),
    //                 ])
    //                 ->mutateDataUsing(function (array $data): array {
    //                     $data['academic_year_id'] = AcademicYear::WhereActive()->first()?->id;
    //                     $data['name'] = strtoupper($data['name']);
    //                     $data['instructor_id'] = auth()->user()->instructor->id;

    //                     if (!AcademicYear::whereActive()->exists()) {
    //                         Notification::make()
    //                             ->title('No active academic year found. Contact the administrator.')
    //                             ->danger()
    //                             ->send();

    //                         $this->halt();
    //                     }
    //                     return $data;
    //                 })
    //                 ->createAnother(false)
    //         ])
    //         ->recordUrl(fn($record) => route('filament.instructor.pages.my-sections.{section}', ['section' => $record]))
    //         ->columns([
    //             Grid::make(1)
    //                 ->schema([
    //                     ImageColumn::make('avatar_url')
    //                         ->label('Avatar')
    //                         ->imageWidth(250)
    //                         ->imageHeight(250)
    //                         ->alignment(Alignment::Center)
    //                         ->defaultImageUrl(url('https://www.shutterstock.com/image-photo/book-open-pages-close-up-600nw-2562942291.jpg')),
    //                     TextColumn::make('name')
    //                         ->formatStateUsing(fn(Column $column, $state): string => $state ? ('<span class="font-bold">' . $column->getLabel() . ':</span> ' . $state) : 'N/A')
    //                         ->alignment(Alignment::Center)
    //                         ->html()
    //                         ->searchable(),
    //                     TextColumn::make('academicyear.year')
    //                         ->label('School Year')
    //                         ->formatStateUsing(fn(Column $column, $state): string => $state ? ('<span class="font-bold">' . $column->getLabel() . ':</span> ' . $state) : 'N/A')
    //                         ->alignment(Alignment::Center)
    //                         ->html()
    //                         ->searchable(),
    //                     TextColumn::make('course.code')
    //                         ->badge()
    //                         ->alignment(Alignment::Center)
    //                         ->searchable(),
    //                 ])
    //         ])
    //         ->contentGrid([
    //             'sm' => 2,
    //             'xl' => 4,
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->actions([
    //             //
    //         ])
    //         ->bulkActions([
    //             //
    //         ]);
    // }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::getNavigationLabel())
                ->group(static::getNavigationGroup())
                ->parentItem(static::getNavigationParentItem())
                ->icon(static::getNavigationIcon())
                ->activeIcon(static::getActiveNavigationIcon())
                ->isActiveWhen(fn (): bool => original_request()->routeIs([
                    'filament.instructor.pages.my-sections',
                    'filament.instructor.pages.my-sections.*',
                ]))
                ->sort(static::getNavigationSort())
                ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
                ->badgeTooltip(static::getNavigationBadgeTooltip())
                ->url(static::getNavigationUrl()),
        ];
    }
}
