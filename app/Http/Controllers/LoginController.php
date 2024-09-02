<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LoginController extends Controller
{
   public function showLoginForm() {
    return Inertia::render('Auth/Login');
   }

   public function login(LoginRequest $request) {
    $credentials = $request->validated();

        if (Login::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
   }

   public function logout(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerate();

    return redirect('Auth/login');
   }

   public function showRegisterForm() {
    return Inertia::render('Auth/Register');
   }

   public function register(RegisterRequest $request)
    {
        $user = Login::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Login::login($user);

        return redirect('/dashboard');
    }

    public function showForgetPasswordForm(){
        return inertia::render('Auth/forgotPassword');
    }

    public function sendResetLinkEmail() {
        $this->validate(request(), ['email' =>'required|email']);

        $user = Login::where('email', request('email'))->first();

        if (!$user) {
            return back()->with('error', 'We can\'t find a user with that email address.');
        }

        $token = app('auth.password.broker')->createToken($user);

        $url = route('password.reset', ['token' => $token, 'email' => $user->email]);

        Mail::to($user->email)->send(new ResetPasswordMail($url));

        return back()->with('success', 'We have sent you a password reset link!');
    }




}
