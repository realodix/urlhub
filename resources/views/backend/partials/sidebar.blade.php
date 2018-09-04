<div class="sidebar">
  <nav class="sidebar-nav">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="/admin">
          <i class="nav-icon icon-speedometer"></i> Dashboard
        </a>
      </li>
      @role('admin')
      <li class="nav-item">
        <a class="nav-link" href="/admin/allurl">
          <i class="nav-icon icon-link"></i> All URL
        </a>
      </li>
      @endrole
    </ul>
  </nav>
  <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
