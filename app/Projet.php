<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projet extends Model
{
    //
    use SoftDeletes;

    protected $dates = ['deleted_at'];



    public function projetDetail()
    {
        return $this->hasOne('App\ProjetDetail');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
