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
