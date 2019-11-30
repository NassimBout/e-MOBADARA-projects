<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjetDetail extends Model
{
    public function projet()
    {
        return $this->belongsTo('App\projet');
    }
}
