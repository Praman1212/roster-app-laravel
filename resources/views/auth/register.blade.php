@extends('layouts.app')

@section('content')
<div class="auth-wrap">
<div class="auth-box">
    <h1>🏠 FamRoster</h1>
    <p class="auth-subtitle">Create your account</p>

    <form method="POST" action="/register">
        @csrf

        <div class="form-group">
            <label>Your Name</label>
            <input name="name" value="{{ old('name') }}" required autofocus />
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required />
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required />
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required />
        </div>

        {{-- Color picker --}}
        <div class="form-group">
            <label>Pick Your Color</label>
            <div class="color-row">
                @foreach(['#6366f1','#f43f5e','#22c55e','#f59e0b','#06b6d4','#a855f7'] as $color)
                    <label>
                        <input type="radio" name="color" value="{{ $color }}" class="color-radio"
                               {{ old('color','#6366f1') === $color ? 'checked' : '' }} />
                        <span class="color-swatch" style="background:{{ $color }}"></span>
                    </label>
                @endforeach
            </div>
        </div>

        @if($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:14px;">
                @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
            </div>
        @endif

        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:8px;">Create Account</button>
    </form>

    <p class="auth-switch">Already have an account? <a href="/login">Login here</a></p>
</div>
</div>
@endsection