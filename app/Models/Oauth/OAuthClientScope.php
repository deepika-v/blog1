<?php

namespace App\Models\Oauth;

use Illuminate\Database\Eloquent\Model;

class OAuthClientScope extends Model
{
    protected  $table="oauth_scopes";
    protected $fillable = [

        'id', 'client_id', 'scope_id', 'created_at', 'updated_at'
    ];
}
