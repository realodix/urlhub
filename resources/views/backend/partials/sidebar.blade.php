<div class="sidebar">
  <nav class="sidebar-nav">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin') }}">
          <i class="nav-icon fas fa-tachometer-alt"></i> @lang('Dashboard')
        </a>
      </li>
      @role('admin')
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.allurl') }}">
          <i class="nav-icon fas fa-link"></i> @lang('All URLs')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('user.index') }}">
          <i class="nav-icon fas fa-users"></i> @lang('All Users')
        </a>
      </li>
      @endrole
    </ul>
  </nav>
  <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
