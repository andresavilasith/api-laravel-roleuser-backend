<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $user_model
     * @return mixed
     */
    public function view(User $user, User $user_model, $perm = null)
    {
        //Si el usuario que ha iniciado sesion tiene el primer permiso
        if ($user->havePermission($perm[0])) {
            return true;
        } else {
            //Si descarta fullaccess y user.show entonces comprueba que el usuario 
            //logueado sea igual al usuario registrado para poder mostrar  
            if ($user->havePermission($perm[1])) {

                return $user->id === $user_model->id;
            }

            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->id > 0;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $user_model
     * @return mixed
     */
    public function update(User $user, User $user_model, $perm = null)
    {
        if ($user->havePermission($perm[0])) {
            return true;
        } else {
            if ($user->havePermission($perm[1])) {
                return $user->id === $user_model->id;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $user_model
     * @return mixed
     */
    public function delete(User $user, User $user_model, $perm = null)
    {
        if ($user->havePermission($perm[0])) {
            return true;
        } else {
            if ($user->havePermission($perm[1])) {
                return $user->id === $user_model->id;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
