<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\contacterNous;

class contacterNousController extends Controller
{
    //

    public function envoiEmail(Request $request){
    	//dd($request);
    	//echo $request->input('email').'fperofjerf';
    	$data=['email'=>$request->input('email'),'name'=>$request->input('name'),'comments'=>$request->input('comments')];
    	Mail::to('m.abik@access-apps.ma')->send(new contacterNous($data));
    	return redirect ('/');
    }
}
