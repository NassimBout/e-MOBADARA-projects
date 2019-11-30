<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Equipe;
use App\Projet;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = '/projet';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest');
        $this->request = $request;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:50',
            'equipe_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'numero' => 'required|digits:10',
            'password' => 'required|string|min:6|confirmed',
            'nom' => 'required | min:3 |max:50 ',
            'url' => 'required | min:3 |URL',
            'description' => 'required | min:20 |max:225 '
        ]);
    }

    /**     'upload' => ' image | max:2000',
            ,
            'fichierB' => 'max:2000 | mimes:pdf,PDF,doc,docx',
            'fichierR' => 'max:2000 | mimes:pdf,PDF,doc,docx'
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request,array $data)
    {
        $equipe=new Equipe;
        $equipe->nomEquipe=$data['equipe_name'];
        $equipe->save();
        $projet= new Projet;
        $projet->title=$data['nom'];
        $projet->description=$data['description'];
        $projet->url=$data['url'];
        $projet->equipe_id=$equipe->id;
        $projet->soustype_id=$equipe->id;
        //dd($data);
        dd($request);
        //$projet->screenshot=$data->upload->store('image');
        //$projet->screenshot=$request->upload->store('image');
        //$projet->business_plan=$request->fichierB->store('business_plan');
        //$projet->resume=$request->fichierR->store('resume');
        $projet->save();    
        return User::create([
            'chef_equipe'=> 1,
            'equipe_id'=> $equipe->id,  // a terminer.
            'name' => $data['name'],
            'email' => $data['email'],
            'numero' => $data['numero'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
