<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	protected  $table="items";
    public $fillable = ['title','description'];
}
