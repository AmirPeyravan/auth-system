@extends('layouts.app')

@section('content')
<div class="container mt-5 text-center">
    <h3>داشبورد کاربر</h3>
    <p>خوش آمدی {{ auth()->user()->phone }}</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger mt-3">خروج</button>
    </form>
</div>
@endsection
