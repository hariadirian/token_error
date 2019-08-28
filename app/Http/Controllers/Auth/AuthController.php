<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\M_Idempiere\M_Ad_User;
use Laravel\Socialite\Facades\Socialite;
use App\Models\M_User_Management\M_Us_Frontend_HD;

class AuthController extends Controller
{
    
    public function redirectToProvider($provider)
    {
        $CEK    =   Socialite::driver($provider)->redirect();
        return $CEK;
    }

    public function handleProviderCallback($provider)
    {
        if($provider === 'google'){
            $user = Socialite::driver($provider)->stateless()->user();
        }else{
            $user = Socialite::driver($provider)->user();
        }
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect('/');
    }

    public function findOrCreateUser($user, $provider)
    {
        switch($provider){
            case "google":
                $authUser = M_Us_Frontend_HD::where("google_id", $user->id)->first();
                break;
            case "twitter":
                $authUser = M_Us_Frontend_HD::where("twitter_id", $user->id)->first();
                break;
            default:
                $authUser = M_Us_Frontend_HD::where("facebook_id", $user->id)->first();
                break;
        }

        
        if($authUser){
            return $authUser;
        }else{
            $cekexist = M_Us_Frontend_HD::where("email", $user->email)->first();
            if(!$cekexist){
                $code = get_prefix('us_frontend'); 
                $data = M_Us_Frontend_HD::create([
                    'cd_us_frontend_hd' => "$code",
                    'email'             => !empty($user->email) ? $user->email : '',
                    'provider'          => $provider,
                    $provider."_id"     => $user->id,
                ]);
            }else{
                $data = M_Us_Frontend_HD::where('id_us_frontend_hd', $cekexist->id_us_frontend_hd)->update([
                    $provider."_id"     => $user->id,
                ]);
            }
            return $data;
        }
    }
}
