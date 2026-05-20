<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Lang;

class AuthController extends Controller
{
    // Login page
    public function login()
    {
        return view('auth.login');
    }

    // Register page
    public function register()
    {
        return view('auth.register');
    }

    // Register process
    public function register_process(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|min:3|max:50',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ],
            [
                // Name
                'name.required' => Lang::get('messages.register.validation.name.required'),
                'name.min' => Lang::get('messages.register.validation.name.min'),
                'name.max' => Lang::get('messages.register.validation.name.max'),

                // Email
                'email.required' => Lang::get('messages.register.validation.email.required'),
                'email.email' => Lang::get('messages.register.validation.email.email'),
                'email.unique' => Lang::get('messages.register.validation.email.unique'),

                // Password
                'password.required' => Lang::get('messages.register.validation.password.required'),
                'password.min' => Lang::get('messages.register.validation.password.min'),
                'password.confirmed' => Lang::get('messages.register.validation.password.confirmed'),
            ]
        );

        $userModel = new \App\Models\User();

        $status = $userModel->register_data(
            $request->name,
            $request->email,
            $request->password
        );

        if ($status) {

            return redirect('/login')
                ->with('success', Lang::get('messages.register.create.success'));
        }

        return back()
            ->withInput()
            ->with('error', Lang::get('messages.register.create.fail'));
    }

    // Login process
    public function login_process(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ],
            [
                'email.required' => Lang::get('messages.login.validation.email.required'),
                'email.email' => Lang::get('messages.login.validation.email.email'),

                'password.required' => Lang::get('messages.login.validation.password.required'),
                'password.min' => Lang::get('messages.login.validation.password.min'),
            ]
        );

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect('/dashboard');
        }
        return back()->with(
            'error',
            Lang::get('messages.login.invalid_credentials')
        );
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
