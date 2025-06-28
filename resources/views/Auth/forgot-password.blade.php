@extends('Layout')

@section('content')
<h2>Forgot Password</h2>
<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <input type="email" name="email" placeholder="Your email" required>
    <button type="submit">Send Reset Link</button>
</form>
@endsection