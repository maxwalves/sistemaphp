<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Permission;

class UserPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */

    public function viewUsers(User $user)
    {
        $permission = Permission::where('name', 'view users')->first();
        try {
            return $user->hasPermissionTo($permission);
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function aprovAvsGestor(User $user)
    {
        $permission = Permission::where('name', 'aprov avs gestor')->first();
        try {
            return $user->hasPermissionTo($permission);
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function aprovAvsDiretoria(User $user)
    {
        $permission = Permission::where('name', 'aprov avs diretoria')->first();
        try {
            return $user->hasPermissionTo($permission);
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function aprovAvsSecretaria(User $user)
    {
        $permission = Permission::where('name', 'aprov avs secretaria')->first();
        try {
            return $user->hasPermissionTo($permission);
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function aprovAvsFinanceiro(User $user)
    {
        $permission = Permission::where('name', 'aprov avs financeiro')->first();
        try {
            return $user->hasPermissionTo($permission);
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function aprovAvsFrota(User $user)
    {
        $permission = Permission::where('name', 'aprov avs frota')->first();
        try {
            return $user->hasPermissionTo($permission);
        } catch (\Throwable $th) {
            return false;
        }
    }

}
