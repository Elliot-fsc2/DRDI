<?php

namespace App\Livewire;

use App\Models\Section;
use Livewire\Component;

class SectionCards extends Component
{
    public Section $section;

    public function render()
    {
        return view('livewire.section-cards');
    }
}
