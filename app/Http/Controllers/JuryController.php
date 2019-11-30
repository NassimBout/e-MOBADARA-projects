<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JuryController extends Controller
{
    //
    public function __construct()
    {
    	//$this->middleware('auth:jury');//middlware
    }



    public function index(){
		
		return view('juryviews.index');
	}
}
