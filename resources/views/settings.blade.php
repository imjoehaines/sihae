@extends('layout')

@section('content')
  <h2>Settings</h2>

  @if ($errors->count())
    <h3>Oops!</h3>
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form method="POST" action="/settings">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <label for="title">Blog Title</label>
    <input id="title" name="title" value="{{ $title }}">

    <label for="postsPerPage">Number of posts per page</label>
    <input type="number" id="postsPerPage" name="postsPerPage" value="{{ $postsPerPage }}">

    <div class="checkbox">
      <input type="checkbox" id="showLoginLink" name="showLoginLink" @if ($showLoginLink) checked @endif>
      <label for="showLoginLink">Show login link</label>
    </div>

    <button type="submit">Save</button>
  </form>
@endsection
