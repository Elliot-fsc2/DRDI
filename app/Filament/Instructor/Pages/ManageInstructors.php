<?php

namespace App\Filament\Instructor\Pages;

use App\Livewire\ManageInstructorsContent;
use BackedEnum;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use UnitEnum;

use function Filament\Support\original_request;

class ManageInstructors extends Page implements HasSchemas, HasTable
{
    use InteractsWithSchemas, InteractsWithTable;

    protected string $view = 'filament.instructor.pages.manage-instructor';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected ?string $subheading = 'Manage all instructors in your department';

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
                        Tab::make('Instructors')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Livewire::make(ManageInstructorsContent::class),
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
                ->isActiveWhen(fn (): bool => original_request()->routeIs([
                    'filament.instructor.pages.manage-instructors',
                    'filament.instructor.pages.manage-instructors.*',
                ]))
                ->sort(static::getNavigationSort())
                ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
                ->badgeTooltip(static::getNavigationBadgeTooltip())
                ->url(static::getNavigationUrl()),
        ];
    }
}
