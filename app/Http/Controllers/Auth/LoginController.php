<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use App\Models\M_Idempiere\M_Ad_User;
use App\Models\M_User_Management\M_Us_Frontend_DT;
use App\Models\M_User_Management\M_Us_Backend_HD;
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
    // protected $redirectTo = '/session';
    protected $redirectTo = '/';

    // protected $username = 'id_rekam_medis';
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:administrator')->except('logout');
        $this->middleware('guest:customer')->except('logout');
    }

    /**************************************************************/
    /*********************AUTH FOR FRONTEND USER*******************/
    /**************************************************************/

    public function username()
    {
        return 'email';
    }

    protected function authenticated(Request $request, $user)
    {
        $detail_user = M_Us_Frontend_Dt::where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)->first();

        session([
            'name'  => $detail_user->first_name.' '.$detail_user->last_name,
        ]);
    }
    
    protected function validateLogin(\Illuminate\Http\Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string|email|is_active',
            'password' => 'required|string',
        ]);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function guard($param = 'customer')
    {
        return Auth::guard($param);
    }

    protected function sendLoginResponse(Request $request, $guard = 'customer')
    { 
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);
        if($guard == 'administrator'){
            return $this->authenticatedBackend($request, $this->guard($guard)->user())
                    ?: redirect()->intended($this->redirectPath());
        }

        return $this->authenticated($request, $this->guard($guard)->user())
        ?: redirect()->intended($this->redirectPath());
    }


    /**************************************************************/
    /*********************AUTH FOR BACKEND USER********************/
    /**************************************************************/

    public function usernameBackend()
    {
        return 'username';
    }

    protected function authenticatedBackend(Request $request, $user)
    {
        $detail_user = M_Us_Backend_HD::where('id_us_backend_hd', $user->id_us_backend_hd )->first();
        foreach(Auth::guard('administrator')->user()->roles as $roles){
            if(!isset($role)){
                $role = $roles['display_name'];
            }else{
                $role .= ', '.$roles['display_name'];
            }
        }
        session([
            'fullname'  => $detail_user->toUsBackendDt->first_name.' '.$detail_user->toUsBackendDt->last_name,
            'roles'  => $role
        ]);
        return redirect('/dashboard');
    }
    
    public function showBackendLoginForm()
    {
        return view('auth.login-backend');
    }
    
    protected function validateBackendLogin(\Illuminate\Http\Request $request)
    {
        $this->validate($request, [
            $this->usernameBackend() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    public function loginBackend(Request $request)
    {
        $this->validateBackendLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLoginBackend($request)) {
            return $this->sendLoginResponse($request, 'administrator');
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function attemptLoginBackend(Request $request)
    {

        return $this->guard('administrator')->attempt(
            $this->credentialsBackend($request), $request->filled('remember')
        );
    }

    protected function credentialsBackend(Request $request)
    {
        return $request->only($this->usernameBackend(), 'password');
    }
}
