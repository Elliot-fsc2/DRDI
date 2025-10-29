<?php

namespace App\Filament\Instructor\Pages;

use BackedEnum;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Announcement;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Checkbox;
use Filament\Navigation\NavigationItem;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\DateTimePicker;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use function Filament\Support\original_request;

class News extends Page implements HasSchemas, HasActions
{
  use InteractsWithSchemas, InteractsWithActions;
  protected string $view = 'filament.instructor.pages.news';
  protected static string|BackedEnum|null $navigationIcon = Heroicon::Megaphone;
  protected static ?int $navigationSort = 4;

  public function getTitle(): string|Htmlable
  {
    return '';
  }

  public function getAnnouncements()
  {
    return Announcement::with('author')
      ->published()
      ->orderBy('is_pinned', 'desc')
      ->orderBy('published_at', 'desc')
      ->get();
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
          'filament.instructor.pages.news',
          'filament.instructor.pages.news.*',
        ]))
        ->sort(static::getNavigationSort())
        ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
        ->badgeTooltip(static::getNavigationBadgeTooltip())
        ->url(static::getNavigationUrl()),
    ];
  }
}
