<?php

namespace App\Livewire;

use App\Models\Group;
use Livewire\Component;

class GroupCardsAssigned extends Component
{
    public Group $group;

    public function render()
    {
        return view('livewire.group-cards-assigned');
    }
}
