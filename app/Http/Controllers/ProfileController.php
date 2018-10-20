<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use JWTFactory;
use JWTAuth;
use App\User;
use Response;
use GuzzleHttp\Client;

class ProfileController extends Controller
{
    //Register function 
      public function register(Request $request)
    {

        $validator= Validator::make($request->all(),[
            'email'=>'required |string |email|max:255|unique:users',
             'name'=>'required',
             'password'=>'required'
        ]);

        if($validator-> fails())
        {

            return response()->json($validator->errors());

        }
        try

        {

        $users=new   User();
        $users->name=$request->name;
        $users->email=$request->email;
        $users->password=Hash::make($request->password);

        $users->save();
        $token=JWTAuth::fromUser($users);

            return Response::json(compact('users')) ;
        }

            catch(Exception $e)
        {
            return response()->json(['error'=>$e]);   
           
        }
     
    }
     
  //Login Function


       public function login(Request $request)
    {


        $validator= Validator::make($request->all(),[

            'email'=>'required |string |email|max:255',
            'password'=>'required'

        ]);

        if($validator-> fails())
        {
            return response()->json($validator->errors());
        }


        $credentials =$request->only('email','password');
            try
        {


            if(!$token=JWTAuth::Attempt($credentials))
            {
                return response()->json(['error'=>'invalid username and password'],400);
            }
        }
            catch(JWTException $e)
            {
                return response()->json(['error'=>'couldnot create token'],500);
            }

            return Response::json(compact('users'));
    }


    //Area 
        
       public function getArea ()
    {


        $client = new Client();
        $res = $client->get('https://restcountries.eu/rest/v2/region/europe');        
        return $res->getBody(); 

      
    }

}



