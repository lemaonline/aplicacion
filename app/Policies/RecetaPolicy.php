<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Receta;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecetaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Receta');
    }

    public function view(AuthUser $authUser, Receta $receta): bool
    {
        return $authUser->can('View:Receta');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Receta');
    }

    public function update(AuthUser $authUser, Receta $receta): bool
    {
        return $authUser->can('Update:Receta');
    }

    public function delete(AuthUser $authUser, Receta $receta): bool
    {
        return $authUser->can('Delete:Receta');
    }

    public function restore(AuthUser $authUser, Receta $receta): bool
    {
        return $authUser->can('Restore:Receta');
    }

    public function forceDelete(AuthUser $authUser, Receta $receta): bool
    {
        return $authUser->can('ForceDelete:Receta');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Receta');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Receta');
    }

    public function replicate(AuthUser $authUser, Receta $receta): bool
    {
        return $authUser->can('Replicate:Receta');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Receta');
    }

}