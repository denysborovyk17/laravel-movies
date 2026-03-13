@extends('admin.layouts.app')

@section('title', 'Register Page')

@section('content')
<h1>Sign up</h1>
@include('errors.form-errors')
<form method="POST" action="{{ route('register.store') }}">
    @csrf
    <input type="text" name="name" placeholder="Name">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Register</button>
</form>
@endsection
