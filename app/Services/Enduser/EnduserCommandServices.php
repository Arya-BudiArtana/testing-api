<?php

namespace App\Services\Enduser;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class EnduserCommandServices
{
    public function userGoogleStore(string $email, string $name)
    {
        $user_data = new User();
        $user_data->name = ucwords($name);
        $user_data->email = $email;
        $user_data->password = Hash::make(Uuid::uuid6());
        $user_data->email_verified_at = now();
        // $user_data->whatsapp = '';
        $user_data->save();

        return $user_data;
    }
}
