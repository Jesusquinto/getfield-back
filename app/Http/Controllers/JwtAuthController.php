<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\usuario;
use Illuminate\Support\Facades\Auth;

class JwtAuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }


    public function cifrar(Request $request){
        $res = Hash::make($request['pass']);
        echo $res;

    }



    


    public function Login(Request $request)
    {


        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);
        $user = array(
            'email' => $request->email,
            'password' => $request->password
        );
        //Try catch
        try { 
            if (!Auth::attempt($user)){
                return response()->json(['user_not_found'], 404);
            }else{
                $user = usuario::where('email',$request['email'])->first();
            }
            if (!$token = $this->jwt->fromUser($user)) {
                 return response()->json(['user_not_found'], 404);
             } 
           
            

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        return response()->json(compact('token'));
    }
}