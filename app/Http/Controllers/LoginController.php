<?php

namespace App\Http\Controllers;

use app\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function create(){
        return view('login');
    }

    public function postLogin(Request $request){
        // dd($request->all());
        if(Auth::attempt($request->only('username', 'password'))){
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        return redirect('/dashboard');
    }

    public function logout(request $request){
        Auth::logout();

        return redirect('/');
    }
}
