<?php

namespace App\Http\Controllers\Auth;

use App\Models\M_User_Management\M_Us_Frontend_HD;
use App\Models\M_User_Management\M_Us_Frontend_DT;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
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
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'mobile_phone' => 'required|string|max:16|unique:us_frontend_dt',
            'first_name' => 'required|string|max:64',
            'email' => 'required|string|email|max:255|unique:us_frontend_hd',
            'password' => 'required|string|min:6|confirmed'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return App\Models\M_User_Management\M_Us_Frontend_HD
     */
    protected function create(array $data)
    {
        $code   = get_prefix('us_frontend_hd'); 
        $save_header = M_Us_Frontend_HD::create([
            'cd_us_frontend_hd' => $code,
            'email' => $data['email'],
            'password' => bcrypt($data['password']),        
            'registered_token'=>str_random(190),
            'created_by' => \Request::ip(),
            'is_active' => 'N'
        ]);
        if($save_header){
            $code_dt   = get_prefix('us_frontend_dt'); 
            M_Us_Frontend_DT::create([
                'cd_us_frontend_dt' => $code_dt,
                'cd_us_frontend_hd' => $code,
                'id_us_frontend_hd' => $save_header->id_us_frontend_hd,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'mobile_phone' => $data['mobile_phone'],
                // 'address' => $data['address'],
                // 'institute' => $data['instansi'],
                // 'id_card' => $data['id_card'],
                'created_by' => \Request::ip()
            ]);
            return $save_header;
        }
        return false;
    }

    public function register(Request $request)
    { 
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        // $this->emailUserVerification($request, $user);
        return view('auth.register-success');
    }
    public function activating($token)
    {
        $model              = M_Us_Frontend_HD::where('registered_token', $token)->where('is_active', 'N')->firstOrFail();
        $model->is_active      = 'Y';
        $model->actived_at  = date('Y-m-d H:i:s');
        $model->save();
        return view('auth.activating-success');
    }
}
