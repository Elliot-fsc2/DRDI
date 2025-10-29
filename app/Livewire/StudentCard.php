<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;

class StudentCard extends Component
{
  public Student $student;
  public function render()
  {
    return view('livewire.student-card');
  }
}
