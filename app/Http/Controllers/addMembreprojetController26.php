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


class addMembreprojetController extends Controller
{
    public function adduserprojet(Request $request){

    	$email=$request->input('email');

        $users = DB::table('users')->where('email', $email)->get();

        $idprojet = $request->idprojet;


        if(is_null($users)){ 
            
            $us=new User;
            $us->name=$request->input('name');
            $us->email = $email;
            $us->password=bcrypt($request->input('password'));
            $us->save();
    
            $projetuser = new projetsUser;
            $projetuser->user_id = $us->id ; 
            $projetuser->projet_id = $idprojet ;
            $projetuser->save();
            
            return redirect("projet");
        
        }else {

            $users_projet = DB::table('projets_users')->where([
                ['user_id', '=', $users[0]->id],
                ['projet_id', '=', $idprojet],
            ])->get();

            if(count($users_projet)==0){

            $projetuser = new projetsUser;
            $projetuser->user_id = $users[0]->id ; 
            $projetuser->projet_id = $idprojet ;
            $projetuser->save();
            
            }

            return redirect("projet");


        }

       

    }


    public function verifierUser(Request $request){
        $pass = $request->password;
      
        $userid = auth::user()->id;

        $user = User::find($userid);
        $user->password =bcrypt($pass);
        $user->save();

        $data=['name'=>$user->name,'email'=>$user->email,'password'=>$pass];

         Mail::to($user->email)->send(new envoipasswordmodifier($data));

        return redirect("projet");

        }
}
