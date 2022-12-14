<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    //google redirect
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }
    //google callback
    public function redirectToGoogleCallback(){
        $user = Socialite::driver('google')->user();
        $this->registrationOrLogin($user);
        return redirect()->route('home');
    }
    // ################## Facebook ########################
    //facebook redirect
    public function redirectToFacebook(){
        return Socialite::driver('facebook')->redirect();
    }
    //facebook callback
    public function redirectToFacebookCallback(){
        $user = Socialite::driver('facebook')->user();
        $this->registrationOrLogin($user);
        return redirect()->route('home');
    }

    // ##################### github #####################
    //github redirect
    public function redirectToGithub(){
        return Socialite::driver('github')->redirect();
    }
    //github callback
    public function redirectToGithubCallback(){
        $user = Socialite::driver('github')->user();
        $this->registrationOrLogin($user);
        return redirect()->route('home');
    }


    protected function registrationOrLogin($data){
        $user = User::where('email',$data->email)->first();
        if(!$user){
            $user = new User;
            $user->name = $data->name;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->save();
        }

        Auth::login($user);
    }
}
