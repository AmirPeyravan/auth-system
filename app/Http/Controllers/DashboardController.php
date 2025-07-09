<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\VerificationCode;
use App\Models\User;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_admin) {
            $currentExpiry = Setting::getValue('otp_expiry', 60);
            return view('dashboard.admin', compact('currentExpiry'));
        } else {
            return view('dashboard.user');
        }
    }

    public function logs()
    {
        $logs = VerificationCode::with('user')->latest()->paginate(20);

        return view('dashboard.logs', compact('logs'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'otp_expiry' => 'required|integer|min:10|max:600',
        ]);

        Setting::setValue('otp_expiry', $request->otp_expiry);

        return back()->with('success', 'تنظیمات با موفقیت ذخیره شد.');
    }
}
