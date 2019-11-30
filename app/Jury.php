<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Jury extends Authenticatable
{
	use Notifiable;
	protected $guard='jury';
    //
}
