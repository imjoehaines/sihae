<aside class="admin">
  <ul>
    @if (Auth::check())
      <li><a href="/settings">
        Settings <i class="fa fa-cog"></i>
      </a></li>

      <li><a href="/post/new">
        Add a new post <i class="fa fa-plus"></i>
      </a></li>
    @endif

    @if ($config::get('showLoginLink'))
      @if (Auth::check())
        <li><a href="/logout">
          Logout <i class="fa fa-sign-out"></i>
        </a></li>
      @else
        <li><a href="/login">
          Login <i class="fa fa-sign-in"></i>
        </a></li>
      @endif
    @endif
  </ul>
</aside>
