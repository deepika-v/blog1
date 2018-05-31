<?php

namespace App\Models\Oauth;

use Illuminate\Database\Eloquent\Model;

class OAuthClient extends Model
{
    protected  $table="oauth_clients";
    protected $fillable = [

        'id', 'secret', 'name', 'created_at', 'updated_at'
    ];
}
