<?php
namespace App\Http\Controllers;
use App\usuario;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class googleAuthController extends Controller
{
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
    public function handleProviderCallback(Request $request)
    {
        echo $request->code;
       $lel = json_encode($request);
       echo $lel; 

      
        // this user we get back is not our user model, but a special user object that has all the information we need
        $providerUser = Socialite::with('google')->getAccessTokenResponse($request);
       
        return new JsonResponse(
            [
        
                'provider_user' => $providerUser
            ]
        );
    }
}