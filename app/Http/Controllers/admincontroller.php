<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
    'captcha'=>'required',

]);

        //This function don't need any custom model it's use by default user model
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            
        ]);
        $credentials = [
            'name' => $user->name,
            'password' => $request->password,
        ];
        Auth::attempt($credentials);
        return redirect()->route('register.store')->with('success', 'Congratulations your account has been created');
    }





    public function postlogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            // $request->session()->regenerate();
            return redirect('/dashboard')->with('success', 'Successfully logged in');
            // return redirect()->route('dashboard.view')->with('success', 'Successfully logged in');

        }
        return back()->with('error', 'Incorrect email or password');
    }
    public function dashboard()
    {
        return view('dashboard');
    }
}
