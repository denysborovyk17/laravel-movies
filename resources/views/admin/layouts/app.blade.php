<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
</head>
<body>
    @guest
        <div style="display: inline">
            <a href="{{ route('login') }}">Sign in</a> |
            <a href="{{ route('register') }}">Sign up</a>
            <b style="position: absolute; top: 10px; right: 10px;">Привіт, гість!</b>
        </div>
        <hr>
    @endguest
    @auth
        <div style="position: absolute; top: 10px; right: 10px;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
                <h3>Привіт, {{ auth()->user()->name }}!</h3>
            </form>
        </div>
    @endauth
    <main>
        @yield('content')
    </main>
</body>
</html>
