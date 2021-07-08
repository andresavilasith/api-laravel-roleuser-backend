<?php

namespace App\Models\Role_User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public static function searchCategory($value=''){
        if(!$value){
            return self::all();
        }

        return self::where('id','like',"%$value%")
        ->orWhere('name','like',"%$value%")
        ->orWhere('description','like',"%$value%")
        ->get();
    }
}
