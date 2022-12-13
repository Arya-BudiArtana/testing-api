<?php

namespace App\Services\User;

use App\Models\User;

class UserQueryServices
{
    public function findAll()
    {
        return User::all();
    }

    public function findById(string $id)
    {
        return User::findOrFail($id);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function findByWhatsapp(string $whatsapp)
    {
        return User::where('whatsapp', $whatsapp)->first();
    }
}
