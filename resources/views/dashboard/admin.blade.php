@extends('layouts.app')

@section('content')
<div class="container mt-5 text-center">
    <h3>پنل ادمین</h3>
    <p>خوش آمدی ادمین {{ auth()->user()->phone }}</p>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="mt-4">
        @csrf
        <div class="mb-3 row justify-content-center">
            <label class="col-form-label col-md-3" for="otp_expiry">مدت اعتبار کد OTP (بر حسب ثانیه):</label>
            <div class="col-md-2">
                <input type="number" class="form-control" name="otp_expiry" id="otp_expiry" value="{{ old('otp_expiry', $currentExpiry ?? 60) }}" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">ذخیره تنظیمات</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-5">
        @csrf
        <button type="submit" class="btn btn-danger">خروج</button>
    </form>
</div>
@endsection
