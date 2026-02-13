
<div class="container" style="max-width:420px;">
  <h1>Register</h1>

  @if($errors->any())
    <div style="margin:12px 0; color:#b00020;">
      {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('register.post') }}">
    @csrf

    <div style="margin:10px 0;">
      <label>Nama</label>
      <input type="text" name="name" value="{{ old('name') }}" required style="width:100%;">
    </div>

    <div style="margin:10px 0;">
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required style="width:100%;">
    </div>

    <div style="margin:10px 0;">
      <label>Password</label>
      <input type="password" name="password" required style="width:100%;">
    </div>

    <div style="margin:10px 0;">
      <label>Ulangi Password</label>
      <input type="password" name="password_confirmation" required style="width:100%;">
    </div>

    <button type="submit">Register</button>
  </form>

  <p style="margin-top:12px;">
    Sudah punya akun? <a href="{{ route('login') }}">Login</a>
  </p>
</div>

