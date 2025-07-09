@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <h4 class="text-center mb-4">ثبت‌نام</h4>

                <form method="POST" action="{{ route('register.attempt') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="phone" class="form-label">شماره موبایل</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            placeholder="مثلاً 09121234567" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">رمز عبور</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">تکرار رمز عبور</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="captcha" class="form-label d-block">کپچا</label>
                        <img src="{{ captcha_src('flat') }}" alt="captcha"
                            onclick="this.src='{{ captcha_src('flat') }}'+Math.random()" style="cursor:pointer;">
                        <input type="text" class="form-control mt-2" id="captcha" name="captcha"
                            placeholder="متن تصویر بالا را وارد کنید" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">ثبت‌نام</button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('login.form') }}">اکانت داری؟ وارد شو</a>
                    </div>

                </form>

                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


            </div>
        </div>
    </div>
@endsection
