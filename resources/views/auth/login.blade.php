@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <h4 class="text-center mb-4">ورود</h4>

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="phone" class="form-label">شماره موبایل</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            placeholder="مثلاً 09121234567" required>
                    </div>

                    <button type="button" id="send-code-btn" class="btn btn-primary mb-3">ارسال کد</button>

                    <div id="code-notification" class="alert alert-info" style="display:none;"></div>

                    {{-- <div class="mb-3">
                        <label for="otp_code" class="form-label">کد یکبار مصرف</label>
                        <input type="text" id="otp_code" name="otp_code" class="form-control"
                            placeholder="کد را وارد کنید" required>
                    </div> --}}

                    <div class="mb-3">
                        <label for="password" class="form-label">رمز عبور (در صورت وجود)</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="رمز عبور دلخواه">
                    </div>

                    <div class="mb-3">
                        <label for="otp" class="form-label">کد یک‌بار مصرف (در صورت استفاده)</label>
                        <input type="text" class="form-control" id="otp" name="otp"
                            placeholder="کد ارسال شده را وارد کنید">
                    </div>


                    <div class="mb-3">
                        <label for="captcha" class="form-label d-block">کپچا</label>
                        <img src="{{ captcha_src('flat') }}" alt="captcha"
                            onclick="this.src='{{ captcha_src('flat') }}'+Math.random()" style="cursor:pointer;">
                        <input type="text" class="form-control mt-2" id="captcha" name="captcha"
                            placeholder="متن تصویر بالا را وارد کنید" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">ورود</button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('register.form') }}">ثبت‌نام نکردی؟ ثبت‌نام کن</a>
                    </div>

                </form>

                @if (session('otp_code'))
                    <div class="alert alert-info mt-3">
                        کد ورود شما: <strong>{{ session('otp_code') }}</strong>
                        <br>
                        <span id="timer">60</span> ثانیه تا انقضا
                    </div>

                    <script>
                        let timer = 60;
                        const el = document.getElementById('timer');
                        const interval = setInterval(() => {
                            timer--;
                            el.innerText = timer;
                            if (timer <= 0) clearInterval(interval);
                        }, 1000);
                    </script>
                @endif

            </div>
        </div>
    </div>
    <script>
        document.getElementById('send-code-btn').addEventListener('click', function() {
            let phone = document.getElementById('phone').value;

            if (!phone.match(/^09\d{9}$/)) {
                alert('شماره موبایل معتبر نیست');
                return;
            }

            fetch("{{ route('otp.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        phone: phone
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        document.getElementById('code-notification').style.display = 'block';
                        document.getElementById('code-notification').innerText = 'کد شما (شبیه‌سازی): ' + data
                            .code;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(err => {
                    alert('خطا در ارسال درخواست.');
                    console.error(err);
                });
        });
    </script>
@endsection
