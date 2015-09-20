<aside class="admin">
  <ul>
    @if (Auth::check())
      <li><a href="/settings">
        <i class="fa fa-cog"></i> Settings
      </a></li>

      <li><a href="/post/new">
        <i class="fa fa-plus"></i> Add a new post
      </a></li>
    @endif

    @if ($config::get('showLoginLink'))
      @if (Auth::check())
        <li><a href="/logout">
          <i class="fa fa-sign-out"></i> Logout
        </a></li>
      @else
        <li><a href="/login">
          <i class="fa fa-sign-in"></i> Login
        </a></li>
      @endif
    @endif
  </ul>
</aside>
