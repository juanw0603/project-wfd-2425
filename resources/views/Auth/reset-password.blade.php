@extends('Layout')

@section('content')
<h2>Reset Password</h2>
<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">
    <input type="password" name="password" placeholder="New password" required>
    <input type="password" name="password_confirmation" placeholder="Confirm password" required>
    <button type="submit">Reset Password</button>
</form>
@endsection
