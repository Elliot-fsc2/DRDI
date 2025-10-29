<?php

namespace App\Livewire;

use App\Models\Instructor;
use Livewire\Component;

class InstructorCards extends Component
{
    public Instructor $instructor;

    public function render()
    {
        return view('livewire.instructor-cards');
    }
}
