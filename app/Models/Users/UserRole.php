<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected  $table="user_roles";
    public $fillable = ['id','user_role'];
}
