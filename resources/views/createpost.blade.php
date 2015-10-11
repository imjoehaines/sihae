@inject('config', 'Sihae\Providers\ConfigServiceProvider')
@extends('layout')

@section('content')
  <h1>Add a new post</h1>

  @if ($errors->count())
    <h2>Oops &mdash; you seem to be missing something!</h2>
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form action="/post/new" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <label for="title">Title</label>
    <input name="title" id="title" maxlength="140" required value="{{ Input::old('title') }}">

    <label for="body">Post</label>
    <textarea name="body" id="body">{{ Input::old('body') }}</textarea>

    <button name="submit" type="submit">Add new post</button>
  </form>

  <script>
    var simplemde = new SimpleMDE()
    simplemde.render()
  </script>
@endsection
