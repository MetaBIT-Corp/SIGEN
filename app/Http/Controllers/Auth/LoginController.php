<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Session;

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
    //protected $redirectTo = '/home';
    protected $redirectTo = '/materias';

    //Bloquear al usuario después de 10 intentos fallidos
    protected $maxAttempts = 100;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * (Override) Get the needed authorization credentials from the request.
     * @author Enrique Menjívar <mt16007@ues.edu.sv>
     * @param  Request $request
     * @return array
     */
     protected function credentials(Request $request){
        $credentials = $request->only($this->username(), 'password');
        $email = $request->input('email');
        
        $user = User::where('email', $email)->first();

        if($user != null){
            if(!$user->is_admin){
                $user->attempts = $user->attempts + 1;
                $user->save();
            }
        }

        if($user != null){
            if($user->attempts > 3){
                $user->enabled = 0;
                $user->save();
                $failed_credentials = array("email" => null, "password" => null);

                Session::put('block_message', 'Este usuario ha consumido la cantidad máxima de intentos, favor contactar al administrador');

                return array_add($failed_credentials, 'enabled', 1);
            }
        }

        Session::put('block_message', null);
        return array_add($credentials, 'enabled', 1);

     }

     /**
     * (Override) The user has been authenticated.
     * @author  Enrique Menjívar <mt16007@ues.edu.sv>
     * @param  Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        Session::put('block_message', null);
        $user->attempts = 0;
        $user->save();
    }
}
