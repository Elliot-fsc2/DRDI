<?php

namespace App\Livewire;

use App\Models\Group;
use Livewire\Component;

class GroupCards extends Component
{
    public Group $group;

    public function mount(Group $group)
    {
        $this->group = $group->loadCount('members');
    }

    public function render()
    {
        return view('livewire.group-cards');
    }
}
