<div class="sidebar">
  <nav class="sidebar-nav">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="/admin">
          <i class="nav-icon fas fa-tachometer-alt"></i> Dashboard
        </a>
      </li>
      @role('admin')
      <li class="nav-item">
        <a class="nav-link" href="/admin/allurl">
          <i class="nav-icon fas fa-link"></i> All URL
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="nav-icon fas fa-users"></i> All Users
        </a>
      </li>
      @endrole
    </ul>
  </nav>
  <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
