<?php

namespace App\Services\ApiNotification;

use App\Helpers\FormatDateToIndonesia;
use App\Http\Requests\Verified\AuthPembelaanPenangguhanRequest;
use App\Models\User;
use App\Services\User\Enduser\EnduserQueryServices;
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

    protected $enduserQueryServices;

    public function __construct(
        ApiNotificationCommandServices $apiNotificationCommandServices,
        EnduserQueryServices $enduserQueryServices,

    ) {
        $this->apiNotificationCommandServices = $apiNotificationCommandServices;
        $this->enduserQueryServices = $enduserQueryServices;
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
                    $send_to = $user->whatsapp_number;
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
                    "[ *G-$otp->token* ] adalah kode verifikasi Akun My Home Desa Wisata anda. Kode OTP aktif sampai ".FormatDateToIndonesia::getIndonesiaDateTime(Carbon::createFromTimestamp($expired_at)->format('d F Y H:i'));
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

    public function sendOtpUpdateWhatsapp(string $id, string $phone_number)
    {
        DB::beginTransaction();
        try {
            $user = User::where('id', $id)->first();
            if ($user != null) {
                //generate OTP
                $otp = Otp::setValidity(5)  // otp validity time in mins
                    ->setMaximumOtpsAllowed(10)
                    ->setLength(6)  // Lenght of the generated otp
                    ->setOnlyDigits(true)  // generated otp contains mixed characters ex:ad2312
                    ->generate($phone_number);
                // $expired = Otp::expiredAt($phone_number);

                // Bypass Expired At Error
                $generated_at = DB::table('otps')->update([
                    'generated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);

                $expired_at = Carbon::now()->addMinutes(5)->timestamp;
                //the message
                $message =
                    "[ *G-$otp->token* ] adalah kode verifikasi pembaharuan nomor WhatsApp pada akun My Home Desa Wisata anda. Kode OTP aktif sampai ".FormatDateToIndonesia::getIndonesiaDateTime(Carbon::createFromTimestamp($expired_at)->format('d F Y H:i'));
                try {
                    $send_wa = $this->apiNotificationCommandServices->sendWaMessage($phone_number, $message);
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
                'phone_old' => $user->whatsapp_number,
                'phone_new' => $phone_number,
                'token' => Crypt::encrypt($otp->token),
                'expired' => $expired_at,
            ];
            Session::put('update_wa_session', $session_data);
            DB::commit();

            return true;
        } catch (Throwable $th) {
            DB::rollBack();

            return 'Internal Server Error';
        }
    }

    public function verifyOtpToken(string $id, string $token, string $whatsapp = null)
    {
        $user = $this->enduserQueryServices->findById($id);
        if ($whatsapp != null) {
            $validate_to = $whatsapp;
        } else {
            $validate_to = $user->whatsapp_number;
        }
        $verify = Otp::setAllowedAttempts(3) // number of times they can allow to attempt with wrong token
            ->validate($validate_to, $token);

        return $verify;
    }

    public function verifyOtpTokenUpdateWhatsapp(string $token, string $whatsapp)
    {
        $verify = Otp::setAllowedAttempts(3) // number of times they can allow to attempt with wrong token
            ->validate($whatsapp, $token);

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

    public function resetPasswordByWhatsapp(string $nomor_tujuan, string $link)
    {
        $message =
            $message =
            "*$link* adalah tautan untuk mengganti kata sandi pada Akun My Home Desa Wisata anda, abaikan jika permintaan ini bukan dari Anda.";
        $send_email = $this->apiNotificationCommandServices->sendWaMessage($nomor_tujuan, $message);
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

    public function resetEmailByEmail(string $emailReset, string $name, string $link)
    {
        $judul = 'Reset Email Notification';
        $message = view('email.securityVerifyResetEmail', compact('link', 'name'))->render();
        $send_email = $this->apiNotificationCommandServices->sendMailMessage($emailReset, $judul, $message);
        if ($send_email['status'] != 200) {
            return false;
        }

        return true;
    }

    public function sendMailFromGuest(string $email, string $name, string $whatsapp = null, string $konten)
    {
        $judul = 'Email Notifikasi Dari Guest';
        $message = view('email.notifikasiEmailToAdmin', compact('email', 'name', 'konten', 'whatsapp'))->render();
        $email_target = explode(',', config('general.admin-email'));
        for ($i = 0; $i < count($email_target); $i++) {
            $send_email = $this->apiNotificationCommandServices->sendMailMessage($email_target[$i], $judul, $message);
            if ($send_email['status'] != 200) {
                return false;
            }
        }

        return true;
    }

    public function pembelaanPenangguhanByEmail(string $link, AuthPembelaanPenangguhanRequest $request)
    {
        $judul = 'Pembelaan Penangguhan Akun';
        $email = Auth::user()->email;
        $name = Auth::user()->nama_user;
        $konten = $request->pembelaan;
        $message = view('email.notifikasiEmail', compact('name', 'email', 'konten'))->render();
        $email_target = explode(',', config('general.admin-email'));
        for ($i = 0; $i < count($email_target); $i++) {
            if ($link == 'Tidak Ada') {
                $send_email = $this->apiNotificationCommandServices->sendMailMessage($email_target[$i], $judul, $message);
                if ($send_email['status'] != 200) {
                    return false;
                }
            } else {
                $filename = $request->file('file_pendukung')->getClientOriginalName();
                $link = config('app.url').Storage::url($link);
                // $link = "https://deyan-ardi.ganatech.my.id/img/4.jpg";
                $send_email = $this->apiNotificationCommandServices->sendMailMedia($email_target[$i], $judul, $message, $filename, $link);
                if ($send_email['status'] != 200) {
                    return false;
                }
            }
        }

        return true;
    }

    public function generateLinkResetEmail(string $email)
    {
        DB::beginTransaction();
        try {
            $token = Uuid::uuid6()->toString();
            DB::table('email_resets')->where('id_user', Auth::user()->id)->delete();
            $link = url(route('profile.security.email', [Auth::user()->id, 'token' => $token]));
            DB::table('email_resets')->insert([
                'id_user' => Auth::user()->id,
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
}
