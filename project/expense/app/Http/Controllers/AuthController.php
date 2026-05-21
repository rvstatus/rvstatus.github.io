<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Repositories\AuthRepository;

class AuthController extends Controller
{
    protected $authRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * show the login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * show the register page.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * register the user data.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register_process(Request $request)
    {
        // server side validation
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
        // register process
        $status = $this->authRepository->register($request->name, $request->email, $request->password);
        if ($status) {
            // success
            return redirect('/login')
                ->with('success', Lang::get('messages.register.create.success'));
        }
        // error
        return back()
            ->withInput()
            ->with('error', Lang::get('messages.register.create.fail'));
    }

    /**
     * login the user process.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login_process(Request $request)
    {
        // server side validation
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

        // get login credentials
        $credentials = $request->only('email', 'password');
        if ($this->authRepository->login($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }
        return back()->with(
            'error',
            Lang::get('messages.login.invalid_credentials')
        );
    }

    /**
     * Logout the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
