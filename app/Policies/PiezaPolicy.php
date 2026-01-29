<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Pieza;
use Illuminate\Auth\Access\HandlesAuthorization;

class PiezaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Pieza');
    }

    public function view(AuthUser $authUser, Pieza $pieza): bool
    {
        return $authUser->can('View:Pieza');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Pieza');
    }

    public function update(AuthUser $authUser, Pieza $pieza): bool
    {
        return $authUser->can('Update:Pieza');
    }

    public function delete(AuthUser $authUser, Pieza $pieza): bool
    {
        return $authUser->can('Delete:Pieza');
    }

    public function restore(AuthUser $authUser, Pieza $pieza): bool
    {
        return $authUser->can('Restore:Pieza');
    }

    public function forceDelete(AuthUser $authUser, Pieza $pieza): bool
    {
        return $authUser->can('ForceDelete:Pieza');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Pieza');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Pieza');
    }

    public function replicate(AuthUser $authUser, Pieza $pieza): bool
    {
        return $authUser->can('Replicate:Pieza');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Pieza');
    }

}