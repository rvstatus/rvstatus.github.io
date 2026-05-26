<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;

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
     * check the login credentials.
     *
     * @param array $credentials
     * @return bool
     */
    public function login($credentials)
    {
        return Auth::attempt($credentials);
    }

    /**
     * send password reset link to user email.
     *
     * @param string $email
     * @return array
     */
    public function sendResetLink($email)
    {
        // check user exists
        $user = DB::table('users')
            ->where('email', $email)
            ->first();

        if (!$user) {
            return [
                'status' => false,
                'message' => Lang::get('messages.forgot_password.validation.email_not_found')
            ];
        }

        // generate token
        $token = Str::random(60);

        // save token
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'email'      => $email,
                'token'      => bcrypt($token),
                'created_at' => now()
            ]
        );

        // reset link
        $link = url('/reset-password') .
            '?email=' . urlencode($email) .
            '&token=' . $token;
        try {
            // send mail
            Mail::raw(
                "Reset your password using this link: " . $link,
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Password Reset');
                }
            );

            return [
                'status' => true,
                'message' => Lang::get('messages.forgot_password.mail.success')
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => Lang::get('messages.forgot_password.mail.fail')
            ];
        }
    }

    /**
     * check the login credentials.
     *
     * @param array $data
     * @return array
     */
    public function resetPassword($data)
    {
        $record = DB::table('password_resets')
            ->where('email', $data['email'])
            ->first();

        if (!$record) {
            return [
                'status' => false,
                'message' => Lang::get('messages.forgot_password.validation.invalid_request')
            ];
        }

        // token verify
        if (!password_verify($data['token'], $record->token)) {
            return [
                'status' => false,
                'message' => Lang::get('messages.forgot_password.validation.invalid_token')
            ];
        }

        // update password
        DB::table('users')
            ->where('email', $data['email'])
            ->update([
                'password' => Hash::make($data['password']),
                'updated_at' => now()
            ]);

        // delete token
        DB::table('password_resets')
            ->where('email', $data['email'])
            ->delete();

        return [
            'status' => true,
            'message' => Lang::get('messages.forgot_password.reset.success')
        ];
    }
}
