@inject('config', 'Sihae\Providers\ConfigServiceProvider')
@extends('layout')

@section('content')
  <h2>Add a new post</h2>

  @if ($errors->count())
    <h3>Oops &mdash; you seem to be missing something!</h3>
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form action="/post/new" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <label for="title">
      Title
      <input name="title" id="title">
    </label>

    <label for="summary">
      Summary
      <input name="summary" id="summary">
    </label>

    <label for="body">
      Post
      <textarea name="body" id="body"></textarea>
    </label>

    <button name="submit" type="submit">New Post</button>
  </form>
@endsection
