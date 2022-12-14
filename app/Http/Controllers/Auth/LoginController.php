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
use App\Services\User\UserCommandServices;
use App\Models\User;
use App\Services\ApiNotification\ApiNotificationCommandServices;
use App\Services\ApiNotification\ApiNotificationMessageServices;
use App\Services\User\UserQueryServices;
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

    protected $userCommandServices;

    protected $apiNotificationCommandServices;

    protected $apiNotificationMessageServices;

    protected $userQueryServices;

    public function __construct(
        UserCommandServices $userCommandServices,
        ApiNotificationCommandServices $apiNotificationCommandServices,
        ApiNotificationMessageServices $apiNotificationMessageServices,
        UserQueryServices $userQueryServices,

    ) {
        $this->middleware('guest')->except('logout');
        $this->userCommandServices = $userCommandServices;
        $this->apiNotificationCommandServices = $apiNotificationCommandServices;
        $this->apiNotificationMessageServices = $apiNotificationMessageServices;
        $this->userQueryServices = $userQueryServices;
    }
    public function loginWhatsappForm()
    {
        $statusApp = $this->apiNotificationCommandServices->getStatusApp();
        if ($statusApp['data']['waNotifStatus'] == 1) {
            return view('auth.login_whatsapp', compact('statusApp'));
        }

        return redirect('login')->with('error', 'Fitur Ini Sedang Dalam Perbaikan');
    }

    public function loginWhatsappVerifikasi(Request $request)
    {
        try {
            DB::beginTransaction();
            $find = $this->userQueryServices->findByWhatsapp($request->whatsapp);
            if (! empty($find)) {
                $send_whatsapp = $this->apiNotificationMessageServices->sendOtpWhatsapp($find->id);
                if ($send_whatsapp) {
                    DB::commit();

                    return redirect()->route('login-whatsapp.index')->with('success', 'Kode Verifikasi dikirim ke WhatsApp Anda');
                }
                DB::rollBack();

                return redirect()->back()->with('error', 'Pengiriman Kode Verifikasi Gagal Dilakukan');
            }
            DB::rollBack();

            return redirect()->back()->with('error', 'Akun dengan No WhatsApp tersebut tidak ditemukan');
        } catch (Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();

            return redirect('login-whatsapp.index')->with('error', 'Gagal Memverifikasi Nomor WhatsApp');
        }
    }

    public function loginWhatsappAksi(Request $request)
    {
        try {
            DB::beginTransaction();
            $token1 = $request->code_1 . $request->code_2. $request->code_3 . $request->code_4 . $request->code_5 . $request->code_6;
            if (Session::has('otp_session')) {
                $data = Session::get('otp_session');
                $user = $data['request_from'];
                $token = $request->code_1 . $request->code_2. $request->code_3 . $request->code_4 . $request->code_5 . $request->code_6;
                $expired_date = Carbon::createFromTimestamp($data['expired'])->format('Y-m-d H:i:s');
                $find = $this->userQueryServices->findById($user);
                if (Carbon::now()->format('Y-m-d H:i:s') >= $expired_date) {
                    Session::forget('otp_session');

                    return redirect()->route('login-whatsapp.index')->with('error', 'Batas Waktu Permintaan Token Telah Habis, Silahkan Kirim Ulang');
                }
                $verify = $this->apiNotificationMessageServices->verifyOtpToken($find->id, $token);
                if (! $verify->status) {
                    $message = 'Reached the maximum allowed attempts';
                    if ($verify->message == $message) {
                        Session::forget('otp_session');
                        DB::rollBack();

                        return redirect()->route('login-whatsapp.index')->with('error', 'Anda Memasukkan Kode OTP Salah Berulang Kali, Silahkan Ulangi Lagi');
                    }
                    DB::rollBack();

                    return redirect()->route('login-whatsapp.index')->with('error', 'Kode OTP Yang Dimasukkan Tidak Sesuai');
                }
                Session::forget('otp_session');
                Auth::login($find);
                DB::commit();

                return redirect()->route('home');
            }
            DB::rollBack();

            return redirect()->route('login-whatsapp.index');
        } catch (Throwable $th) {
            DB::rollBack();
            dd($th);
            return redirect()->route('login-whatsapp.index')->with('error', 'Terjadi Kesalahan, Coba Lagi');
        }
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
                $create = $this->userCommandServices->userGoogleStore($user_google->getEmail(), $user_google->getName());
                Auth::login($create, true);

                return redirect()->route('home')->with('success', 'Akun Berhasil Terverifikasi');
            }
        } catch (\Exception $e) {
            dd($e);

            return redirect()->route('login');
        }
    }
}
