<?php
namespace App\Http\Controllers;
use App\usuario;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\JwtAuthController;
use Tymon\JWTAuth\JWTAuth;
class googleAuthController extends Controller
{

      /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }


   
//    /**
//     * Create a new controller instance.
//     *
//     */
//    public function __construct()
//    {
//
//    }
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        /**
         * Use this if you want to do the redirect portion from your Lumen App.  You can also do this portion from your frontend app... for example you
         * could be using https://github.com/sahat/satellizer in angular for the redirect portion, and then have it CALLBACK to your lumen app.
         * In other words, this "redirectToProvider" method is optional on the backend (you can do it from your frontend)
         */
        return Socialite::driver('google')->stateless()->redirect();
    }
    /**
     * Obtain the user information from Facebook.
     *
     * @return JsonResponse
     */
    public function googleSingIn(Request $request)
    {
        $driver = Socialite::driver('google'); 
        $socialUser = $driver->userFromToken($request->accessToken);
        if(!$user = usuario::where('email',$socialUser->email)->first()){
            return response()->json([
                'error' => [
                   'title'=> 'No se pudo iniciar sesion',
                    'code' => 400,
                    'message' => "La cuenta google con la que intentas ingresar, no esta registrada",
                ]
                ], 400);
        }
        return $this->createToken($socialUser->email);

 }
    
    public function googleSingUp(Request $request){
        $driver = Socialite::driver('google'); 
        $socialUser = $driver->userFromToken($request->accessToken);
        if($user = usuario::where('email',$socialUser->email)->first()){
            return response()->json([
                'error' => [
                   'title'=> 'No se pudo registrar la cuenta',
                    'code' => 400,
                    'message' => "La cuenta google con la que intentas registrate, ya está en uso",
                    'note' => 'Por favor, inicia sesión'
                ]
                ], 400);  
        }else{
            $nickname = explode('@', $socialUser->email);
            $usuario = usuario::create([
                'nombre' => $socialUser->user['given_name'],
                'apellido' => $socialUser->user['family_name'],
                'usuario' => $nickname[0],
                'estado' => 'A',
                'email' => $socialUser->user['email'],
                'imagen' => $socialUser->user['picture'],
                'rol_id' => '3'
             ]);
            return $this->createToken($socialUser->email);
        }
    }

    public function createToken($user){
        $user = usuario::where('email',$user)->first();
         try{
             if (!$token = $this->jwt->fromUser($user)) {
                 return response()->json(['user_not_found'], 404);
             } 
             echo $token;
         }catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
             return response()->json(['token_expired'], 500);
         } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
             return response()->json(['token_invalid'], 500);
         } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
             return response()->json(['token_absent' => $e->getMessage()], 500);
         }
         return response()->json(compact('token'));
     }
 

}