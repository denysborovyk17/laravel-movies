@extends('admin.layouts.app')

@section('title', 'Login Page')

@section('content')
    <h1>Sign in</h1>
    @include('errors.form-errors')
    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <label>
            <input type="checkbox" name="remember_me" value="1">
            Remember me
        </label>
        <button type="submit">Login</button>
        <a href="{{ route('register') }}">Create account</a>
    </form>
@endsection


