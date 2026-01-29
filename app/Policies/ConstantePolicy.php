<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Constante;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConstantePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Constante');
    }

    public function view(AuthUser $authUser, Constante $constante): bool
    {
        return $authUser->can('View:Constante');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Constante');
    }

    public function update(AuthUser $authUser, Constante $constante): bool
    {
        return $authUser->can('Update:Constante');
    }

    public function delete(AuthUser $authUser, Constante $constante): bool
    {
        return $authUser->can('Delete:Constante');
    }

    public function restore(AuthUser $authUser, Constante $constante): bool
    {
        return $authUser->can('Restore:Constante');
    }

    public function forceDelete(AuthUser $authUser, Constante $constante): bool
    {
        return $authUser->can('ForceDelete:Constante');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Constante');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Constante');
    }

    public function replicate(AuthUser $authUser, Constante $constante): bool
    {
        return $authUser->can('Replicate:Constante');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Constante');
    }

}