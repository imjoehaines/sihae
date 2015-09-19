@extends('layout')

@section('content')
  <h2>Login</h2>

  <form method="POST" action="/login">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}">

    <label for="password">Password</label>
    <input type="password" id="password" name="password" id="password">

    <div class="checkbox">
      <input type="checkbox" id="remember" name="remember">
      <label for="remember">keep me logged in</label>
    </div>

    <button type="submit">Login</button>
  </form>

@endsection
