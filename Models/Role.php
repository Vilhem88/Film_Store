<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [

        'name',
        'display_name',
    ];


    // public function admin(){
    //     return $this->hasOne(AdminController::class);
    // }

    public function users(){
        return $this->hasMany(UserController::class);
    }


}
