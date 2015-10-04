<ul class="admin">
  <li><a href="/settings">
    <i class="fa fa-cog"></i> Settings
  </a></li>

  <li><a href="/post/new">
    <i class="fa fa-plus"></i> Add a new post
  </a></li>

  @if ($config::get('showLoginLink'))
    <li><a href="/logout">
      <i class="fa fa-sign-out"></i> Logout
    </a></li>
  @endif
</ul>
