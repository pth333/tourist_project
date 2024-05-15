<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        $remember = $request->has('remember_me') ? true : false;

        // dd($remember);
        if (Auth::attempt($credentials, $remember)) {
            return redirect()->route('dashboard')->with('ok','Bạn đã đăng nhập thành công!');
        }else{
            return redirect()->back()->with('no','Vui lòng nhập lại tài khoản hoặc mật khẩu!');
        }
    }

    public function logout(){
        auth()->logout();
        return redirect()->route('login')->with('ok','Bạn đã đăng xuất thành công!');
    }
}
