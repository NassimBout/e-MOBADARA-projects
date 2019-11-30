<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soustype extends Model
{
    //
    public function typeprojet(){
        return $this->belongsTo('App\Typeprojet');
    }
}
