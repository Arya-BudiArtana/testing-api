<?php

namespace App\Services\ApiNotification;

use App\Models\User;
use App\Services\User\UserQueryServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Seshac\Otp\Otp;
use Throwable;

/**
 * Created by Deyan Ardi 2022.
 * API Services Message connect to http://sv1.notif.ganadev.com.
 */
class ApiNotificationMessageServices
{
    protected $apiNotificationCommandServices;

    protected $userQueryServices;

    public function __construct(
        ApiNotificationCommandServices $apiNotificationCommandServices,
        UserQueryServices $userQueryServices,

    ) {
        $this->apiNotificationCommandServices = $apiNotificationCommandServices;
        $this->userQueryServices = $userQueryServices;
    }

    public function sendOtpWhatsapp(string $id, string $whatsapp = null)
    {
        DB::beginTransaction();
        try {
            $user = User::where('id', $id)->first();
            if ($user != null) {
                if ($whatsapp != null) {
                    $send_to = $whatsapp;
                } else {
                    $send_to = $user->whatsapp;
                }
                //generate OTP
                $otp = Otp::setValidity(5)  // otp validity time in mins
                    ->setMaximumOtpsAllowed(10)
                    ->setLength(6)  // Lenght of the generated otp
                    ->setOnlyDigits(true)  // generated otp contains mixed characters ex:ad2312
                    ->generate($send_to);
                // $expired = Otp::expiredAt($send_to);

                // Bypass Expired At Error
                $generated_at = DB::table('otps')->update([
                    'generated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);

                $expired_at = Carbon::now()->addMinutes(5)->timestamp;

                //the message
                $message =
                    "[ *G-$otp->token* ] adalah kode verifikasi Akun My Home Desa Wisata anda. Kode OTP aktif sampai ";
                try {
                    $send_wa = $this->apiNotificationCommandServices->sendWaMessage($send_to, $message);
                    if ($send_wa['status'] != 200) {
                        DB::rollBack();

                        return $send_wa['info'];
                    }
                } catch (Throwable $e) {
                    DB::rollBack();

                    return 'Terjadi kesalahan tidak terduga';
                }
            } else {
                DB::rollBack();

                return 'Akun tidak ditemukan';
            }
            $session_data = [
                'request_from' => $user->id,
                'phone' => $send_to,
                'token' => Crypt::encrypt($otp->token),
                'expired' => $expired_at,
            ];
            Session::put('otp_session', $session_data);
            DB::commit();

            return true;
        } catch (Throwable $th) {
            DB::rollBack();

            return 'Internal Server Error';
        }
    }

    public function verifyOtpToken(string $id, string $token, string $whatsapp = null)
    {
        $user = $this->userQueryServices->findById($id);
        if ($whatsapp != null) {
            $validate_to = $whatsapp;
        } else {
            $validate_to = $user->whatsapp;
        }
        $verify = Otp::setAllowedAttempts(3) // number of times they can allow to attempt with wrong token
            ->validate($validate_to, $token);

        return $verify;
    }

    public function resetPasswordByEmail(string $email, string $name, string $link)
    {
        $judul = 'Reset Password Notification';
        $message = view('email.securityResetPassword', compact('link', 'name'))->render();
        $send_email = $this->apiNotificationCommandServices->sendMailMessage($email, $judul, $message);
        if ($send_email['status'] != 200) {
            return false;
        }

        return true;
    }

    public function generateLinkResetPassword(string $email)
    {
        DB::beginTransaction();
        try {
            $token = Uuid::uuid6()->toString();
            DB::table('password_resets')->where('email', $email)->delete();
            $link = url(route('reset.password.getEmail', ['token' => $token, 'email' => $email]));
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
            DB::commit();

            return $link;
        } catch (Throwable $th) {
            DB::rollBack();

            return false;
        }
    }

    public function updateNewPassword(string $token, string $email, string $password)
    {
        DB::beginTransaction();
        try {
            $reset = DB::table('password_resets')->where('token', $token)->where('email', $email)->first();
            if (! empty($reset)) {
                $expired = Carbon::parse($reset->created_at)->addMinutes(30)->format('Y-m-d H:i:s');
                $now = Carbon::now()->format('Y-m-d H:i:s');
                if ($now <= $expired) {
                    $update = User::where('email', $email)->update(['password' => Hash::make($password)]);
                    DB::table('password_resets')->where(['email' => $email])->delete();
                    DB::commit();

                    return 200;
                }
                DB::rollBack();

                return 401;
            }
            DB::rollBack();

            return 400;
        } catch (Throwable $th) {
            DB::rollBack();
            dd($th);

            return 500;
        }
    }
}
