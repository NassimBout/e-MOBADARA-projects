<?php

namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Projet;
use File;

use App\Typeprojet;
use App\Soustype;
use App\User;
use App\ProjetDetail ; 
use Auth;
use App\Http\Requests\projetRequest;
use App\Http\Requests\soumissionRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\envoiPasswordClient;

class SoumissionController extends Controller
{
    //  protected $redirectTo = '';
        public function __construct(){

            $this->middleware('guest');

        }
        public function storeUserProjetEquipe(soumissionRequest $request){

        //echo('<h1>hello world</h1>'.$request->input('equipe_name'));
 
        $projet= new Projet;// creation d'un object 'model' projet 
        $projetD = new ProjetDetail ; 
        // affectation des attribute d'object
        $projet->title=$request->input('nom');
      //  dump($projet->title);
        $projet->description=$request->input('description');
        $publicPath = public_path('storage');



      //  $projet->equipe_id=$equipe->id;

        $projet->soustype_id=$request->input('soustype');

     
     $fileR = $request->file("fichierR");
        //$equipe->save();
        $user=new User;
        $user->numero=$request->input('numero');
        $user->name=$request->input('name');
        $email=$request->input('email');
        $user->email= $email;
        $user->organisme= $request->input('organisme');;

        $hashed_random_password = str_random(8);
        //$user->password=bcrypt($request->input('password')); a remplace par la methode faker!!!
        $user->password=bcrypt($hashed_random_password);
        $user->save();
        $projet->user_id=$user->id;

        $pathname =  $publicPath."/".$user->id."/".$projet->title;
        $projetD->resume=$fileR->move($pathname,$fileR->getClientOriginalName());

        $projetD->save();
        $projet->detail_id = $projetD->id;
        $projet->save();


        $data=['titreduprojet'=>$projet->title,'name'=>$request->input('name'),'numero'=>$request->input('numero'),'password'=>$hashed_random_password, 'email'=>$request->input('email')];

        try {
            
         Mail::to($email)->send(new envoiPasswordClient($data));//pour l'envoi password au client pour qu'il puisse se connecter.
        } catch (Exception $e) {
            
            \Log::info('password  information.' .$hashed_random_password);        
        }
            
     //mounia
        session()->flash('success','Votre soumission a été bien effectuée. Veuillez consulter votre boite email pour récupérer le mot de passe!!!!' );
        return redirect('login');
    }

    public function soumission(){
        $typeprojets=Typeprojet::all();
        return view('projetviews/soumission',['typeprojets'=>$typeprojets]);
    }
    
    public function requestSousType(Request $request){
        $typeprojet=Typeprojet::find($request->input('id'));
        $soustypes=$typeprojet->soustypes;
        return $soustypes->toJson() ;
    }
}
