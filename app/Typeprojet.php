<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typeprojet extends Model
{
    //
    public function soustypes(){
    	return $this->hasMany('App\Soustype');
    }
}
