<?php

namespace App\Http\Controllers;
use App\Typeprojet;
use App\Projet;
use App\Soustype;
use Illuminate\Http\Request;
use App\Http\Requests\JuryRequest;
use App\Jury;
use Illuminate\Support\Facades\Mail;
use App\Mail\juryMail;
use App\Affectation;
use App\User;

use Auth;

class adminController extends Controller
{
    //systeme crud create read update delete
	public function __construct(){
		$this->middleware('auth:admin');//middlware
	}




	public function index(){
		$typeprojets=Typeprojet::all();
		$juries=Jury::all();
		$projets = Projet::all();
		$users = User::All();

		return view('adminviews.index',['users'=>$users,'typeprojets'=>$typeprojets,'juries'=>$juries,'projets'=>$projets]);
	}


	public function indexSousType($id){
		$typeprojets=Typeprojet::all();
		$typeprojet=Typeprojet::find($id);
		$soustypes=$typeprojet->soustypes;
		$juries=Jury::all();
		return view('adminviews.index',['typeprojets'=>$typeprojets,'juries'=>$juries,'soustypes'=>$soustypes]);
	}

	public function create(){
		return view('adminviews.create');
	}

	public function createSousType(){
		$typeprojets=Typeprojet::all();
		return view('adminviews.createSousType',['typeprojets'=>$typeprojets]);
	}

	public function store(Request $request){
		$typeprojet=new Typeprojet;
		$typeprojet->type=$request->input('nom');
		$typeprojet->save();
		$soustype=new Soustype;
		$soustype->typeprojet_id=$typeprojet->id;
		$soustype->soustype=$request->input('nomS');
		$soustype->save();
		return redirect('admin');
	}

	public function storeSousProjet(Request $request){
		$sousType=new Soustype;
		$sousType->soustype=$request->input('nomS');
		$sousType->typeprojet_id=$request->input('type');
		$sousType->save();
		return redirect('admin');
	}

	public function destroyType($id){
		$typeprojet=Typeprojet::find($id);
		$typeprojet->delete();
		return redirect('admin');
	}

	public function destroySousType($id){
		$soustype=Soustype::find($id);
		$soustype->delete();
		return redirect('admin');
	}

	public function createJury(){
		return view('adminviews.createJury');
	}
	public function storeJury(JuryRequest $request){
		$jury=new Jury;
		$jury->admin_id=Auth::user()->id;
       	$jury->numero=$request->input('numero');
       	$jury->name=$request->input('name');
       	$jury->email=$request->input('email');
       	$jury->type=$request->input('type');
        $email=$request->input('email');
        $hashed_random_password = str_random(8);
        //	$jury->password=bcrypt($request->input('password')); a remplace par la methode faker!!!
       	$jury->password=bcrypt($hashed_random_password);
       	
        $data=['type'=>$request->input('type'),'name'=>$request->input('name'),'numero'=>$request->input('numero'),'password'=>$hashed_random_password];

         Mail::to($email)->send(new juryMail($data));//pour l'envoi password au client pour qu'il puisse se connecter.
        $jury->save();
        session()->flash('success','Jury est bien ajouter.' );
        return redirect('admin');
	}
	public function deleteJury($id){
		$jury=Jury::find($id);
		$jury->delete();
		return redirect('admin');
	}

	public function affectationjury($id){
		$typeprojets=Typeprojet::all();
		return view('adminviews.affectation',['typeprojets'=>$typeprojets,'id'=>$id]);
	}
	public function storeAffectationJury(Request $request,$id){
		$affectation=new Affectation;
		$affectation->jury_id=$id;
		$affectation->soustype_id=$request->input('soustype');
		$affectation->save();
		return redirect('admin');
	}

}
