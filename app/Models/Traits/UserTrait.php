<?php

namespace App\Models\Traits;

use App\Models\Role_User\Role;

trait UserTrait
{
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function havePermission($permission)
    {

        //Verificar si el acceso es absoluto
        foreach ($this->roles as $role) {
            if ($role->full_access == 'yes') {
                return true;
            }
        }

        //Verificar si el permison pertenece al rol
        foreach($role->permissions as $perm){
            if($perm->slug == $permission){
                return true;
            }
        }

        return false;
    }
}
