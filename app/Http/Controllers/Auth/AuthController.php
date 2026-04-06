<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showLogin()
    {
        return view('auth.login');
    }



    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);


        $user = User::where('email',$data['email'])->first();


        if(
            !$user ||
            !$user->is_active ||
            !Hash::check($data['password'],$user->password)
        ){
            return back()
                ->withErrors([
                    'email'=>'Email atau password salah, atau akun nonaktif.'
                ])
                ->onlyInput('email');
        }


        // regenerate session
        $request->session()->regenerate();


        // simpan session manual
        $request->session()->put('user_id',$user->id);
        $request->session()->put('user_role',$user->role);
        $request->session()->put('user_name',$user->name);



        /*
        |--------------------------------------------------------------------------
        | Redirect berdasarkan role
        |--------------------------------------------------------------------------
        */

        // Admin / WBS officer
        if(in_array($user->role,['admin','wbs_officer'],true)){

            return redirect()->route('admin.dashboard');

        }


        // User biasa
        return redirect()->route('web.home',[
            'locale' => 'id'
        ]);
    }



    public function showRegister()
    {
        return view('auth.register');
    }



    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'email' => ['required','email','max:190','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
        ]);


        // default role untuk user umum
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'role'=>'user',
            'is_active'=>true,
        ]);


        // auto login
        $request->session()->regenerate();

        $request->session()->put('user_id',$user->id);
        $request->session()->put('user_role',$user->role);
        $request->session()->put('user_name',$user->name);


        return redirect()->route('web.home',[
            'locale'=>'id'
        ]);
    }



    public function logout(Request $request)
    {

        $request->session()->forget([
            'user_id',
            'user_role',
            'user_name'
        ]);


        $request->session()->invalidate();

        $request->session()->regenerateToken();


        return redirect()->route('login');
    }

}