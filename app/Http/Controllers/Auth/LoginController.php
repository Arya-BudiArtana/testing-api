<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Services\Enduser\EnduserCommandServices;
use App\Models\User;
use Throwable;

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

    protected $enduserCommandServices;

    public function __construct(
        EnduserCommandServices $enduserCommandServices,
    ) {
        $this->middleware('guest')->except('logout');
        $this->enduserCommandServices = $enduserCommandServices;
    }


    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        try {
            $user_google = Socialite::driver('google')->user();
            $user =  User::where('email', '=', $user_google->getEmail())->first();
            if ($user != null) {
                Auth::login($user, true);

                return redirect()->route('home');
            } else {
                $create = $this->enduserCommandServices->userGoogleStore($user_google->getEmail(), $user_google->getName());
                Auth::login($create, true);

                return redirect()->route('home')->with('success', 'Akun Berhasil Terverifikasi');
            }
        } catch (\Exception $e) {
            dd($e);

            return redirect()->route('login');
        }
    }
}
