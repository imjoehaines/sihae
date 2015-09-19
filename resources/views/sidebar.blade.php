<aside>
  <header>
    <h1><a href="/">{{ $config::get('title') }}</a></h1>
  </header>

  <p><em>Beep boop</em></p>

  <p>Alienum deterruisset mea an, at eos albucius adipiscing, mea ex viderer menandri facilisis. An inimicus partiendo qualisque quo, ex qui aperiri constituto. Ne deleniti splendide sed, id mea aperiri imperdiet. Et sed veniam definitionem, sit eu erant integre delectus. Ut sea erat lobortis iracundia. Vim timeam conceptam ut, sed antiopam senserit comprehensam cu, an mel facer cetero.</p>

  <ul>
    @if (Auth::user())
      <li><a href="/logout">Logout</a></li>
      <li><a href="/post/new">Add a new post</a></li>
    @else
      <li><a href="/login">Login</a></li>
    @endif
  </ul>
</aside>
