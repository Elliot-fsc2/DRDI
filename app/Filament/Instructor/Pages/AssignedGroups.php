<?php

namespace App\Filament\Instructor\Pages;

use BackedEnum;
use App\Models\Group;
use App\Models\Section;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationItem;
use function Filament\Support\original_request;

class AssignedGroups extends Page
{
    protected string $view = 'filament.instructor.pages.assigned-groups';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?int $navigationSort = 3;

    public $selectedSection = null;

    public $selectedRole = null;

    #[Computed]
    public function availableSections()
    {
        return Section::whereHas('groups.personnels', function ($query) {
            $query->where('instructor_id', Auth::user()->instructor->id);
        })
            ->with('course')
            ->get()
            ->pluck('name', 'id');
    }

    #[Computed]
    public function groups()
    {
        return Group::with(['section', 'section.instructor', 'personnels.instructor'])
            ->withCount('members')
            ->when(
                $this->selectedSection,
                fn($query) => $query->where('section_id', $this->selectedSection)
            )
            ->whereHas('personnels', function ($query) {
                $query->where('instructor_id', Auth::user()->instructor->id)
                    ->when(
                        $this->selectedRole,
                        fn($q) => $q->where('role', $this->selectedRole)
                    );
            })
            ->paginate(15);
    }

    #[Computed]
    public function availableRoles()
    {
        return [
            'technical_adviser' => 'Technical Adviser',
            'language_critic' => 'Language Critic',
            'statistician' => 'Statistician',
            'grammarian' => 'Grammarian',
        ];
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
                    'filament.instructor.pages.assigned-groups',
                    'filament.instructor.pages.assigned-groups.*',
                ]))
                ->sort(static::getNavigationSort())
                ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
                ->badgeTooltip(static::getNavigationBadgeTooltip())
                ->url(static::getNavigationUrl()),
        ];
    }
}
