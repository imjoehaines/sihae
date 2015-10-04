<aside class="sidebar">
  <header>
    <h1><a href="/">{{ $config::get('title') }}</a></h1>
  </header>

  {!! Purifier::clean(Markdown::string($config::get('summary'))) !!}

  @if (Auth::check())
    @include('partials/admin')
  @elseif ($config::get('showLoginLink'))
    <ul class="admin">
      <li><a href="/login">
        <i class="fa fa-sign-in"></i> Login
      </a></li>
    </ul>
  @endif
</aside>
