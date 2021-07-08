<?php

namespace App\Models\Role_User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public static function searchPermission($value=''){
        if(!$value){
            return self::all();
        }

        return self::where('id','like',"%$value%")
        ->orWhere('name','like',"%$value%")
        ->orWhere('slug','like',"%$value%")
        ->orWhere('description','like',"%$value%")
        ->get();
    }
}
