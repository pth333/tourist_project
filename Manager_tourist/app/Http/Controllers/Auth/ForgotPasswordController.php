<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NotificationResetEmail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class ForgotPasswordController extends Controller
{
    public function showForgetPassword()
    {
        return view('auth.forget-password');
    }
    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ], [
            'email.required' => 'Vui lòng nhập vào trường',
            'email.email' => 'Vui lòng nhập email',
            'email.exists' => 'Email không tồn tại'
        ]);

        $user = User::where('email', $request->email)->first();

        $token = Str::random(20);

        $url = url('/reset-password'. '/' . $token);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        DB::table('password_reset_tokens')->updateOrInsert([
            'email' => $request->email,
            'token' => $token,
            // 'create_at' => Carbon::now(),
        ]);

        $user->notify(new NotificationResetEmail($user, $url));

        return redirect()->back()->with('status', 'Email đã được gửi vui lòng kiểm tra hòm thư email!');

    }
    public function showResetPassword($token)
    {
        // dd($token);
        return view('auth.reset-password', ['token' => $token]);
    }
    public function submitResetPassword(User $user, Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        dd($user->token);
        if($request->input('token') === $user->token){
            $user->update([
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            return redirect()->route('login');
        }
    }
}
