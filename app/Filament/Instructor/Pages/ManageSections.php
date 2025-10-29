<?php

namespace App\Filament\Instructor\Pages;

use App\Livewire\ManageSectionsContent;
use BackedEnum;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

use function Filament\Support\original_request;

class ManageSections extends Page implements HasActions, HasSchemas
{
  use InteractsWithActions;
  use InteractsWithSchemas;

  protected string $view = 'filament.instructor.pages.manage-sections';

  protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

  protected ?string $subheading = 'Manage all sections in your department for the current academic year';

  protected static string|UnitEnum|null $navigationGroup = 'Management';

  // public function mount(): void
  // {
  //     Auth::loginUsingId(2);
  // }
  public static function canAccess(): bool
  {
    return auth()->user()->instructor?->canManage() ?? false;
  }

  public function schema(Schema $schema): Schema
  {
    return $schema
      ->components([
        Tabs::make('tabs')
          ->contained(false)
          ->tabs([
            Tabs\Tab::make('Sections')
              // ->badge('sad')
              ->schema([
                Section::make()
                  ->schema([
                    Livewire::make(ManageSectionsContent::class),
                  ]),
              ])
              ->icon(Heroicon::AcademicCap),
          ]),
      ]);
  }

  // #[Computed]
  // public function table(Table $table): Table
  // {
  //     return $table
  //         ->query(
  //             Section::query()->whereActive()->withCount('students')
  //         )
  //         ->headerActions([
  //             CreateAction::make('Create Section')
  //                 ->label('Create Section')
  //                 ->color('primary')
  //                 ->model(Section::class)
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
  //                     Select::make('instructor_id')
  //                         ->label('Instructor')
  //                         ->relationship(
  //                             'instructor',
  //                             modifyQueryUsing: fn($query) => $query->where('department_id', auth()->user()->instructor->department_id)
  //                         )
  //                         ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->full_name)
  //                         ->preload()
  //                         ->searchable()
  //                         ->required(),
  //                 ])
  //                 ->using(function (array $data, string $model): Model {
  //                     $data['academic_year_id'] = AcademicYear::WhereActive()->first()?->id;
  //                     $data['name'] = strtoupper($data['name']);

  //                     if (!AcademicYear::whereActive()->exists()) {
  //                         Notification::make()
  //                             ->title('No active academic year found. Contact the administrator.')
  //                             ->danger()
  //                             ->send();

  //                         $this->halt();
  //                     }
  //                     // dd($data);
  //                     return $model::create($data);
  //                 })
  //                 ->createAnother(false)
  //         ])
  //         // ->searchable(['name', 'email', 'role'])
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
  //                         ->alignment(Alignment::Center)
  //                         ->label('Section Name')
  //                         ->formatStateUsing(fn(Column $column, $state): string => $state ? ('<span class="font-bold">' . $column->getLabel() . ':</span> ' . $state) : 'N/A')
  //                         ->html()
  //                         ->searchable(),

  //                     TextColumn::make('instructor.user.name')
  //                         ->alignment(Alignment::Center)
  //                         ->formatStateUsing(fn(Column $column, $state): string => $state ? ('<span class="font-bold">' . $column->getLabel() . ':</span> ' . $state) : 'N/A')
  //                         ->html()
  //                         ->searchable(),
  //                     TextColumn::make('students_count')
  //                         ->label('Enrolled Students')
  //                         ->formatStateUsing(fn(Column $column, $state): string => $state ? ('<span class="font-bold">' . $column->getLabel() . ':</span> ' . $state) : '<span class="font-bold">' . 'Enrolled Students: ' . '</span> ' . '0')
  //                         ->html()
  //                         ->counts('students')
  //                         ->alignment(Alignment::Center),
  //                     TextColumn::make('course.code')
  //                         ->alignment(Alignment::Center)
  //                         ->formatStateUsing(function (Column $column, $state): string {
  //                             if (blank($state)) {
  //                                 return 'N/A';
  //                             }

  //                             // If $state is an array or Collection, join the roles
  //                             if (is_iterable($state)) {
  //                                 $roles = collect($state)->filter()->all();
  //                                 if (empty($roles)) {
  //                                     return 'N/A';
  //                                 }
  //                                 return collect($roles)
  //                                     ->map(fn($role) => '<span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-200 mr-1">' . e($role) . '</span>')
  //                                     ->implode('');
  //                             }

  //                             // Otherwise, single role
  //                             return '<span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-200">' . e($state) . '</span>';
  //                         })
  //                         ->html(),
  //                 ])
  //         ])
  //         ->contentGrid([
  //             'sm' => 2,
  //             'xl' => 4,
  //         ])
  //         ->recordUrl(fn(Section $record): string => route('filament.instructor.pages.manage-sections.{section}', $record))
  //         ->emptyStateHeading('No Sections Found')
  //         ->filters([

  //             SelectFilter::make('course')
  //                 ->relationship('course', 'code')
  //                 ->searchable()
  //                 ->preload()
  //                 ->multiple(),
  //         ])
  //         ->deferFilters(false)
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
        ->isActiveWhen(fn(): bool => original_request()->routeIs([
          'filament.instructor.pages.manage-sections',
          'filament.instructor.pages.manage-sections.*',
        ]))
        ->sort(static::getNavigationSort())
        ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
        ->badgeTooltip(static::getNavigationBadgeTooltip())
        ->url(static::getNavigationUrl()),
    ];
  }
}
