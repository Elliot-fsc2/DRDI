<?php

namespace App\Livewire;

use App\Models\Instructor;
use Livewire\Component;

class InstructorSectionHandle extends Component
{
    public Instructor $instructor;

    public function render()
    {
        $sections = $this->instructor->sections()
            ->with(['course'])
            ->withCount(['students', 'groups'])
            ->get();

        return view('livewire.instructor-section-handle', [
            'sections' => $sections,
        ]);
    }
}
