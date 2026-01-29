<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Labor;
use Illuminate\Auth\Access\HandlesAuthorization;

class LaborPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Labor');
    }

    public function view(AuthUser $authUser, Labor $labor): bool
    {
        return $authUser->can('View:Labor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Labor');
    }

    public function update(AuthUser $authUser, Labor $labor): bool
    {
        return $authUser->can('Update:Labor');
    }

    public function delete(AuthUser $authUser, Labor $labor): bool
    {
        return $authUser->can('Delete:Labor');
    }

    public function restore(AuthUser $authUser, Labor $labor): bool
    {
        return $authUser->can('Restore:Labor');
    }

    public function forceDelete(AuthUser $authUser, Labor $labor): bool
    {
        return $authUser->can('ForceDelete:Labor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Labor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Labor');
    }

    public function replicate(AuthUser $authUser, Labor $labor): bool
    {
        return $authUser->can('Replicate:Labor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Labor');
    }

}