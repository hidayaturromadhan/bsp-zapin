<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BSP Zapin') }}</title>
</head>
<body>
    <header style="padding:12px 16px; border-bottom:1px solid #ddd;">
        <a href="{{ route('home') }}">Home</a>

        <span style="float:right;">
            @if(session()->has('user_id'))
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}" style="margin-left:8px;">Register</a>
            @endif
        </span>
    </header>

    <main style="padding:16px;">
        @yield('content')
    </main>
</body>
</html>
