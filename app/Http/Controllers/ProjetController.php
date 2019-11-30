<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Http\UploadedFile;
use App\Projet;
use App\ProjetDetail;
use App\Typeprojet;
use App\Soustype;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Equipe;
use Auth;
use App\Http\Requests\projetRequest;
use App\Http\Requests\soumissionRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class ProjetController extends Controller
{
	//CREATION de constructeur pour l'authentification
	public function __construct(){
		$this->middleware('auth');
	}
    //le systeme crud l'acronyme de create read update delete
    //les methodes a utiliser dans cette class

        public function deleteMembre(Request $request){
                 $idMembre = $request->idMembre ;
                $idProjet = $request->idprojet ;
                
                $whereArray = array('user_id' => $idMembre,'projet_id' => $idProjet);

                $query = DB::table('projets_users');
                foreach($whereArray as $field => $value) {
                    $query->where($field, $value);
                }
                return $query->delete();
             
  /* 
        $users_to_delete = DB::table('projets_users')->where('projet_id', $idProjet, 'user_id', $idMembre )->get();     
            
        $ids_to_delete = array_map(function($item){ return $item[0]; }, $users_to_delete);
       
        DB::table('projets_users')->whereIn('id', $u_p->id)->delete();  
                
            */   
                
        }  


    public function index(){
    	// cette methode permet de lister les projets
    	//$listeProjet = Projet::all();
    	//$listeProjet=Projet::where('user_id',auth::user()->id)->get();
        //$listeProjet= Auth::user()->projets;
        $typeProjets=Typeprojet::all();
        $SousProjets=Soustype::all();


        $user=auth::user();
        $listeProjet = DB::table('projets')->where('user_id', '=',$user->id)->get();

        $user = DB::table('projets_users')->where('user_id', $user->id)->get();
        $mem = new Collection();

            //$new->push(< object >); 
            foreach($user as $u) {
                    $us = Projet::find($u->projet_id);
                         $mem->push($us) ;
            
            }


    	return view('projetviews.index',['listeProjet' => $listeProjet,'typeprojets'=>$typeProjets,'SousProjets'=>$SousProjets,"membres"=>$mem]);
    }



    public function create(){
    	// cette methode permet de cree un projet
        $typeProjets=Typeprojet::all();
    	return view('projetviews.create',['typeprojets'=>$typeProjets]);
    }

    public function store(projetRequest $request){
    	//cette methode permet d'enregistre un projet dans la base de donnée
            $projet= new Projet;
            $projetD = new ProjetDetail ;
            $projet->title=$request->input('nom');
            $pathname = public_path()."/"."storage/".auth::user()->id."/".$projet->title;

            $projet->description=$request->input('description');
            $projet->soustype_id=$request->input('soustype');
            $fileR = $request->file("fichierR");
            $projetD->resume=$fileR->move($pathname,$fileR->getClientOriginalName());
            $projetD->save();
            $projet->detail_id = $projetD->id;
            $projet->user_id = auth::user()->id;
            $projet->save(); 	
            
	      	session()->flash('success','Le projet à été bien enregistré !!');
	      	return redirect('projet');

    }

    public function edit($id){
    	//cette methode permet de modifier un projet
        $rep=Projet::find($id);
        $repd = ProjetDetail::find($rep->detail_id);
        $user = DB::table('projets_users')->where('projet_id', $rep->id)->get();
        $mem = new Collection();

            //$new->push(< object >); 
            foreach($user as $u) {
                    $us = User::find($u->user_id);
                         $mem->push($us) ;
            
            }

    	return view('projetviews.detail',["projet"=>$rep,"projetD"=>$repd,"membres"=>$mem]);
    
    }


  

    public function update(Request $request,$id){
    	//cette methode mise a jour un projet dans la base de donnée
         $this->validate($request, [
            'nom' => "required | min:3 |max:50|unique:projets,title,$id",
            'url' => "nullable | URL |unique:projets,url,$id",
            'apk' => 'nullable | mimes:apk | max:100000',
            'upload' => ' image | max:2000',
            'description' => 'required | min:10 |max:225 ',
            'fichierB' => 'max:2000 | mimes:pdf,PDF,doc,docx',
            'fichierR' => 'max:2000 | mimes:pdf,PDF,doc,docx',
            
    ]);

 	
    }

    public function destroy(Request $request){
        //permet de supprimer un projet
        $idDetail = $request->idProjet;
        $rep=Projet::find($request->idProjet);
    
        File::deleteDirectory(public_path("storage/".auth::user()->id."/".$rep->title));
        DB::table('projets_users')->where('projet_id', $idDetail)->delete();
        DB::table('projets')->where('id', $idDetail)->delete();
        DB::table('projet_details')->where('id', $idDetail)->delete();

                return  $idDetail;
    }

    public function deleteprojet(Request $request){
        //permet de supprimer un projet
        $rep=Projet::find($request->idProjet)->get();
        $idDetail = $rep[0]->detail_id;
        $rep->delete();
        ProjetDetail::find($idDetail)->delete();
        return Response::json($request->idProjet);
        
    	
    }

    public function requestSousType(Request $request){
        $typeprojet=Typeprojet::find($request->input('id'));
        $soustypes=$typeprojet->soustypes;
        return $soustypes->toJson() ;
    }


    public function cloture(Request $request){
        $codeprojet = $request->codeprojet;

        $rep = Projet::find($codeprojet);
        $user = DB::table('projets_users')->where('projet_id', $rep->id)->get();
        $mem = new Collection();

            //$new->push(< object >); 
            foreach($user as $u) {
                    $us = User::find($u->user_id);
                         $mem->push($us) ;
            
            }
        
        return view("projetviews.cloture",["projet"=>$rep,"membres"=>$mem]);
    }


    public function requestImageProjet($idProjet){

        
    }


public function projetupadatedesc(Request $request){
    $textdesc = $request->textdesc;
    $idProjet = $request->idProjet;
    $projet = Projet::find($idProjet);
    $projet->description = $textdesc;
    $projet->save();

}
    public function modifierprojet(Request $request){

            $varprojetname = $request->projetname;
            $varidprojet = $request->idprojet;
            $projet = Projet::find($varidprojet);
            $projetd = ProjetDetail::find($projet->detail_id);
            $pathname = public_path()."/"."storage/".auth::user()->id."/".$projet->title;

        dump($varprojetname);
         if($varprojetname=='URL'){

                $projetd->url = $request->url ;


            }else{

                $varfile = $request->file("file");
                $pathname=$varfile->move($pathname,$varfile->getClientOriginalName());

                if($varprojetname =='APK'){
                    File::delete($projetd->apk);

                    $projetd->apk = $pathname;
    
                }else if($varprojetname =="BUISNESS PLAN"){
                   

                    File::delete($projetd->business_plan);

                    $projetd->business_plan = $pathname ;
                }else {
                    File::delete($projetd->resume);
                    $projetd->resume = $pathname;


                }


            }

            

            $projetd->save();
            
             
            
           
    }

    public function deletefileprojet(Request $request){

        $varprojetname = $request->myinfo;
        $varidprojet = $request->idprojet;
        $projet = Projet::find($varidprojet);
        $projetd = ProjetDetail::find($projet->detail_id);
        if($varprojetname =="APK"){
            File::delete($projetd->apk);

            $projetd->apk = NULL;

        }else if($varprojetname=="URL"){
            $projetd->url = NULL ;


        }else if($varprojetname=="BUISNESS PLAN" ){
            File::delete($projetd->business_plan);
            $projetd->business_plan = NULL ;


        }else {
            File::delete($projetd->resume);

            $projetd->resume = NULL ;

        }

        

        $projetd->save();
    }




    public function verifiernomprojet(Request $request){

        $nomprojet = $request->nomprojet;

        $projets = DB::table('projets')->where('title', $nomprojet)->get();
        return $projets;

    }
}
