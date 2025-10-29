<?php

namespace App\Filament\Instructor\Pages;

use App\Models\Announcement;
use BackedEnum;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Form as ComponentsForm;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class CreateAnnouncement extends Page implements HasForms
{
  use InteractsWithForms;
  protected static string|BackedEnum|null $navigationIcon = Heroicon::Plus;
  protected string $view = 'filament.instructor.pages.create-announcement';
  protected static ?string $title = 'Create Announcement';
  protected static ?string $navigationLabel = 'Create Announcement';
  protected static bool $shouldRegisterNavigation = false;
  protected static ?string $slug = 'news/create';
  public ?array $data = [];

  public static function canAccess(): bool
  {
    $user = auth()->user();
    return $user && $user->instructor?->roles()->where('name', 'DRDI Head')->exists();
  }

  public function mount(): void
  {
    $this->form->fill();
  }

  public function form(Schema $form): Schema
  {
    return $form
      ->components([
        ComponentsSection::make('Announcement Details')
          ->schema([
            TextInput::make('title')
              ->required()
              ->maxLength(255)
              ->placeholder('Enter announcement title')
              ->columnSpanFull(),

            RichEditor::make('content')
              ->required()
              ->extraInputAttributes(['style' => 'min-height: 20rem; max-height: 50vh; overflow-y: auto;'])
              ->placeholder('Write your announcement content here...')
              ->toolbarButtons([
                'bold',
                'italic',
                'underline',
                'bulletList',
                'orderedList',
                'h2',
                'h3',
                'link',
              ])
              ->columnSpanFull(),
          ]),

        ComponentsSection::make('Publishing Settings')
          ->schema([
            DatePicker::make('published_at')
              ->label('Publish Date')
              ->default(now())
              ->required()
              ->placeholder('When should this announcement be published?'),

            Checkbox::make('is_pinned')
              ->label('Pin this announcement')
              ->helperText('Pinned announcements appear at the top of the news feed'),
          ]),
      ])
      ->statePath('data');
  }

  public function create(): void
  {
    $data = $this->form->getState();

    Announcement::create([
      'title' => $data['title'],
      'content' => $data['content'],
      'author_id' => auth()->user()->id,
      'published_at' => $data['published_at'],
      'is_pinned' => $data['is_pinned'] ?? false,
      'is_active' => true,
    ]);

    Notification::make()
      ->title('Announcement created successfully!')
      ->success()
      ->send();

    $this->redirect(route('filament.instructor.pages.news'));
  }

  protected function getFormActions(): array
  {
    return [
      \Filament\Actions\Action::make('create')
        ->label('Create Announcement')
        ->action('create')
        ->color('primary')
        ->icon('heroicon-o-plus'),

      \Filament\Actions\Action::make('cancel')
        ->label('Cancel')
        ->url(route('filament.instructor.pages.news'))
        ->color('gray')
        ->icon('heroicon-o-arrow-left'),
    ];
  }
}
