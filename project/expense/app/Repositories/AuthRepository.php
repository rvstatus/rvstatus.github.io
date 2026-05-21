<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthRepository
{

    /**
     * register the user data.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function register($name, $email, $password)
    {
        $insert = DB::table('users')->insert([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $insert ? true : false;
    }

    /**
     * Check the login credentials.
     *
     * @param array $credentials
     * @return bool
     */
    public function login($credentials)
    {
        return Auth::attempt($credentials);
    }
}
