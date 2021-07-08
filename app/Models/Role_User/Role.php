<?php

namespace App\Models\Role_User;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    public static function searchRole($value=''){
        if(!$value){
            return self::all();
        }

        return self::where('id','like',"%$value%")
        ->orWhere('name','like',"%$value%")
        ->orWhere('slug','like',"%$value%")
        ->orWhere('description','like',"%$value%")
        ->orWhere('full_access','like',"%$value%")
        ->get();
    }
}
