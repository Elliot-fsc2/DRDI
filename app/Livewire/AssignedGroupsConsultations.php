<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\Consultation;
use App\Models\Instructor;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Computed;

class AssignedGroupsConsultations extends Component implements HasActions, HasForms
{
  use InteractsWithActions, InteractsWithForms;

  public Group $group;

  public function createConsultationAction(): Action
  {
    return Action::make('createConsultation')
      ->label('Schedule Consultation')
      ->modalHeading('Schedule New Consultation')
      ->modalWidth('md')
      ->modalSubmitActionLabel('Schedule')
      ->modalCancelActionLabel('Cancel')
      ->form([
        DatePicker::make('consultation_date')
          ->label('Consultation Date & Time')
          ->required()
          ->minDate(now()),

        Textarea::make('student_concerns')
          ->label('Student Concerns')
          ->helperText('What topics or concerns will be discussed?')
          ->rows(3),

        TextInput::make('location')
          ->label('Location')
          ->placeholder('e.g., Room 101, Online, etc.'),

        Select::make('consultation_method')
          ->label('Consultation Method')
          ->options([
            'face_to_face' => 'Face to Face',
            'online' => 'Online',
            'hybrid' => 'Hybrid',
          ])
          ->default('face_to_face')
          ->required(),

        Select::make('status')
          ->label('Status')
          ->options([
            'scheduled' => 'Scheduled',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'rescheduled' => 'Rescheduled',
          ])
          ->default('scheduled')
          ->required(),
      ])
      ->action(function (array $data): void {
        $this->group->consultations()->create([
          'instructor_id' => Auth::user()->instructor->id,
          'consultation_date' => $data['consultation_date'],
          'student_concerns' => $data['student_concerns'],
          'location' => $data['location'],
          'consultation_method' => $data['consultation_method'],
          'status' => $data['status'],
        ]);

        Notification::make()
          ->title('Consultation scheduled successfully')
          ->success()
          ->send();
      })
      ->icon('heroicon-o-calendar');
  }

  public function deleteConsultationAction(): Action
  {
    return Action::make('deleteConsultation')
      ->label('Delete')
      ->color('danger')
      ->requiresConfirmation()
      ->modalHeading('Delete Consultation')
      ->modalDescription('Are you sure you want to delete this consultation? This action cannot be undone.')
      ->modalSubmitActionLabel('Delete')
      ->modalCancelActionLabel('Cancel')
      ->visible(function (array $arguments): bool {
        $consultation = Consultation::find($arguments['consultation']);
        return $consultation && $consultation->instructor_id === Auth::user()->instructor->id;
      })
      ->action(function (array $arguments): void {
        $consultation = Consultation::find($arguments['consultation']);

        if ($consultation->instructor_id !== Auth::user()->instructor->id) {
          Notification::make()
            ->title('Unauthorized')
            ->body('You can only delete consultations you created.')
            ->danger()
            ->send();
          return;
        }

        $consultation->delete();

        Notification::make()
          ->title('Consultation deleted successfully')
          ->success()
          ->send();
      })
      ->icon('heroicon-o-trash');
  }

  public function editConsultationAction(): Action
  {
    return Action::make('editConsultation')
      ->label('Edit')
      ->color('primary')
      ->modalHeading('Edit Consultation')
      ->modalWidth('md')
      ->modalSubmitActionLabel('Update')
      ->modalCancelActionLabel('Cancel')
      ->visible(function (array $arguments): bool {
        $consultation = Consultation::find($arguments['consultation']);
        return $consultation && $consultation->instructor_id === Auth::user()->instructor->id;
      })
      ->fillForm(function (array $arguments): array {
        $consultation = Consultation::find($arguments['consultation']);

        return [
          'consultation_date' => $consultation->consultation_date,
          'student_concerns' => $consultation->student_concerns,
          'instructor_feedback' => $consultation->instructor_feedback,
          'location' => $consultation->location,
          'consultation_method' => $consultation->consultation_method,
          'status' => $consultation->status,
          'next_consultation_date' => $consultation->next_consultation_date,
        ];
      })
      ->form([
        DateTimePicker::make('consultation_date')
          ->label('Consultation Date & Time')
          ->required(),

        Textarea::make('student_concerns')
          ->label('Student Concerns')
          ->rows(3),

        Textarea::make('instructor_feedback')
          ->label('Instructor Feedback')
          ->rows(3),

        TextInput::make('location')
          ->label('Location'),

        Select::make('consultation_method')
          ->label('Consultation Method')
          ->options([
            'face_to_face' => 'Face to Face',
            'online' => 'Online',
            'hybrid' => 'Hybrid',
          ])
          ->required(),

        Select::make('status')
          ->label('Status')
          ->options([
            'scheduled' => 'Scheduled',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'rescheduled' => 'Rescheduled',
          ])
          ->required(),

        DatePicker::make('next_consultation_date')
          ->label('Next Consultation Date (Optional)'),
      ])
      ->action(function (array $data, array $arguments): void {
        $consultation = Consultation::find($arguments['consultation']);

        if ($consultation->instructor_id !== Auth::user()->instructor->id) {
          Notification::make()
            ->title('Unauthorized')
            ->body('You can only edit consultations you created.')
            ->danger()
            ->send();
          return;
        }

        $consultation->update([
          'consultation_date' => $data['consultation_date'],
          'student_concerns' => $data['student_concerns'],
          'instructor_feedback' => $data['instructor_feedback'],
          'location' => $data['location'],
          'consultation_method' => $data['consultation_method'],
          'status' => $data['status'],
          'next_consultation_date' => $data['next_consultation_date'],
        ]);

        Notification::make()
          ->title('Consultation updated successfully')
          ->success()
          ->send();
      })
      ->icon('heroicon-o-pencil');
  }

  #[Computed]
  public function consultations()
  {
    return $this->group->consultations()
      ->with('instructor.user')
      ->latest('consultation_date')
      ->get();
  }

  public function render()
  {
    return view('livewire.assigned-groups-consultations');
  }
}
