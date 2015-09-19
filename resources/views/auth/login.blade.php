@extends('layout')

@section('content')
  <h2>Login</h2>

  @if ($errors->count())
    <h3>Oops!</h3>
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form method="POST" action="/login">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}">

    <label for="password">Password</label>
    <input type="password" id="password" name="password">

    <div class="checkbox">
      <input type="checkbox" id="remember" name="remember">
      <label for="remember">Stay logged in</label>
    </div>

    <button type="submit">Login</button>
  </form>

@endsection
