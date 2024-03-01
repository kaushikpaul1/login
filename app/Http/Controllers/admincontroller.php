<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\ForgotPasswordMail;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class admincontroller extends Controller
{
    public function login()
    {
        return view('login');
    }


    public function register()
    {
        return view('register');
    }


    public function postregister(Request $request)
    {
        $check_email = User::where('email', $request->email)->first();
        if ($check_email) {
            return back()->with('error', 'Email already in use');
        }

        $request->validate([
            // 'name'=>'required',
            // 'email'=>'required|email',
            // 'password'=>'required',
            'captcha' => 'required|captcha',

        ]);

        //This function don't need any custom model it's use by default user model
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);
        // $credentials = [
        //     'name' => $user->name,
        //     'password' => $request->password,
        // ];
        // Auth::attempt($credentials);
        return redirect()->route('login.store')->with('successregister', 'Congratulations your account has been created');
    }





    public function postlogin(Request $request)
    {
        $request->validate([
            // 'name'=>'required',
            // 'email'=>'required|email',
            // 'password'=>'required',
            'captcha' => 'required|captcha',

        ]);
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],


        ]);
        if (Auth::attempt($credentials)) {
            // dd('hi');
            // $request->session()->regenerate();
            return redirect('/dashboard')->with('successlogin', 'Successfully logged in');
            // return redirect()->route('dashboard.view')->with('success', 'Successfully logged in');

        }
        return back()->with('error', 'Incorrect email or password');
    }
    public function dashboard()
    {
        return view('dashboard');
    }
    public function forgotview()
    {
        return view('forgot');
    }
    public function forgotpost(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        if (!empty($user)) {

            $user->remember_token = Str::random(40);
            $user->save();
            Mail::to($user->email)->send(new ForgotPasswordMail($user));
            return redirect()->back()->with('success', 'Please check your email and reset your password');
        } else {
            return redirect()->back()->with('error', 'Email is not register');
        }
    }
    public function reset($token)
    {
        $user = User::where('remember_token', '=', $token)->first();
        if (!empty($user)) {
            // return view('reset');
            return view('reset', ['token' => $token]);
        } else {
            abort(404);
        }
    }
    public function postreset($token, Request $request)
    {
        $user = User::where('remember_token', '=', $token)->first();
        if (!empty($user)) {
            if ($request->password == $request->cpassword) {
                $user->password = Hash::make($request->password);
                if (empty($user->email_verified_at)) {
                    $user->email_verified_at = date('Y-m-d H:i:s');
                }
                $user->remember_token = Str::random(40);
                $user->save();
                return redirect('login')->with('successlog', 'Your password successfully reset.You can login now');
            } else {
                return redirect()->back()->with('error', 'Password Doesnot Match');
            }
        } else {
            abort(404);
        }
    }
    public function logout(){
        Session::flush();
        Auth::logout();
        return redirect('login');
    }
}
