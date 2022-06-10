<?php

namespace App\Http\Controllers;

use app\Http\Requests\LoginRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected $comp_name;

    public function index(){
        return view('login');
    }

    public function postLogin(Request $request){
        //Authentification is user and password are correct or not
        if(Auth::attempt($request->only('username', 'password'))){
            $request->session()->regenerate();
            $data = $request->input();

            $username = Auth::User()->username;
            $comp_name = Auth::User()->comp_name;
            $request->session()->put('comp_name', $comp_name);
            $request->session()->put('username', $username);
            // dd(session()->all());

            $current_date_time = Carbon::now()->toDateTimeString();
            DB::table('userlog')->insert(['username' =>Auth::user()->name, 'tbl'=>'ONLINE', 'idtbl'=> '0', 'notbl'=>'', 'act'=>'LOGIN', 'comp_code'=>'BYC', 'usin'=>1,'datein'=>$current_date_time]);
            
            return redirect()->intended('/pemasukan');
        }
        return redirect('/');
    }

    public function logout(request $request){
        Auth::logout();
        $current_date_time = Carbon::now()->toDateTimeString();
        DB::table('userlog')->insert(['username' =>session()->get('username'), 'tbl'=>'ONLINE', 'idtbl'=> '0', 'notbl'=>'', 'act'=>'LOGOUT', 'comp_code'=>'BYC', 'usin'=>1,'datein'=>$current_date_time]);

        return redirect('/');
    }
}
