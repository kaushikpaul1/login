<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    public function index(){
        return view('register');
    }
    public function reloadCaptcha(){
        return response()->json(['captcha'=>captcha_img()]);
    }
    // public function post(Request $request){
    //     $request->validate([
    //         'name'=>'required'
    //     ])
    // }
}
