<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuthLog;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Setting;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'regex:/^09\\d{9}$/', 'unique:users,phone'],
            'password' => 'required|confirmed|min:4',
            //'captcha' => 'required|captcha'
        ]);

        $user = User::create([
            'phone' => $data['phone'],
            'password' => Hash::make($data['password'])
        ]);
    }


    public function login(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^09\\d{9}$/', 'exists:users,phone'],
            'captcha' => 'required|captcha'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if ($request->filled('password')) {
            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'رمز اشتباه است']);
            }
            Auth::login($user);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['password' => 'رمز عبور الزامی است یا بعداً با OTP تکمیل شود']);

        if ($request->filled('otp')) {
            $record = VerificationCode::where('user_id', $user->id)
                ->where('code', $request->otp)
                ->where('used', false)
                ->where('expired_at', '>', now())
                ->latest()
                ->first();

            if (!$record) {
                return back()->withErrors(['otp' => 'کد وارد شده معتبر نیست یا منقضی شده است.']);
            }

            $record->update(['used' => true]);
            Auth::login($user);
            return redirect()->route('dashboard');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.form');
    }

    private function generateAndSendCode(User $user)
    {
        // باطل کردن کدهای قبلی
        VerificationCode::where('user_id', $user->id)->update(['used' => true]);

        $code = rand(1000, 9999);
        $expiry = Setting::getValue('otp_expiry', 60);

        VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expired_at' => now()->addSeconds($expiry),
            'used' => false,
        ]);

        // 🔸 ثبت لاگ otp_sent
        AuthLog::create([
            'user_id' => $user->id,
            'event' => 'otp_sent',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return $code;
    }


    public function sendCode(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^09\d{9}$/']
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'کاربری با این شماره پیدا نشد.'], 404);
        }

        $code = $this->generateAndSendCode($user);

        return response()->json([
            'status' => true,
            'message' => 'کد ارسال شد (شبیه‌سازی)',
            'code' => $code // فقط برای تست و شبیه‌سازی نمایش داده میشه
        ]);
    }
}
