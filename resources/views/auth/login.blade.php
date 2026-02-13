@extends('layouts.app')

@section('content')
<div class="container" style="max-width:420px;">
  <h1>Login</h1>

  @if($errors->any())
    <div style="margin:12px 0; color:#b00020;">
      {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('login.post') }}">
    @csrf

    <div style="margin:10px 0;">
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required style="width:100%;">
    </div>

    <div style="margin:10px 0;">
      <label>Password</label>
      <input type="password" name="password" required style="width:100%;">
    </div>

    <button type="submit">Login</button>
  </form>

  <p style="margin-top:12px;">
    Belum punya akun? <a href="{{ route('register') }}">Register</a>
  </p>
</div>
@endsection
