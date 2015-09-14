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

    <label for="title">Title</label>
    <input name="title" id="title" maxlength="140" required>

    <label for="summary">Summary</label>
    <input name="summary" id="summary" maxlength="255" required>

    <label for="body">Post</label>
    <textarea name="body" id="body" maxlength="7500" required></textarea>

    <button name="submit" type="submit">Add new post</button>
  </form>
@endsection
