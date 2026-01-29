<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\GastoGeneral;
use Illuminate\Auth\Access\HandlesAuthorization;

class GastoGeneralPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:GastoGeneral');
    }

    public function view(AuthUser $authUser, GastoGeneral $gastoGeneral): bool
    {
        return $authUser->can('View:GastoGeneral');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:GastoGeneral');
    }

    public function update(AuthUser $authUser, GastoGeneral $gastoGeneral): bool
    {
        return $authUser->can('Update:GastoGeneral');
    }

    public function delete(AuthUser $authUser, GastoGeneral $gastoGeneral): bool
    {
        return $authUser->can('Delete:GastoGeneral');
    }

    public function restore(AuthUser $authUser, GastoGeneral $gastoGeneral): bool
    {
        return $authUser->can('Restore:GastoGeneral');
    }

    public function forceDelete(AuthUser $authUser, GastoGeneral $gastoGeneral): bool
    {
        return $authUser->can('ForceDelete:GastoGeneral');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:GastoGeneral');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:GastoGeneral');
    }

    public function replicate(AuthUser $authUser, GastoGeneral $gastoGeneral): bool
    {
        return $authUser->can('Replicate:GastoGeneral');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:GastoGeneral');
    }

}