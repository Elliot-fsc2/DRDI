<?php

namespace App\Observers;

use App\Models\Group;

class GroupObserver
{
    public function creating(Group $group): void
    {
        // Automatically set the group's name if not provided
        if (empty($group->name)) {
            $sectionId = $group->section_id ?? null;

            if ($sectionId) {
                $count = Group::where('section_id', $sectionId)->count();
                $group->name = 'Group #'.($count + 1);
            } else {
                $group->name = 'Group '.strtoupper(bin2hex(random_bytes(3)));
            }
        }
    }

    /**
     * Handle the Group "created" event.
     */
    public function created(Group $group): void
    {
        //
    }

    /**
     * Handle the Group "updated" event.
     */
    public function updated(Group $group): void
    {
        //
    }

    /**
     * Handle the Group "deleted" event.
     */
    public function deleted(Group $group): void
    {
        //
    }

    /**
     * Handle the Group "restored" event.
     */
    public function restored(Group $group): void
    {
        //
    }

    /**
     * Handle the Group "force deleted" event.
     */
    public function forceDeleted(Group $group): void
    {
        //
    }
}
