<?php

namespace App\Filament\Instructor\Pages;

use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Tabs;
use Filament\Navigation\NavigationItem;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Tabs\Tab;
use App\Livewire\ManageStudentsContent;
use function Filament\Support\original_request;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;

class ManageStudents extends Page implements HasSchemas
{
  use InteractsWithSchemas;
  protected string $view = 'filament.instructor.pages.manage-students';
  protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;
  protected ?string $subheading = 'Manage all students in your department';
  protected static string|UnitEnum|null $navigationGroup = 'Management';

  public static function canAccess(): bool
  {
    return auth()->user()->instructor?->canManage() ?? false;
  }

  public function schema(Schema $schema): Schema
  {
    return $schema
      ->schema([
        Tabs::make('tabs')
          ->persistTabInQueryString()
          ->contained(false)
          ->tabs([
            Tab::make('Students')
              ->schema([
                Section::make()
                  ->schema([
                    Livewire::make(ManageStudentsContent::class),
                  ]),

              ]),
          ]),
      ]);
  }



  public static function getNavigationItems(): array
  {
    return [
      NavigationItem::make(static::getNavigationLabel())
        ->group(static::getNavigationGroup())
        ->parentItem(static::getNavigationParentItem())
        ->icon(static::getNavigationIcon())
        ->activeIcon(static::getActiveNavigationIcon())
        ->isActiveWhen(fn(): bool => original_request()->routeIs([
          'filament.instructor.pages.manage-students',
          'filament.instructor.pages.manage-students.*',
        ]))
        ->sort(static::getNavigationSort())
        ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
        ->badgeTooltip(static::getNavigationBadgeTooltip())
        ->url(static::getNavigationUrl()),
    ];
  }
}
