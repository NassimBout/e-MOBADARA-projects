<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\membreRequest;
use Auth;
use App\User;

class membreController extends Controller
{
	//définition de constructeur pour verifier si l'utilisateur est connecter.

	public function __construct(){
		$this->middleware('auth');
	}

    //systeme crud pour les membres d'equipe

    public function create(){
    	return view('membreviews.create');
    }


    public function store(membreRequest $request){
    	$us=new User;
    	$us->name=$request->input('name');
    	$us->email=$request->input('email');
		$us->password=bcrypt($request->input('password'));
		$us->numero = $request->numero;
		$us->save();
		$idprojet = $request->idprojet;

		$projetuser = new projetsUser;
		$projetuser->user_id = $us->id ; 
		$projetuser->projet_id = $idprojet ;
		$projetuser->save();

        return redirect('projet');

	      	//return redirect('projet');
    }

    public function destroy($id){
        
        $user=User::find($id);
        $user->delete();
        return redirect('projet');
	}
	


  
}
