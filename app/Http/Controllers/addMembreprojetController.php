<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\projetsUser;
use Illuminate\Support\Facades\Hash;
use App\Mail\envoipasswordmodifier;
use Illuminate\Support\Facades\DB;
use App\Mail\envoimail;

class addMembreprojetController extends Controller
{
    public function adduserprojet(Request $request){

    	$email=$request->input('email');

        $users = DB::table('users')->where('email', $email)->get();

        $idprojet = $request->idprojet;

  //mounia count au lieu de isnul pose pb
        if(count($users)==0){ 
            
        // dump("not exist");
            $us=new User;
            $us->name=$request->input('name');
            $us->email = $email;
          //  $us->password=bcrypt($request->input('password'));
              $us->password=bcrypt('test');
            $us->numero=$request->input('numero');
            $us->save();
    
            $projetuser = new projetsUser;
            $projetuser->user_id = $us->id ; 
            $projetuser->projet_id = $idprojet ;
            $projetuser->save();
    
        }else {
        
            $users_projet = DB::table('projets_users')->where([
                ['user_id', '=', $users[0]->id],
                ['projet_id', '=', $idprojet],
            ])->get();
            
            $projet = DB::table('projets')->where('id', $idprojet)->get();

            if($users[0]->id!=$projet[0]->user_id){
            if(count($users_projet)==0){

            $projetuser = new projetsUser;
            $projetuser->user_id = $users[0]->id ; 
            $projetuser->projet_id = $idprojet ;
            $projetuser->save();
            
            }
        }
    
        }
       
         $data=['name'=>$request->input('name'),'numero'=>$request->input('numero'),'email'=>$request->input('email'), 'id_projet'=>$request->idprojet];    
     
      // dump ('mes données : ' + $request->input('name') + ' ' + $idprojet + ' '  + $email ) ;
       
         Mail::to($email)->send(new envoimail($data));
        
         return redirect("projet");

      
    }


    public function verifierUser(Request $request){
        $pass = $request->password;
      
        $userid = auth::user()->id;

        $user = User::find($userid);
        $user->password =bcrypt($pass);
     
        $user->save();

     // dump($user->email);
     
        $data=['name'=>$user->name,'email'=>$user->email,'password'=>$pass];

        Mail::to($user->email)->send(new envoipasswordmodifier($data));

        return redirect("projet");

        }
        
        
        public function modifierUser(Request $request){
  
        $userid = auth::user()->id;
 
       $user = User::find($userid);
      
    
if ($request->input('name')!=NULL) {$user->name=$request->input('name');}
if ($request->input('numero')!=NULL) {$user->numero=$request->input('numero');}

        $user->save();

    /*
        $data=['name'=>$user->name,'email'=>$user->email,'password'=>$pass];

         Mail::to($user->email)->send(new envoipasswordmodifier($data));  */

        return redirect("projet");

        } 
        
        
        
        
        
        
        
        
        
}
