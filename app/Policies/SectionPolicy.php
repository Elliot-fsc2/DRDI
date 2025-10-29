<?php

namespace App\Policies;

use App\Models\section;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SectionPolicy
{
    /**
     * Handle all authorization before checking specific abilities.
     */
    public function before(User $user): ?bool
    {
        if ($user->role === 'admin' || ($user->instructor && $user->instructor->roles->contains('name', 'DRDI Head'))) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, section $section): Response
    {
        return $user->instructor->id === $section->instructor_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, section $section): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, section $section): Response
    {
        return $user->instructor->id === $section->instructor_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, section $section): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, section $section): bool
    {
        return false;
    }
}
